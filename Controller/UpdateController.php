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
	var $err_msg = '';
	var $db   = null;
	var $path = '';
	
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

			$this->db   = ConnectionManager::getDataSource('default');
			$this->path = APP.DS.'Config'.DS.'update.sql';
			$err_statements = $this->__executeSQLScript();
			
			if(count($err_statements) > 0)
			{
				$this->err_msg = 'クエリの実行中にエラーが発生しました。詳細はデバッグログ(tmp/logs/debug.log)をご確認ください。';
				
				foreach($err_statements as $err)
				{
					$err .= $err."\n";
				}
				
				// デバッグログ
				$this->log($err, LOG_DEBUG);
				$this->error();
				$this->render('error');
				return;
			}
		}
		catch (Exception $e)
		{
			$this->err_msg = 'データベースへの接続に失敗しました。<br>Config / database.php ファイル内のデータベースの設定を確認して下さい。';
			$this->error();
			$this->render('error');
		}
	}
	
	function error()
	{
		$this->set('loginURL', "/users/login/");
		$this->set('loginedUser', $this->Auth->user());
		$this->set('body', $this->err_msg);
	}
	
	private function __executeSQLScript()
	{
		$statements = file_get_contents($this->path);
		$statements = explode(';', $statements);
		$err_statements = array();
		
		foreach ($statements as $statement)
		{
			if (trim($statement) != '')
			{
				try
				{
					$this->db->query($statement);
				}
				catch (Exception $e)
				{
					$err_statements[count($err_statements)] = $statement;
				}
			}
		}
		
		return $err_statements;
	}
}
?>