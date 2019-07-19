<?php

namespace app\assets;

use yii\web\AssetBundle;

class IconsAsset extends AssetBundle
{
    
    public $sourcePath = '@bower';
    
    public $css = [
        'font-awesome/css/font-awesome.min.css',
        'mdi/css/materialdesignicons.min.css',
        'simple-line-icons/css/simple-line-icons.css',
        'glyphicons/styles/glyphicons.css',
    ];
}