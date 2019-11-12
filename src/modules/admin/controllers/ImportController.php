<?php

namespace app\modules\admin\controllers;

use app\components\daemons\WebSocketDaemon;
use app\components\helpers\TransactionHelper;
use app\components\models\Status;
use app\models\Category;
use app\models\Image;
use app\models\Item;
use app\models\ItemColor;
use app\models\ItemColorSize;
use app\models\ItemDescription;
use app\models\UploadForm;
use app\modules\admin\models\Import;
use app\modules\admin\models\ImportFromExcelForm;
use app\modules\admin\models\ImportSearch;
use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use phpQuery;
use Yii;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Default controller for the `admin` module
 */
class ImportController
    extends Controller
{
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ImportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render(
            'index', [
                       'searchModel' => $searchModel,
                       'dataProvider' => $dataProvider,
                   ]
        );
    }
    
    public function actionUploadExcel()
    {
        $modelUpload = new UploadForm();
        
        if (Yii::$app->request->isPost and $post = Yii::$app->request->post()) {
            
            $model = new Import();
            $model->user_id = Yii::$app->user->identity->id;
            $model->created_at = time();
            $model->type = Import::TYPE_UPLOAD_EXCEL;
            
            $modelUpload->excel = UploadedFile::getInstance($modelUpload, 'excel');
            
            $model->params = serialize([
                                           'name' => $modelUpload->excel->name,
                                           'type' => $modelUpload->excel->type,
                                       ]);
            
            $model->result = $modelUpload->uploadExcel($model->created_at) ? serialize([
                                                                                           'code' => Import::RESULT_CODE_OK,
                                                                                           'msg' => 'Файл был успешно загружен на сервер',
                                                                                       ]) : serialize([
                                                                                                          'code' => Import::RESULT_CODE_ERROR,
                                                                                                          'msg' => 'Не удалось загрузить файл на сервер',
                                                                                                      ]);
            
            if (unserialize($model->result)['code'] == Import::RESULT_CODE_OK) {
                Yii::$app->session->setFlash('success', unserialize($model->result)['msg']);
            } else {
                Yii::$app->session->setFlash('error', unserialize($model->result)['msg']);
            }
            
            
            if (!$model->save()) {
                throw new Exception(serialize($model->firstErrors));
            }
            
            return $this->redirect([ '/admin/import/index' ]);
            
        }
        
        return $this->render('upload-excel', [
            'modelUpload' => $modelUpload,
        ]);
    }
    
    public function actionImportFromExcel()
    {
        $model = new ImportFromExcelForm();
        
        if (Yii::$app->request->isPost and $post = Yii::$app->request->post() and $model->load($post) and $model->validate()) {
            $reader = new Xls();
            $filename = 'stock-' . Import::find()
                                         ->where([
                                                     'type' => Import::TYPE_UPLOAD_EXCEL,
                                                 ])
                                         ->andFilterWhere([
                                                           'like', 'result', 's:4:"code";i:' . Import::RESULT_CODE_OK,
                                                       ])
                                         ->orderBy([
                                                       'created_at' => SORT_DESC,
                                                   ])
                                         ->one()
                    ->created_at . '.xls';
            
            $path = Import::getStockBasePath() . $filename;
            
            if (file_exists($path) and $reader->canRead($path)) {
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($path);
                
                $worksheet = $spreadsheet->getActiveSheet();
                
                $from = $model->from;
                $to = $model->to;
                $category_id = $model->category_id;
    
                $import = new Import();
    
                $import->user_id = Yii::$app->user->identity->id;
                $import->created_at = time();
                $import->type = Import::TYPE_IMPORT_FROM_EXCEL;
                $import->params = serialize([
                                                'from' => $from,
                                                'to' => $to,
                                                'category_id' => $category_id,
                                            ]);
    
                $err = false;
    
                try {
                    TransactionHelper::wrap(function () use ($from, $to, $worksheet, $category_id)
                    {
                        $prev = [];
            
                        for ($i = $from + 1; $i <= $to; $i++) {
                            if (empty($collection) or !empty($worksheet->getCell('D' . $i)->getValue())) {
                                $collection = $worksheet->getCell('D' . $i)->getValue();
                                continue;
                            }
                
                            $data = [
                                'model' => ucwords(strtolower($worksheet->getCell('E' . $i)->getValue())),
                                'firm' => ucwords(strtolower($worksheet->getCell('F' . $i)->getValue())),
                                'code' => $worksheet->getCell('G' . $i)->getValue(),
                                'color' => $worksheet->getCell('H' . $i)->getValue(),
                                'base_price' => (int) $worksheet->getCell('I' . $i)->getValue(),
                                'size' => (string) $worksheet->getCell('L' . $i)
                                                             ->getValue() === '0' ? ItemColorSize::WITHOUT_SIZE : (string) $worksheet->getCell('L' . $i)
                                                                                                                                     ->getValue(),
                                'quantity' => (int) $worksheet->getCell('M' . $i)->getValue(),
                            ];
                
                            // Получаем товар, если не существует такого, то создаем
                            if (!$item = Item::find()
                                             ->where([
                                                         'firm' => $data['firm'],
                                                         'model' => $data['model'],
                                                         'collection' => $collection,
                                                         'category_id' => $category_id,
                                                     ])
                                             ->asArray()
                                             ->one()) {
                    
                                $item = new Item();
                    
                                $item->firm = $data['firm'];
                                $item->model = $data['model'];
                                $item->collection = $collection;
                                $item->category_id = (int) $category_id;
                                $item->status = Status::STATUS_ACTIVE;
                                $item->rate = rand(75, 90);
                    
                                if (!$item->save()) {
                                    $msg = $item->firstErrors;
                                    throw new Exception("Не удалось сохранить товар на строке {$i}: " . reset($msg));
                                }
                    
                                $description = new ItemDescription();
                    
                                $description->item_id = $item['id'];
                    
                                if (!$description->save()) {
                                    $msg = $description->firstErrors;
                                    throw new Exception("Не удалось сохранить описание для товара на строке {$i}: " . reset($msg));
                                }
                    
                                $item = ArrayHelper::toArray($item);
                            }
                
                            if (!$color = ItemColor::find()
                                                   ->where([
                                                               'item_id' => $item['id'],
                                                               'code' => $data['code'],
                                                               'color' => $data['color'],
                                                           ])
                                                   ->asArray()
                                                   ->one()) {
                                $color = new ItemColor();
                                $color->item_id = $item['id'];
                                $color->code = $data['code'];
                                $color->color = $data['color'];
                                $color->status = Status::STATUS_ACTIVE;
                    
                                if (!$color->save()) {
                                    $msg = $color->firstErrors;
                                    throw new Exception("Не удалось сохранить цвет на строке {$i}: " . reset($msg));
                                }
                    
                                $color = ArrayHelper::toArray($color);
                            }
                
                            if (!$size = ItemColorSize::find()
                                                      ->where([
                                                                  'color_id' => $color['id'],
                                                                  'size' => $data['size'],
                                                              ])
                                                      ->asArray()
                                                      ->all()) {
                                $size = new ItemColorSize();
                                $size->color_id = $color['id'];
                                $size->size = $data['size'];
                                $size->quantity = $data['quantity'];
                                $size->base_price = $data['base_price'];
                                $size->status = Status::STATUS_ACTIVE;
                    
                                if (!$size->save()) {
                                    $msg = $size->firstErrors;
                                    throw new Exception("Не удалось сохранить размер на строке {$i}: " . reset($msg));
                                }
                    
                                $size = ArrayHelper::toArray($size);
                            }
                        }
            
                    });
                } catch (IntegrityException $exception) {
                    Yii::$app->errorHandler->logException($exception);
        
                    $err = true;
                }
    
                if (!$err) {
                    $import->result = serialize([
                                                    'code' => Import::RESULT_CODE_OK,
                                                    'msg' => 'Товары были успешно имортированы на сервер',
                                                ]);
        
                    Yii::$app->session->setFlash('success', 'Товары были успешно имортированы');
                } else {
                    $import->result = serialize([
                                                    'code' => Import::RESULT_CODE_ERROR,
                                                    'msg' => $exception->getMessage(),
                                                ]);
        
                    Yii::$app->session->setFlash('error', 'Ошибка при импортировании');
                }
                
                if ($import->save()) {
                    
                    return $this->redirect('/admin/import/index');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Невозможно прочитать файл с наличием');
            }
        }
    
        return $this->render('import-from-excel', [
            'model' => $model,
            'categories' => Category::getCategoriesIndexNameWithParents(),
        ]);
    }
    
    public function actionLoadMulcano($token = null, $firm, $model, array $types, $category_id,
                                      $collection = 'Pandamonium')
    {
        if ($token == 'tyztyz') {
            
            TransactionHelper::wrap(function () use ($firm, $model, $types, $category_id, $collection)
            {
                $sizes = [
                    'Mens' => [
                        'sizes' => [ 'XS', 'S', 'M', 'L', 'XL' ],
                        'price' => 880,
                        'name' => ' (Mens, 17+)',
                    ],
                    'Boys' => [
                        'sizes' => [ '8', '10', '12', '14' ],
                        'price' => 770,
                        'name' => ' (Boys, 12+)',
                    ],
                    'Toddler' => [
                        'sizes' => [ '1', '2', '3', '4', '5', '6', '7' ],
                        'price' => 660,
                        'name' => ' (Toddler, 5+)',
                    ],
                ];
                
                foreach ($types as $type_name => $data) {
                    if (is_array($data) and !empty($data)) {
                        
                        // Получаем товар, если не существует такого, то создаем
                        if (!$item = Item::find()
                            ->where([
                                        'firm' => $firm,
                                        'model' => $model . $sizes[$type_name]['name'],
                                        'category_id' => $category_id,
                                    ])
                            ->asArray()
                            ->one()) {
                            
                            $item = new Item();
                            
                            $item->firm = $firm;
                            $item->model = $model . $sizes[$type_name]['name'];
                            $item->collection = $collection;
                            $item->category_id = $category_id;
                            $item->status = Status::STATUS_ACTIVE;
                            $item->rate = rand(65, 90);
                            
                            if (!$item->save()) {
                                throw new Exception("Не удалось сохранить товар {$firm} {$model}");
                            }
                            
                            $description = new ItemDescription();
                            
                            $description->item_id = $item['id'];
                            
                            if (!$description->save()) {
                                throw new Exception("Не удалось сохранить описание для товара {$firm} {$model}");
                            }
                            
                            $item = ArrayHelper::toArray($item);
                        }
                        
                        foreach ($data as $code) {
                            
                            if (!$color = ItemColor::find()
                                ->where([
                                            'item_id' => $item['id'],
                                            'code' => $code,
                                            'color' => $type_name,
                                        ])
                                ->asArray()
                                ->one()) {
                                $color = new ItemColor();
                                $color->item_id = $item['id'];
                                $color->code = $code;
                                $color->color = $type_name;
                                $color->status = Status::STATUS_ACTIVE;
                                
                                if (!$color->save()) {
                                    throw new Exception("Не удалось сохранить цвет для товара {$firm} {$model} {$type_name}");
                                }
                                
                                $color = ArrayHelper::toArray($color);
                            }
                            
                            foreach ($sizes[$type_name]['sizes'] as $size_name) {
                                if (!$size = ItemColorSize::find()
                                    ->where([
                                                'color_id' => $color['id'],
                                                'size' => $size_name,
                                            ])
                                    ->asArray()
                                    ->all()) {
                                    $size = new ItemColorSize();
                                    $size->color_id = $color['id'];
                                    $size->size = $size_name === '0' ? ItemColorSize::WITHOUT_SIZE : $size_name;
                                    $size->quantity = 0;
                                    $size->base_price = $sizes[$type_name]['price'];
                                    $size->status = Status::STATUS_ACTIVE;
                                    
                                    if (!$size->save()) {
                                        throw new Exception("Не удалось сохранить размер для товара {$firm} {$model} {$type_name} {$size_name}");
                                    }
                                    
                                    $size = ArrayHelper::toArray($size);
                                }
                            }
                        }
                    }
                }
            });
            
            die('ok');
        }
        throw new NotFoundHttpException();
    }
    
    public function actionParsePics($token = null)
    {
        if ($token == 'tyztyz') {
            $queryWithPics = Item::find()
                ->select([ 'item.id' ])
                ->from(Item::tableName() . ' item')
                ->joinWith([ 'allColors colors' => function ($query)
                {
                    $query->joinWith([ 'mainImage' ]);
                },
                           ]);
            
            $items = Item::find()
                ->joinWith([ 'allColors colors' ])
                ->where([ 'not in', 'item.id', $queryWithPics ])
                ->andWhere([
                               'firm' => 'Saucony',
                           ])
                ->orderBy([
                              'id' => SORT_DESC,
                          ])
                ->asArray()
                ->all();
            
            $pathTmp = Yii::getAlias('@webroot') . '/files/tmp/';
            
            if (!file_exists($pathTmp)) {
                FileHelper::createDirectory($pathTmp, 0777);
            }
            
            $count_colors = 0;
            $count_images = 0;
            
            Yii::$app->db->createCommand('SET SESSION wait_timeout = 28800;')->execute();
            
            if (!empty($items) and is_array($items)) {
                $client = new Client([ 'base_uri' => 'http://saucony.kiev.ua', ]);
                foreach ($items as $item) {
                    foreach ($item['allColors'] as $color) {
                        $response = $client->request('GET',
                                                     'search',
                                                     [ 'query' => [
                                                         'search' => $color['code'],
                                                     ] ])->getBody()->getContents();
                        
                        // get search page
                        $pq = phpQuery::newDocumentHTML($response);
    
                        $link = $pq->find('#search_block #content .row .product .product-img a')->attr('href');
    
                        if (empty($link)) {
                            continue;
                        }
                        
                        // get product page
                        $response = $client->request('GET', $link)->getBody()->getContents();
                        
                        $pq = phpQuery::newDocumentHTML($response);
    
                        $code = $pq->find('#tovar .col-md-4 ul.list-unstyled li:first')->html();
                        $start = strpos($code, ':');
                        $code = trim(substr($code, $start + 1));
                        
                        if ($code !== $color['code']) {
                            continue;
                        }
    
                        $images = $pq->find('#tovar .col-md-8 #carouselExampleIndicators ul.carousel-inner')
                                     ->children();
                        
                        if (empty($images)) {
                            continue;
                        }
                        
                        foreach ($images as $image) {
                            $pqImage = pq($image);
    
                            $pic = $pqImage->find('img')->attr('src');
    
                            if (!$this->copyRemote('http://saucony.kiev.ua', $pic,
                                                   $pathTmp . basename($pic))) {
                                continue;
                            }
                            
                        }
                        
                        foreach (scandir($pathTmp) as $one) {
                            if (in_array($one, [ '.', '..' ])) {
                                continue;
                            }
                            
                            $mdl = new Image();
                            // generate filename
                            $mdl->url = Yii::$app->security->generateRandomString(16) . '_' . time() . '.' . strtolower(pathinfo($one,
                                                                                                                                 PATHINFO_EXTENSION));
                            $mdl->type = Image::TYPE_ITEM;
                            $mdl->subject_id = $color['id'];
                            
                            if (!$mdl->save()) {
                                throw new Exception("Не удалось сохранить картинку для товара с артикулом: {$color['code']}");
                            }
                            
                            foreach (Image::getSizes() as $size => $folder) {
                                // create path to save image
                                $path = Yii::getAlias('@webroot') . $mdl->getPath($size);
                                
                                // check if path exists
                                if (!file_exists($path)) {
                                    // if not -> create
                                    FileHelper::createDirectory($path, 0777);
                                }
                                
                                if (!copy($pathTmp . $one, $path . $mdl->url)) {
                                    throw new NotFoundHttpException('Изображения не были сохранены');
                                }
                                
                                if ($size == Image::SIZE_ORIGINAL) {
                                    Image::resize($path . $mdl->url, 1024, 1024, true);
                                } elseif ($size == Image::SIZE_MEDIUM) {
                                    Image::resize($path . $mdl->url, 512, 512, true);
                                } elseif ($size == Image::SIZE_THUMBNAIL) {
                                    Image::resize($path . $mdl->url, 192, 192);
                                }
                                
                            }
                            
                            
                            $count_images += 1;
                            unlink($pathTmp . $one);
                            unset($mdl);
                            
                        }
                        
                        $count_colors += 1;
                        
                    }
                }
                
            }
            
            echo "<p> Цветов добавлено: {$count_colors} </p><br>";
            echo "<p> Картинок добавлено: {$count_images} </p><br>";
            
            die('ok');
        }
        
        throw new NotFoundHttpException('Страница не найдена.');
    }
    
    
    public function copyRemote($baseUri, $fromUrl, $toFile)
    {
        try {
            $client = new Client([ 'base_uri' => $baseUri, ]);
            $response = $client->request('GET', $fromUrl)->getBody()->getContents();
            
            return file_put_contents($toFile, $response);
        } catch (ErrorException $e) {
            Yii::$app->errorHandler->logException($e);
            
            return false;
        }
    }
}
