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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
                
                if ($model->load($post) and $model->validate()) {
                    TransactionHelper::wrap(function () use ($model, $post)
                    {
                        $model->save();
                        
                        $fromCat = [];
                        $fromItems = [];
                        
                        if (!empty($post['Category']) and $post['Category'] !== 'true') {
                            $category = explode(',', $post['Category']);
                            if (!empty($category)) {
                                $fromCat = array_column(ItemColorSize::find()
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
                        }
                        
                        if (!empty($post['ItemColorSize'])) {
                            $fromItems = array_column(ItemColorSize::find()
                                                                   ->where([
                                                                               'in', 'id', $post['ItemColorSize'],
                                                                           ])
                                                                   ->indexBy('id')
                                                                   ->asArray()
                                                                   ->all(), 'id', 'id');
                        }
                        
                        $result = array_merge($fromItems, $fromCat);
                        
                        echo '<pre>';
                        var_dump($result);
                        echo '</pre>';
                        die;
                        
                    });
                    
                }
                
            } catch
            (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        
        return $this->render('create', [ 'model' => $model,
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
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([ 'view', 'id' => $model->id ]);
        }
        
        return $this->render('update', [
            'model' => $model,
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
