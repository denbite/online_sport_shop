<?php

namespace app\components\grid;

use yii\grid\DataColumn;

class IdColumn extends DataColumn
{
    
    public $contentOptions = [
        'style' => 'width: 80px; white-space: normal;font-weight: 600; font-size: 16px;text-align:center;',
    ];
    
    public $headerOptions = [
        'style' => 'width: 80px; white-space: normal;font-weight: 600; font-size: 16px; text-align:center;',
    ];
    
    public $filterOptions = [
        'style' => 'width: 80px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
    ];
    
    public $filterInputOptions = [
        'class' => 'btn bg-secondary-gradient-animet dropdown-toggle',
        'style' => 'width: 80px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
    ];
}