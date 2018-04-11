<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
           'allowedIPs' => ['193.142.159.28', '::1']
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['193.142.159.28', '::1']
        ],
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            //be sure, that permissions ok 
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => Yii::getAlias('@appWeb') . '/images/store', //path to origin images
            'imagesCachePath' => Yii::getAlias('@appWeb') . '/images/cache', //path to resized copies
            'graphicsLibrary' => 'Imagick', //but really its better to use 'Imagick'  //GD
            'placeHolderPath' => Yii::getAlias('@appWeb') . '/images/noimage-min.jpg', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
            'imageCompressionQuality' => 75, // Optional. Default value is 85.
        ],
    ],  
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@frontend/runtime/cache', 
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
    ],
];
