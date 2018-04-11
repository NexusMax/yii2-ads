<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    // public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];


    public $cssOption = [
        // 'onload' => "if(media!='all')media='all'";
    ];
    
    public $css = [
        "/css/bootstrap.min.css",
        //"/css/tether.min.css",
        "/css/bootstrap-grid.min.css",
        //"/css/bootstrap-theme.min.css",
        //"/js/assets/owl.carousel.min.css",
        //"/js/assets/owl.theme.default.min.css",
        //"/css/font-awesome.min.css",
        //"/css/jquery.fancybox.min.css",
        "/css/custom.css",
        "/css/media.css",
    ];
    public $js = [
        // "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js",
        "/js/tether.min.js",
        "/js/bootstrap.min.js",
        "/js/owl.carousel.min.js",
        "/js/jquery.fancybox.min.js",
        '/js/nprogress.js',
        "/js/main.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
