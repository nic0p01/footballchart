<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'tifhf84490u97jknghmbn^%&jkhbn%&$gjknjns',
            'baseUrl'=> '',
        ],
        'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
        'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'nic0p01@footballcharts.ru',
                'password' => 'qwerty123456',
                'port' => '465',
                'encryption' => 'ssl',
            ],
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
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '/login',
                    'route' => 'site/login',
                ],
                [
                    'pattern' => '/signup',
                    'route' => 'site/signup',
                ],
                [
                    'pattern' => '/signup-partner',
                    'route' => 'site/signup-partner',
                ],
                [
                    'pattern' => '/events',
                    'route' => 'site/events',
                ],
                [
                    'pattern' => '/cabinet',
                    'route' => 'site/cabinet',
                ],
                [
                    'pattern' => '/logout',
                    'route' => 'site/logout',
                ],
                [
                    'pattern' => '/policy',
                    'route' => 'site/policy',
                ],
                [
                    'pattern' => '/faq',
                    'route' => 'site/faq',
                ],
                [
                    'pattern' => '/contract',
                    'route' => 'site/contract',
                ],
                [
                    'pattern' => '/success-pay',
                    'route' => 'site/success-pay',
                ],
                [
                    'pattern' => '/pay',
                    'route' => 'site/pay',
                ],
                [
                    'pattern' => '/result-pay',
                    'route' => 'site/result-pay',
                ],
                [
                    'pattern' => '/sitemap.xml',
                    'route' => 'site/sitemap',
                ],
                [
                    'pattern' => '/fail-pay',
                    'route' => 'site/fail-pay',
                ],
                [
                    'pattern' => '/request-password-reset',
                    'route' => 'site/request-password-reset',
                ],
                [
                    'pattern' => 'matches/txt',
                    'route' => 'matches/txt',
                ],
                [
                    'pattern' => 'matches/cal',
                    'route' => 'matches/cal',
                ],
                [
                    'pattern' => 'matches/date/<date_g:[\w-]+>',
                    'route' => 'matches/index',
                ],
                [
                    'pattern' => 'matches/<id:[\w-]+>',
                    'route' => 'matches/view',
                ]
            ],
        ],
        
    ],
    'controllerMap' => [
        'elfinder' => [
			'class' => 'mihaildev\elfinder\PathController',
			'access' => ['@'],
			'root' => [
                            'baseUrl'=>'/web',
                            'path' => 'upload/global',
                            'name' => 'Global'
			],
		]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '95.24.27.120'],
    ];
}

return $config;
