<?php

namespace app\modules\admin\controllers;

use app\components\helpers\TransactionHelper;
use app\models\Banner;
use app\models\Image;
use app\models\UploadForm;
use app\modules\admin\models\BannerSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Banner model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $modelImage = $model->image;
        
        return $this->render('view', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }
    
    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (( $model = Banner::findOne($id) ) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();
        $modelUpload = new UploadForm();
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            try {
                
                if ($model->load(Yii::$app->request->post()) and $model->save()) {
                    
                    TransactionHelper::wrap(function () use ($model, $modelUpload)
                    {
                        $modelUpload->setType(Image::TYPE_BANNER);
                        $modelUpload->setSubject($model->id);
                        $modelUpload->image = UploadedFile::getInstance($modelUpload, 'image');
                        
                        if (!$modelUpload->uploadImage()) {
                            throw new NotFoundHttpException('Не удалось сохранить изображение');
                        }
                        
                    });
                }
                
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            
            return $this->redirect([ 'index' ]);
        }
        
        return $this->render('form', [
            'model' => $model,
            'modelUpload' => $modelUpload,
        ]);
    }
    
    /**
     * Updates an existing Banner model.
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
        $modelUpload = new UploadForm(Image::TYPE_BANNER, $model->id);
        
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            try {
                
                if ($model->load(Yii::$app->request->post()) and $model->save()) {
                    
                    TransactionHelper::wrap(function () use ($model, $modelUpload)
                    {
                        $modelUpload->image = UploadedFile::getInstance($modelUpload, 'image');
                        
                        if (!$modelUpload->uploadImage()) {
                            throw new NotFoundHttpException('Не удалось сохранить изображение');
                        }
                        Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                    });
                }
                
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            
            return $this->redirect([ 'view', 'id' => $model->id ]);
        }
        
        return $this->render('form', [
            'model' => $model,
            'modelUpload' => $modelUpload,
        ]);
    }
    
    /**
     * Deletes an existing Banner model.
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
