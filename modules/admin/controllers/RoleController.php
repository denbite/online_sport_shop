<?php

namespace app\modules\admin\controllers;

use app\components\helpers\Permission;
use app\models\AuthItem;
use app\modules\admin\models\RoleSearch;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class RoleController
    extends Controller
{
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new AuthItem();
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if ($model->load($post) and $model->validate()) {
                if ($model->save()) {
                    if (!empty($post['AuthItemChild'])) {
                        
                        foreach ($post['AuthItemChild'] as $rule => $value) {
                            if ($value and Permission::permissionExist($rule)) {
                                Yii::$app->authManager->addChild(Yii::$app->authManager->getRole($model->name),
                                    Yii::$app->authManager->getPermission($rule));
                            }
                        }
                        
                    }
                    Yii::$app->session->setFlash('success', 'Успешно сохранено');
                    
                    return $this->redirect([ 'view', 'name' => $model->name ]);
                }
            }
            Yii::$app->session->setFlash('error', serialize($model->getErrors()));
        }
        
        $model->type = 1;
        
        return $this->render('create', [
            'model' => $model,
            'permissions' => Permission::getAllPermissionGroups(),
        ]);
    }
}
