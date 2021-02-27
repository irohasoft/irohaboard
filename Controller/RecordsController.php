<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController',		'Controller');

/**
 * Records Controller
 *
 * @property Record $Record
 * @property PaginatorComponent $Paginator
 */
class RecordsController extends AppController
{
	public $components = [
		'Paginator',
		'Search.Prg'
	];

	/**
	 * 学習履歴一覧を表示
	 */
	public function admin_index()
	{
		// SearchPluginの呼び出し
		$this->Prg->commonProcess();
		
		// モデルの filterArgs で定義した内容にしたがって検索条件を作成
		// ただし独自の検索条件は別途追加する必要がある
		$conditions = $this->Record->parseCriteria($this->Prg->parsedParams());
		
		// 独自の検索条件
		$group_id			= $this->getQuery('group_id');
		$content_category	= $this->getQuery('content_category');
		
		// グループが指定されている場合、指定したグループに所属するユーザの履歴を抽出
		if($group_id != '')
			$conditions['User.id'] = $this->Group->getUserIdByGroupID($group_id);
		
		// コンテンツ種別：学習の場合
		if($content_category == 'study')
			$conditions['Content.kind'] = ['text', 'html', 'movie', 'url'];
		
		// コンテンツ種別：テストの場合
		if($content_category == 'test')
			$conditions['Content.kind'] = ['test'];
		
		// 対象日時による絞り込み
		$from_date	= ($this->hasQuery('from_date')) ? implode('-', $this->getQuery('from_date')) : date('Y-m-d', strtotime('-1 month'));
		$to_date	= ($this->hasQuery('to_date'))   ? implode('-', $this->getQuery('to_date'))   : date('Y-m-d');
		
		$conditions['Record.created BETWEEN ? AND ?'] = [$from_date, $to_date.' 23:59:59'];
		
		// CSV出力モードの場合
		if($this->getQuery('cmd') == 'csv')
		{
			$this->autoRender = false;

			// メモリサイズ、タイムアウト時間を設定
			ini_set('memory_limit', '512M');
			ini_set('max_execution_time', (60 * 10));

			// Content-Typeを指定
			$this->response->type('csv');

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="user_records.csv"');
			
			$fp = fopen('php://output','w');
			
			$this->Record->recursive = 0;
			
			$rows = $this->Record->find()
				->where($conditions)
				->order('Record.created desc')
				->all();
			
			$header = [
				__('ログインID'),
				__('氏名'),
				__('コース'),
				__('コンテンツ'),
				__('得点'),
				__('合格点'),
				__('結果'),
				__('理解度'),
				__('学習時間'),
				__('学習日時')
			];
			
			mb_convert_variables('SJIS-WIN', 'UTF-8', $header);
			fputcsv($fp, $header);
			
			foreach($rows as $row)
			{
				$row = [
					$row['User']['username'], 
					$row['User']['name'], 
					$row['Course']['title'], 
					$row['Content']['title'], 
					$row['Record']['score'], 
					$row['Record']['pass_score'], 
					Configure::read('record_result.'.$row['Record']['is_passed']), 
					Configure::read('record_understanding.'.$row['Record']['understanding']), 
					Utils::getHNSBySec($row['Record']['study_sec']), 
					Utils::getYMDHN($row['Record']['created']),
				];
				
				mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
				
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
				$records = $this->paginate();
			}
			catch(Exception $e)
			{
				$this->request->params['named']['page']=1;
				$records = $this->paginate();
			}
			
			$groups = $this->Group->find('list');
			$courses = $this->Record->Course->find('list');
			
			$this->set(compact('records', 'groups', 'group_id', 'courses', 'content_category', 'from_date', 'to_date'));
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
		$this->autoRender = FALSE;
		
		// コンテンツ情報を取得
		$this->loadModel('Content');
		$content = $this->Content->get($content_id);
		
		$this->Record->create();
		$data = [
			'user_id'		=> $this->readAuthUser('id'),
			'course_id'		=> $content['Course']['id'],
			'content_id'	=> $content_id,
			'study_sec'		=> $study_sec,
			'understanding'	=> $understanding,
			'is_passed'		=> -1,
			'is_complete'	=> $is_complete
		];
		
		if($this->Record->save($data))
		{
			$this->Flash->success(__('学習履歴を保存しました'));
			return $this->redirect([
				'controller' => 'contents',
				'action' => 'index',
				$content['Course']['id']
			]);
		}
		else
		{
			$this->Flash->error(__('The record could not be saved. Please, try again.'));
		}
	}
}
