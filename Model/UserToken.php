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
App::uses('ConnectionManager', 'Model');

/**
 * UserToken Model
 *
 * Remember Me 用トークン（selector:validator）
 * /update 前（ib_user_tokens 未作成）でも例外を出さない。
 */
class UserToken extends AppModel
{
	/**
	 * ib_user_tokens が利用可能か（リクエスト内キャッシュ）
	 *
	 * @var bool|null
	 */
	protected $_tableReady = null;

	/**
	 * トークンテーブルが存在するか
	 *
	 * Model::getDataSource() は未作成テーブルで MissingTableException を投げるため、
	 * ConnectionManager 経由で存在確認する。
	 *
	 * @return bool
	 */
	public function isAvailable()
	{
		if($this->_tableReady !== null)
			return $this->_tableReady;

		try
		{
			$db = ConnectionManager::getDataSource($this->useDbConfig);
			$prefix = !empty($db->config['prefix']) ? $db->config['prefix'] : '';
			$table = $prefix . 'user_tokens';
			$sources = $db->listSources();

			$this->_tableReady = false;
			if(is_array($sources))
			{
				foreach($sources as $source)
				{
					if(strtolower($source) === strtolower($table))
					{
						$this->_tableReady = true;
						break;
					}
				}
			}
		}
		catch(Exception $e)
		{
			$this->_tableReady = false;
		}

		return $this->_tableReady;
	}

	/**
	 * テーブル未作成時は setSource をスキップし MissingTableException を防ぐ
	 *
	 * @return DataSource
	 */
	public function getDataSource()
	{
		if(!$this->isAvailable())
			return ConnectionManager::getDataSource($this->useDbConfig);

		return parent::getDataSource();
	}

	/**
	 * Cookie 文字列を分解する
	 *
	 * @param string $cookieValue selector:validator
	 * @return array|null {selector, validator}
	 */
	public function parseCookie($cookieValue)
	{
		if(!is_string($cookieValue) || $cookieValue === '')
			return null;

		$parts = explode(':', $cookieValue, 2);
		if(count($parts) !== 2)
			return null;

		list($selector, $validator) = $parts;

		if(!preg_match('/^[a-f0-9]{32}$/i', $selector))
			return null;

		if(!preg_match('/^[a-f0-9]{64}$/i', $validator))
			return null;

		return [
			'selector' => $selector,
			'validator' => $validator,
		];
	}

	/**
	 * Remember Me トークンを発行し、Cookie 用文字列を返す
	 *
	 * @param int $userId
	 * @return string|null selector:validator（保存失敗・テーブル未作成時は null）
	 */
	public function issueRememberToken($userId)
	{
		if(!$this->isAvailable())
			return null;

		$userId = (int)$userId;
		if($userId <= 0)
			return null;

		$days = (int)Configure::read('remember_token_expired_days');
		if($days <= 0)
			$days = 14;

		$selector = bin2hex(random_bytes(16));
		$validator = bin2hex(random_bytes(32));

		$data = [
			'UserToken' => [
				'user_id' => $userId,
				'token_type' => 'remember',
				'token_selector' => $selector,
				'token_hash' => password_hash($validator, PASSWORD_DEFAULT),
				'expired' => date('Y-m-d H:i:s', strtotime('+' . $days . ' days')),
				'last_used' => date('Y-m-d H:i:s'),
				'revoked' => null,
				'user_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
				'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
			]
		];

		try
		{
			$this->create();
			if(!$this->save($data))
				return null;
		}
		catch(Exception $e)
		{
			$this->_tableReady = false;
			return null;
		}

		return $selector . ':' . $validator;
	}

	/**
	 * Cookie からユーザを認証する
	 *
	 * @param string $cookieValue
	 * @return array|null User 配列（Auth->login 用）。失敗時 null
	 */
	public function authenticateRememberCookie($cookieValue)
	{
		if(!$this->isAvailable())
			return null;

		$parsed = $this->parseCookie($cookieValue);
		if($parsed === null)
			return null;

		try
		{
			$token = $this->find('first', [
				'conditions' => [
					'UserToken.token_type' => 'remember',
					'UserToken.token_selector' => $parsed['selector'],
					'UserToken.revoked' => null,
					'UserToken.expired >=' => date('Y-m-d H:i:s'),
				]
			]);

			if(!$token)
				return null;

			if(!password_verify($parsed['validator'], $token['UserToken']['token_hash']))
			{
				// 不正な Cookie の可能性 → トークン無効化
				$this->id = $token['UserToken']['id'];
				$this->saveField('revoked', date('Y-m-d H:i:s'));
				return null;
			}

			App::uses('User', 'Model');
			$User = ClassRegistry::init('User');
			$user = $User->findById($token['UserToken']['user_id']);

			if(!$user)
				return null;

			$this->id = $token['UserToken']['id'];
			$this->saveField('last_used', date('Y-m-d H:i:s'));

			return $user['User'];
		}
		catch(Exception $e)
		{
			$this->_tableReady = false;
			return null;
		}
	}

	/**
	 * Cookie に対応するトークンを無効化する
	 *
	 * @param string $cookieValue
	 * @return void
	 */
	public function revokeByCookie($cookieValue)
	{
		if(!$this->isAvailable())
			return;

		$parsed = $this->parseCookie($cookieValue);
		if($parsed === null)
			return;

		try
		{
			$token = $this->find('first', [
				'conditions' => [
					'UserToken.token_type' => 'remember',
					'UserToken.token_selector' => $parsed['selector'],
					'UserToken.revoked' => null,
				]
			]);

			if(!$token)
				return;

			$this->id = $token['UserToken']['id'];
			$this->saveField('revoked', date('Y-m-d H:i:s'));
		}
		catch(Exception $e)
		{
			$this->_tableReady = false;
		}
	}

	/**
	 * ユーザの Remember Me トークンをすべて無効化する
	 *
	 * @param int $userId
	 * @return void
	 */
	public function revokeAllForUser($userId)
	{
		if(!$this->isAvailable())
			return;

		$userId = (int)$userId;
		if($userId <= 0)
			return;

		try
		{
			$this->updateAll(
				['UserToken.revoked' => "'" . date('Y-m-d H:i:s') . "'"],
				[
					'UserToken.user_id' => $userId,
					'UserToken.token_type' => 'remember',
					'UserToken.revoked' => null,
				]
			);
		}
		catch(Exception $e)
		{
			$this->_tableReady = false;
		}
	}
}
