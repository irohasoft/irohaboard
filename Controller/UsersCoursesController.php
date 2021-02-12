<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
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
	/**
	 * 受講コース一覧（ホーム画面）を表示
	 */
	public function index()
	{
		$user_id = $this->readAuthUser('id');
		
		// 全体のお知らせの取得
		$data = $this->Setting->find()
			->where(['Setting.setting_key' => 'information'])
			->first();
		
		$info = $data['Setting']['setting_value'];
		
		// お知らせ一覧を取得
		$this->loadModel('Info');
		$infos = $this->Info->getInfos($user_id, 2);
		
		$no_info = '';
		
		// 全体のお知らせもお知らせも存在しない場合
		if(($info == '') && (count($infos) == 0))
			$no_info = __('お知らせはありません');
		
		// 受講コース情報の取得
		$courses = $this->UsersCourse->getCourseRecord($user_id);
		
		$no_record = '';
		
		if(count($courses) == 0)
			$no_record = __('受講可能なコースはありません');
		
		$this->set(compact('courses', 'no_record', 'info', 'infos', 'no_info'));
	}
}
