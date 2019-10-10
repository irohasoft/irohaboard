#!/usr/bin/php -q
<?php
/**
 * Command-line code generation utility to automate programmer chores.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Console
 * @since         CakePHP(tm) v 2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$dispatcher = 'Cake' . DS . 'Console' . DS . 'ShellDispatcher.php';

if (function_exists('ini_set')) {
	$root = dirname(dirname(dirname(__FILE__)));
	$appDir = basename(dirname(dirname(__FILE__)));
	$install = $root . DS . 'lib';
	//$composerInstall = $root . DS . $appDir . DS . 'Vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib';

	if (file_exists(dirname(dirname(dirname(__FILE__))).DS.'cake'.DS.'lib')) {
		// cake ディレクトリが webroot の1階層上に存在する場合．デフォルトはこの状態．
		$composerInstall = dirname(dirname(dirname(__FILE__))).DS.'cake'.DS.'lib';
	} elseif (file_exists(dirname(dirname(__FILE__)).DS.'cake'.DS.'lib')) {
		// cake ディレクトリが webroot と同じ階層に存在する場合
		$composerInstall = dirname(dirname(__FILE__)).DS.'cake'.DS.'lib';
	} elseif (file_exists(dirname(dirname(dirname(dirname(__FILE__)))).DS.'cake'.DS.'lib')) {
		// cake ディレクトリが webroot の2階層上に存在する場合
		$composerInstall = dirname(dirname(dirname(dirname(__FILE__)))).DS.'cake'.DS.'lib';
	} else {
		trigger_error('Cake directory not found.', E_USER_ERROR);
	}

	// the following lines differ from its sibling
	// /lib/Cake/Console/Templates/skel/Console/cake.php
	if (file_exists($composerInstall . DS . $dispatcher)) {
		$install = $composerInstall;
	}

	ini_set('include_path', $install . PATH_SEPARATOR . ini_get('include_path'));
	unset($root, $appDir, $install, $composerInstall);
}

if (!include $dispatcher) {
	trigger_error('Could not locate CakePHP core files.', E_USER_ERROR);
}
unset($dispatcher);

return ShellDispatcher::run($argv);
