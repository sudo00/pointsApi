<?php

use yii\web\Response;

$config = [
    'id' => '',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
    'components' => [
        'dataCache' => [
            'class' => 'yii\caching\MemCache',
            'servers' => [
                [
                    'host' => getenv('MEMCACHED_DATA_ADDRESS'),
                    'port' => getenv('MEMCACHED_DATA_PORT'),
                ],
            ],
            'useMemcached' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('MYSQL_HOST') .
            ';port=' . getenv('MYSQL_PORT') .
            ';dbname=' . getenv('MYSQL_DB'),
            'username' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'charset' => 'utf8',
            'attributes' => [
                PDO::ATTR_PERSISTENT => true,
            ],
        ],
        'ipApiRequest' => [
            'class' => 'app\components\Request',
            'resource' => 'http://ip-api.com/json/',
        ],
        'locationIQRequest' => [
            'class' => 'app\components\Request',
            'resource' => 'https://eu1.locationiq.com/v1/',
        ],
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfCookie' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => [
                        'v1/points',
                    ],
                    'extraPatterns' => [
                        'OPTIONS get-nearest' => 'options',
                        'OPTIONS get-all' => 'options',
                    ],
                ],
            ]
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
