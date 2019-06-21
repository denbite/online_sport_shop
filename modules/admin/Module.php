<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';
    
    //    public function behaviors()
    //    {
    //        return [
    //            'access' => [
    //                'class' => AccessControl::className(),
    //                'rules' => [
    //                    [
    //                        'allow' => true,
    //                        'roles' => [ '*' ],
    //                    ],
    //                ],
    //            ],
    //        ];
    //    }
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
