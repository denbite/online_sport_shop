<?php

namespace app\modules\admin\controllers;

use app\components\helpers\TransactionHelper;
use app\models\Config;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController
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
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = ArrayHelper::index(Config::find()->all(), 'name', 'group');
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            try {
                
                TransactionHelper::wrap(function () use ($params, $post)
                {
                    foreach ($params as &$group) {
                        Model::loadMultiple($group, $post);
                        
                        foreach ($group as $key => $conf) {
                            //                            if ($key == 'priceMultiplier' and $conf->oldAttributes['value'] != $conf->attributes['value']) {
                            //                                ItemColorSize::updateAllPrices($conf->attributes['value']);
                            //                            }
                            
                            if (!$conf->save()) {
                                throw new Exception('Не удалось сохранить изменения');
                            }
                        }
                    }
                    
                    Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                });
            } catch (Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            
        }
        
        return $this->render('index', [
            'params' => $params,
        ]);
    }
    
    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (( $model = Config::findOne($id) ) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
