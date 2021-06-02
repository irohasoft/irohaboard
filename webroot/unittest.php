<?php
/**
 * Web Access Frontend for TestSuite
 *
 * @link          http://book.cakephp.org/2.0/en/development/testing.html
 * @package       app.webroot
 * @since         CakePHP(tm) v 1.2.0.4433
 */


//任意のPHPUnitのパスを指定
$phpunit_dir = 'C:\sdks\phpunit5\vendor\autoload.php';

if(file_exists($phpunit_dir))
{
	require_once $phpunit_dir;
}


set_time_limit(0);
ini_set('display_errors', 1);

// アプリケーション名の設定
if (!defined('APP_NAME')) {
	define('APP_NAME', 'iroha Board');
}

// PHPのバージョンチェック
if (version_compare(PHP_VERSION, '5.4.0') <= 0)
{
	header('Content-Type: text/html; charset=UTF-8');
	echo 'ERROR-001 : '.APP_NAME.' の動作には 5.4.0 以上が必要です。現在のバージョンは ' . PHP_VERSION . ' です。\n';
	exit;
}

/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * These defines should only be edited if you have CakePHP installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 */
if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(__FILE__))));
}

/**
 * The actual directory name for the "app".
 */
if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(__FILE__))));
}

// tmpディレクトリが存在しない場合、作成
if(!file_exists(ROOT.DS.APP_DIR.DS.'tmp'))
{
	mkdir(ROOT.DS.APP_DIR.DS.'tmp');
	mkdir(ROOT.DS.APP_DIR.DS.'tmp'.DS.'cache');
	mkdir(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs');
}

/**
 * Config Directory
 */
if (!defined('CONFIG')) {
	define('CONFIG', ROOT . DS . APP_DIR . DS . 'Config' . DS);
}

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * Un-comment this line to specify a fixed path to CakePHP.
 * This should point at the directory containing `Cake`.
 *
 * For ease of development CakePHP uses PHP's include_path. If you
 * cannot modify your include_path set this value.
 *
 * Leaving this constant undefined will result in it being defined in Cake/bootstrap.php
 *
 * The following line differs from its sibling
 * /lib/Cake/Console/Templates/skel/webroot/index.php
 */
//define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');

// cake ディレクトリが webroot の1階層上に存在する場合
//define('CAKE_CORE_INCLUDE_PATH', dirname(dirname(dirname(__FILE__))).DS.'cake'.DS.'lib');

// cake ディレクトリが webroot の2階層上に存在する場合
//define('CAKE_CORE_INCLUDE_PATH', dirname(dirname(dirname(dirname(__FILE__)))).DS.'cake'.DS.'lib');

/**
 * This auto-detects CakePHP as a composer installed library.
 * You may remove this if you are not planning to use composer (not recommended, though).
 */
$vendorPath = ROOT . DS . APP_DIR . DS . 'Vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib';
$dispatcher = 'Cake' . DS . 'Console' . DS . 'ShellDispatcher.php';
if (!defined('CAKE_CORE_INCLUDE_PATH') && file_exists($vendorPath . DS . $dispatcher)) {
	define('CAKE_CORE_INCLUDE_PATH', $vendorPath);
}


/**
 * Editing below this line should not be necessary.
 * Change at your own risk.
 */
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
	}
	if (!include 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
} else {
	if (!include CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
}
if (!empty($failed)) {
	trigger_error("CakePHP core could not be found. Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php. It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

if (Configure::read('debug') < 1) {
	throw new NotFoundException(__d('cake_dev', 'Debug setting does not allow access to this URL.'));
}

require_once CAKE . 'TestSuite' . DS . 'CakeTestSuiteDispatcher.php';

CakeTestSuiteDispatcher::run();
