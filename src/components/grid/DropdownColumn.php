<?php

namespace app\components\grid;

use yii\helpers\Html;

class DropdownColumn
    extends OwnColumn
{
    
    public $css;
    
    public $contentOptions = [
        'style' => 'max-width: 200px; white-space: normal; font-weight: 600; font-size: 16px; text-align:center;',
    ];
    
    public $headerOptions = [
        'style' => 'max-width: 200px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
    ];
    
    private $_attr;
    
    protected function renderDataCellContent($model, $key, $index)
    {
        $attribute = $this->attribute;
    
        $val = $this->getDataCellValue($model, $key, $index);
    
        $this->_attr = !empty($val) ? $val : $model->$attribute;
    
        unset($val);
        
        if (array_key_exists($this->_attr, $this->css) and array_key_exists($this->_attr, $this->filter)) {
            return Html::tag('span', $this->filter[$this->_attr],
                             [ 'class' => 'label bg-' . $this->css[$this->_attr] ]);
        }
        
        return null;
    }
}
