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

	/**
	 * 受講コース一覧（ホーム画面）を表示
	 */
	public function index()
	{
		$this->loadModel('Group');
		$this->loadModel('User');
		$this->loadModel('Attendance');
		$this->loadModel('Date');
		$this->loadModel('Enquete');

		$user_id = $this->Auth->user('id');

		$role = $this->Auth->user('role');
    $this->set('role',$role);

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
    $this->loadModel('Course');
		$infos = $this->Info->getInfos($user_id, 2);

		$no_info = "";

		// 全体のお知らせが存在しない場合
		if($info==""){ $no_info = __('お知らせはありません'); }

		// 次回までのゴールを取得
		$next_goal = $this->Enquete->findCurrentNextGoal($user_id);
		$this->set('next_goal', $next_goal);

		// 受講コース情報の取得
		//$courses = $this->UsersCourse->getCourseRecord($user_id);
		$all_courses = $this->UsersCourse->getCourseRecord($user_id);
		$courses = [];
		// 管理者の場合，コースを全部表示
    if($role === 'admin'){
      $courses = $all_courses;
    }else{
			//受講生の場合
      foreach($all_courses as $course){
				//もし,コースが非公開設定になっている場合
				if($course['Course']['status'] == 0){
					continue;
				}

        $before_course_id = $course['Course']['before_course'];
        $now_course_id = $course['Course']['id'];
        // 前提コースが無いか，既にクリアしたコンテンツが一つ以上ある
        if($this->Course->existCleared($user_id, $now_course_id) || $before_course_id === null){
          array_push($courses, $course);
        }else{
          $result = $this->Course->goToNextCourse($user_id, $before_course_id, $now_course_id);
          if($result){
            array_push($courses, $course);
          }else{
            continue;
          }
        }
      }
    }

		$no_record = "";

		if(count($courses)==0){$no_record = __('受講可能なコースはありません');}

		$this->set(compact('courses', 'no_record', 'info', 'infos', 'no_info'));

		// role == 'user'の出席情報を取る
		if($role === 'user' && $this->Date->isClassDate()){
			$user_ip = $this->request->ClientIp();
			$have_to_write_today_goal = $this->Attendance->takeAttendance($user_id, $user_ip);
			$this->set('have_to_write_today_goal', $have_to_write_today_goal);

			$group_list = $this->Group->find('list');
			$this->set('group_list',$group_list);
			$group_id = $this->User->findUserGroup($user_id);
			$this->set('group_id',$group_id);
		}

		$user_info = $this->Attendance->getAllTimeAttendances($user_id);
		$this->set(compact("user_info"));
	}
}
