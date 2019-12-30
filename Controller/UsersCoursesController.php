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
		$this->loadModel('User');
		$this->loadModel('Attendance');
		$this->loadModel('Date');

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

		// 全体のお知らせもお知らせも存在しない場合
		if(($info=="") && count($infos)==0)
			$no_info = __('お知らせはありません');

		// 受講コース情報の取得
		//$courses = $this->UsersCourse->getCourseRecord($user_id);
		$all_courses = $this->UsersCourse->getCourseRecord($user_id);
    //$this->log($all_courses);
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
        // 前庭コースが無いか，既にクリアしたコンテンツが一つ以上ある
        if($this->Course->existCleared($user_id, $now_course_id) || $before_course_id === null){
          //$this->log($course);
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

		// role == 'user'の出席情報を取る(授業日のみ)
		if($role === 'user' && $this->Date->isClassDate()){
			$this->loadModel('Log');

			$today_date_id = $this->Date->getTodayClassId();

			$standard_ip = $this->findStandardIP();
			$user_ip = $this->request->ClientIp();

			//実用する時，ここを==にする．
			if($user_ip == $standard_ip){

				$today_attendance_info = $this->Attendance->find('first', array(
					'conditions' => array(
						'user_id' => $user_id,
						'date_id' => $today_date_id
					),
					'recursive' => -1
				));

				$save_info = $today_attendance_info['Attendance'];

				if($save_info['status'] == 0 or $save_info['status'] == 2){  // 元の出欠情報が欠席または未定なら
					$save_info['status'] = 1;

					$save_info['login_time'] = date('Y-m-d H:i:s');
					$login_time = (int)strtotime($save_info['login_time']);
					$save_info['late_time'] = $this->Attendance->calcLateTime($today_date_id, $login_time);

					$this->Attendance->save($save_info);
				}
			}
		}

		$user_info = $this->Attendance->findRecentAttendances($user_id);
		$this->set(compact("user_info"));
		$this->log($user_info);
	}
}
