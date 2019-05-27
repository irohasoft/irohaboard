<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * Infos Controller
 *
 * @property Info $Info
 * @property PaginatorComponent $Paginator
 */
class InfosController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Paginator',
		'Security' => array(
			'csrfUseOnce' => false,
		),
	);

	/**
	 * お知らせ一覧を表示（受講者側）
	 */
	public function index()
	{
		// お知らせ一覧を取得
		$this->loadModel('Info');
		$this->paginate = $this->Info->getInfoOption($this->Auth->user('id'));
		
		$infos = $this->paginate();
		
		$this->set('infos', $infos);
	}

	/**
	 * お知らせの内容を表示
	 * @param string $info_id 表示するお知らせのID
	 */
	public function view($info_id = null)
	{
		if (! $this->Info->exists($info_id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		$options = array(
			'conditions' => array(
				'Info.' . $this->Info->primaryKey => $info_id
			)
		);
		$this->set('info', $this->Info->find('first', $options));
	}

	/**
	 * お知らせ一覧を表示
	 */
	public function admin_index()
	{
		$this->Info->virtualFields['group_title'] = 'InfoGroup.group_title'; // 外部結合テーブルのフィールドによるソート用
		
		$this->Paginator->settings = array(
			'fields' => array('*', 'InfoGroup.group_title'),
			'limit' => 20,
			'order' => 'Info.created desc',
			'joins' => array(
				array('type' => 'LEFT OUTER', 'alias' => 'InfoGroup',
						'table' => '(SELECT ug.info_id, group_concat(g.title order by g.id SEPARATOR \', \') as group_title FROM ib_infos_groups ug INNER JOIN ib_groups g ON g.id = ug.group_id GROUP BY ug.info_id)',
						'conditions' => 'Info.id = InfoGroup.info_id'),
			)
		);
		
		$result = $this->paginate();
		
		$this->set('infos', $result);
	}

	/**
	 * お知らせの追加
	 */
	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	/**
	 * お知らせの編集
	 * @param int $info_id 編集するお知らせのID
	 */
	public function admin_edit($info_id = null)
	{
		if ($this->action == 'admin_edit' && ! $this->Info->exists($info_id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if(Configure::read('demo_mode'))
				return;
			
			// 作成者を設定
			$this->request->data['Info']['user_id'] = $this->Auth->user('id');
			
			if ($this->Info->save($this->request->data))
			{
				$this->Flash->success(__('お知らせが保存されました'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The info could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
				'conditions' => array(
					'Info.' . $this->Info->primaryKey => $info_id
				)
			);
			$this->request->data = $this->Info->find('first', $options);
		}
		//$users = $this->Info->User->find('list');

		$this->Group = new Group();
		
		$groups = $this->Group->find('list');
		$this->set(compact('groups'));
	}

	/**
	 * お知らせを削除
	 * @param int $info_id 削除するお知らせのID
	 */
	public function admin_delete($info_id = null)
	{
		$this->Info->id = $info_id;
		if (! $this->Info->exists())
		{
			throw new NotFoundException(__('Invalid info'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Info->delete())
		{
			$this->Flash->success(__('お知らせが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The info could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
