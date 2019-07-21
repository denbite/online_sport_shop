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
        'vendor/jquery-ui/jquery-ui.js',
        'vendor/popper/popper.min.js',
        'vendor/jquery-slimscroll/jquery.slimscroll.js',
        'vendor/fastclick/lib/fastclick.js',
        'vendor/bootstrap/dist/js/bootstrap.js',
    
        'js/template.js',
        'js/item.js',
        //        'js/demo.js',
    
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\IconsAsset',
    ];
    
}