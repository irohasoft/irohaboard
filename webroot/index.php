<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2020 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

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

// ロードバランサー対応
if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
{
	// 1.2.3.4, 1.2.3.4 形式をカンマで分解
	$host_list = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
	
	$_SERVER['HTTP_HOST'] = trim($host_list[count($host_list) - 1]); // 先頭のIPアドレスを設定
	
	if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']))
	{
		if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
		{
			$_SERVER['HTTPS'] = 'on'; // HTTPSアクセスを強制
		}
	}
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

// app と同じ階層に lib ディレクトリが存在しない場合、上の階層の cake/lib ディレクトリを検索する (旧バージョン対応)
$lib_path = dirname(dirname(dirname(__FILE__))).DS.'lib';

if(!file_exists($lib_path))
{
	// 1階層上の cake/lib ディレクトリ
	$lib_path = dirname(dirname(dirname(__FILE__))).DS.'cake'.DS.'lib';
	
	if(file_exists($lib_path))
	{
		define('CAKE_CORE_INCLUDE_PATH', $lib_path);
	}
	
	// 2階層上の cake/lib ディレクトリ
	$lib_path = dirname(dirname(dirname(__FILE__))).DS.'cake'.DS.'lib';
	
	if(!defined('CAKE_CORE_INCLUDE_PATH') && (file_exists($lib_path)))
	{
		define('CAKE_CORE_INCLUDE_PATH', $lib_path);
	}
}

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
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 */
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}

// For the built-in server
if (PHP_SAPI === 'cli-server') {
	if ($_SERVER['PHP_SELF'] !== '/' . basename(__FILE__) && file_exists(WWW_ROOT . $_SERVER['PHP_SELF'])) {
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
