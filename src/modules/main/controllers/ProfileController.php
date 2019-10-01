<?php

namespace app\modules\main\controllers;

use app\components\helpers\SeoHelper;
use app\models\Order;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class ProfileController
    extends Controller
{
    
    public function beforeAction($action)
    {
        SeoHelper::putOpenGraphTags([
                                        'og:site_name' => 'Интернет-магазин Aquista',
                                        'og:image' => Yii::$app->params['host'] . '/images/logo-admin-icon.png',
                                    ]);
        
        return parent::beforeAction($action);
    }
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    /**
     * Processing all slugs
     *
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest and $user = Yii::$app->user->identity) {
            
            $orders = Order::find()
                           ->where([
                                       'user_id' => $user['id'],
                                   ])
                           ->orderBy([
                                         'created_at' => SORT_DESC,
                                     ])
                           ->asArray()
                           ->all();
            
            return $this->render('index', [
                'user' => $user,
                'orders' => $orders,
            ]);
        }
        
        return $this->redirect([ '/user/default/login' ]);
    }
}
