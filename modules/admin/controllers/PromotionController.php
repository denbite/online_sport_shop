<?php

namespace app\modules\admin\controllers;

use app\components\helpers\TransactionHelper;
use app\models\ItemColorSize;
use app\models\Promotion;
use app\modules\admin\models\PromotionSearch;
use Yii;
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
            'sizes' => ItemColorSize::getAllEnableItemColorSizeNames(),
            'current' => array_column($model->sizes, 'id'),
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
                        $sizes = [];
            
                        if (!empty($post['Category']) and $post['Category'] !== 'true') {
                            $category = explode(',', $post['Category']);
                            if (!empty($category)) {
                                $sizes = array_column(ItemColorSize::find()
                                                                   ->joinWith([ 'color color' => function ($q)
                                                                   {
                                                                       $q->joinWith('item item');
                                                                   } ])
                                                                   ->where([
                                                                       'in', 'item.category_id', $category,
                                                                       //                                                                       'item.status' => Status::STATUS_ACTIVE,
                                                                       //                                                                       'color.status' => Status::STATUS_ACTIVE,
                                                                       //                                                                       'size.status' => Status::STATUS_ACTIVE,
                                                                   ])
                                                                   ->indexBy('id')
                                                                   ->asArray()
                                                                   ->all(), 'id', 'id');
                            }
                        } elseif (!empty($post['ItemColorSize'])) {
                            $sizes = array_column(ItemColorSize::find()
                                                                   ->where([
                                                                       'in', 'id', $post['ItemColorSize'],
                                                                   ])
                                                                   ->indexBy('id')
                                                                   ->asArray()
                                                                   ->all(), 'id', 'id');
                        }
    
                        if (!empty($sizes) and is_array($sizes)) {
                            foreach ($sizes as $item) {
            
                                $result[] = [
                                    'size_id' => $item,
                                    'promotion_id' => $model->id,
                                ];
            
                            }
                            if (!empty($result)) {
            
                                Yii::$app->db->createCommand()->batchInsert('{{%size_promotion}}', [
                                    'size_id', 'promotion_id',
                                ], $result)->execute();
                                Yii::$app->session->setFlash('success', 'Успешно сохранено');
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
            'sizes' => ItemColorSize::getAllEnableItemColorSizeNames(), ]);
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
                        $sizes = [];
                    
                        if (!empty($post['Category']) and $post['Category'] !== 'true') {
                            $category = explode(',', $post['Category']);
                            if (!empty($category)) {
                                $sizes = array_column(ItemColorSize::find()
                                                                   ->joinWith([ 'color color' => function ($q)
                                                                   {
                                                                       $q->joinWith('item item');
                                                                   } ])
                                                                   ->where([
                                                                       'in', 'item.category_id', $category,
                                                                       //                                                                       'item.status' => Status::STATUS_ACTIVE,
                                                                       //                                                                       'color.status' => Status::STATUS_ACTIVE,
                                                                       //                                                                       'size.status' => Status::STATUS_ACTIVE,
                                                                   ])
                                                                   ->indexBy('id')
                                                                   ->asArray()
                                                                   ->all(), 'id', 'id');
                            }
                        } elseif (!empty($post['ItemColorSize'])) {
                            $sizes = array_column(ItemColorSize::find()
                                                               ->where([
                                                                   'in', 'id', $post['ItemColorSize'],
                                                               ])
                                                               ->indexBy('id')
                                                               ->asArray()
                                                               ->all(), 'id', 'id');
                        }
                    
                        if (!empty($sizes) and is_array($sizes)) {
                            foreach ($sizes as $item) {
                            
                                $result[] = [
                                    'size_id' => $item,
                                    'promotion_id' => $model->id,
                                ];
                            
                            }
                            if (!empty($result)) {
                            
                                Yii::$app->db->createCommand()
                                             ->delete('{{%size_promotion}}', [ 'promotion_id' => $model->id ])
                                             ->execute();
                            
                                Yii::$app->db->createCommand()->batchInsert('{{%size_promotion}}', [
                                    'size_id', 'promotion_id',
                                ], $result)->execute();
                                Yii::$app->session->setFlash('success', 'Успешно сохранено');
                            }
                        }
                    
                    });
                
                }
            
            } catch
            (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        
            return $this->redirect([ 'view', 'id' => $model->id ]);
        }
    
        return $this->render('form', [
            'model' => $model,
            'types' => Promotion::getTypes(),
            'sizes' => ItemColorSize::getAllEnableItemColorSizeNames(),
            'current' => array_column($model->sizes, 'id'),
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
