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
        'vendor/Magnific-Popup-master/dist/magnific-popup.css',
        'vendor/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css',
    ];
    
    public $js = [
        'vendor/jquery-ui/jquery-ui.js',
        'vendor/popper/popper.min.js',
        'vendor/jquery-slimscroll/jquery.slimscroll.js',
        'vendor/fastclick/lib/fastclick.js',
        'vendor/bootstrap/dist/js/bootstrap.js',
        'vendor/Magnific-Popup-master/dist/jquery.magnific-popup.min.js',
        'vendor/Magnific-Popup-master/dist/jquery.magnific-popup-init.js',
        'vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js',
        'vendor/moment/src/moment2.js',
        
        'js/template.js',
        'js/item.js',
        'js/xeditable.js',
        'js/jquery.maskedinput.min.js',
    
        //        'js/demo.js',
    
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\IconsAsset',
    ];
    
}