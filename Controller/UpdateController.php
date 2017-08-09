<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */
App::uses('AppController', 'Controller');

class UpdateController extends AppController
{
	var $name = 'Update';
	var $uses = array();
	var $helpers = array('Html');
	
	public $components = array(
		'Session',
		'Auth' => array(
			'allowedActions' => array(
				'index',
				'error',
			)
		)
	);
	
	function index()
	{
		try
		{
			App::import('Model','ConnectionManager');

			$db = ConnectionManager::getDataSource('default');
			$this->__executeSQLScript($db, APP.DS.'Config'.DS.'update.sql');
		}
		catch (Exception $e)
		{
			$this->error();
			$this->render('error');
		}
	}
	
	function error()
	{
		$this->set('loginURL', "/users/login/");
		$this->set('loginedUser', $this->Auth->user());
	}
	
	private function __executeSQLScript($db, $fileName)
	{
		//echo "__executeSQLScript()<br>";
		
		$statements = file_get_contents($fileName);
		$statements = explode(';', $statements);
		
		foreach ($statements as $statement)
		{
			if (trim($statement) != '')
			{
				$db->query($statement);
			}
		}
	}
}
?>