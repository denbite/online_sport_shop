<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Category::find()->addOrderBy('root, lft');
    
        return $this->render('index', [
            'query' => $query,
        ]);
    }
}
