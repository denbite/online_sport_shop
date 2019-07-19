<?php

namespace app\modules\admin\controllers;

use app\components\helpers\Permission;
use app\components\helpers\TransactionHelper;
use app\models\AuthItem;
use app\modules\admin\models\RoleSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
                try {
        
                    TransactionHelper::wrap(function () use ($model, $post)
                    {
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
                        }
                    });
                } catch (\Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
    
                return $this->redirect([ 'view', 'name' => $model->name ]);
    
            }
        }
        
        $model->type = 1;
    
        return $this->render('form', [
            'model' => $model,
            'permissions' => Permission::getAllPermissionGroups(),
        ]);
    }
    
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        
        $modelChild = Yii::$app->authManager->getChildren($name);
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) and $model->validate()) {
                try {
                    
                    TransactionHelper::wrap(function () use ($model, $post)
                    {
                        if ($model->save()) {
                            if (!empty($post['AuthItemChild'])) {
                                $auth = Yii::$app->authManager;
                                $auth->removeChildren($auth->getRole($model->name));
                                
                                foreach ($post['AuthItemChild'] as $rule => $value) {
                                    if ($value and Permission::permissionExist($rule)) {
                                        $auth->addChild($auth->getRole($model->name),
                                                        $auth->getPermission($rule));
                                    }
                                }
                                
                            }
                            Yii::$app->session->setFlash('success', 'Успешно сохранено');
                        }
                    });
                } catch (\Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                
                return $this->redirect([ 'view', 'name' => $model->name ]);
                
            }
        }
        
        return $this->render('form', [
            'model' => $model,
            'modelChild' => $modelChild,
            'permissions' => Permission::getAllPermissionGroups(),
        ]);
    }
    
    public function actionView($name)
    {
        $model = $this->findModel($name);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();
        
        return $this->redirect([ 'index' ]);
    }
    
    protected function findModel($name)
    {
        if (( $model = AuthItem::findOne([ 'name' => $name ]) ) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
