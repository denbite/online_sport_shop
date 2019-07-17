<?php

namespace app\modules\main\controllers;

use app\components\models\Status;
use app\models\Banner;
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
        
        return $this->render('index', [
            'banners' => $banners,
        ]);
    }
}
