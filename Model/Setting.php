<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');
/**
 * Setting Model
 *
 */
class Setting extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'setting_key' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'setting_value' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	/**
	 * システム設定の値を取得
	 * @param int $setting_key 設定キー
	 * @return string 設定値
	 */
	public function getSettingValue($setting_key)
	{
		$setting_value = "";
		
		$sql = <<<EOF
SELECT setting_value
  FROM ib_settings
 WHERE setting_key = :setting_key
EOF;
		$params = array(
				'setting_key' => $setting_key
		);
		
		$data = $this->query($sql, $params);
		
		
		return $setting_value;
	}
	
	/**
	 * システム設定の値のリストを取得
	 * @return array 設定値リスト（連想配列）
	 */
	public function getSettings()
	{
		$result = array();
		
		$settings = $this->query("SELECT setting_key, setting_value FROM ib_settings");
		
		foreach ($settings as $setting)
		{
			$result[$setting['ib_settings']['setting_key']] = $setting['ib_settings']['setting_value'];
		}
		
		return $result;
	}
	
	/**
	 * システム設定を保存
	 * @param array 保存する設定値リスト（連想配列）
	 */
	public function setSettings($settings)
	{
		foreach ($settings as $key => $value)
		{
			$params = array(
				'setting_key' => $key,
				'setting_value' => $value
			);
			
			$this->query("UPDATE ib_settings SET setting_value = :setting_value WHERE setting_key = :setting_key", $params);
		}
	}
}
