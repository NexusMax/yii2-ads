<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class HeadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $css = [
    ];


    public $js = [
        // 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
    ];

    public $depends = [
    ];
}
