<?php
/**
 * CakePHP 4 相当のフォーム改ざん防止トークン生成
 *
 * hash_hmac('sha1', URL + serialize(fields) + unlocked + sessionId, Security.salt)
 */
class FormToken
{
	/**
	 * CakePHP 4 方式を使うか
	 *
	 * @return bool
	 */
	public static function useCake4Style()
	{
		$mode = Configure::read('Security.formProtection');
		if ($mode === null || $mode === '') {
			return true;
		}
		return $mode === 'cake4';
	}

	/**
	 * フォーム保護ハッシュを生成
	 *
	 * @param array $fields フィールドリスト（locked は key=>value、通常は値なしの名前一覧）
	 * @param string|array $unlocked unlocked フィールド（配列 or '|' 連結文字列）
	 * @param string $url フォーム送信先 URL
	 * @param string $sessionId セッション ID
	 * @return string
	 */
	public static function hash(array $fields, $unlocked, $url, $sessionId)
	{
		if (is_array($unlocked)) {
			sort($unlocked, SORT_STRING);
			$unlocked = implode('|', $unlocked);
		}

		$payload = $url . serialize($fields) . $unlocked . $sessionId;
		return hash_hmac('sha1', $payload, Configure::read('Security.salt'));
	}

	/**
	 * セッション ID を取得（未開始なら開始する）
	 *
	 * @return string
	 */
	public static function sessionId()
	{
		App::uses('CakeSession', 'Model/Datasource');
		if (!CakeSession::started()) {
			CakeSession::start();
		}
		return (string)CakeSession::id();
	}

	/**
	 * タイミング攻撃耐性のある文字列比較
	 *
	 * @param string $known
	 * @param string $user
	 * @return bool
	 */
	public static function equals($known, $user)
	{
		if (function_exists('hash_equals')) {
			return hash_equals((string)$known, (string)$user);
		}
		return (string)$known === (string)$user;
	}
}
