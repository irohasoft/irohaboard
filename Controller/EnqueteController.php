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
App::uses('Sanitize', 'Utility');


class EnqueteController extends AppController{
  public $helpers = array('Html', 'Form');

  public $components = array(
    'Paginator',
    'Search.Prg'
);

  //public $presetVars = true;

  public $paginate = array();

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
    $this->loadModel('Group');
    $this->loadModel('User');

    //$existed = $this->Enquete->isEnqueteExist;

    //今ログインしているUserのidを確認
    $user_id = $this->Auth->user('id');
    //$this->set('user_id',$user_id);

    //今日の日付を生成
    $today = date("Y/m/d");
    $this->set('today', $today);

    //グループリストを生成
    $group_list = $this->Group->find('list');
    $this->set('group_list',$group_list);

    //今所属するグループのidを探す．
    $group_id = $this->User->findUserGroup($user_id);
    $this->set('group_id',$group_id);

    //$entity = $this->Enquete->newEntity($this->request->data);

    if ($this->request->is(array(
      'post',
      'put'
  ))){
      $this->Enquete->set($this->request->data);
      //もしvalidateに満たさない場合
      if(!$this->Enquete->validates()){
        $errors = $this->Enquete->validationErrors;
        foreach($errors as $error){
          //this->log($error);
          $this->Session->setFlash($error[0]);
          return;
        }

      }else{
        //dataをサニタイズする．
        $request_data = array('user_id' => $user_id) + $this->request->data;
        $save_data = Sanitize::clean($request_data, array('encode' => false));
        //$result = $this->Enquete->save($save_data);
        if($this->Enquete->save($save_data)){
          $this->Flash->success(__('アンケートは提出されました，ありがとうございます'));

          $this->redirect("/users_courses");
        }else{
          $this->Flash->error(__('The enquete could not be saved. Please, try again.'));
        }
      }

    }
  }

  public function records(){
		$this->loadModel('Group');
		$user_id = $this->Auth->user('id');



		$this->Prg->commonProcess();
		$conditions = $this->Enquete->parseCriteria($this->Prg->parsedParams());

		$conditions['User.id'] = $user_id;

		$this->Paginator->settings['conditions'] = $conditions;
		$this->Paginator->settings['order']      = 'Enquete.created desc';
		$this->Enquete->recursive = 0;

		try
		{
			$result = $this->paginate();
		}
		catch (Exception $e)
		{
			$this->request->params['named']['page']=1;
			$result = $this->paginate();
  	 }

  	 //$this->log($result);

		$this->set('records', $result);

		//$groups = $this->Group->getGroupList();

		$this->Group = new Group();
		//$this->Course = new Course();
		$this->User = new User();
		//debug($this->User);

		$this->set('groups',     $this->Group->find('list'));
		//$this->set('courses',    $this->Course->find('list'));
  	 //$this->log($this->Course->find('list'));
		$this->set('group_id',   $group_id);
  	$this->set('name',       $name);
  	$this->set('period_list', Configure::read('period'));
  	$this->set('period',    $period);
  	$this->set('TF_list', Configure::read('true_or_false'));
		$this->set('from_date', $from_date);
		$this->set('to_date', $to_date);

  }

  public function admin_index(){

    $this->Prg->commonProcess();

		// Model の filterArgs に定義した内容にしたがって検索条件を作成
		// ただしアソシエーションテーブルには対応していないため、独自に検索条件を設定する必要がある
		$conditions = $this->Enquete->parseCriteria($this->Prg->parsedParams());

		$group_id			= (isset($this->request->query['group_id'])) ? $this->request->query['group_id'] : "";
    $period       = (isset($this->request->query['period'])) ? $this->request->query['period'] : "";
    $name				= (isset($this->request->query['name'])) ? $this->request->query['name'] : "";


		// グループが指定されている場合、指定したグループに所属するユーザの履歴を抽出
		if($group_id != "")
			$conditions['User.id'] = $this->Group->getUserIdByGroupID($group_id);

		if($name != ""){
			$conditions['OR'] = array(
				"User.name like" => "%$name%",
				"User.name_furigana like" => "%$name%"
			);
		}

    if($period != "")
			$conditions['User.period'] = $period;


		$from_date	= (isset($this->request->query['from_date'])) ?
		$this->request->query['from_date'] :
			array(
				'year' => date('Y', strtotime("-1 month")),
				'month' => date('m', strtotime("-1 month")),
				'day' => date('d', strtotime("-1 month"))
			);

		$to_date	= (isset($this->request->query['to_date'])) ?
			$this->request->query['to_date'] :
				array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));


		// 学習日付による絞り込み
		$conditions['Enquete.created BETWEEN ? AND ?'] = array(
			implode("/", $from_date),
			implode("/", $to_date).' 23:59:59'
		);

		// CSV出力モードの場合
		if(@$this->request->query['cmd']=='csv')
		{
      /*
			$this->autoRender = false;

			// メモリサイズ、タイムアウト時間を設定
			ini_set("memory_limit", '512M');
			ini_set("max_execution_time", (60 * 10));

			// Content-Typeを指定
			$this->response->type('csv');

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="user_records.csv"');

			$fp = fopen('php://output','w');

			$options = array(
				'conditions'	=> $conditions,
				'order'			=> 'Record.created desc'
			);

			$this->Record->recursive = 0;
			$rows = $this->Record->find('all', $options);

			$header = array("コース", "コンテンツ", "氏名", "得点", "合格点", "結果", "理解度", "学習時間", "学習日時");

			mb_convert_variables("SJIS-WIN", "UTF-8", $header);
			fputcsv($fp, $header);

			foreach($rows as $row)
			{
				$row = array(
					$row['Course']['title'],
					$row['Content']['title'],
					$row['User']['name'],
					$row['Record']['score'],
					$row['Record']['pass_score'],
					Configure::read('record_result.'.$row['Record']['is_passed']),
					Configure::read('record_understanding.'.$row['Record']['understanding']),
					Utils::getHNSBySec($row['Record']['study_sec']),
					Utils::getYMDHN($row['Record']['created']),
				);

				mb_convert_variables("SJIS-WIN", "UTF-8", $row);

				fputcsv($fp, $row);
			}

      fclose($fp);
      */
		}
		else
		{
			$this->Paginator->settings['conditions'] = $conditions;
			$this->Paginator->settings['order']      = 'Enquete.created desc';
			$this->Enquete->recursive = 0;

			try
			{
				$result = $this->paginate();
			}
			catch (Exception $e)
			{
				$this->request->params['named']['page']=1;
				$result = $this->paginate();
      }

      //$this->log($result);

			$this->set('records', $result);

			//$groups = $this->Group->getGroupList();

			$this->Group = new Group();
			//$this->Course = new Course();
			$this->User = new User();
			//debug($this->User);

			$this->set('groups',     $this->Group->find('list'));
			//$this->set('courses',    $this->Course->find('list'));
      //$this->log($this->Course->find('list'));
			$this->set('group_id',   $group_id);
      $this->set('name',       $name);
      $this->set('period_list', Configure::read('period'));
      $this->set('period',    $period);
      $this->set('TF_list', Configure::read('true_or_false'));
			$this->set('from_date', $from_date);
			$this->set('to_date', $to_date);
    }
  }
}
?>
