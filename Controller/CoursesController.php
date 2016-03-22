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

class CoursesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
			'Paginator'
	);

	public function index()
	{
		$this->Course->recursive = 0;
		$this->set('courses', $this->Paginator->paginate());
	}

	public function admin_index()
	{
		$this->Course->recursive = 0;
		$this->paginate = array(
			'Course' => array(
				'fields' => array('Course.*', 'UserCourse.course_count'),
				'conditions' => array(),
				'limit' => 10,
				'order' => 'created desc',
				'joins' => array(
					array('type' => 'LEFT OUTER', 'alias' => 'UserCourse',
							'table' => '(SELECT course_id, COUNT(*) as course_count FROM ib_users_courses GROUP BY course_id)',
							'conditions' => 'Course.id = UserCourse.course_id')
				))
		);

		$result = $this->paginate();

		//debug($this->paginate);
		$this->set('courses', $result);
	}

	public function view($id = null)
	{
		if (! $this->Course->exists($id))
		{
			throw new NotFoundException(__('Invalid course'));
		}
		$options = array(
				'conditions' => array(
						'Course.' . $this->Course->primaryKey => $id
				)
		);
		$this->set('course', $this->Course->find('first', $options));
	}

	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	public function admin_edit($id = null)
	{
		if ($this->action == 'edit' && ! $this->Course->exists($id))
		{
			throw new NotFoundException(__('Invalid course'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->Course->save($this->request->data))
			{
				$this->Flash->success(__('コースが保存されました'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The course could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'Course.' . $this->Course->primaryKey => $id
					)
			);
			$this->request->data = $this->Course->find('first', $options);
		}
		$users = $this->Course->User->find('list');
	}

	public function admin_delete($id = null)
	{
		$this->Course->id = $id;
		if (! $this->Course->exists())
		{
			throw new NotFoundException(__('Invalid course'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Course->delete())
		{
			$this->Flash->success(__('コースが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The course could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
