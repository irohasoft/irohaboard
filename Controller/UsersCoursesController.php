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

/**
 * UsersCourses Controller
 *
 * @property UsersCourse $UsersCourse
 * @property PaginatorComponent $Paginator
 */
class UsersCoursesController extends AppController
{
	public $components = array(
	);

	public function index()
	{
		// 全体のお知らせの取得
		App::import('Model', 'Setting');
		$this->Setting = new Setting();
		
		$data = $this->Setting->find('all', array(
			'conditions' => array(
				'Setting.setting_key' => 'information'
			)
		));
		
		$info = $data[0]['Setting']['setting_value'];
		
		// お知らせ一覧を取得
		$this->loadModel('Info');
		$infos = $this->Info->getInfos($this->Session->read('Auth.User.id'), 2);
		
		$no_info = "";
		
		// 全体のお知らせもお知らせも存在しない場合
		if(($info=="") && count($infos)==0)
			$no_info = "お知らせはありません";
		
		// 受講コース情報の取得
		$courses = $this->UsersCourse->getCourseRecord( $this->Session->read('Auth.User.id') );
		
		$no_record = "";
		
		if(count($courses)==0)
			$no_record = "受講可能なコースはありません";
		
		$this->set(compact('courses', 'no_record', 'info', 'infos', 'no_info'));
	}
}
