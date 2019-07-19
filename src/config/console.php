<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
if (file_exists(__DIR__ . '/db-local.php')) {
    $db_local = require __DIR__ . '/db-local.php';
    $db = array_merge($db, $db_local);
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [ 'log' ],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'modules' => [
        'treemanager' => [
            'class' => '\kartik\tree\Module',
        ],
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => 'http://swim.local',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [ 'error', 'warning' ],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
