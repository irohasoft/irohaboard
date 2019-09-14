<?php
/**
 * Ripple Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');
App::uses('UsersGroup',    'UsersGroup');
App::uses('User',          'User');
App::uses('Group',         'Group');
App::uses('Soap',          'Soap');

/**
 * Records Controller
 *
 * @property Record $Record
 * @property PaginatorComponent $Paginator
 */
class SoapRecordsController extends AppController
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
		)
	);

	/**
	 * SOAP一覧を表示
	 */
	public function admin_index(){
		$this->loadModel('Soap');

		// SearchPluginの呼び出し
		$this->Prg->commonProcess('Soap');
		// Model の filterArgs に定義した内容にしたがって検索条件を作成
		// ただしアソシエーションテーブルには対応していないため、独自に検索条件を設定する必要がある
		$conditions = $this->Soap->parseCriteria($this->Prg->parsedParams());

		$name	        = (isset($this->request->query['name'])) ? $this->request->query['name'] : "";
		$group_title	= (isset($this->request->query['group_title'])) ? $this->request->query['group_title'] : "";
		$period       = (isset($this->request->query['period'])) ? $this->request->query['period'] : "";
		$course_id	  = (isset($this->request->query['course_id'])) ? $this->request->query['course_id'] : "";
		$from_date	  = (isset($this->request->query['from_date'])) ?
			$this->request->query['from_date'] :
				array(
					'year' => date('Y', strtotime("-1 month")),
					'month' => date('m', strtotime("-1 month")),
					'day' => date('d', strtotime("-1 month"))
				);
		$to_date	= (isset($this->request->query['to_date'])) ?
			$this->request->query['to_date'] :
				array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));


		if($name != "")
			$conditions['User.name like'] = '%'.$name.'%';

		if($group_title != "")
			$conditions['Group.title like'] = '%'.$group_title.'%';

		if($period != "")
			$conditions['User.period'] = $period;

		if($course_id != "")
			$conditions['Course.id'] = $course_id;

		// 受講日による絞り込み
		$conditions['Soap.created BETWEEN ? AND ?'] = array(
			implode("/", $from_date),
			implode("/", $to_date).' 23:59:59'
		);

		// CSV出力モードの場合
		if(@$this->request->query['cmd']=='csv')
		{/*
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

			fclose($fp);*/
		}
		else
		{
			$this->Paginator->settings['conditions'] = $conditions;
			$this->Paginator->settings['order']      = 'Soap.created desc';
			$this->Soap->recursive = 0;

			try
			{
				$result = $this->paginate('Soap');
			}
			catch (Exception $e)
			{
				$this->request->params['named']['page']=1;
				$result = $this->paginate('Soap');
			}

			$this->set('records', $result);


			$this->Course = new Course();

			$this->set('period_list', array('1限','2限'));
			$this->set('courses',     $this->Course->find('list'));
			$this->set('name',        $name);
			$this->set('group_title', $group_title);
			$this->set('period',      $period);
			$this->set('course_id',   $course_id);
			$this->set('from_date',   $from_date);
			$this->set('to_date',     $to_date);

			// 最も古いSoapが作られた年を取得
			$oldest_created_year = $this->Soap->getOldestCreatedYear();
			$this->set('oldest_created_year', $oldest_created_year);
		}
	}
}
