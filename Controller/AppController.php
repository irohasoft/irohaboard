<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('Controller', 'Controller');
App::import('Vendor', 'Utils');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
	public $components = [
		'DebugKit.Toolbar',
		'Session',
		'Flash',
		'Auth' => [
			'loginRedirect' => ['controller' => 'users_courses', 'action' => 'index'],
			'logoutRedirect' => ['controller' => 'users','action' => 'login','home'],
			'authError' => false
		]
	];
	
	public $helpers = [
		'Session',
		'Html' => ['className' => 'BoostCake.BoostCakeHtml'],
		'Form' => ['className' => 'BoostCake.BoostCakeForm'],
		'Paginator' => ['className' => 'BoostCake.BoostCakePaginator'],
	];
	
	public $uses = ['Setting', 'Group'];
	public $viewClass = 'App'; // 独自のビュークラスを指定

	/**
	 * コールバック（コントローラのアクションロジック実行前に実行）
	 */
	public function beforeFilter()
	{
		$this->set('loginedUser', $this->readAuthUser()); // ログインユーザ情報（旧バージョン用）
		
		// 他のサイトの設定が存在する場合、設定情報及びログイン情報をクリア
		if($this->hasSession('Setting'))
		{
			if($this->readSession('Setting.app_dir') != APP_DIR)
			{
				// セッション内の設定情報を削除
				$this->deleteSession('Setting');
				
				// 他のサイトとのログイン情報の混合を避けるため、強制ログアウト
				if($this->readAuthUser())
				{
					//$this->Cookie->delete('Auth');
					$this->redirect($this->Auth->logout());
					return;
				}
			}
		}
		
		// データベース内に格納された設定情報をセッションに格納
		if(!$this->hasSession('Setting'))
		{
			$settings = $this->Setting->getSettings();
			
			$this->writeSession('Setting.app_dir', APP_DIR);
			
			foreach($settings as $key => $value)
			{
				$this->writeSession('Setting.'.$key, $value);
			}
		}
		
		if($this->isAdminPage())
		{
			// role が admin, manager, editor, teacher以外の場合、強制ログアウトする
			if($this->readAuthUser())
			{
				if(
					($this->readAuthUser('role') != 'admin')&&
					($this->readAuthUser('role') != 'manager')&&
					($this->readAuthUser('role') != 'editor')&&
					($this->readAuthUser('role') != 'teacher')
				)
				{
					if($this->Cookie)
						$this->Cookie->delete('Auth');
					
					$this->Flash->error(__('管理画面へのアクセス権限がありません'));
					$this->redirect($this->Auth->logout());
					return;
				}
			}
			
			$this->Auth->loginAction = ['controller' => 'users','action' => 'login','admin' => true];
			$this->Auth->loginRedirect = ['controller' => 'users','action' => 'index','admin' => true];
			$this->Auth->logoutRedirect = ['controller' => 'users','action' => 'login','admin' => true];
		}
		else
		{
			$this->Auth->loginAction = ['controller' => 'users', 'action' => 'login', 'admin' => false];
			$this->Auth->loginRedirect = ['controller' => 'users', 'action' => 'index', 'admin' => false];
			$this->Auth->logoutRedirect = ['controller' => 'users', 'action' => 'login', 'admin' => false];
		}
	}

	public function beforeRender()
	{
		//header("X-XSS-Protection: 1; mode=block")
		
		// 他のドメインからのiframeへの埋め込みの禁止
		header("X-Frame-Options: SAMEORIGIN");
	}

	/**
	 * セッションの取得
	 * @param string $key キー
	 */
	protected function readSession($key)
	{
		return $this->Session->read($key);
	}

	/**
	 * セッションの削除
	 * @param string $key キー
	 */
	protected function deleteSession($key)
	{
		$this->Session->delete($key);
	}

	/**
	 * セッションの存在確認
	 * @param string $key キー
	 */
	protected function hasSession($key)
	{
		return $this->Session->check($key);
	}

	/**
	 * セッションの保存
	 * @param string $key キー
	 * @param string $value 値
	 */
	protected function writeSession($key, $value)
	{
		$this->Session->write($key, $value);
	}

	/**
	 * ログインユーザ情報の取得
	 * @param string $key キー
	 */
	protected function readAuthUser($key = null)
	{
		if(!$key)
			return $this->Auth->user();
		
		return $this->Auth->user($key);
	}

	/**
	 * ログイン確認
	 * @return bool true : ログイン済み, false : ログインしていない
	 */
	protected function isLogined()
	{
		$val =  $this->Auth->user();
		
		return  ($val != null);
	}

	/**
	 * クエリストリングの取得
	 * @param string $key キー
	 * @param string $default キーが存在しない場合に返す値
	 */
	protected function getQuery($key, $default = '')
	{
		if(!isset($this->request->query[$key]))
			return $default;
		
		$val = $this->request->query[$key];
		
		if($val == null)
			return $default;
		
		return $val;
	}

	/**
	 * クエリストリングの存在確認
	 * @param string $key キー
	 */
	protected function hasQuery($key)
	{
		return isset($this->request->query[$key]);
	}

	/**
	 * ルート要素とリクエストパラメータを取得
	 * @param string $key キー
	 * @param string $default キーが存在しない場合に返す値
	 */
	protected function getParam($key, $default = '')
	{
		if(!isset($this->request->params[$key]))
			return $default;
		
		$val = $this->request->params[$key];
		
		if($val == null)
			return $default;
		
		return $val;
	}

	/**
	 * POSTデータの取得
	 * @param string $key キー
	 * @param string $default キーが存在しない場合に返す値
	 */
	protected function getData($key = null, $default = null)
	{
		$val = $this->request->data;
		
		if(!$val)
			return $default;
		
		if($key)
			$val = empty($val[$key]) ? $default :$val[$key];
		
		return $val;
	}

	/**
	 * POSTデータの上書き
	 * @param string $key キー
	 * @param string $value 値
	 */
	protected function setData($key, $value)
	{
		if($key)
		{
			$this->request->data[$key] = $value;
		}
		else
		{
			$this->request->data = $value;
		}
	}

	/**
	 * 管理画面へのアクセスかを確認
	 * @return bool true : 管理画面, false : 受講者画面
	 */
	protected function isAdminPage()
	{
		return (isset($this->request->params['admin']));
	}

	/**
	 * 編集画面へのアクセスかを確認
	 */
	protected function isEditPage()
	{
		return (($this->action == 'edit') || ($this->action == 'admin_edit'));
	}

	/**
	 * テスト結果画面へのアクセスかを確認
	 */
	protected function isRecordPage()
	{
		return (($this->action == 'record') || ($this->action == 'admin_record'));
	}

	/**
	 * ログイン画面へのアクセスかを確認
	 */
	protected function isLoginPage()
	{
		return (($this->action == 'login') || ($this->action == 'admin_login'));
	}

	/**
	 * ログの保存
	 * @param string $log_type ログの種類
	 * @param string $log_content ログの内容
	 */
	protected function writeLog($log_type, $log_content)
	{
		$data = [
			'log_type'    => $log_type,
			'log_content' => $log_content,
			'user_id'     => $this->readAuthUser('id'),
			'user_ip'     => $_SERVER['REMOTE_ADDR'],
			'user_agent'  => $_SERVER['HTTP_USER_AGENT']
		];
		
		$this->loadModel('Log');
		$this->Log->create();
		$this->Log->save($data);
	}
}
