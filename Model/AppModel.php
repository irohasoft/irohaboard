<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
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
	private $result = null;
	private $type = 'first';
	private $query = [];
	
	/**
	 * 英数字チェック（マルチバイト対応）
	 */
	public function alphaNumericMB($check)
	{
		$value = array_values($check);
		$value = $value[0];
		
		return preg_match('/^[a-zA-Z0-9]+$/', $value);
	}

	public function get($id)
	{
		return $this->findById($id);
	}

	public function find($type = null, $query = [])
	{
		if($type == null)
		{
			$this->type = $type;
			$this->query = $query;
			
			return $this;
		}
		
		return parent::find($type, $query);
	}

	public function select($param)
	{
		$this->query['fields'] = $param;
		
		return $this;
	}

	public function where($param)
	{
		$this->query['conditions'] = $param;
		
		return $this;
	}

	public function order($param)
	{
		$this->query['order'] = $param;
		
		return $this;
	}

	public function group($param)
	{
		$this->query['group'] = $param;
		
		return $this;
	}

	public function limit($param)
	{
		$this->query['limit'] = $param;
		
		return $this;
	}

	public function page($param)
	{
		$this->query['page'] = $param;
		
		return $this;
	}

	public function all()
	{
		return parent::find('all', $this->query);
	}

	public function first()
	{
		return parent::find('first', $this->query);
	}

	public function count()
	{
		return parent::find('count', $this->query);
	}
}
