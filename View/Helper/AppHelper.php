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
		
		$options['div'] = (isset($options['div'])) ? $options['div'] : 'form-group required';
		$options['before'] = (isset($options['before'])) ? $options['before'] : '<label class="col col-sm-3 control-label">'.$options['label'].'</label>';
		$options['separator'] = (isset($options['separator'])) ? $options['separator'] : '　';
		$options['legend'] = (isset($options['legend'])) ? $options['legend'] : false;
		$options['class'] = (isset($options['class'])) ? $options['class'] : false;
		$options['default'] = (isset($options['default'])) ? $options['default'] : null;
		
		if($exp != '')
			$options['after'] = '<div class="col col-sm-3">&nbsp;</div><div class="col col-sm-9 col-exp status-exp">'.$exp.'</div>';
		
		
		return $this->input($fieldName, $options);
	}

	/**
	 * 検索フィールド
	 * @param string $fieldName フィールド名
	 * @param array $additional_options 追加オプション
	 * @return string 出力タグ
	 */
	public function searchField($fieldName, $additional_options = [])
	{
		// デフォルトオプション
		$options = [
			'class'=>'form-control',
			'required' => false
		];

		// 追加オプション
		foreach($additional_options as $key => $value)
		{
			$options[$key] = $value;
		}
		
		if(isset($options['label']))
			$options['label'] .= ' :';
		
		return $this->input($fieldName, $options);
	}

	/**
	 * 日付指定リストボックスの出力
	 * @param string $fieldName フィールド名
	 * @param array $additional_options 追加オプション
	 * @return string 出力タグ
	 */
	public function inputDate($fieldName, $additional_options = [])
	{
		// デフォルトオプション
		$options = [
			'type' => 'date',
			'dateFormat' => 'YMD',
			'monthNames' => false,
			'timeFormat' => '24',
			'minYear' => date('Y') - 5,
			'maxYear' => date('Y') + 5,
			'separator' => ' / ',
			'class'=>'form-control',
			'style' => 'width:initial; display: inline;',
		];
		
		// 追加オプション
		foreach($additional_options as $key => $value)
		{
			$options[$key] = $value;
		}
		
		if(isset($options['label']))
		{
			if($options['label'] != '～')
				$options['label'] .=  ' :';
		}
		
		return $this->input($fieldName, $options);
	}

	/**
	 * 検索用日付指定リストボックスの出力
	 * @param string $fieldName フィールド名
	 * @param array $additional_options 追加オプション
	 * @return string 出力タグ
	 */
	public function searchDate($fieldName, $additional_options = [])
	{
		// デフォルトオプション
		$options = [
			'type' => 'date',
			'dateFormat' => 'YMD',
			'monthNames' => false,
			'timeFormat' => '24',
			'minYear' => date('Y') - 5,
			'maxYear' => date('Y'),
			'separator' => ' / ',
			'class'=>'form-control',
			'style' => 'width:initial; display: inline;',
		];
		
		// 追加オプション
		foreach($additional_options as $key => $value)
		{
			$options[$key] = $value;
		}
		
		if(isset($options['label']))
		{
			if($options['label'] != '～')
				$options['label'] .=  ' :';
		}
		
		return $this->input($fieldName, $options);
	}

	/**
	 * 説明用ブロックの出力
	 * @param string $label ラベル
	 * @param string $content 内容
	 * @param bool $is_bold 太字
	 * @param string $block_class クラス
	 * @return string 出力タグ
	 */
	public static function block($label, $content, $is_bold = false, $block_class = '')
	{
		$content = $is_bold ? '<h5>'.$content.'</h5>' : $content;
		
		$tag = 
			'<div class="form-group '.$block_class.'">'.
			'  <label for="UserRegistNo" class="col col-sm-3 control-label">'.$label.'</label>'.
			'  <div class="col col-sm-9">'.$content.'</div>'.
			'</div>';
		
		return $tag;
	}

/*
	private function getHyphenName($str)
	{
		$str = str_replace('_', '-', $str);
		return $str;
	}
*/
}
