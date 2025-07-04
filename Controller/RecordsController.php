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
 * https://book.cakephp.org/2/ja/controllers.html
 */
class RecordsController extends AppController
{
	/**
	 * 使用するコンポーネント
	 * https://book.cakephp.org/2/ja/core-libraries/toc-components.html
	 */
	public $components = [
		'Paginator',
		'Search.Prg',
		'Security' => [
			'validatePost' => false,
			'csrfUseOnce' => false,
			'csrfExpires' => '+3 hours',
			'csrfLimit' => 10000,
			'unlockedFields' => ['add']
		],
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
		{
			$conditions['Content.kind'] = ['text', 'html', 'movie', 'url'];
		}
		// テスト（test）等、学習以外のコンテンツ種別の場合
		if(
			($content_category != '')&&
			($content_category != 'study')
		)
		{
			$conditions['Content.kind'] = $content_category;
		}
		
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
		// テスト結果／アンケート回答CSV出力機能
		else if($this->getQuery('cmd') == 'csv_detail')
		{
			$conditions['ContentsQuestion.question_type != '] = 'label';
			
			$this->autoRender = false;

			// メモリサイズ、タイムアウト時間を設定
			ini_set('memory_limit', '512M');
			ini_set('max_execution_time', (60 * 10));
			
			//Content-Typeを指定
			
			$this->response->type('csv');

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="record_details.csv"');
			
			$fp = fopen('php://output','w');
			
			$options = [
				'conditions' => $conditions
			];
			
			$options['joins'] = [
				[
					'type' => 'LEFT',
					'table' => 'ib_users',
					'alias' => 'User',
					'conditions' => '`User`.`id`=`Record`.`user_id`',
				],
				[
					'type' => 'LEFT',
					'table' => 'ib_courses',
					'alias' => 'Course',
					'conditions' => '`Course`.`id`=`Record`.`course_id`',
				],
				[
					'type' => 'LEFT',
					'table' => 'ib_contents',
					'alias' => 'Content',
					'conditions' => '`Content`.`id`=`Record`.`content_id`',
				],
			];
			
			$options['fields'] = [
				'Course.title',
				'Content.title',
				'Content.kind',
				'ContentsQuestion.title',
				'ContentsQuestion.body',
				'ContentsQuestion.question_type',
				'ContentsQuestion.options',
				'ContentsQuestion.sort_no',
				'User.name',
				'User.username',
				'RecordsQuestion.answer',
				'RecordsQuestion.is_correct',
				'Record.created',
				'Record.id',
			];
			
			$options['order'] = ['User.name', 'Course.sort_no', 'Content.sort_no', 'Record.id', 'ContentsQuestion.sort_no'];
			
			//debug($options);
			$rows = $this->fetchTable('RecordsQuestion')->find('all', $options);
			
			$header = [
				__('ログインID'),
				__('氏名'),
				__('コース'),
				__('コンテンツ'),
				__('番号'),
				__('タイトル'),
				__('問題/質問'),
				__('解答/回答'),
				__('正誤'),
				__('学習日時')
			];
			
			mb_convert_variables("SJIS-WIN", "UTF-8", $header);
			
			fputcsv($fp, $header);
			
			$record_id  = '';
			$questno_no = '';
			
			foreach($rows as $row)
			{
				if($record_id!=$row['Record']['id'])
				{
					$questno_no = 1;
				}
				else
				{
					$questno_no++;
				}
				
				$record_id	= $row['Record']['id'];
				$answer = '';	// 解答/回答
				$result = '';	// 正誤
				
				if($row['ContentsQuestion']['question_type'] == 'text') // 記述式の場合
				{
					$answer = $row['RecordsQuestion']['answer'];
					// 計算式をエスケープ
					if(preg_match('/^\s*[=\+\-@]/', $answer))
						$answer = "'" . $answer;
				}
				else
				{
					$answer_no		= $row['RecordsQuestion']['answer'];
					$option_list	= explode('|', $row['ContentsQuestion']['options']);		// 選択肢リスト
					$answer_list	= explode(',', $row['RecordsQuestion']['answer']);			// 解答リスト
					$answer_str_list= [];
					
					foreach($answer_list as $answer)
					{
						$index = (int)$answer - 1;
						$answer_str_list[] = (isset($option_list[$index])) ? $option_list[$index] : '';
					}
					
					$answer = implode("|", $answer_str_list);
					
					if($row['Content']['kind'] == 'test')
						$result = Configure::read('is_correct.'.$row['RecordsQuestion']['is_correct']);
				}
				
				//debug(implode("/", $answer_str_list));
				$row = [
					$row['User']['username'], 
					$row['User']['name'], 
					$row['Course']['title'], 
					$row['Content']['title'], 
					$questno_no, 
					$row['ContentsQuestion']['title'], 
					strip_tags($row['ContentsQuestion']['body']), 
					$answer, 
					$result, 
					Utils::getYMDHN($row['Record']['created']),
				];
				
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
				$records = $this->paginate();
			}
			catch(Exception $e)
			{
				$this->request->params['named']['page']=1;
				$records = $this->paginate();
			}
			
			$groups = $this->fetchTable('Group')->find('list');
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
	public function add($content_id)
	{
		$this->autoRender = FALSE;
		$this->request->allowMethod('post');
		
		$content_id = intval($content_id);
		
		// コンテンツが存在しない場合
		if(!$this->fetchTable('Content')->exists($content_id))
		{
			throw new NotFoundException(__('Invalid content'));
		}

		// コンテンツ情報を取得
		$content = $this->fetchTable('Content')->get($content_id);
		
		// コンテンツの閲覧権限の確認
		if(!$this->fetchTable('Course')->hasRight($this->readAuthUser('id'), $content['Content']['course_id']))
		{
			throw new NotFoundException(__('Invalid access'));
		}

		// 管理者以外の場合、非公開コンテンツへのアクセスを禁止
		if($this->readAuthUser('role') != 'admin' && $content['Content']['status'] != 1)
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
		// POSTデータを取得
		$data = $this->getData();

		// 学習履歴データを作成
		$this->Record->create();

		$record_data = [
			'user_id'		=> $this->readAuthUser('id'),
			'course_id'		=> $content['Course']['id'],
			'content_id'	=> $content_id,
			'study_sec'		=> $data['study_sec'],
			'understanding'	=> $data['understanding'],
			'is_passed'		=> -1,
			'is_complete'	=> $data['is_complete']
		];
		
		// 学習履歴を保存
		if($this->Record->save($record_data))
		{
			$this->Flash->success(__('学習履歴を保存しました'));
			return $this->redirect(['controller' => 'contents', 'action' => 'index', $content['Course']['id']]);
		}
		else
		{
			$this->Flash->error(__('The record could not be saved. Please, try again.'));
		}
	}
}
