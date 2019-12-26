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
		$period       = (isset($this->request->query['period'])) ? $this->request->query['period'] : "";
		$group_id			= (isset($this->request->query['group_id'])) ? $this->request->query['group_id'] : "";
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


		if($name != ""){
			$conditions['OR'] = array(
				"User.name like" => "%$name%",
				"User.name_furigana like" => "%$name%",
				"User.username like" => "%$name%"
			);
		}

		// グループが指定されている場合、指定したグループに所属するユーザの履歴を抽出
		if($group_id != "")
			$conditions['User.id'] = $this->Group->getUserIdByGroupID($group_id);

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
		{
			$this->autoRender = false;

			// メモリサイズ、タイムアウト時間を設定
			ini_set("memory_limit", '512M');
			ini_set("max_execution_time", (60 * 10));

			// Content-Typeを指定
			$this->response->type('csv');

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="soap_records.csv"');

			$fp = fopen('php://output','w');

			$options = array(
				'conditions'	=> $conditions,
				'order'			=> 'Soap.created desc'
			);

			$this->Soap->recursive = 0;
			$rows = $this->Soap->find('all', $options);

			$header = array(
				"受講日",
				"受講生名",
				"担当講師名",
				"1限 or 2限",
				"STEP教材 or 応用教材",
				"S",
				"O",
				"A",
				"P",
				"自由記述"
			);

			mb_convert_variables("SJIS-WIN", "UTF-8", $header);
			fputcsv($fp, $header);

			foreach($rows as $row)
			{
				if($row['User']['period'] == 0) {
					$class_hour = "1限";
				} elseif($row['User']['period'] == 1) {
					$class_hour = "2限";
				} else {
					$class_hour = "時限未設定";
				}
				$row = array(
					Utils::getYMD($row['Soap']['created']),
					$row['User']['name'],
					$row['Group']['title'],
					$class_hour,
					$row['Course']['title'],
					$row['Soap']['S'],
					$row['Soap']['O'],
					$row['Soap']['A'],
					$row['Soap']['P'],
					$row['Soap']['comment']
				);

				mb_convert_variables("SJIS-WIN", "UTF-8", $row);

				fputcsv($fp, $row);
			}

			fclose($fp);
		}
		else
		{
			if(@$this->request->query['cmd']=='today'){
				$this->log('work');
	
				$from_date = array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));
				$to_date = array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));
	
				
				// 学習日付による絞り込み
				$conditions['Soap.created BETWEEN ? AND ?'] = array(
					implode("/", $from_date),
					implode("/", $to_date).' 23:59:59'
				);
			}
			$this->Paginator->settings['conditions'] = $conditions;
			$this->Paginator->settings['order']      = 'Soap.created desc';
			$this->Paginator->settings['limit'] = 1000;
      $this->Paginator->settings['maxLimit'] = 1000;
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
			$this->set('groups',      $this->Group->find('list'));
			$this->set('courses',     $this->Course->find('list'));
			$this->set('name',        $name);
			$this->set('period',      $period);
			$this->set('group_id',    $group_id);
			$this->set('course_id',   $course_id);
			$this->set('from_date',   $from_date);
			$this->set('to_date',     $to_date);

			// 最も古いSoapが作られた年を取得
			$oldest_created_year = $this->Soap->getOldestCreatedYear();
			$this->set('oldest_created_year', $oldest_created_year);
		}
	}
}
