<?php

namespace app\components\grid;

use app\components\helpers\Permission;
use Yii;
use yii\helpers\Html;

class ActionColumn extends OwnColumn
{
    
    private $_controller;
    
    protected function renderDataCellContent($model, $key, $index)
    {
        $this->_controller = Yii::$app->controller->id;
        
        if (!empty($this->_controller) and Permission::can("admin_{$this->_controller}_update")) {
            return Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-pencil' ]) . ' Редактировать', [ "/admin/{$this->_controller}/update", 'id' => $model->id ], [ 'class' => 'btn btn-sm btn-warning' ]);
        }
        
        return null;
    }
    
}