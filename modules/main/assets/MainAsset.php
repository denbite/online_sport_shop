<?php

namespace app\modules\main\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    
    public $sourcePath = '@app/modules/main/web';
    
    public $css = [
        'css/icons.min.css',
        'css/plugins.css',
        'css/style.css',
    ];
    
    public $js = [
        'js/ajax-mail.js',
        'js/main.js',
        'js/plugins.js',
        'js/popper.min.js',
        'js/modernizr-2.8.3.min.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
    
}