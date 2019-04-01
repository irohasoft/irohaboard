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
	public $components = array(
		'Security' => array(
			'csrfUseOnce' => false,
		),
	);

	/**
	 * システム設定項目を表示
	 */
	public function admin_index()
	{
		if ($this->request->is(array('post', 'put')))
		{
			if(Configure::read('demo_mode'))
				return;
			
			$this->Setting->setSettings($this->request->data['Setting']);
			
			foreach ($this->request->data['Setting'] as $key => $value)
			{
				$this->Session->Write('Setting.'.$key, $value);
			}
			
			$this->Flash->success(__('設定が保存されました'));
		}
		
		$this->Setting->recursive = 0;
		$this->set('settings',		$this->Setting->getSettings());
		$this->set('colors',		Configure::read('theme_colors'));
	}
}
