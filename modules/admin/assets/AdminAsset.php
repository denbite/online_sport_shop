<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    
    public $sourcePath = '@app/modules/admin/web';
    
    public $css = [
        'css/bootstrap-extend.css',
        'css/master_style.css',
        'css/skins/_all-skins.css',
    ];
    
    public $js = [
        'js/template.js',
        'js/demo.js',
        'vendor/popper/dist/popper.min.js',
        'vendor/jquery-slimscroll/jquery.slimscroll.min.js',
        'vendor/fastclick/lib/fastclick.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\IconsAsset',
    ];
}