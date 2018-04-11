<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap-switch.min.css',
        'css/magnific-popup.css',
        'css/admin.css',
    ];
    public $js = [
        'js/jquery.magnific-popup.min.js',
        'js/bootstrap-switch.min.js',
        'js/admin.js',
        'js/sortable.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
