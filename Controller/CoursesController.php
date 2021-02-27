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

class CoursesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = [
		'Security' => [
			'csrfUseOnce' => false,
			'unlockedActions' => ['admin_order']
		],
	];

	/**
	 * コース一覧を表示
	 */
	public function admin_index()
	{
		// 不要なリレーションを解除
		$this->Course->recursive = 0;
		
		$courses = $this->Course->find()
			->order('Course.sort_no asc')
			->all();
		$this->set(compact('courses'));
	}

	/**
	 * コースの追加
	 */
	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	/**
	 * コースの編集
	 * @param int $course_id コースID
	 */
	public function admin_edit($course_id = null)
	{
		if($this->isEditPage() && !$this->Course->exists($course_id))
		{
			throw new NotFoundException(__('Invalid course'));
		}
		
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			// 作成者を設定
			$this->request->data['Course']['user_id'] = $this->readAuthUser('id');
			
			if($this->Course->save($this->request->data))
			{
				$this->Flash->success(__('コースが保存されました'));
				return $this->redirect(['action' => 'index']);
			}
			else
			{
				$this->Flash->error(__('The course could not be saved. Please, try again.'));
			}
		}
		else
		{
			$this->request->data = $this->Course->get($course_id);
		}
	}

	/**
	 * コースの削除
	 * @param int $course_id コースID
	 */
	public function admin_delete($course_id = null)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->Course->id = $course_id;
		if(!$this->Course->exists())
		{
			throw new NotFoundException(__('Invalid course'));
		}

		$this->request->allowMethod('post', 'delete');
		$this->Course->deleteCourse($course_id);
		$this->Flash->success(__('コースが削除されました'));

		return $this->redirect(['action' => 'index']);
	}

	/**
	 * Ajax によるコースの並び替え
	 *
	 * @return string 実行結果
	 */
	public function admin_order()
	{
		$this->autoRender = FALSE;
		if($this->request->is('ajax'))
		{
			$this->Course->setOrder($this->data['id_list']);
			return "OK";
		}
	}
}
