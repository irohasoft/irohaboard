<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');
App::uses('Record', 'Record');

/**
 * ContentsQuestions Controller
 *
 * @property ContentsQuestion $ContentsQuestion
 * @property PaginatorComponent $Paginator
 */
class ContentsQuestionsController extends AppController
{

	public $components = array(
			'Paginator'
	);

	public function index($id, $record_id = null)
	{
		$this->ContentsQuestion->recursive = 0;
		$contentsQuestions = $this->ContentsQuestion->find('all', 
				array(
						'conditions' => array(
								'contentsQuestion.content_id' => $id
						)
				));
		
		// 過去の成績を取得
		if ($record_id)
		{
			$this->loadModel('Record');
			$record = $this->Record->find('first', 
					array(
							'conditions' => array(
									'Record.id' => $record_id
							)
					));
			
			$this->set('record', $record);
			// debug($record);
			// echo "get record";
		}
		
		// 採点処理
		if ($this->request->is('post'))
		{
			$details = array();
			$full_score = 0;
			$pass_score = 0;
			$my_score = 0;
			$pass_rate = 0;
			
			// 成績の詳細情報の作成
			$i = 0;
			foreach ($contentsQuestions as $contentsQuestion)
			{
				$question_id = $contentsQuestion['ContentsQuestion']['id'];
				$answer = @$this->request->data['answer_' . $question_id];
				$correct = $contentsQuestion['ContentsQuestion']['correct'];
				$is_correct = ($answer == $correct) ? 1 : 0;
				$score = $contentsQuestion['ContentsQuestion']['score'];
				
				$full_score += $score;
				$pass_rate = $contentsQuestion['Content']['pass_rate'];
				
				if ($is_correct == 1)
					$my_score += $score;
					
					// echo $answer.'<br>';
					// echo
					// $this->request->data['ContentsQuestion']['correct_'.$contentsQuestion['ContentsQuestion']['id']].'<br>';
					// echo $contentsQuestion['ContentsQuestion']['score'];
					// echo '<hr>';
				$details[$i] = array(
						'question_id' => $question_id,
						'answer' => $answer,
						'correct' => $correct,
						'is_correct' => $is_correct,
						'score' => $score
				);
				$i ++;
			}
			
			$pass_score = ($full_score * $pass_rate) / 100;
			
			$record = array(
					'full_score' => $full_score,
					'pass_score' => $pass_score,
					'score' => $my_score,
					'is_passed' => ($pass_score >= $my_score) ? 0 : 1,
					'study_sec' => 0
			);
			
			$this->loadModel('Record');
			$this->Record->create();
			
			// debug($this->Record);
			
			$data = array(
//					'group_id' => $this->Session->read('Auth.User.Group.id'),
					'user_id' => $this->Session->read('Auth.User.id'),
					'course_id' => $this->Session->read('Iroha.course_id'),
					'content_id' => $id,
					'full_score' => $record['full_score'],
					'pass_score' => $record['pass_score'],
					'score' => $record['score'],
					'is_passed' => $record['is_passed'],
					'study_sec' => $record['study_sec'],
					'is_complete' => 1
			);
			
			if ($this->Record->save($data))
			{
				$this->loadModel('RecordsQuestion');
				$record_id = $this->Record->getLastInsertID();
				
				foreach ($details as $detail)
				:
					$this->RecordsQuestion->create();
					$detail['record_id'] = $record_id;
					$this->RecordsQuestion->save($detail);
				endforeach
				;
				
				$this->redirect(
						array(
								'action' => 'record',
								$id,
								$this->Record->getLastInsertID()
						));
			}
		}
		
		$this->set('course_name', $this->Session->read('Iroha.course_name'));
		$this->set('contentsQuestions', $contentsQuestions);
	}

	public function record($id, $record_id)
	{
		$this->index($id, $record_id);
		$this->render('index');
	}

	public function admin_index($id)
	{
		$this->ContentsQuestion->recursive = 0;
		$contentsQuestions = $this->ContentsQuestion->find('all', 
				array(
						'conditions' => array(
								'contentsQuestion.content_id' => $id
						)
				));
		
		$this->Session->write('Iroha.content_id', $id);
		// $this->Session->write('Iroha.content_name',
		// $contentsQuestions['ContentsQuestion']['title']);
		
		$this->set('course_name', $this->Session->read('Iroha.course_name'));
		$this->set('contentsQuestions', $contentsQuestions);
	}

	public function view($id = null)
	{
		if (! $this->ContentsQuestion->exists($id))
		{
			throw new NotFoundException(__('Invalid contents question'));
		}
		$options = array(
				'conditions' => array(
						'ContentsQuestion.' . $this->ContentsQuestion->primaryKey => $id
				)
		);
		$this->set('contentsQuestion', $this->ContentsQuestion->find('first', $options));
	}

	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	public function admin_edit($id = null)
	{
		if ($this->action == 'edit' && ! $this->Post->exists($id))
		{
			throw new NotFoundException(__('Invalid contents question'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($id == null)
			{
				$this->request->data['ContentsQuestion']['user_id'] = $this->Session->read('Auth.User.id');
				$this->request->data['ContentsQuestion']['group_id'] = $this->Session->read('Iroha.group_id');
				$this->request->data['ContentsQuestion']['content_id'] = $this->Session->read('Iroha.content_id');
			}
			
			echo "test";
			if (! $this->ContentsQuestion->validates())
				return;
			
			if ($this->ContentsQuestion->save($this->request->data))
			{
				$this->Flash->success(__('The contents question has been saved.'));
				return $this->redirect(
						array(
								'controller' => 'contents_questions',
								'action' => 'index',
								$this->Session->read('Iroha.content_id')
						));
			}
			else
			{
				$this->Flash->error(__('The contents question could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'ContentsQuestion.' . $this->ContentsQuestion->primaryKey => $id
					)
			);
			$this->request->data = $this->ContentsQuestion->find('first', $options);
		}
		$groups = $this->ContentsQuestion->Group->find('list');
		// $courses = $this->ContentsQuestion->Course->find('list');
		$this->set(compact('groups', 'courses'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function admin_delete($id = null)
	{
		$this->ContentsQuestion->id = $id;
		if (! $this->ContentsQuestion->exists())
		{
			throw new NotFoundException(__('Invalid contents question'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ContentsQuestion->delete())
		{
			$this->Flash->success(__('The contents question has been deleted.'));
			return $this->redirect(
					array(
							'controller' => 'contents_questions',
							'action' => 'index',
							$this->Session->read('Iroha.content_id')
					));
		}
		else
		{
			$this->Flash->error(__('The contents question could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
