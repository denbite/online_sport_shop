<?php

namespace app\components\grid;

use yii\grid\DataColumn;

class OwnColumn extends DataColumn
{
    
    public $contentOptions = [
        'style' => 'white-space: normal;font-weight: 600; font-size: 16px;text-align:center;',
    ];
    
    public $headerOptions = [
        'style' => 'white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
    ];
    
    public $filterOptions = [
        'style' => 'max-width: 200px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
    ];
    
    public $filterInputOptions = [
        'class' => 'btn bg-secondary-gradient-animet dropdown-toggle',
        'style' => 'max-width: 200px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
    ];
}