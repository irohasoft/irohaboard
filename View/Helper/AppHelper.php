<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper
{
	/**
	 * 説明付きテキストボックス出力
	 * @param string $fieldName フィールド名
	 * @param array $options input 用のオプション
	 * @param string $exp 説明
	 * @return string 出力タグ
	 */
	public function inputExp($fieldName, $options = [], $exp)
	{
		$options['after'] = '<div class="col col-sm-3"></div><div class="col col-sm-9 status-exp">'.$exp.'</div>';
		
		return $this->input($fieldName, $options);
	}

	/**
	 * ラジオボタン出力
	 * @param string $fieldName フィールド名
	 * @param array $options input 用のオプション
	 * @param string $exp 説明
	 * @return string 出力タグ
	 */
	public function inputRadio($fieldName, $options = [], $exp = '')
	{
		$options['type'] = 'radio';
		$options['div'] = 'form-group required';
		$options['before'] = '<label class="col col-sm-3 control-label">'.$options['label'].'</label>';
		
		if($exp!='')
			$options['after'] = '<div class="col col-sm-3"></div><div class="col col-sm-9 col-exp status-exp">'.$exp.'</div>';
		
		$options['separator'] = (isset($options['separator'])) ? $options['separator'] : '　';
		
		$options['legend'] = false;
		$options['class'] = false;
		
		if(isset($options['default']))
			$options['default'] = $options['default'];
		
		return $this->input($fieldName, $options);
	}
}
