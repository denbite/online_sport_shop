<?php

namespace app\modules\admin\controllers;

use app\components\helpers\TransactionHelper;
use app\models\Item;
use app\models\Promotion;
use app\models\PromotionItem;
use app\modules\admin\models\PromotionSearch;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PromotionController implements the CRUD actions for Promotion model.
 */
class PromotionController
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
     * Lists all Promotion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PromotionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'types' => Promotion::getTypes(),
        ]);
    }
    
    /**
     * Displays a single Promotion model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
            'types' => Promotion::getTypes(),
            'items' => Item::getItemsIdName(),
            'current' => array_column($model->items, 'id'),
        ]);
    }
    
    /**
     * Finds the Promotion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Promotion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (( $model = Promotion::findOne($id) ) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Creates a new Promotion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Promotion();
        
        if (Yii::$app->request->isPost) {
            
            $post = Yii::$app->request->post();
            
            try {
    
                if ($model->load($post) and $model->save()) {
                    TransactionHelper::wrap(function () use ($model, $post)
                    {
                        $items = [];
                        
    
                        if (!empty($post['Category']) and $post['Category'] !== 'true') {
                            $category = explode(',', $post['Category']);
    
                            if (!empty($category)) {
                                $items = array_column(Item::find()
                                                          ->where([
                                                              'in', 'category_id', $category,
                                                                   ])
                                                          ->indexBy('id')
                                                          ->asArray()
                                                          ->all(), 'id', 'id');
                            }
                        } elseif (!empty($post['PromotionItem'])) {
                            $items = array_column(Item::find()
                                                      ->where([
                                                          'in', 'id', $post['PromotionItem'],
                                                                   ])
                                                      ->indexBy('id')
                                                      ->asArray()
                                                      ->all(), 'id', 'id');
                        }
    
                        if (!empty($items) and is_array($items)) {
                            $result = [];
                            foreach ($items as $item) {
            
                                $result['PromotionItem'][] = [
                                    'item_id' => $item,
                                    'promotion_id' => $model->id,
                                ];
            
                            }
        
                            for ($i = 0; $i < count($result['PromotionItem']); $i++) {
                                $mdl[] = new PromotionItem();
                            }
        
                            if (!empty($result) and Model::loadMultiple($mdl,
                                    $result) and Model::validateMultiple($mdl)) {
                                foreach ($mdl as $m) {
                                    if (!$m->save()) {
                                        throw new Exception('Не удалось сохранить товары, учавствующие в акции');
                                    }
                                }
            
                                Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                            }
                        }
            
                    });
                    
                }
                
            } catch
            (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
    
            return $this->redirect([ 'index', ]);
        }
    
        return $this->render('form', [
            'model' => $model,
            'types' => Promotion::getTypes(),
            'items' => Item::getItemsIdName(),
        ]);
    }
    
    /**
     * Updates an existing Promotion model.
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
    
        if (Yii::$app->request->isPost) {
        
            $post = Yii::$app->request->post();
    
            try {
            
                if ($model->load($post) and $model->save()) {
                    TransactionHelper::wrap(function () use ($model, $post)
                    {
                        $items = [];
                    
                    
                        if (!empty($post['Category']) and $post['Category'] !== 'true') {
                            $category = explode(',', $post['Category']);
    
                            if (!empty($category)) {
                                $items = array_column(Item::find()
                                                          ->where([
                                                              'in', 'category_id', $category,
                                                          ])
                                                          ->indexBy('id')
                                                          ->asArray()
                                                          ->all(), 'id', 'id');
                            }
                        } elseif (!empty($post['PromotionItem'])) {
                            $items = array_column(Item::find()
                                                      ->where([
                                                          'in', 'id', $post['PromotionItem'],
                                                      ])
                                                      ->indexBy('id')
                                                      ->asArray()
                                                      ->all(), 'id', 'id');
                        }
    
                        PromotionItem::deleteAll([ 'promotion_id' => $model->id ]);
    
                        if (!empty($items) and is_array($items)) {
                            $result = [];
                            foreach ($items as $item) {
            
                                $result['PromotionItem'][] = [
                                    'item_id' => $item,
                                    'promotion_id' => $model->id,
                                ];
                            
                            }
        
                            for ($i = 0; $i < count($result['PromotionItem']); $i++) {
                                $mdl[] = new PromotionItem();
                            }
        
                            if (!empty($result) and Model::loadMultiple($mdl,
                                    $result) and Model::validateMultiple($mdl)) {
                                foreach ($mdl as $m) {
                                    if (!$m->save()) {
                                        throw new Exception('Не удалось сохранить товары, учавствующие в акции');
                                    }
                                }
            
                                Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                            }
                        }
                    
                    });
                
                }
            
            } catch
            (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
    
            return $this->redirect([ 'index', ]);
        }
    
        return $this->render('form', [
            'model' => $model,
            'types' => Promotion::getTypes(),
            'items' => Item::getItemsIdName(),
            'current' => array_column($model->items, 'id'),
        ]);
    }
    
    /**
     * Deletes an existing Promotion model.
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
}
