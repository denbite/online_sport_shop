<?php

namespace app\modules\main\controllers;

use app\components\helpers\SeoHelper;
use app\components\models\Status;
use app\models\Banner;
use app\models\Category;
use app\models\Item;
use app\modules\user\models\User;
use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController
    extends Controller
{
    
    public function beforeAction($action)
    {
        SeoHelper::putOpenGraphTags([
                                        'og:site_name' => 'Интернет-магазин Aquista',
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
        $banners = Banner::find()
                         ->joinWith('image')
                         ->where([
                                     'status' => Status::STATUS_ACTIVE,
                                 ])
                         ->andWhere([
                                        'and', [ '<', 'publish_from', time() ], [ '>', 'publish_to', time() ],
                                    ])
                         ->orderBy([ 'publish_from' => SORT_DESC ])
                         ->asArray()
                         ->all();
        
        $popular = Item::find()
            ->select([ 'item.*', 'MIN(sizes.sell_price) as min_price' ])
            ->from(Item::tableName() . ' item')
            ->joinWith([ 'allColors colors' => function ($query)
                       {
                           $query->joinWith([ 'allSizes sizes', 'mainImage' ]);
                       }, 'category' => function ($query)
                       {
                           $query->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
                       } ])
            ->with([ 'promotion' => function ($query)
                       {
                           $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
                       } ])
            ->where([
                                   'item.status' => Status::STATUS_ACTIVE,
                                   'colors.status' => Status::STATUS_ACTIVE,
                                   'sizes.status' => Status::STATUS_ACTIVE,
                               ])
            ->andWhere([ '>', 'sizes.quantity', 0 ])
            ->groupBy('id')
            ->orderBy([ 'rate' => SORT_DESC ])
            ->limit(8)
            ->asArray()
            ->all();
    
        $categories = Category::findOne([ 'lvl' => 0, 'root' => 1 ])
                              ->children(1)
                              ->with('image')
                              ->andWhere([ 'active' => Status::STATUS_ACTIVE ])
                              ->all();
        
        SeoHelper::putDefaultTags();
    
        //        $instagram_token = Config::findOne([ 'name' => 'instagram_access_token' ])->value;
        
        return $this->render('index', [
            'banners' => $banners,
            'popular' => $popular,
            'categories' => $categories,
            //            'instagram_token' => $instagram_token,
        ]);
    }
    
    public function actionContacts()
    {
        return $this->render('contacts');
    }
    
    public function actionDelivery()
    {
        return $this->render('delivery');
    }
    
    public function actionPayment()
    {
        return $this->render('payment');
    }
    
    public function actionSizes()
    {
        return $this->render('sizes');
    }
    
    public function actionTest()
    {
        return $this->render('@app/modules/user/mails/reset-password-request', [
            'user' => User::findByEmail('mirloxd3@gmail.com'),
        ]);
    }
}
