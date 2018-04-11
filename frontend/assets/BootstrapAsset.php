<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class BootstrapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    
    public $css = [
	    "/css/bootstrap.min.css",
    ];

}
