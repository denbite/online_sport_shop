<?php

namespace app\modules\admin;

use app\components\helpers\Permission;
use Yii;
use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [ '@' ],
                        'matchCallback' => function() {
                            $module = Yii::$app->controller->module->id;
                            $controller = Yii::$app->controller->id;
                            $action = Yii::$app->controller->action->id;
                            
                            $rule = $module . '_' . $controller . '_' . $action;
                            
                            if (!Permission::permissionExist($rule)) {
                                Permission::addNewPermission($module, $controller, $action);
                            }
    
                            return Permission::can($rule);
                        },
                    ],
                ],
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
