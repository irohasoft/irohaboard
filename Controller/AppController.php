<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
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
	);
	
	public $uses = array('Setting');
	
	public function beforeFilter()
	{
		$this->set('loginedUser', $this->Auth->user());
		
		// データベース内に格納された設定情報をセッションに格納
		if(!$this->Session->check('Setting'))
		{
			$settings = $this->Setting->getSettings();
			
			foreach ($settings as $key => $value)
			{
				$this->Session->Write('Setting.'.$key, $value);
			}
		}
		
		if (isset($this->request->params['admin']))
		{
			// roleがadminもしくは manager以外の場合、強制ログアウトする
			if($this->Auth->user())
			{
				if($this->Auth->user('role')!='admin')
				{
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
					'controller' => 'users',
					'action' => 'index',
					'admin' => true
			);
			$this->Auth->logoutRedirect = array(
					'controller' => 'users',
					'action' => 'login',
					'admin' => true
			);
			$this->set('loginURL', "/admin/users/login/");
			$this->set('logoutURL', "/admin/users/logout/");
			
			// グループ一覧を共通で保持する
			$this->loadModel('Group');
			$group_list = $this->Group->find('all');
			
			$this->set('group_list', 
					$this->Group->find('list', 
							array(
									'fields' => array(
											'id',
											'title'
									)
							)));
		}
		else
		{
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
}
