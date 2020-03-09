<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
*/

App::uses('AppController',   'Controller');
App::uses('RecordsQuestion', 'RecordQuestion');
App::uses('UsersGroup',      'UsersGroup');
App::uses('Course',          'Course');
App::uses('User',            'User');
App::uses('Group',           'Group');
App::uses('Enquete',         'Enquete');
App::uses('Attendance',      'Attendance');


class AdminManagesController extends AppController{
  public $helpers = array('Html', 'Form', 'Image');

  public $components = array(
    'Paginator',
    'Search.Prg'
);

  //public $presetVars = true;

  public $paginate = array(
    'maxLimit' => 1000
  );

  public $presetVars = array(
    array(
      'name' => 'name',
      'type' => 'value',
      'field' => 'User.name'
    ),
    array(
      'name' => 'username',
      'type' => 'like',
      'field' => 'User.username'
    )
  );

  public function index(){
  }

  public function records(){
  }

  public function admin_index(){
    $this->loadModel('User');
    $this->loadModel('Group');
    $this->loadModel('Date');

    $user_list = $this->User->find('all',array(
      'conditions' => array(
        'User.role' => 'user'
      ),
      'order' => 'User.created ASC'
    ));

    $this->set('user_list',$user_list);
    //$this->log($user_list);

    $group_list = $this->Group->find('list');
    $this->set('group_list',$group_list);

    //$this->log($user_list);
    $date_list = [];
    $date_list = $this->Date->find('list');
    $this->log($date_list);
    /*
    foreach($user_list as $user){
      $attendance_list = $user['Attendance'];
      foreach($attendance_list as $attendance){
        $created = new DateTime($attendance['created']);
        $created_day = $created->format('Y-m-d');
        $date_list[] = $created_day;
      }
      break;
    }
    */
    $this->set('date_list',$date_list);
    //$this->log($this->request->data);

    if(isset($this->request->data['User']['target_date'])){
      $target_date = $date_list[$this->request->data['User']['target_date']];
      $from_date_time = (int)strtotime($target_date);
      $to_date_time = $from_date_time + 172800;

      $this->autoRender = false;

			// メモリサイズ、タイムアウト時間を設定
			ini_set("memory_limit", '512M');
			ini_set("max_execution_time", (60 * 10));

			// Content-Typeを指定
			$this->response->type('csv');

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$target_date.'.csv"');

			$fp = fopen('php://output','w');

      /*
			$this->Enquete->recursive = 0;
			$rows = $this->Enquete->find('all', $options);
      */

			$header = array(
        "学籍番号",
        "氏名",
        "ふりがな",
        "時限",
        "出席状況",
        "担当講師",
        "今日の感想",
        "前回ゴールT/F",
        "前回ゴールF理由",
        "今日のゴール",
        "今日のゴールT/F",
        "今日のゴールF理由",
        "次回までゴール",
        "SOAP"
      );

			mb_convert_variables("SJIS-WIN", "UTF-8", $header);
      fputcsv($fp, $header);

      foreach($user_list as $user){
        $output_list = [];
        //学籍番号
        $output_list[] = $user['User']['username'];
        //氏名
        $output_list[] = $user['User']['name'];
        //ふりがな
        $output_list[] = $user['User']['name_furigana'];
        //時限
        if($user['User']['period'] == 0) {
          $class_hour = "1限";
        } elseif($user['User']['period'] == 1) {
          $class_hour = "2限";
        } else {
          $class_hour = "時限未設定";
        }
        $output_list[] = $class_hour;
        //出席
        $flag = 0;
        $attendance_info = $user['Attendance'];
        foreach($attendance_info as $row){
          $row_time = (int)strtotime($row['created']);
          if($from_date_time <= $row_time && $row_time <= $to_date_time){
            $flag = 1;
            if($row['status']){
              if($row['late_time'] != 0){
                $late_time = $row['late_time'];
                $output_list[] = '△'."($late_time)";
              }else{
                $output_list[] = '○';
              }
            }else{
              $output_list[] = '×';
            }

          }
        }
        if($flag != 1){
          $output_list[] = '';
        }

        //担当講師 & アンケート
        $flag = 0;
        $enquete_info = $user['Enquete'];
        foreach($enquete_info as $row){
          $row_time = (int)strtotime($row['created']);
          if($from_date_time <= $row_time && $row_time <= $to_date_time){
            $flag = 1;
            $output_list[] = $group_list[$row['group_id']];
            $output_list[] = $row['today_impressions'];
            if($row['before_goal_cleared']){
              $before_goal_cleared = "True";
            } else {
              $before_goal_cleared = "False";
            }
            $output_list[] = $row['before_false_reason'];
            $output_list[] = $row['today_goal'];
            $output_list[] = $before_goal_cleared;
            if($row['today_goal_cleared']){
              $today_goal_cleared = "True";
            } else {
              $today_goal_cleared = "False";
            }
            $output_list[] = $today_goal_cleared;
            $output_list[] = $row['today_false_reason'];
            $output_list[] = $row['next_goal'];
          }
        }

        if($flag != 1){
          $output_list += array(
            '','','','','','','',''
          );
        }

        //SOAP
        $flag = 0;
        $soap_info = $user['Soap'];
        foreach($soap_info as $row){
          $row_time = (int)strtotime($row['created']);
          if($from_date_time <= $row_time && $row_time <= $to_date_time){
            $flag = 1;
            $S = "S:".$row['S']."\n";
            $O = "O:".$row['O']."\n";
            $A = "A:".$row['A']."\n";
            $P = "P:".$row['P'];
            $SOAP = $S.$O.$A.$P;
            //$this->log($SOAP);
            $output_list[] = $SOAP;
          }
        }

        if($flag != 1){
          $output_list[] = '';
        }

				mb_convert_variables("SJIS-WIN", "UTF-8", $output_list);
				fputcsv($fp, $output_list);
			}

      fclose($fp);
    }
  }

  public function admin_download(){
    $this->loadModel('User');
    $this->loadModel('Group');
    $this->loadModel('Date');

    $user_list = $this->User->find('all',array(
      'conditions' => array(
        'User.role' => 'user'
      ),
      'order' => 'User.username ASC'
    ));

    $group_list = $this->Group->find('list');

    $today = date('Y-m-d');
    $date_list = [];
    $date_list = $this->Date->find('list',array(
      'fields'     => array('id', 'date'),
      'conditions' => array('date <= ?' => $today),
      'order'      => 'date DESC',
      'recursive'  => -1
    ));
    $this->set('date_list',$date_list);

    if(isset($this->request->data['User']['target_date'])){
      $target_date = $date_list[$this->request->data['User']['target_date']];
      $from_date_time = (int)strtotime($target_date);
      $to_date_time = $from_date_time + 172800;
      $target_date_id = array_search($target_date, $date_list);

      $this->autoRender = false;

			// メモリサイズ、タイムアウト時間を設定
			ini_set("memory_limit", '512M');
			ini_set("max_execution_time", (60 * 10));

			// Content-Typeを指定
			$this->response->type('csv');

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$target_date.'.csv"');

			$fp = fopen('php://output','w');

      /*
			$this->Enquete->recursive = 0;
			$rows = $this->Enquete->find('all', $options);
      */

			$header = array(
        "学籍番号",
        "氏名",
        "出席状況",
        "担当講師",
        "今日の感想",
        "SOAP"
      );

			mb_convert_variables("SJIS-WIN", "UTF-8", $header);
      fputcsv($fp, $header);



      foreach($user_list as $user){
        //$this->log($user);
        $output_list = [];
        //学籍番号
        $output_list[] = $user['User']['username'];
        //氏名
        $output_list[] = $user['User']['name'];

        //出席
        $flag = 0;
        $attendance_info = $user['Attendance'];
        foreach($attendance_info as $row){
          /** 条件を満たすログイン時間を探す */
          $date_id = $row['date_id'];
          $login_time = (int)strtotime($row['login_time']);
          if($date_id == $target_date_id){
            $flag = 1;
            if($row['status'] == 1){
              if($row['late_time'] != 0){
                $late_time = $row['late_time'];
                $output_list[] = '遅刻('."$late_time".'分)';
              }else{
                $output_list[] = '出席';
              }
            }else{
              $output_list[] = '欠席';
            }
            break;
          }
        }
        if($flag != 1){
          $output_list[] = '';
        }

        //担当講師 & アンケート
        $flag = 0;
        $enquete_info = $user['Enquete'];
        foreach($enquete_info as $row){
          $row_time = (int)strtotime($row['created']);
          if($from_date_time <= $row_time && $row_time <= $to_date_time){
            $flag = 1;
            $output_list[] = $group_list[$row['group_id']];
            $output_list[] = $row['today_impressions'];
          }
        }

        if($flag != 1){
          $output_list[] = '';
          $output_list[] = '';
        }

        //SOAP
        $flag = 0;
        $soap_info = $user['Soap'];
        foreach($soap_info as $row){
          $row_time = (int)strtotime($row['created']);
          if($from_date_time <= $row_time && $row_time <= $to_date_time){
            $flag = 1;
            $S = "S:".$row['S']."\n";
            $O = "O:".$row['O']."\n";
            $A = "A:".$row['A']."\n";
            $P = "P:".$row['P'];
            $SOAP = $S.$O.$A.$P;
            //$this->log($SOAP);
          }
        }
        if($flag == 1){
          $output_list[] = $SOAP;
        }

        if($flag != 1){
          $output_list[] = '';
        }
        $flag = 1;

				mb_convert_variables("SJIS-WIN", "UTF-8", $output_list);
				fputcsv($fp, $output_list);
			}
      fclose($fp);
    }
  }
}
?>
