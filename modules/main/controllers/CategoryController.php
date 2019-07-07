<?php

namespace app\modules\main\controllers;

use app\components\models\Status;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `main` module
 */
class CategoryController
    extends Controller
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
     * Processing all slugs after /category/{$slug}
     *
     * @param bool $slug
     *
     * @return string
     */
    public function actionIndex($slug = false)
    {
        $slug = (int) preg_replace("[\D+]", "", $slug);
        
        // if empty after regexp check
        if (empty($slug)) {
            // get all nodes lvl=1
            
            $items = Category::find()
                             ->with('image')
                             ->select([ 'root', 'lvl', 'name', 'active', 'id', 'description' ])
                             ->where([
                                         'root' => 1,
                                         'lvl' => 1,
                                         'active' => Status::STATUS_ACTIVE,
                                     ])
                             ->asArray()
                             ->all();
            
            return $this->render('index', [
                'items' => $items,
            ]);
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
}
