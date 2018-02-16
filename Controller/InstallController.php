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

class InstallController extends AppController
{
	var $name = 'Install';
	var $uses = array();
	var $helpers = array('Html');
	var $db   = null;
	var $path = '';
	
	public $components = array(
			'Session',
			'Auth' => array(
					'allowedActions' => array(
							'index',
							'installed',
							'complete',
							'error',
							'add'
					)
			)
	);
	
	function beforeFilter()
	{
	}
	
	function index()
	{
		App::import('Model','ConnectionManager');
		
		try
		{
			$db = ConnectionManager::getDataSource('default');
			$cdd = new DATABASE_CONFIG();
			
			//debug($db);
			$sql = "SHOW TABLES FROM `".$cdd->default['database']."` LIKE 'ib_users'";
			$data = $db->query($sql);
			
			if (count($data) > 0)
			{
				$this->installed();
				$this->render('installed');
			}
			else
			{
				$this->__createTables();
				$this->complete();
				$this->render('complete');
			}
		}
		catch (Exception $e)
		{
			$this->error();
			$this->render('error');
		}
	}
	
	function installed()
	{
		$this->set('loginURL', "/users/login/");
		$this->set('loginedUser', $this->Auth->user());
	}
	
	function complete()
	{
		$this->set('loginURL', "/users/login/");
		$this->set('loginedUser', $this->Auth->user());
	}
	
	function error()
	{
		$this->set('loginURL', "/users/login/");
		$this->set('loginedUser', $this->Auth->user());
	}
	
	private function __createTables()
	{
		App::import('Model','ConnectionManager');

		$this->db   = ConnectionManager::getDataSource('default');
		$this->path = APP.DS.'Config'.DS.'app.sql';
		$this->__executeSQLScript();
	}
	
	private function __executeSQLScript()
	{
		$statements = file_get_contents($this->path);
		$statements = explode(';', $statements);

		foreach ($statements as $statement)
		{
			if (trim($statement) != '')
			{
				$this->db->query($statement);
			}
		}
	}
}
?>