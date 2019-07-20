<?php

namespace app\modules\admin\controllers;

use app\components\helpers\TransactionHelper;
use app\models\Category;
use app\models\Image;
use app\models\Item;
use app\models\ItemColor;
use app\models\ItemColorSize;
use app\models\ItemDescription;
use app\models\UploadForm;
use app\modules\admin\models\ItemSearch;
use Yii;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController
    extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }
    
    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render(
            'index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'category' => Category::getAllCategoriesByRoot(),
                'categories' => Category::getCategoriesIndexNameWithParents(),
            ]
        );
    }
    
    /**
     * Displays a single Item model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $modelDescription = $model->description;
        $modelColors = $model->allColors;
        
        foreach ($modelColors as $modelColor) {
            $modelSizes[$modelColor->id] = $modelColor->allSizes;
            $modelImages[$modelColor->id] = $modelColor->allImages;
        }
        
        return $this->render(
            'view', [
                'model' => $model,
                'modelColors' => !empty($modelColors) ? $modelColors : [],
                'modelSizes' => !empty($modelSizes) ? $modelSizes : [],
                'modelImages' => !empty($modelImages) ? $modelImages : [],
                'modelDescription' => !empty($modelDescription) ? $modelDescription : [],
            ]
        );
    }
    
    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (( $model = Item::findOne($id) ) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();
        $modelDescription = new ItemDescription();
        
        if (Yii::$app->request->isPost) {
            
            $post = Yii::$app->request->post();
    
            if (!empty($post[$modelDescription->formName()]['small_list_array']) and $list = $post[$modelDescription->formName()]['small_list_array'] and is_array($list)) {
                foreach ($list as $index => $one) {
                    if (empty($one)) {
                        unset($list[$index]);
                    }
                }
                $modelDescription->small_list = implode(ItemDescription::ITEMS_SEPARATOR, $list);
                unset($list);
            }
    
            if (!empty($post[$modelDescription->formName()]['list_array']) and $list = $post[$modelDescription->formName()]['list_array'] and is_array($list) and key_exists('key',
                    $list) and key_exists('value', $list) and count($list['key']) == count($list['value'])) {
                for ($i = 0; $i < count($list['key']); $i++) {
                    $list['result'][] = implode(ItemDescription::PARTS_SEPARATOR,
                        [ $list['key'][$i], $list['value'][$i] ]);
                }
        
                $modelDescription->list = implode(ItemDescription::ITEMS_SEPARATOR, $list['result']);
                unset($list);
            }
    
            // add transactions
            if ($model->load($post) and $model->validate() and $modelDescription->load($post)) {
                try {
                    TransactionHelper::wrap(function () use ($model, $modelDescription)
                    {
                        if ($model->save() and $modelDescription->item_id = $model->id and $modelDescription->save()) {
                            
                            Yii::$app->session->setFlash('success', 'Товар успешно создан');
                        }
                    });
                } catch (\Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                
                return $this->redirect([ '/admin/item/create-color', 'id' => (int) $model->id ]);
            }
        }
        
        return $this->render(
            'create', [
                'model' => $model,
                'modelDescription' => $modelDescription,
                'categories' => Category::getCategoriesIndexNameWithParents(),
            ]
        );
    }
    
    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelDescription = $model->description;
        
        $modelColors = !empty($model->allColors) ? $model->allColors : [];
        
        foreach ($modelColors as $modelColor) {
            $modelColorsSizes[$modelColor->id] = $modelColor->allSizes;
            $modelUploads[$modelColor->id] = new UploadForm(Image::TYPE_ITEM, $modelColor->id);
        }
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
    
            if (!empty($post[$modelDescription->formName()]['small_list_array']) and $list = $post[$modelDescription->formName()]['small_list_array'] and is_array($list)) {
                foreach ($list as $index => $one) {
                    if (empty($one)) {
                        unset($list[$index]);
                    }
                }
                $modelDescription->small_list = implode(ItemDescription::ITEMS_SEPARATOR, $list);
                unset($list);
            }
    
            if (!empty($post[$modelDescription->formName()]['list_array']) and $list = $post[$modelDescription->formName()]['list_array'] and is_array($list) and key_exists('key',
                    $list) and key_exists('value', $list) and count($list['key']) == count($list['value'])) {
                for ($i = 0; $i < count($list['key']); $i++) {
                    $list['result'][] = implode(ItemDescription::PARTS_SEPARATOR,
                        [ $list['key'][$i], $list['value'][$i] ]);
                }
        
                $modelDescription->list = implode(ItemDescription::ITEMS_SEPARATOR, $list['result']);
                unset($list);
            }
            
            try {
                
                if ($model->load($post) and $model->validate() and $modelDescription->load($post) and $modelDescription->validate() and Model::loadMultiple($modelColors,
                        $post) and Model::validateMultiple($modelColors) and !empty($modelColorsSizes)) {
                    // load sizes and validate
                    foreach ($modelColorsSizes as $color_id => $modelSizes) {
                        
                        $modelUploads[$color_id]->images = UploadedFile::getInstancesByName("UploadForm[{$color_id}][images]");
                        
                        if (!Model::loadMultiple($modelSizes,
                                $post['ItemColorSize'],
                                $color_id) or !Model::validateMultiple($modelSizes) or !$modelUploads[$color_id]->validate()) {
                            throw new \Exception('Не удалось сохранить изменения, добавьте размер для всех существующих цветов');
                        }
                        
                    }
                    
                    TransactionHelper::wrap(function () use (
                        $model, $modelDescription, $modelColors, $modelColorsSizes, $modelUploads
                    )
                    {
                        
                        $model->save();
                        
                        $modelDescription->save();
                        
                        foreach ($modelColors as $modelColor) {
                            $modelColor->save();
                            foreach ($modelColorsSizes[$modelColor->id] as $modelSize) {
                                $modelSize->save();
                            }
                            $modelUploads[$modelColor->id]->uploadImages();
                        }
                        
                        Yii::$app->session->setFlash('success', 'Изменения успешно сохранены');
                        
                    });
                    
                } else {
                    throw new \Exception('Не удалось сохранить изменения, добавьте хотя бы один цвет, размер и описание');
                }
            } catch
            (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            
            return $this->redirect([
                'view',
                'id' => $model->id,
            ]);
        }
    
        $modelDescription->small_list_array = explode(ItemDescription::ITEMS_SEPARATOR, $modelDescription->small_list);
        $modelDescription->list_array = explode(ItemDescription::ITEMS_SEPARATOR, $modelDescription->list);
        
        return $this->render(
            'update', [
                'model' => $model,
                'modelDescription' => !empty($modelDescription) ? $modelDescription : null,
                'modelColors' => !empty($modelColors) ? $modelColors : [],
                'modelColorsSizes' => !empty($modelColorsSizes) ? $modelColorsSizes : [],
                'modelUploads' => !empty($modelUploads) ? $modelUploads : [],
                'categories' => Category::getCategoriesIndexNameWithParents(),
            ]
        );
    }
    
    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->redirect([ 'index' ]);
    }
    
    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateColor($id = null)
    {
        $model = new ItemColor();
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if ($model->load($post) and $model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Цвет успешно добавлен');
                    
                    return $this->redirect([ '/admin/item/create-size', 'id' => $model->id, 'item' => $model->item_id ]);
                }
                
                Yii::$app->session->setFlash('error', 'Не удалось сохранить цвет');
            }
        }
        
        if ($id = (int) $id and $id > 0) {
            $model->item_id = $id;
        }
        
        return $this->render('create-color', [
            'model' => $model,
            'items' => Item::getItemsIdName(),
        ]);
    }
    
    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateSize($id = null, $item = null)
    {
        $model = new ItemColorSize();
        
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->isAjax) {
                $result = [];
                if ($post = Yii::$app->request->post()) {
                    switch ($post['query']) {
                        case 'getColors':
                            if ($item_id = (int) $post['item_id'] and $item_id > 0) {
                                $result = ArrayHelper::map(ItemColor::find()
                                                                    ->where([ 'item_id' => $item_id ])
                                                                    ->asArray()
                                                                    ->all(), 'id', 'color');
                            }
                            break;
                        default:
                            $result = null;
                            break;
                    }
                } else {
                    exit('Данные не были получены');
                }
                
                Yii::$app->response->format = Response::FORMAT_JSON;
                
                return $result;
            }
            $post = Yii::$app->request->post();
            
            if ($model->load($post) and $model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Размер сохранен');
                    
                    return $this->refresh();
                }
                Yii::$app->session->setFlash('error', 'Не удалось сохранить размер');
            }
        }
        
        if ($id = (int) $id and $id > 0) {
            $model->color_id = $id;
        }
        
        return $this->render('create-size', [
            'model' => $model,
            'item' => !empty($item) ? $item : 0,
            'items' => Item::getItemsIdName(),
            'colors' => ItemColor::getColorById($item),
        ]);
    }
    
}