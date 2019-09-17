<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\UserSearch;
use app\modules\user\models\forms\SignupForm;
use app\modules\user\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
    
    public function actionCreate()
    {
        $model = new SignupForm();
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if ($model->load($post)) {
                if ($model->signup()) {
                    return $this->redirect([ 'index' ]);
                }
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if ($model->load($post) and $model->validate()) {
    
                if ($model->loadNewRoles($post[$model->formName()]['roles']) and $model->save()) {
                    return $this->redirect([ 'view', 'id' => $model->id ]);
                }
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'roles' => array_keys(Yii::$app->authManager->getRoles()),
            'statuses' => User::getStatusesArray(),
            'user_roles' => Yii::$app->authManager->getRolesByUser($model->id),
        ]);
    }
    
    protected function findModel($id)
    {
        if (( $model = User::findOne($id) ) != null) {
            return $model;
        }
        
        return new NotFoundHttpException('Страница не найдена');
    }
}
