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

/**
 * UsersCourses Controller
 *
 * @property UsersCourse $UsersCourse
 * @property PaginatorComponent $Paginator
 */
class UsersCoursesController extends AppController
{

	public $components = array(
			'Paginator'
	);

	public function index()
	{
		// 全体のお知らせの取得
		App::import('Model', 'Setting');
		$this->Setting = new Setting();
		
		$info = $this->Setting->find('all',
				array(
						'conditions' => array(
								'Setting.setting_key' => 'information'
						)
				));
		
		$this->set('info', $info[0]);
		
		// 受講コース情報の取得
		$this->UsersCourse->recursive = 0;
		$usersCourses = $this->UsersCourse->find('all', 
				array(
						'conditions' => array(
								'UsersCourse.user_id ' => $this->Session->read('Auth.User.id')
						)
				));
		
		$data = $this->UsersCourse->getCourseRecord($this->Session->read('Auth.User.Group.id'), 
				$this->Session->read('Auth.User.id'));
		
		// debug($usersCourses);
		// debug($data);
		
		$this->set('usersCourses', $data);
	}

	public function view($id = null)
	{
		if (! $this->UsersCourse->exists($id))
		{
			throw new NotFoundException(__('Invalid users course'));
		}
		$options = array(
				'conditions' => array(
						'UsersCourse.' . $this->UsersCourse->primaryKey => $id
				)
		);
		$this->set('usersCourse', $this->UsersCourse->find('first', $options));
	}

	public function add()
	{
		if ($this->request->is('post'))
		{
			$this->UsersCourse->create();
			if ($this->UsersCourse->save($this->request->data))
			{
				$this->Flash->success(__('The users course has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The users course could not be saved. Please, try again.'));
			}
		}
		$users = $this->UsersCourse->User->find('list');
		$courses = $this->UsersCourse->Course->find('list');
		$this->set(compact('users', 'courses'));
	}

	public function edit($id = null)
	{
		if (! $this->UsersCourse->exists($id))
		{
			throw new NotFoundException(__('Invalid users course'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->UsersCourse->save($this->request->data))
			{
				$this->Flash->success(__('The users course has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The users course could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'UsersCourse.' . $this->UsersCourse->primaryKey => $id
					)
			);
			$this->request->data = $this->UsersCourse->find('first', $options);
		}
		$users = $this->UsersCourse->User->find('list');
		$courses = $this->UsersCourse->Course->find('list');
		$this->set(compact('users', 'courses'));
	}

	public function delete($id = null)
	{
		$this->UsersCourse->id = $id;
		if (! $this->UsersCourse->exists())
		{
			throw new NotFoundException(__('Invalid users course'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->UsersCourse->delete())
		{
			$this->Flash->success(__('The users course has been deleted.'));
		}
		else
		{
			$this->Flash->error(__('The users course could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
