<?php
/**
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package app.Model
 */
class AppModel extends Model
{
	private $options = []; // メソッドチェーン を使用した場合に find() で使用するパラメータ
	private $is_object = false; // 連想配列をオブジェクトに変換するかどうか（実験的実装）
	
	/**
	 * 英数字チェック（マルチバイト対応）
	 * 
	 * @param array $check チェック対象
	 * @return bool OK:true, NG:false
	 */
	public function alphaNumericMB($check)
	{
		$value = array_values($check);
		$value = $value[0];
		
		return preg_match('/^[a-zA-Z0-9]+$/', $value);
	}

	/**
	 * findById() と同様の仕様
	 */
	public function get($id)
	{
		return $this->findById($id);
	}

	/**
	 * 既存の find() メソッドを上書き
	 * 
	 * @param string $type 取得形式（指定した場合は、通常の動きとなる。省略した場合はメソッドチェーンを利用し、最後に all(), fisrt() をつけてデータを取得）
	 * @param array $options 各種条件（$type を指定した場合のみ指定可能）
	 * @return array type を指定した場合は取得結果、省略した場合はメソッドチェーン用にインスタンスを返す
	 */
	public function find($type = null, $options = [])
	{
		if($type == null)
		{
			$this->options = [];
			return $this;
		}
		
		return parent::find($type, $options);
	}

	/**
	 * フィールドを指定
	 */
	public function select($value)
	{
		$this->options['fields'] = $value;
		return $this;
	}

	/**
	 * where 句を指定
	 */
	public function where($value)
	{
		$this->options['conditions'] = $value;
		return $this;
	}

	/**
	 * ソート順を指定（文字列もしくは配列で指定）
	 */
	public function order($value)
	{
		$this->options['order'] = $value;
		return $this;
	}

	/**
	 * group by を指定
	 */
	public function group($value)
	{
		$this->options['group'] = $value;
		return $this;
	}

	/**
	 * データの取得数を指定
	 */
	public function limit($value)
	{
		$this->options['limit'] = $value;
		return $this;
	}

	/**
	 * ページ番号を指定
	 */
	public function page($value)
	{
		$this->options['page'] = $value;
		return $this;
	}

	/**
	 * find('all')の結果を返す
	 */
	public function all()
	{
		if($this->is_object)
		{
			$data = parent::find('all', $this->options);
			
			// 連想配列[Model][field] を [field] に変更
			foreach($data as &$row)
			{
				$row = array_merge($row, $row[$this->name]);
				unset($row[$this->name]);
			}
			
			// [Model][field] を 削除
			$object= new stdClass();
			$data = $this->_arrayToObject($data, $object);
			
			return $data;
		}
		
		return parent::find('all', $this->options);
	}

	/**
	 * find('first')の結果を返す
	 */
	public function first()
	{
		if($this->is_object)
		{
			$data = parent::find('first', $this->options);
			
			// 連想配列[Model][field] を [field] に変更
			$data = array_merge($data, $data[$this->name]);
			
			// [Model][field] を 削除
			unset($data[$this->name]);
			
			$object= new stdClass();
			$data = $this->_arrayToObject($data, $object);
			
			return $data;
		}
		
		return parent::find('first', $this->options);
	}

	/**
	 * find('list')の結果を返す
	 */
	public function toList()
	{
		return parent::find('list', $this->options);
	}

	/**
	 * find('count')の結果を返す
	 */
	public function count()
	{
		return parent::find('count', $this->options);
	}

	/**
	 * クエリの結果を配列で返す
	 * 
	 * @param string $sql SQL
	 * @param array $params SQL用パラメータ
	 * @param string $table_name テーブル名
	 * @param string $field_name フィールド名
	 * @return array クエリの結果
	 */
	public function queryList($sql, $params, $table_name, $field_name)
	{
		$data = $this->query($sql, $params);
		
		$list = [];
		
		for($i=0; $i< count($data); $i++)
		{
			$list[$i] = $data[$i][$table_name][$field_name];
		}
		
		return $list;
	}


	/**
	 * 取得形式をオブジェクトに指定
	 */
	public function convert()
	{
		$this->is_object = true;
		return $this;
	}
	
	/**
	 * 配列をオブジェクトに変換
	 */
	private function _arrayToObject($array, &$obj)
	{
		foreach($array as $key => $value)
		{
			if(is_array($value))
			{
				// データが複数かつ連想配列の場合、オブジェクト名の最後に sをつける
				if(is_string($key))
					$key .= 's';
				
				$obj->{strtolower($key)} = new stdClass();
				$this->_arrayToObject($value, $obj->{strtolower($key)});
			}
			else
			{
				$obj->{strtolower($key)} = $value;
			}
		}
		return $obj;
	}
}
