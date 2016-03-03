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
 * Settings Controller
 *
 * @property Setting $Setting
 * @property PaginatorComponent $Paginator
 */
class SettingsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function admin_index()
	{
		if ($this->request->is(array('post', 'put')))
		{
			//debug($this->request->data);
			
			$this->Setting->setSettings($this->request->data);
			
			foreach ($this->request->data as $key => $value)
			{
				$this->Session->Write('Setting.'.$key, $value);
			}
		}
		
		$this->Setting->recursive = 0;
		$this->set('settings', $this->Paginator->paginate());
	}
}
