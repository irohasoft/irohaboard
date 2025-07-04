<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * ContentsQuestions Controller
 * https://book.cakephp.org/2/ja/controllers.html
 */
class EnquetesQuestionsController extends AppController
{
	/**
	 * 使用するコンポーネント
	 * https://book.cakephp.org/2/ja/core-libraries/toc-components.html
	 */
	public $components = [
		'Security' => [
			'validatePost' => false,
			'csrfUseOnce' => false,
			//'csrfCheck' => false,
			'csrfExpires' => '+3 hours',
			'csrfLimit' => 10000,
		],
	];

	/**
	 * 問題を出題
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID (テスト結果表示の場合、指定)
	 */
	public function index($content_id, $record_id = null)
	{
		$content_id = intval($content_id);
		$record_id = ($record_id != null) ? intval($record_id) : null;
		
		$this->fetchTable('ContentsQuestion')->recursive = 0;
		
		//------------------------------//
		//	コンテンツ情報を取得		//
		//------------------------------//
		$content = $this->fetchTable('Content')->get($content_id);
		
		//------------------------------//
		//	権限チェック				//
		//------------------------------//
		// 管理者以外の場合、コンテンツの閲覧権限の確認
		if(!$this->isAdminPage())
		{
			if(!$this->fetchTable('Course')->hasRight($this->readAuthUser('id'), $content['Content']['course_id']))
				throw new NotFoundException(__('Invalid access'));
		}
		
		// 管理者以外の場合、非公開コンテンツへのアクセスを禁止
		if($this->readAuthUser('role') != 'admin' && $content['Content']['status'] != 1)
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
		//------------------------------//
		//	問題情報を取得				//
		//------------------------------//
		$record = null;
		
		if($record_id != null) // テスト結果表示モードの場合
		{
			// テスト結果情報を取得
			$record = $this->fetchTable('Record')->get($record_id);
			
			// 受講者によるテスト結果表示の場合、自身のテスト結果か確認
			if(!$this->isAdminPage() && $this->isRecordPage() && ($record['Record']['user_id'] != $this->readAuthUser('id')))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			// テスト結果に紐づく問題ID一覧（出題順）を作成
			// 問題が存在しない場合のエラーを防ぐため、0を追加
			$question_id_list = [0];
			
			foreach($record['RecordsQuestion'] as $question)
			{
				$question_id_list[] = $question['question_id'];
			}
			
			// 問題ID一覧を元に問題情報を取得
			$contentsQuestions = $this->fetchTable('ContentsQuestion')->find()
				->where(['content_id' => $content_id, 'ContentsQuestion.id' => $question_id_list])
				->order('FIELD(ContentsQuestion.id,'.implode(',', $question_id_list).')')  // 指定したID順で並び替え
				->all();
			
			//debug($contentsQuestions);
		}
		else // 通常の出題の場合
		{
			// 全ての問題情報を取得（通常の処理）
			$contentsQuestions = $this->fetchTable('ContentsQuestion')->find()
				->where(['content_id' => $content_id])
				->order('ContentsQuestion.sort_no asc')
				->all();
		}
		
		//------------------------------//
		//	保存処理					//
		//------------------------------//
		if($this->request->is('post'))
		{
			$details	= [];								// 回答詳細情報
			
			//------------------------------//
			//	回答の詳細情報の作成		//
			//------------------------------//
			foreach($contentsQuestions as $contentsQuestion)
			{
				$question_id	= $contentsQuestion['ContentsQuestion']['id'];		// 問題ID
				$answer			= $this->getData('answer_' . $question_id);			// 解答
				$question_type  = $contentsQuestion['ContentsQuestion']['question_type'];

				// 回答内容
				$details[] = [
					'question_id'	=> $question_id,	// 問題ID
					'answer'		=> $answer,			// 解答
					'is_correct'	=> -1,				// 正誤
				];
			}
			
			// テスト実施時間
			$study_sec = $this->getData('ContentsQuestion')['study_sec'];
			
			$this->fetchTable('Record')->create();
			
			// 追加する回答情報
			$data = [
				'user_id'		=> $this->readAuthUser('id'),					// ログインユーザのユーザID
				'course_id'		=> $content['Course']['id'],					// コースID
				'content_id'	=> $content_id,									// コンテンツID
				'study_sec'		=> $study_sec,									// テスト実施時間
				'is_passed'		=> 2,
				'is_complete'	=> 1
			];
			
			//------------------------------//
			//	アンケート結果の保存		//
			//------------------------------//
			if($this->fetchTable('Record')->save($data))
			{
				$record_id = $this->fetchTable('Record')->getLastInsertID();
				
				// 問題単位の回答を保存
				foreach($details as $detail)
				{
					$this->fetchTable('RecordsQuestion')->create();
					$detail['record_id'] = $record_id;
					$this->fetchTable('RecordsQuestion')->save($detail);
				}
				
				// ランダム出題用の問題IDリストを削除
				$this->deleteSession('Iroha.RondomQuestions.'.$content_id.'.id_list');
				
				$this->Flash->success(__('回答内容を送信しました'));
				
				$this->redirect([
					'action' => 'record',
					$content_id,
					$this->fetchTable('Record')->getLastInsertID()
				]);
			}
		}
		
		$is_record = $this->isRecordPage();	// テスト結果表示フラグ
		$is_admin_record = $this->isAdminPage() && $this->isRecordPage();
		
		$this->set(compact('content', 'contentsQuestions', 'record', 'is_record', 'is_admin_record'));
	}

