<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'language' => 'ru-RU',
    'bootstrap' => [
        'log',
        'queue', // Компонент регистрирует свои консольные команды 
    ],
    'components' => [

        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'ttr' => 2 * 60, // Максимальное время выполнения задания 
            'attempts' => 3, // Максимальное кол-во попыток
            'path' => '@runtime/queue',
        ],
//        'view' => [
//             'theme' => [
//                 'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                 ],
//             ],
//        ],
        'user' => [
            'class' => 'common\models\User', // extend User component
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:j F, H:i',
            'timeFormat' => 'php:H:i:s',
            'defaultTimeZone' => 'Europe/Moscow',
            'locale' => 'ru-RU'
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            // 'viewPath' => '@app/mail',
            // 'htmlLayout' => 'layouts/main-html',
            // 'textLayout' => 'layouts/main-text',
            // 'messageConfig' => [
            //     'charset' => 'UTF-8',
            //     'from' => ['antonlitvinov14@gmail.com' => 'Site Name'],
            // ],
            'useFileTransport' => false, // false - real server
           // 'transport' => [
           //     'class' => 'Swift_SmtpTransport',
           //     'host' => 'smtp.gmail.com',
           //     'username' => 'antonlitvinov14@gmail.com',
           //     'password' => 'password',
           //     'port' => '465', //465
           //     'encryption' => 'ssl', //ssl
           //  ],
        ],

        'request' => [
            'baseUrl' => '/admin',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'image' => 'yii2images/images/image-by-item-and-alias', 
                '' => 'site/index',                                
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                '<module:admin>/<controller:catalog>/<action:category>' => '<module:admin>/<controller:categories>/<action:index>',
            ],
        ], 
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                '/admin/assets/522ef28c/no.conflict.js' => false,
            ],
        ], 
        
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'baseUrl' => '/web',
                'basePath' =>  Yii::getAlias('@appWeb'),
                'path' => 'images/',
                'name' => 'Global'
            ],
        ]
    ],
    'params' => $params,
];
