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
 *
 * @property ContentsQuestion $ContentsQuestion
 * @property PaginatorComponent $Paginator
 */
class ContentsQuestionsController extends AppController
{
	public $components = [
		'Security' => [
			'validatePost' => false,
			'csrfUseOnce' => false,
			//'csrfCheck' => false,
			'csrfExpires' => '+3 hours',
			'csrfLimit' => 10000,
			'unlockedActions' => ['admin_order', 'index']
		],
	];

	/**
	 * 問題を出題
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID (テスト結果表示の場合、指定)
	 */
	public function index($content_id, $record_id = null)
	{
		$this->ContentsQuestion->recursive = 0;
		
		//------------------------------//
		//	コンテンツ情報を取得		//
		//------------------------------//
		$this->loadModel('Content');
		$content = $this->Content->get($content_id);
		
		//------------------------------//
		//	権限チェック				//
		//------------------------------//
		// 管理者以外の場合、コンテンツの閲覧権限の確認
		if(!$this->isAdminPage())
		{
			$this->loadModel('Course');
			
			if(!$this->Course->hasRight($this->readAuthUser('id'), $content['Content']['course_id']))
				throw new NotFoundException(__('Invalid access'));
		}
		
		//------------------------------//
		//	問題情報を取得				//
		//------------------------------//
		$record = null;
		
		if($record_id != null) // テスト結果表示モードの場合
		{
			// テスト結果情報を取得
			$this->loadModel('Record');
			$record = $this->Record->get($record_id);
			
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
			$contentsQuestions = $this->ContentsQuestion->find()
				->where(['content_id' => $content_id, 'ContentsQuestion.id' => $question_id_list])
				->order('FIELD(ContentsQuestion.id,'.implode(',', $question_id_list).')')  // 指定したID順で並び替え
				->all();
			
			//debug($contentsQuestions);
		}
		else if($this->readSession('Iroha.RondomQuestions.'.$content_id.'.id_list') != null) // 既にランダム出題情報がセッション上にある場合
		{
			// セッションにランダム出題情報が存在する場合、その情報を使用
			$question_id_list = $this->readSession('Iroha.RondomQuestions.'.$content_id.'.id_list');
			
			$contentsQuestions = $this->ContentsQuestion->find()
				->where(['content_id' => $content_id, 'ContentsQuestion.id' => $question_id_list])
				->order('FIELD(ContentsQuestion.id,'.implode(',', $question_id_list).')') // 指定したID順で並び替え
				->all();
		}
		else if($content['Content']['question_count'] > 0) // ランダム出題の場合
		{
			// ランダム出題情報を取得
			$contentsQuestions = $this->ContentsQuestion->find()
				->where(['content_id' => $content_id])
				->limit($content['Content']['question_count']) // 出題数
				->order('rand()') // 乱数で並び替え
				->all();
			
			// 問題IDの一覧を作成
			$question_id_list = [];
			
			foreach($contentsQuestions as $contentsQuestion)
			{
				$question_id_list[] = $contentsQuestion['ContentsQuestion']['id'];
			}
			
			// ランダム出題情報を一時的にセッションに格納（リロードによる変化や、採点時の問題情報との矛盾を防ぐため）
			$this->writeSession('Iroha.RondomQuestions.'.$content_id.'.id_list', $question_id_list);
		}
		else // 通常の出題の場合
		{
			// 全ての問題情報を取得（通常の処理）
			$contentsQuestions = $this->ContentsQuestion->find()
				->where(['content_id' => $content_id])
				->order('ContentsQuestion.sort_no asc')
				->all();
		}
		
		//------------------------------//
		//	採点処理					//
		//------------------------------//
		if($this->request->is('post'))
		{
			$details	= [];								// 成績詳細情報
			$full_score	= 0;									// 最高点
			$pass_score	= 0;									// 合格基準点
			$my_score	= 0;									// 得点
			$pass_rate	= $content['Content']['pass_rate'];		// 合格得点率
			
			//------------------------------//
			//	成績の詳細情報の作成		//
			//------------------------------//
			$i = 0;
			foreach($contentsQuestions as $contentsQuestion)
			{
				$question_id	= $contentsQuestion['ContentsQuestion']['id'];		// 問題ID
				$answer			= $this->getData('answer_' . $question_id);			// 解答
				$correct		= $contentsQuestion['ContentsQuestion']['correct'];	// 正解
				$corrects		= explode(',', $correct);							// 複数選択
				
				$is_correct		= ($answer == $correct) ? 1 : 0;					// 正誤判定
				$score			= $contentsQuestion['ContentsQuestion']['score'];	// 配点
				$full_score += $score;												// 合計点（配点の合計）
				
				// 複数選択問題の場合
				if(count($corrects) > 1)
				{
					$answers	= $this->getData('answer_'.$question_id);
					$answer		= @implode(',', $answers);
					$is_correct	= $this->isMultiCorrect($answers, $corrects) ? 1 : 0;
					//debug($is_correct);
				}
				else
				{
					$answer		= $this->getData('answer_'.$question_id);
					$is_correct	= ($answer == $correct) ? 1 : 0;
				}
				
				if($is_correct == 1)
					$my_score += $score;
				
				// 問題の正誤
				$details[$i] = [
					'question_id'	=> $question_id,	// 問題ID
					'answer'		=> $answer,			// 解答
					'correct'		=> $correct,		// 正解
					'is_correct'	=> $is_correct,		// 正誤
					'score'			=> $score,			// 配点
				];
				$i++;
			}
			
			// 合格基準得点
			$pass_score = ($full_score * $pass_rate) / 100;
			
			// 合格基準得点を超えていた場合、合格とする
			$is_passed = ($my_score >= $pass_score) ? 1 : 0;
			
			// テスト実施時間
			$study_sec = $this->getData('ContentsQuestion')['study_sec'];
			
			$this->loadModel('Record');
			$this->Record->create();
			
			// 追加する成績情報
			$data = [
				'user_id'		=> $this->readAuthUser('id'),					// ログインユーザのユーザID
				'course_id'		=> $content['Course']['id'],					// コースID
				'content_id'	=> $content_id,									// コンテンツID
				'full_score'	=> $full_score,									// 合計点
				'pass_score'	=> $pass_score,									// 合格基準得点
				'score'			=> $my_score,									// 得点
				'is_passed'		=> $is_passed,									// 合否判定
				'study_sec'		=> $study_sec,									// テスト実施時間
				'is_complete'	=> 1
			];
			
			//------------------------------//
			//	テスト結果の保存			//
			//------------------------------//
			if($this->Record->save($data))
			{
				$this->loadModel('RecordsQuestion');
				$record_id = $this->Record->getLastInsertID();
				
				// 問題単位の成績を保存
				foreach($details as $detail)
				{
					$this->RecordsQuestion->create();
					$detail['record_id'] = $record_id;
					$this->RecordsQuestion->save($detail);
				}
				
				// ランダム出題用の問題IDリストを削除
				$this->deleteSession('Iroha.RondomQuestions.'.$content_id.'.id_list');
				
				$this->redirect([
					'action' => 'record',
					$content_id,
					$this->Record->getLastInsertID()
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
		
		$this->ContentsQuestion->recursive = 0;
		$contentsQuestions = $this->ContentsQuestion->find()
			->where(['ContentsQuestion.content_id' => $content_id])
			->order('ContentsQuestion.sort_no asc')
			->all();
		
		// コンテンツ情報を取得
		$this->loadModel('Content');
		
		$content = $this->Content->get($content_id);
		
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
		
		if($this->isEditPage() && !$this->ContentsQuestion->exists($question_id))
		{
			throw new NotFoundException(__('Invalid contents question'));
		}

		// コンテンツ情報を取得
		$this->loadModel('Content');
		
		$content = $this->Content->get($content_id);
		
		if($this->request->is(['post', 'put']))
		{
			if($question_id == null)
			{
				$this->request->data['ContentsQuestion']['user_id'] = $this->readAuthUser('id');
				$this->request->data['ContentsQuestion']['content_id'] = $content_id;
				$this->request->data['ContentsQuestion']['sort_no']   = $this->ContentsQuestion->getNextSortNo($content_id);
			}
			
			if(!$this->ContentsQuestion->validates())
				return;
			
			if($this->ContentsQuestion->save($this->request->data))
			{
				$this->Flash->success(__('問題が保存されました'));
				return $this->redirect([
					'controller' => 'contents_questions',
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
			$this->request->data = $this->ContentsQuestion->get($question_id);
		}
		
		$this->set(compact('content'));
	}

	/**
	 * 問題を削除
	 * @param int $question_id 削除対象の問題のID
	 */
	public function admin_delete($question_id = null)
	{
		$this->ContentsQuestion->id = $question_id;
		
		if(!$this->ContentsQuestion->exists())
		{
			throw new NotFoundException(__('Invalid contents question'));
		}
		
		$this->request->allowMethod('post', 'delete');
		
		// 問題情報を取得
		$question = $this->ContentsQuestion->get($question_id);
		
		if($this->ContentsQuestion->delete())
		{
			$this->Flash->success(__('問題が削除されました'));
			return $this->redirect([
				'controller' => 'contents_questions',
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
			$this->ContentsQuestion->setOrder($this->data['id_list']);
			return 'OK';
		}
	}

	// 複数選択問題の正誤判定
	private function isMultiCorrect($answers, $corrects)
	{
		// 解答数と正解数が一致しない場合、不合格
		if(count($answers) != count($corrects))
			return false;
		
		// 解答が正解に含まれるか確認
		for($i =0; $i < count($answers); $i++)
		{
			if(!in_array($answers[$i], $corrects))
				return false;
		}
		
		// 全て含まれていれば正解
		return true;
	}
}