	/**
	 * テスト結果を表示
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID
	 */
	public function record($content_id, $record_id)
	{
		$this->index($content_id, $record_id);
		$this->render('index');
	}

	/**
	 * テスト結果を表示
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID
	 */
	public function admin_record($content_id, $record_id)
	{
		$this->record($content_id, $record_id);
	}

	/**
	 * 問題一覧を表示
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 */
	public function admin_index($content_id)
	{
		$content_id = intval($content_id);
		
		$this->fetchTable('ContentsQuestion')->recursive = 0;
		
		$contentsQuestions = $this->fetchTable('ContentsQuestion')->find()
			->where(['ContentsQuestion.content_id' => $content_id])
			->order('ContentsQuestion.sort_no asc')
			->all();
			
		// コンテンツ情報を取得
		$content = $this->fetchTable('Content')->get($content_id);
		
		$this->set(compact('content', 'contentsQuestions'));
	}

	/**
	 * 問題を追加
	 * @param int $content_id 追加対象のコンテンツ(テスト)のID
	 */
	public function admin_add($content_id)
	{
		$this->admin_edit($content_id);
		$this->render('admin_edit');
	}
		
	/**
	 * 問題を編集
	 * @param int $content_id 追加対象のコンテンツ(テスト)のID
	 * @param int $question_id 編集対象の問題のID
	 */
	public function admin_edit($content_id, $question_id = null)
	{
		$content_id = intval($content_id);
		
		unset($this->fetchTable('ContentsQuestion')->validate['option_list']);
		
		if($this->isEditPage() && !$this->fetchTable('ContentsQuestion')->exists($question_id))
		{
			throw new NotFoundException(__('Invalid contents question'));
		}

		// コンテンツ情報を取得
		$content = $this->fetchTable('Content')->get($content_id);
		
		if($this->request->is(['post', 'put']))
		{
			if($question_id == null)
			{
				$this->request->data['ContentsQuestion']['user_id'] = $this->readAuthUser('id');
				$this->request->data['ContentsQuestion']['content_id'] = $content_id;
				$this->request->data['ContentsQuestion']['sort_no']   = $this->fetchTable('ContentsQuestion')->getNextSortNo($content_id);
			}
			
			if(!$this->fetchTable('ContentsQuestion')->validates())
				return;
			
			if($this->fetchTable('ContentsQuestion')->save($this->request->data))
			{
				$this->Flash->success(__('質問が保存されました'));
				
				return $this->redirect([
					'controller' => 'enquetes_questions',
					'action' => 'index',
					$content_id
				]);
			}
			else
			{
				$this->Flash->error(__('The contents question could not be saved. Please, try again.'));
			}
		}
		else
		{
			$this->request->data = $this->fetchTable('ContentsQuestion')->get($question_id);
		}
		
		$this->set(compact('content'));
	}

	/**
	 * 問題を削除
	 * @param int $question_id 削除対象の問題のID
	 */
	public function admin_delete($question_id = null)
	{
		$this->fetchTable('ContentsQuestion')->id = $question_id;
		
		if(!$this->fetchTable('ContentsQuestion')->exists())
		{
			throw new NotFoundException(__('Invalid contents question'));
		}
		
		$this->request->allowMethod('post', 'delete');
		
		// 問題情報を取得
		$question = $this->fetchTable('ContentsQuestion')->get($question_id);
		
		if($this->fetchTable('ContentsQuestion')->delete())
		{
			$this->Flash->success(__('質問が削除されました'));
			
			return $this->redirect([
				'controller' => 'enquetes_questions',
				'action' => 'index',
				$question['ContentsQuestion']['content_id']
			]);
		}
		else
		{
			$this->Flash->error(__('The contents question could not be deleted. Please, try again.'));
		}
		return $this->redirect(['action' => 'index']);
	}

	/**
	 * Ajax によるコンテンツの並び替え
	 *
	 * @return string 実行結果
	 */
	public function admin_order()
	{
		$this->autoRender = FALSE;
		
		if($this->request->is('ajax'))
		{
			$this->fetchTable('ContentsQuestion')->setOrder($this->data['id_list']);
			return 'OK';
		}
	}
}
