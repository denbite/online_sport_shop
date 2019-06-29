<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\UserSearch;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class UserController extends Controller
{
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
