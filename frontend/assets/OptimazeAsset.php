<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class OptimazeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
	    "/css/custom.css",
    ];

    public $js = [
    	
    ];

     public $depends = [
         '\frontend\assets\MainAsset',
     ];
}
