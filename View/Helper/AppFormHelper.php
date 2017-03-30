<?php
 App::uses('FormHelper', 'View/Helper');
  
 class AppFormHelper extends FormHelper
 {
	 /**
	  * ここに記述したキーが $options にあった場合、
	  * コールバックを実行
	  *
	  * @var array
	  */
	 public $app_tags = array(
		 'help', 'helpText', 'helpList', 'example'
	 );
  
	 /**
	  * $options に self::$app_tags で定義されたキーがあった場合、
	  * self::$key()を実行する
	  *
	  * @param string $fieldName
	  * @param array $options
	  * @return string
	  * @throws Exception
	  */
	 public function input($fieldName, $options = array())
	 {
		 foreach ($options as $key => $value) {
			 if (in_array($key, $this->app_tags)) {
				 if (method_exists($this, $key)) {
					 $options = call_user_func(array($this, $key), $options);
				 } else {
					 throw new Exception(sprintf('AppFormHelper::%s()がありません。', $key));
				 }
			 }
		 }
		 
  
		 return parent::input($fieldName, $options);
	 }

	public function bs_input($fieldName, $options = array())
	{
		$options['label'] = false;
		
		return parent::input($fieldName, $options);
	}
	

	 /**
	  * ヘルプの出力
	  *
	  * $options['help'] が 配列の場合 self::helpList()
	  * それ以外の場合 self::helpText() を実行する
	  *
	  * @param array $options
	  * @return array
	  */
	 protected function help(Array $options)
	 {
		 if (is_array($options['help'])) {
			 $options['helpList'] = $options['help'];
			 $options = $this->helpList($options);
		 } else {
			 $options['helpText'] = $options['help'];
			 $options = $this->helpText($options);
		 }
		 unset($options['help']);
  
		 return $options;
	 }
	 /**
	  * 入力についての注意事項
	  *
	  * $options['helpText']の内容を pタグでラップして
	  * after に入れ替える
	  *
	  * @param array $options
	  * @return array
	  */
	 protected function helpText(Array $options)
	 {
		 $helptext = String::insert('<p class="help-text">:helptext</p>', array('helptext' => $options['helpText']));
		 if (array_key_exists('after', $options)) {
			 $options['after'] .= $helptext;
		 } else {
			 $options['after'] = $helptext;
		 }
		 unset($options['helpText']);
  
		 return $options;
	 }
  
	 /**
	  * 入力についての注意事項 複数
	  *
	  * $options['helpText']の内容を ulタグでラップして
	  * after に入れ替える
	  *
	  * @param array $options
	  * @return array
	  */
	 protected function helpList(Array $options)
	 {
  
		 $ul = $this->Html->nestedList($options['helpList'], array('class' => 'help-list'));
  
		 if (array_key_exists('after', $options)) {
			 $options['after'] .= $ul;
		 } else {
			 $options['after'] = $ul;
		 }
		 unset($options['helpList']);
  
		 return $options;
	 }
  
	 /**
	  * 入力例を追加
	  *
	  * $options['example']の内容を pタグでラップして
	  * after に入れ替える。inputタグの直後に表示される。
	  *
	  * @param array $options
	  * @return array
	  */
	 protected function example(Array $options)
	 {
		 $text = String::insert('<p class="example">入力例) :text</p>', array('text' => $options['example']));
		 if (array_key_exists('after', $options)) {
			 // 入力例はinputの直後に表示
			 $options['after'] = $text . $options['after'];
		 } else {
			 $options['after'] = $text;
		 }
		 unset($options['example']);
  
		 return $options;
	 }
 }
 ?>