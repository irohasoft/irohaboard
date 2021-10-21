<?php
/**
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 */

App::uses('AppController', 'Controller');

/**
 * Settings Controller
 * https://book.cakephp.org/2/ja/controllers.html
 */
class SettingsController extends AppController
{
	/**
	 * 使用するコンポーネント
	 * https://book.cakephp.org/2/ja/core-libraries/toc-components.html
	 */
	public $components = [
		'Security' => [
			'csrfUseOnce' => false,
		],
	];

	/**
	 * システム設定項目を表示
	 */
	public function admin_index()
	{
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			$this->Setting->setSettings($this->getData('Setting'));
			
			foreach($this->getData('Setting') as $key => $value)
			{
				$this->writeSession('Setting.'.$key, $value);
			}
			
			$this->Flash->success(__('設定が保存されました'));
		}
		
		$this->Setting->recursive = 0;
		$settings = $this->Setting->getSettings();
		$colors = Configure::read('theme_colors');
		
		$this->set(compact('settings', 'colors'));
	}
}
