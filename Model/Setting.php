<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');
/**
 * Setting Model
 *
 */
class Setting extends AppModel
{
	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'setting_key'   => ['notBlank' => ['rule' => ['notBlank']]],
		'setting_value' => ['notBlank' => ['rule' => ['notBlank']]]
	];	

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
		$params = [
			'setting_key' => $setting_key
		];
		
		$data = $this->query($sql, $params);
		
		
		return $setting_value;
	}
	
	/**
	 * システム設定の値のリストを取得
	 * @return array 設定値リスト（連想配列）
	 */
	public function getSettings()
	{
		$result = [];
		
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
			$params = [
				'setting_key' => $key,
				'setting_value' => $value
			];
			
			$this->query("UPDATE ib_settings SET setting_value = :setting_value WHERE setting_key = :setting_key", $params);
		}
	}
}
