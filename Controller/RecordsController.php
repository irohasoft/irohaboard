<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
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
	// 検索対象のフィルタ設定
	/*
	 * public $filterArgs = array( array('name' => 'name', 'type' => 'value',
	 * 'field' => 'User.name'), array('name' => 'username', 'type' => 'like',
	 * 'field' => 'User.username'), array('name' => 'title', 'type' => 'like',
	 * 'field' => 'Content.title') );
	 */
	public function admin_index()
	{
		// 検索条件設定
		$this->Prg->commonProcess();
		
		$conditions = $this->Record->parseCriteria($this->Prg->parsedParams());
		
		$group_id		= (isset($this->request->query['group_id'])) ? $this->request->query['group_id'] : "";
		$course_id		= (isset($this->request->query['course_id'])) ? $this->request->query['course_id'] : "";
		$user_id		= (isset($this->request->query['user_id'])) ? $this->request->query['user_id'] : "";
		$contenttitle	= (isset($this->request->query['contenttitle'])) ? $this->request->query['contenttitle'] : "";
		
		if($group_id != "")
			$conditions['User.id'] = $this->Group->getUserIdByGroupID($group_id);
		
		if($course_id != "")
			$conditions['Course.id'] = $course_id;
		
		if($user_id != "")
			$conditions['User.id'] = $user_id;
		
		$from_date	= (isset($this->request->query['from_date'])) ? 
			$this->request->query['from_date'] : 
				array(
					'year' => date('Y', strtotime("-1 month")),
					'month' => date('m', strtotime("-1 month")), 
					'day' => date('d', strtotime("-1 month"))
				);
		
		// debug($from_date);
		
		$to_date	= (isset($this->request->query['to_date'])) ? 
			$this->request->query['to_date'] : 
				array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));
		
		// debug($to_date);
		
		// 学習日付による絞り込み
		$conditions['Record.created BETWEEN ? AND ?'] = array(
			implode("/", $from_date), 
			implode("/", $to_date).' 23:59:59'
		);
		
		if($contenttitle != "")
			$conditions['Content.title like'] = '%'.$contenttitle.'%';
		
		$this->Paginator->settings['conditions'] = $conditions;
		$this->Paginator->settings['order']      = 'Record.created desc';
		$this->Record->recursive = 0;
		$this->set('records', $this->Paginator->paginate());
		
		//$groups = $this->Group->getGroupList();
		
		$this->Group = new Group();
		$this->Course = new Course();
		$this->User = new User();
		//debug($this->User);
		
		$this->set('groups',     $this->Group->find('list'));
		$this->set('courses',    $this->Course->find('list'));
		$this->set('users',      $this->User->find('list'));
		$this->set('group_id',   $group_id);
		$this->set('course_id',  $course_id);
		$this->set('user_id',    $user_id);
		$this->set('contenttitle', $contenttitle);
		$this->set('from_date', $from_date);
		$this->set('to_date', $to_date);
	}

	public function view($id = null)
	{
		if (! $this->Record->exists($id))
		{
			throw new NotFoundException(__('Invalid record'));
		}
		$options = array(
				'conditions' => array(
						'Record.' . $this->Record->primaryKey => $id
				)
		);
		$this->set('record', $this->Record->find('first', $options));
	}

	public function add($id, $is_complete, $study_sec, $understanding)
	{
		$this->Record->create();
		$data = array(
//				'group_id' => $this->Session->read('Auth.User.Group.id'),
				'user_id' => $this->Session->read('Auth.User.id'),
				'course_id' => $this->Session->read('Iroha.course_id'),
				'content_id' => $id,
				'study_sec' => $study_sec,
				'understanding' => $understanding,
				'is_passed' => -1,
				'is_complete' => $is_complete
		);
		
		if ($this->Record->save($data))
		{
			$this->Flash->success(__('学習履歴を保存しました'));
			return $this->redirect(
					array(
							'controller' => 'contents',
							'action' => 'index',
							$this->Session->read('Iroha.course_id')
					));
		}
		else
		{
			$this->Flash->error(__('The record could not be saved. Please, try again.'));
		}
	}

	public function record($id, $record, $details)
	{
		$this->Record->create();
		
		$data = array(
//				'group_id' => $this->Session->read('Auth.User.Group.id'),
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
			$this->RecordsQuestion = new RecordsQuestion();
			
			foreach ($details as $detail)
			:
				$this->RecordsQuestion->create();
				$detail['record_id'] = $this->Record->getLastInsertID();
				$this->RecordsQuestion->save($detail);
			endforeach
			;
		}
	}

	public function edit($id = null)
	{
		if (! $this->Record->exists($id))
		{
			throw new NotFoundException(__('Invalid record'));
		}
		
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->Record->save($this->request->data))
			{
				$this->Flash->success(__('The record has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The record could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'Record.' . $this->Record->primaryKey => $id
					)
			);
			$this->request->data = $this->Record->find('first', $options);
		}
		
		$groups = $this->Record->Group->find('list');
		$courses = $this->Record->Course->find('list');
		$users = $this->Record->User->find('list');
		$contents = $this->Record->Content->find('list');
		$this->set(compact('groups', 'courses', 'users', 'contents'));
	}

	public function admin_delete($id = null)
	{
		$this->Record->id = $id;
		
		if (! $this->Record->exists())
		{
			throw new NotFoundException(__('Invalid record'));
		}
		
		$this->request->allowMethod('post', 'delete');
		
		if ($this->Record->delete())
		{
			$this->Flash->success(__('The record has been deleted.'));
		}
		else
		{
			$this->Flash->error(__('The record could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
