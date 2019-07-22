<?php

namespace app\modules\main\controllers;

use app\components\helpers\SeoHelper;
use app\components\models\Status;
use app\models\Banner;
use app\models\Item;
use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    
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
                       }, ])
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
                       ->limit(6)
                       ->asArray()
                       ->all();
        
        SeoHelper::putDefaultTags();
        
        return $this->render('index', [
            'banners' => $banners,
            'popular' => $popular,
        ]);
    }
}
