<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

if (version_compare(PHP_VERSION, '5.3.0') <= 0)
{
	echo "ERROR-001 : iroha Board の動作には 5.3.0 以上が必要です。現在のバージョンは " . PHP_VERSION . " です。\n";
	exit;
}

// タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * These defines should only be edited if you have CakePHP installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * 1. ルートディレクトリのパスの設定
 */
if (!defined('ROOT'))
{
	// webroot ディレクトリがアプリケーションディレクトリ内に存在する場合
	define('ROOT', dirname(dirname(dirname(__FILE__))));
	
	// webroot と app ディレクトリを分離する場合 (cake フォルが1階層上の場合)
	//define('ROOT', dirname(dirname(__FILE__)).DS.'cake');
	
	// webroot と app ディレクトリを分離する場合 (cake フォルが2階層上の場合)
	//define('ROOT', dirname(dirname(dirname(__FILE__))).DS.'cake');
}

if(!file_exists(ROOT))
{
	echo "ERROR-002 : ROOTディレクトリが見つかりません\n index.php の ROOT の設定を確認して下さい。";
	exit;
}

/**
 * 2. アプリケーションディレクトリ名の設定
 */
if (!defined('APP_DIR'))
{
	// webroot ディレクトリがアプリケーションディレクトリ内に存在する場合
	define('APP_DIR', basename(dirname(dirname(__FILE__))));

	// webroot と app ディレクトリと分離する場合
	//define('APP_DIR', 'app');
}

if(!file_exists(ROOT.DS.APP_DIR))
{
	echo "ERROR-003 : アプリケーションディレクトリが見つかりません\n index.php の APP_DIR の設定を確認して下さい。\n";
	echo "現在の指定 : ".APP_DIR;
	exit;
}

/**
 * 2. CakePHP のライブラリディレクトリのパスの設定
 */
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	// webroot ディレクトリがアプリケーションディレクトリ内に存在する場合
	define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'cake' . DS . 'lib');

	// webroot と app ディレクトリを分離する場合
	//define('CAKE_CORE_INCLUDE_PATH', ROOT.DS.'lib');
}

if(!file_exists(CAKE_CORE_INCLUDE_PATH))
{
	echo "ERROR-004 : libディレクトリが見つかりません\n index.php の CAKE_CORE_INCLUDE_PATH の設定を確認して下さい。";
	exit;
}

// tmpディレクトリが存在しない場合、作成
if(!file_exists(ROOT.DS.APP_DIR.DS.'tmp'))
{
	mkdir(ROOT.DS.APP_DIR.DS.'tmp');
	mkdir(ROOT.DS.APP_DIR.DS.'tmp'.DS.'cache');
	mkdir(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs');
}

/**
 * This auto-detects CakePHP as a composer installed library.
 * You may remove this if you are not planning to use composer (not recommended, though).
 */
$vendorPath = ROOT . DS . APP_DIR . DS . 'Vendor' . DS . 'cake' . DS . 'lib';
$dispatcher = 'Cake' . DS . 'Console' . DS . 'ShellDispatcher.php';
if (!defined('CAKE_CORE_INCLUDE_PATH') && file_exists($vendorPath . DS . $dispatcher))
{
	define('CAKE_CORE_INCLUDE_PATH', $vendorPath);
}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 */
if (!defined('WEBROOT_DIR'))
{
	// webroot ディレクトリがアプリケーションディレクトリ内に存在する場合
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
	
	// webroot と app ディレクトリを分離する場合
	// define('WEBROOT_DIR', '');
}
if (!defined('WWW_ROOT'))
{
	define('WWW_ROOT', dirname(__FILE__) . DS);
}

if(!file_exists(ROOT.DS.APP_DIR.DS.WEBROOT_DIR))
{
	echo "ERROR-005 : WEBROOTディレクトリが見つかりません\n index.php の WEBROOT_DIR の設定を確認して下さい。";
	exit;
}

// uploadsディレクトリが存在しない場合、作成
if(!file_exists(ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'/uploads'))
{
	mkdir(ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'/uploads');
}

// For the built-in server
if (PHP_SAPI === 'cli-server') {
	if ($_SERVER['REQUEST_URI'] !== '/' && file_exists(WWW_ROOT . $_SERVER['PHP_SELF'])) {
		return false;
	}
	$_SERVER['PHP_SELF'] = '/' . basename(__FILE__);
}

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
	}
	if (!include 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
} elseif (!include CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php') {
echo CAKE_CORE_INCLUDE_PATH;
	$failed = true;
}
if (!empty($failed)) {
	trigger_error("CakePHP core could not be found. Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php. It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

App::uses('Dispatcher', 'Routing');

$Dispatcher = new Dispatcher();
$Dispatcher->dispatch(
	new CakeRequest(),
	new CakeResponse()
);
