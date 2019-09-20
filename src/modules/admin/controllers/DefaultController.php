<?php

namespace app\modules\admin\controllers;

use app\components\helpers\TransactionHelper;
use app\components\models\Status;
use app\models\Item;
use app\models\ItemColor;
use app\models\ItemColorSize;
use app\models\ItemDescription;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `admin` module
 */
class DefaultController
    extends Controller
{
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionLoadExcel($token = null, $from = null, $to = null, $category_id = null)
    {
        $reader = new Xls();
        $path = Yii::getAlias('@webroot') . '/files/stock.xls';
        
        if ($token == 'tyztyztyz' and !empty($from) and !empty($to) and !empty($category_id) and $from < $to and file_exists($path) and $reader->canRead($path)) {
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            
            $worksheet = $spreadsheet->getActiveSheet();
            
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
                        'size' => (string) $worksheet->getCell('L' . $i)->getValue(),
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
                        $item->category_id = $category_id;
                        $item->status = Status::STATUS_ACTIVE;
                        $item->rate = rand(75, 90);
                        
                        if (!$item->save()) {
                            throw new Exception('Не удалось сохранить товар на строке ' . $i);
                        }
    
                        $description = new ItemDescription();
    
                        $description->item_id = $item['id'];
    
                        if (!$description->save()) {
                            throw new Exception('Не удалось сохранить описание для товара на строке ' . $i);
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
                            throw new Exception('Не удалось сохранить цвет на строке ' . $i);
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
                        $size->size = $data['size'] === '0' ? ItemColorSize::WITHOUT_SIZE : $data['size'];
                        $size->quantity = $data['quantity'];
                        $size->base_price = $data['base_price'];
                        $size->status = Status::STATUS_ACTIVE;
                        
                        if (!$size->save()) {
                            throw new Exception('Не удалось сохранить размер на строке ' . $i);
                        }
                        
                        $size = ArrayHelper::toArray($size);
                    }
                }
                
            });
            
            die('200');
            
            return $this->redirect('/admin/default/index');
        }
    
        throw new NotFoundHttpException();
    }
    
    public function actionLoadMulcano($token = null, $firm, $model, array $colors, $price, $category_id,
                                      $collection = 'Pandamonium')
    {
        if ($token == 'tyztyz') {
    
            TransactionHelper::wrap(function () use ($firm, $model, $colors, $price, $category_id, $collection)
            {
                $sizes = [
                    'Mens' => [
                        'XS', 'S', 'M', 'L', 'XL',
                    ],
                    'Boys' => [
                        'Boys 8', 'Boys 10', 'Boys 12', 'Boys 14',
                    ],
                    'Toddler' => [
                        'Toddler 1', 'Toddler 2', 'Toddler 3', 'Toddler 4', 'Toddler 5', 'Toddler 6', 'Toddler 7',
                    ],
                ];
                    // Получаем товар, если не существует такого, то создаем
                    if (!$item = Item::find()
                                     ->where([
                                                 'firm' => $firm,
                                                 'model' => $model,
                                                 'category_id' => $category_id,
                                             ])
                                     ->asArray()
                                     ->one()) {
                        
                        $item = new Item();
                        
                        $item->firm = $firm;
                        $item->model = $model;
                        $item->collection = $collection;
                        $item->category_id = $category_id;
                        $item->status = Status::STATUS_ACTIVE;
                        $item->rate = rand(85, 97);
                        
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
    
                foreach ($colors as $color_name => $data) {
        
                    if (is_array($data) and !empty($data)) {
                        foreach ($data as $code) {
                
                            if (!$color = ItemColor::find()
                                                   ->where([
                                                               'item_id' => $item['id'],
                                                               'code' => $code,
                                                               'color' => $color_name,
                                                           ])
                                                   ->asArray()
                                                   ->one()) {
                                $color = new ItemColor();
                                $color->item_id = $item['id'];
                                $color->code = $code;
                                $color->color = $color_name;
                                $color->status = Status::STATUS_ACTIVE;
                    
                                if (!$color->save()) {
                                    throw new Exception("Не удалось сохранить цвет для товара {$firm} {$model} {$color_name}");
                                }
                    
                                $color = ArrayHelper::toArray($color);
                            }
                
                            foreach ($sizes[$color_name] as $size_name) {
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
                                    $size->base_price = $price;
                                    $size->status = Status::STATUS_ACTIVE;
                        
                                    if (!$size->save()) {
                                        throw new Exception("Не удалось сохранить размер для товара {$firm} {$model} {$color_name} {$size_name}");
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
}
