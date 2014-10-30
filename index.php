<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//die(__FILE__.__LINE__);
// Prevent cache.
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//directory root
defined('ROOT_DIR') or define('ROOT_DIR', dirname(__FILE__));

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

define('TESTS_BASE_PATH', '/users/');

// change the following paths if necessary
$yii = ROOT_DIR.'/core/framework/yii.php';
$config = ROOT_DIR.'/core/application/config/config.inc';

require_once($yii);

Yii::createWebApplication($config)->run();