<?php
include( $_SERVER['DOCUMENT_ROOT'].'/MainDbConfig.php' );

$dbConfig = MainDbConfig::getDbConfig($_SERVER['SERVER_NAME']);

return array(
	'basePath' => ROOT_DIR.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'application',
	'runtimePath' => ROOT_DIR.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'runtime',
	'name' => 'Site',
    'theme' => 'users',
    'defaultController' => 'user',

    'aliases' => array
    (
        'application' => ROOT_DIR.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'application',
        'RestfullYii' => realpath(__DIR__ . '/../extensions/starship/RestfullYii'),
    ),

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
        'application.components.*',
        'application.controllers.*',
        'application.models.*',
        'application.widgets.*',
        'application.extensions.*',
	),

    // application components
    'components' => array
    (

        'cache' => array
        (
            'class'=>'system.caching.CDbCache',
            'connectionID'=>'db',
            'autoCreateCacheTable'=>true,
            'cacheTableName'=>'yii_cache',
        ),

        'db' => array
        (
            'connectionString' => 'mysql:host='.$dbConfig[ 'host' ].';dbname='.$dbConfig['db']['site'].';',
            'username' => $dbConfig[ 'user' ],
            'password' => $dbConfig[ 'pass' ],
            //'emulatePrepare' => true,
            'enableParamLogging' => false,
            'enableProfiling' => false,
            //'schemaCachingDuration'=>3600,
        ),

        'importer' => array
        (
            'class' => 'Importer',
            'importPath' => ROOT_DIR . '/resources/import/xml',
        ),

        'themeManager' => array
        (
            'basePath' => ROOT_DIR.'/templates',
        ),

        'user' => array
        (
            //'class' => 'User',
            // enable cookie-based authentication
           'allowAutoLogin' => true,
           'loginUrl'=>array('user/login'),
        ),

        'request'=>array(
            'enableCsrfValidation'=>true,
        ),

        'session' => array(
            'autoStart' => true,
            //'cookieMode'=>'none',
            'useTransparentSessionID' => true,
            //'sessionName' => 'session',
            'timeout' => 28800,
        ),

        "redis" => array(
            "class" => 'ext.redis.ARedisConnection',
            "hostname" => "127.0.0.1",
            "port" => 6379,
            "database" => 0,
            "prefix" => "session:php:"
        ),

        'urlManager'=>array(
	        'urlFormat'=>'path',
            'showScriptName'=>false,
	        'rules'=>array(

                'login' => array('user/login', 'urlSuffix'=>'.html',),
                'register' => array('user/register', 'urlSuffix'=>'.html',),
                'logout' => array('user/logout', 'urlSuffix'=>'.html',),
                'posts' => array('user/index', 'urlSuffix'=>'.html',),
		        '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

	        ),
        ),

        'request' => array
        (
            'enableCookieValidation' => true,
        ),

        'clientScript' => array
        (
            'coreScriptUrl' => ROOT_DIR . '/resources',
            'corePackages' => array(),
            'packages' => array()
        ),

        'themeManager' => array(
            'basePath' => ROOT_DIR.'/templates',
        ),

        'log' => array
        (
            'class' => 'CLogRouter',
            'routes' => array
            (
                array
                (
                    'class' => 'CFileLogRoute',
                    'enabled'=> true,
                    'levels'=>'error,  info',
                    'categories'=>'application.*'
                ),
                array
                (
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, warning, info, profile, trace',
                    'enabled'=> false,
                  //'showInFireBug' => true,
                  //'levels' => 'error, warning, info, info, profile',
                ),
            ),
        ),
    ),

	'params'=>array(
		'adminEmail' => 'dima@example.com',
        'key' => 'id:',
        'timeout' => 60 * 60 * 24 * 365
	),
);