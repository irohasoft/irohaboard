<?php
/**
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
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
	public $components = [
		'Paginator',
		'Security' => [
			'csrfUseOnce' => false,
			'csrfExpires' => '+3 hours',
			'csrfLimit' => 10000,
		],
	];

	/**
	 * お知らせ一覧を表示（受講者側）
	 */
	public function index()
	{
		// お知らせ一覧を取得
		$this->loadModel('Info');
		$this->paginate = $this->Info->getInfoOption($this->readAuthUser('id'));
		
		$infos = $this->paginate();
		
		$this->set(compact('infos'));
	}

	/**
	 * お知らせの内容を表示
	 * @param string $info_id 表示するお知らせのID
	 */
	public function view($info_id)
	{
		if(!$this->Info->exists($info_id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		
		// お知らせの閲覧権限の確認
		if(!$this->Info->hasRight($this->readAuthUser('id'), $info_id))
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
		$info = $this->Info->get($info_id);
		
		$this->set(compact('info'));
	}

	/**
	 * お知らせ一覧を表示
	 */
	public function admin_index()
	{
		$this->Info->virtualFields['group_title'] = 'InfoGroup.group_title'; // 外部結合テーブルのフィールドによるソート用
		
		$this->Paginator->settings = [
			'fields' => ['*', 'InfoGroup.group_title'],
			'limit' => 20,
			'order' => 'Info.created desc',
			'joins' => [
				['type' => 'LEFT OUTER', 'alias' => 'InfoGroup',
						'table' => '(SELECT ug.info_id, group_concat(g.title order by g.id SEPARATOR \', \') as group_title FROM ib_infos_groups ug INNER JOIN ib_groups g ON g.id = ug.group_id GROUP BY ug.info_id)',
						'conditions' => 'Info.id = InfoGroup.info_id'],
			]
		];
		
		$infos = $this->paginate();
		
		$this->set(compact('infos'));
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
		if($this->isEditPage() && !$this->Info->exists($info_id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			// 作成者を設定
			$this->request->data['Info']['user_id'] = $this->readAuthUser('id');
			
			if($this->Info->save($this->request->data))
			{
				$this->Flash->success(__('お知らせが保存されました'));
				return $this->redirect(['action' => 'index']);
			}
			else
			{
				$this->Flash->error(__('The info could not be saved. Please, try again.'));
			}
		}
		else
		{
			$this->request->data = $this->Info->get($info_id);
		}
		
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
		
		if(!$this->Info->exists())
		{
			throw new NotFoundException(__('Invalid info'));
		}
		
		$this->request->allowMethod('post', 'delete');
		
		if($this->Info->delete())
		{
			$this->Flash->success(__('お知らせが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The info could not be deleted. Please, try again.'));
		}
		
		return $this->redirect(['action' => 'index']);
	}
}
