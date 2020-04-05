<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
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

	public $components = array(
			'DebugKit.Toolbar',
			'Session',
			'Flash',
			'Auth' => array(
					'loginRedirect' => array(
							'controller' => 'users_courses',
							'action' => 'index'
					),
					'logoutRedirect' => array(
							'controller' => 'users',
							'action' => 'login',
							'home'
					),
					'authError' => false
			)
	);

	//public $helpers = array('Session');
	public $helpers = array(
		'Session',
		'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
		'Form' => array('className' => 'BoostCake.BoostCakeForm'),
		'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
		'Image'
	);

	public $uses = array('Setting');

	public function beforeFilter()
	{
		$this->set('loginedUser', $this->Auth->user());

		// 他のサイトの設定が存在する場合、設定情報及びログイン情報をクリア
		if($this->Session->check('Setting'))
		{
			if($this->Session->read('Setting.app_dir')!=APP_DIR)
			{
				// セッション内の設定情報を削除
				$this->Session->delete('Setting');

				// 他のサイトとのログイン情報の混合を避けるため、強制ログアウト
				if($this->Auth->user())
				{
					//$this->Cookie->delete('Auth');
					$this->redirect($this->Auth->logout());
					return;
				}
			}
		}

		// データベース内に格納された設定情報をセッションに格納
		if(!$this->Session->check('Setting'))
		{
			$settings = $this->Setting->getSettings();

			$this->Session->Write('Setting.app_dir', APP_DIR);

			foreach ($settings as $key => $value)
			{
				$this->Session->Write('Setting.'.$key, $value);
			}
		}

		if (isset($this->request->params['admin']))
		{
			// role が admin, manager, editor, teacher以外の場合、強制ログアウトする
			if($this->Auth->user())
			{
				if(
					($this->Auth->user('role')!='admin')&&
					($this->Auth->user('role')!='manager')&&
					($this->Auth->user('role')!='editor')&&
					($this->Auth->user('role')!='teacher')
				)
				{
					if($this->Cookie)
						$this->Cookie->delete('Auth');

					$this->redirect($this->Auth->logout());
					return;
				}
			}

			$this->Auth->loginAction = array(
					'controller' => 'users',
					'action' => 'login',
					'admin' => true
			);
			$this->Auth->loginRedirect = array(
					'controller' => 'recentstates',
					'action' => 'index',
					'admin' => true
			);
			$this->Auth->logoutRedirect = array(
					'controller' => 'users',
					'action' => 'login',
					'admin' => true
			);
			$this->set('loginURL', "/users/login/");
			$this->set('logoutURL', "/users/logout/");

			// グループモデルを共通で保持する
			$this->loadModel('Group');
		}
		else
		{
			// role が graduateの場合、強制ログアウトする
			if($this->Auth->user())
			{
				if($this->Auth->user('role')=='graduate')
				{
					if($this->Cookie)
						$this->Cookie->delete('Auth');

					$this->Flash->error("卒業生はログインできません．");
					$this->redirect($this->Auth->logout());
					return;
				}
			}

			$this->Auth->loginAction = array(
					'controller' => 'users',
					'action' => 'login',
					'admin' => false
			);
			$this->Auth->loginRedirect = array(
					'controller' => 'users',
					'action' => 'index',
					'admin' => false
			);
			$this->Auth->logoutRedirect = array(
					'controller' => 'users',
					'action' => 'login',
					'admin' => false
			);

			$this->set('loginURL', "/users/login/");
			$this->set('logoutURL', "/users/logout/");
			// $this->layout = 'login'; //レイアウトを切り替える。
			// AuthComponent::$sessionKey = "Auth.User";
		}
	}

	public function beforeRender()
	{
		//header("X-XSS-Protection: 1; mode=block")

		// iframeへの埋め込みの禁止
		header("X-Frame-Options: DENY");
	}

	function writeLog($log_type, $log_content)
	{
		$data = array(
			'log_type'    => $log_type,
			'log_content' => $log_content,
			'user_id'     => $this->Auth->user('id'),
			'user_ip'     => $_SERVER['REMOTE_ADDR'],
			'user_agent'  => $_SERVER['HTTP_USER_AGENT']
		);


		$this->loadModel('Log');
		$this->Log->create();
		$this->Log->save($data);
	}

	function findStandardIP(){
		$this->loadModel('Group');
		$this->loadModel('User');
		$this->loadModel('Log');
		//スタッフグループのidを探す
		$staff_group_info = $this->Group->find('first',array(
			'conditions' => array(
				'Group.title like' => 'スタッフ'
			)
		));
		$staff_group_id = $staff_group_info['Group']['id'];

		//スタッフグループに所属するメンバーリストを探す
		$staff_member_list = $this->User->find('list',array(
			'conditions' => array(
				'User.group_id' => $staff_group_id
			),
			'fields' => array(
				'User.id',
				'User.id'
			),
			'order' => array(
				'User.id ASC'
			)
		));

		//メンバーのログインipを探し，当日の基準ipを決める
		$staff_member_last_login_ip_list = [];
		foreach ($staff_member_list as $row){
			$row_info = $this->Log->find('first',array(
				'conditions' => array(
					'Log.user_id' => $row
				),
				'order' => array(
					'Log.created' => 'desc'
				)
			));
			$row_ip = $row_info['Log']['user_ip'];
			$staff_member_last_login_ip_list[$row] = (string)$row_ip;
		}

		$ip_count = array_count_values($staff_member_last_login_ip_list);
		$standard = array_keys($ip_count, max($ip_count));
		$standard_ip = $standard[0];
		//$this->log($standard_ip);
		return $standard_ip;
	}

}
