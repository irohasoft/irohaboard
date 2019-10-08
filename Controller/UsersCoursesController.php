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
        //$this->log($now_course_id);
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
		
		if(count($courses)==0)
			$no_record = __('受講可能なコースはありません');
		
		$this->set(compact('courses', 'no_record', 'info', 'infos', 'no_info'));
	}
}
