<?php

namespace app\modules\admin\controllers;

use app\models\Item;
use app\models\ItemColor;
use app\models\ItemColorSize;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemSizeController extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }
    
    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null, $item = null)
    {
        $model = new ItemColorSize();
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if ($model->load($post) and $model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Размер сохранен');
                    
                    return $this->redirect([ '/admin/item/index' ]);
                }
                Yii::$app->session->setFlash('error', reset($model->errors));
            }
        }
        
        if ($id = (int) $id and $id > 0) {
            $model->color_id = $id;
        }
        
        return $this->render('create', [
            'model' => $model,
            'item' => !empty($item) ? $item : 0,
            'items' => Item::getItemsIdName(),
            'colors' => ItemColor::getColorById($item),
        ]);
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
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([ 'view', 'id' => $model->id ]);
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
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
    
    public function actionQuery()
    {
        $result = [];
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost && $post = Yii::$app->request->post()) {
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
    
}
