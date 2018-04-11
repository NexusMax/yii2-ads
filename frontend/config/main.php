<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);




return [
    'id' => 'app-frontend',
    'name' => 'JANDOOO',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'devicedetect'],
    'controllerNamespace' => 'frontend\controllers',
    'on beforeRequest' => function () {
        Yii::$app->params['siteSettings'] = backend\models\Settings::find()->where(['id' => 1])->one();            
        if (Yii::$app->params['siteSettings']->active == 0) {
            Yii::$app->catchAll = [
              'settings/index', 
              'message' => 'Сайт временно недоступен'
            ];
        }

        $pages = Yii::$app->db->createCommand('SELECT alias, name, position FROM jandoo_pages WHERE active = 1 order By sort ASC')->queryAll();
       
        Yii::$app->params['pages'] = $pages;
        $routeArray = [];

        if(!empty($pages)){
            foreach ($pages as $key) {
                $routeArray['<alias:'.$key['alias'].'>'] = 'pages/view';
            }
            Yii::$app->getUrlManager()->addRules($routeArray, false);
        }
    },
    'language' => 'ru',
    'layout' => 'main',
    'modules' => [
            'comment' => [
                'class' => 'yii2mod\comments\Module',
            ],
            'yii2images' => [
                'class' => 'rico\yii2images\Module',
                //be sure, that permissions ok 
                //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
                'imagesStorePath' => Yii::getAlias('@appWeb') . '/images/store', //path to origin images
                'imagesCachePath' => Yii::getAlias('@appWeb') . '/images/cache', //path to resized copies
                'graphicsLibrary' => 'Imagick', //but really its better to use 'Imagick'  //GD
                'placeHolderPath' => Yii::getAlias('@appWeb') . '/images/noimage-min.jpg', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
                'imageCompressionQuality' => 60, // Optional. Default value is 85.
            ],
    ],
    'components' => [
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
                // 'enableSchemaCache' => true,
               // 'schemaCacheDuration' => 30,

        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [


                'image' => 'yii2images/images/image-by-item-and-alias', 
                // '<module:(my-module-name)>/<action:(my-action-name)>' => '<module>/my-controller-name/<action>',

                '<action>'=>'site/<action>',

                '<controller:magazine>/<alias:[-\w]+>/contact'=>'<controller>/contact',
                '<controller:magazine>/<alias:[-\w]+>/about'=>'<controller>/about',


                [
	                'class' => 'yii\web\GroupUrlRule',
	                'prefix' => 'myaccount',
	                'routePrefix' => '',
	                'rules' => [
                        'updatephonecount' => 'myaccount/updatephonecount',
	                    'magazine' => 'myacc-magazine/index',
	                    'magazine/view' => 'myacc-magazine/view',
		                'magazine/update' => 'myacc-magazine/update',
		                'magazine/delete' => 'myacc-magazine/delete',
		                'magazine/pay' => 'myacc-magazine/pay',

		                'magazine/payment' => 'myacc-magazine/payment',
		                'magazine/payment/update' => 'myacc-magazine/payment-update',
		                'magazine/payment/delete' => 'myacc-magazine/payment-delete',
		                'magazine/payment/view' => 'myacc-magazine/payment-view',

		                'magazine/magazine-has-categories' => 'magazine-has-categories/index',
		                'magazine/magazine-has-categories/view' => 'magazine-has-categories/view',
		                'magazine/magazine-has-categories/update' => 'magazine-has-categories/update',
		                'magazine/magazine-has-categories/create' => 'magazine-has-categories/create',
		                'magazine/magazine-has-categories/delete' => 'magazine-has-categories/delete',

		                'magazine/ads' => 'magazine-ads/index',
		                'magazine/ads/create' => 'magazine-ads/create',
		                'magazine/ads/view' => 'magazine-ads/view',
		                'magazine/ads/update' => 'magazine-ads/update',
		                'magazine/ads/delete' => 'magazine-ads/delete',
		                'magazine/ads/copy' => 'magazine-ads/copy',
                        'magazine/ads/copy-save' => 'magazine-ads/copy-save',
                        'magazine/ads/upd' => 'magazine-ads/upd',
		                'magazine/ads/fire' => 'magazine-ads/fire',

		                'magazine/order' => 'magazine-order/index',
		                'magazine/order/view' => 'magazine-order/view',
		                'magazine/order/delete' => 'magazine-order/delete',
		                'magazine/order/update' => 'magazine-order/update',

		                'magazine/order-item' => 'magazine-order-item/index',
		                'magazine/order-item/view' => 'magazine-order-item/view',
		                'magazine/order-item/delete' => 'magazine-order-item/delete',
		                'magazine/order-item/update' => 'magazine-order-item/update',
	                ],
	            ],

                // 'myaccount/magazine' => 'myacc-magazine/index',
                // 'myaccount/magazine/view' => 'myacc-magazine/view',
                // 'myaccount/magazine/update' => 'myacc-magazine/update',
                // 'myaccount/magazine/delete' => 'myacc-magazine/delete',
                // 'myaccount/magazine/pay' => 'myacc-magazine/pay',
                
                // 'myaccount/magazine/payment' => 'myacc-magazine/payment',
                // 'myaccount/magazine/payment/update' => 'myacc-magazine/payment-update',
                // 'myaccount/magazine/payment/delete' => 'myacc-magazine/payment-delete',
                // 'myaccount/magazine/payment/view' => 'myacc-magazine/payment-view',

                // 'myaccount/magazine/magazine-has-categories' => 'magazine-has-categories/index',
                // 'myaccount/magazine/magazine-has-categories/view' => 'magazine-has-categories/view',
                // 'myaccount/magazine/magazine-has-categories/update' => 'magazine-has-categories/update',
                // 'myaccount/magazine/magazine-has-categories/create' => 'magazine-has-categories/create',
                // 'myaccount/magazine/magazine-has-categories/delete' => 'magazine-has-categories/delete',

                // 'myaccount/magazine/ads' => 'magazine-ads/index',
                // 'myaccount/magazine/ads/create' => 'magazine-ads/create',
                // 'myaccount/magazine/ads/view' => 'magazine-ads/view',
                // 'myaccount/magazine/ads/update' => 'magazine-ads/update',
                // 'myaccount/magazine/ads/delete' => 'magazine-ads/delete',
                // 'myaccount/magazine/ads/copy' => 'magazine-ads/copy',
                // 'myaccount/magazine/ads/copy-save' => 'magazine-ads/copy-save',

                // 'myaccount/magazine/order' => 'magazine-order/index',
                // 'myaccount/magazine/order/view' => 'magazine-order/view',
                // 'myaccount/magazine/order/delete' => 'magazine-order/delete',
                // 'myaccount/magazine/order/update' => 'magazine-order/update',

                // 'myaccount/magazine/order-item' => 'magazine-order-item/index',
                // 'myaccount/magazine/order-item/view' => 'magazine-order-item/view',
                // 'myaccount/magazine/order-item/delete' => 'magazine-order-item/delete',
                // 'myaccount/magazine/order-item/update' => 'magazine-order-item/update',
                // '<controller:\w+>/<action:[-\w]+>/<id:\d+>'=>'<controller>/<action>',

                // 'category/user-ads/<id:\d+>' => 'category/user-ads',
                // 'category/all-vip' => 'category/all-vip',
                '<controller:blog>/index'=>'<controller>/index',
                '<controller:blog>/<alias:[-\w]+>'=>'<controller>/view',
                '<controller:ads>/<alias:create>'=>'<controller>/create',
                '<controller:ads>/<alias:[-\w]+>'=>'<controller>/view',
    			'<controller:ads>/<action:[-\w]+>/<alias:[-\w]+>'=>'<controller>/<action>',

                //'<controller:category>/search/<city:[-\w]+>/<q:[-\w]+>' => '<controller>/search',     
                //'<controller:category>/search' => '<controller>/search',     
                '<controller:\w+>/index' => '<controller>/index',
                '<controller:\w+>/<cat:[-\w]+>/<subcat:[-\w]+>/<q:[-\w]+>/<reg:[-\w]+>' => '<controller>/view',
                // '<controller:\w+>/<cat:[-\w]+>/<subcat:[-\w]+>/<reg:[-\w]+>' => '<controller>/view',      
                '<controller:\w+>/<cat:[-\w]+>/<subcat:[-\w]+>/<q:[-\w]+>' => '<controller>/view',         
                '<controller:\w+>/<cat:[-\w]+>/<subcat:[-\w]+>' => '<controller>/view',       
                '<controller:category>/<cat:[-\w]+>'=>'<controller>/view',       
                // '<controller:\w+>/<action:\w+>/<cat:[-\w]+>/<subcat:[-\w]+>' => '<controller>/view',
                
                // 'https://<alias>.jandooo.com/magazine/view' => 'magazine/view',
                // 'magazine/standart/<name:[-\w]+>' => 'magazine/standart/',
               

	            [
	                'class' => 'yii\web\GroupUrlRule',
	                'prefix' => 'magazine',
	                'routePrefix' => '',
	                'rules' => [
	                    'shops' => 'magazine/shops',
	                    'create' => 'magazine/create',
	                    'finish' => 'magazine/finish',
	                    'ajax' => 'magazine/ajax',
	                    'ajax-finish' => 'magazine/ajax-finish',
	                    'ajax-plan' => 'magazine/ajax-plan',
	                    'save' => 'magazine/save',
	                    'login-social' => 'magazine/login-social',
	                    'captcha' => 'magazine/captcha',
	                    '<alias:[-\w]+>/contact'=>'magazine/contact',
                		'<alias:[-\w]+>/about'=>'magazine/about',
                		'<alias:[-\w]+>' => 'magazine/view',
	                    // '<alias:[-\w]+>/contact' =>
	                ],
	            ],
                // '<controller:magazine>/shops'=>'<controller>/shops',
                // '<controller:magazine>/create'=>'<controller>/create',
                // '<controller:magazine>/finish'=>'<controller>/finish',
                // '<controller:magazine>/ajax'=>'<controller>/ajax',
                // '<controller:magazine>/ajax-finish'=>'<controller>/ajax-finish',
                // '<controller:magazine>/ajax-plan'=>'<controller>/ajax-plan',
                // '<controller:magazine>/save'=>'<controller>/save',
                // '<controller:magazine>/login-social'=>'<controller>/login-social',
                // '<controller:magazine>/captcha'=>'<controller>/captcha',

                // 'magazine/<alias:[-\w]+>/contact' => 'magazine/contact',
                // '<controller:magazine>/contact/<alias:[-\w]+>'=>'<controller>/contact',
                // '<controller:magazine>/about/<alias:[-\w]+>'=>'<controller>/about',

                // '<controller:magazine>/<alias:[-\w]+>'=>'<controller>/view',
                'product/<alias:[-\w]+>'=>'magazine/product',


    			// '<controller:\w+>/<id:\d+>'=>'<controller>/view',
    			// '<controller:\w+>/<action:[-\w]+>/<id:\d+>'=>'<controller>/<action>',
                // 'category/user-ads/<id:\d+>'=>'category/user-ads',

                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>', 
            ],
        ],
        'assetManager' => [
        	'appendTimestamp' => true,
        	// 'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
//                    'js'=>[],
//                    'sourcePath' => null,
                    'js' => ["https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"], // тут путь до Вашего экземпляра jquery
                ],
//                 'yii\bootstrap\BootstrapPluginAsset' => [
//                     'js'=>[],
//                 ],
               // 'yii\bootstrap\BootstrapAsset' => [
               //     'css' => [],
               // ],
                // 'yii\web\JqueryAsset' => false,
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
            ],
        ],
        
    ],
    'params' => $params,
];
