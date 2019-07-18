<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController',		'Controller');
App::uses('RecordsQuestion',	'RecordsQuestion');
App::uses('UsersGroup',			'UsersGroup');
App::uses('Course', 'Course');
App::uses('User',   'User');
App::uses('Group',  'Group');

/**
 * Records Controller
 *
 * @property Record $Record
 * @property PaginatorComponent $Paginator
 */
class RecordsController extends AppController
{

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
		), 
		array(
			'name' => 'contenttitle', 'type' => 'like',
			'field' => 'Content.title'
		)
	);

	/**
	 * 学習履歴一覧を表示
	 */
	public function admin_index()
	{
		// SearchPluginの呼び出し
		$this->Prg->commonProcess();
		
		// Model の filterArgs に定義した内容にしたがって検索条件を作成
		// ただしアソシエーションテーブルには対応していないため、独自に検索条件を設定する必要がある
		$conditions = $this->Record->parseCriteria($this->Prg->parsedParams());
		
		$group_id			= (isset($this->request->query['group_id'])) ? $this->request->query['group_id'] : "";
		$course_id			= (isset($this->request->query['course_id'])) ? $this->request->query['course_id'] : "";
		$name				= (isset($this->request->query['name'])) ? $this->request->query['name'] : "";
		$content_category	= (isset($this->request->query['content_category'])) ? $this->request->query['content_category'] : "";
		$contenttitle		= (isset($this->request->query['contenttitle'])) ? $this->request->query['contenttitle'] : "";
		
		
		// グループが指定されている場合、指定したグループに所属するユーザの履歴を抽出
		if($group_id != "")
			$conditions['User.id'] = $this->Group->getUserIdByGroupID($group_id);
		
		if($course_id != "")
			$conditions['Course.id'] = $course_id;
		
		if($name != "")
			$conditions['User.name like'] = '%'.$name.'%';
		
		// コンテンツ種別：学習の場合
		if($content_category == "study")
			$conditions['Content.kind'] = array('text', 'html', 'movie', 'url');
		
		// コンテンツ種別：テストの場合
		if($content_category == "test")
			$conditions['Content.kind'] = array('test');
		
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
		
		if($contenttitle != "")
			$conditions['Content.title like'] = '%'.$contenttitle.'%';
		
		// 学習日付による絞り込み
		$conditions['Record.created BETWEEN ? AND ?'] = array(
			implode("/", $from_date), 
			implode("/", $to_date).' 23:59:59'
		);
		
		// CSV出力モードの場合
		if(@$this->request->query['cmd']=='csv')
		{
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
		}
		else
		{
			$this->Paginator->settings['conditions'] = $conditions;
			$this->Paginator->settings['order']      = 'Record.created desc';
			$this->Record->recursive = 0;
			
			try
			{
				$result = $this->paginate();
			}
			catch (Exception $e)
			{
				$this->request->params['named']['page']=1;
				$result = $this->paginate();
			}
			
			$this->set('records', $result);
			
			//$groups = $this->Group->getGroupList();
			
			$this->Group = new Group();
			$this->Course = new Course();
			$this->User = new User();
			//debug($this->User);
			
			$this->set('groups',     $this->Group->find('list'));
			$this->set('courses',    $this->Course->find('list'));
			$this->set('group_id',   $group_id);
			$this->set('course_id',  $course_id);
			$this->set('name',       $name);
			$this->set('content_category',	$content_category);
			$this->set('contenttitle',		$contenttitle);
			$this->set('from_date', $from_date);
			$this->set('to_date', $to_date);
		}
	}

	/**
	 * 学習履歴を追加
	 * 
	 * @param int $content_id    コンテンツID
	 * @param int $is_complete   完了フラグ
	 * @param int $study_sec     学習時間
	 * @param int $understanding 理解度
	 */
	public function add($content_id, $is_complete, $study_sec, $understanding)
	{
		// コンテンツ情報を取得
		$this->loadModel('Content');
		$content = $this->Content->find('first', array(
			'conditions' => array(
				'Content.id' => $content_id
			)
		));
		
		$this->Record->create();
		$data = array(
//				'group_id' => $this->Session->read('Auth.User.Group.id'),
			'user_id'		=> $this->Auth->user('id'),
			'course_id'		=> $content['Course']['id'],
			'content_id'	=> $content_id,
			'study_sec'		=> $study_sec,
			'understanding'	=> $understanding,
			'is_passed'		=> -1,
			'is_complete'	=> $is_complete
		);
		
		if ($this->Record->save($data))
		{
			$this->Flash->success(__('学習履歴を保存しました'));
			return $this->redirect(array(
				'controller' => 'contents',
				'action' => 'index',
				$content['Course']['id']
			));
		}
		else
		{
			$this->Flash->error(__('The record could not be saved. Please, try again.'));
		}
	}
}
