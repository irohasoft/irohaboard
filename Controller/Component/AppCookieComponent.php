<?php
/**
 * CookieComponent の PHP 8.5 対応
 *
 * CakePHP 2 の Security::cipher() は Security.cipherSeed を (int)(float) するため、
 * 桁数の多い seed では PHP 8.5 で float→int の警告が出る。
 * 範囲外の float→int は PHP の仕様上は従来から結果未定義で、
 * この変換は 64-bit PHP の従来実装（2^64 剰余）を再現する。
 */
App::uses('CookieComponent', 'Controller/Component');

class AppCookieComponent extends CookieComponent
{
	const CIPHER_PREFIX = 'Q2FrZQ==.';

	/**
	 * @param string $value Value to encrypt
	 * @return string Encoded values
	 */
	protected function _encrypt($value)
	{
		if (is_array($value)) {
			$value = $this->_implode($value);
		}
		if (!$this->_encrypted) {
			return $value;
		}
		if ($this->_type === 'cipher') {
			return self::CIPHER_PREFIX . base64_encode(static::_cipher($value, $this->key));
		}
		return parent::_encrypt($value);
	}

	/**
	 * @param string $value The value to decode & decrypt.
	 * @return string|array Decoded value.
	 */
	protected function _decode($value)
	{
		if ($this->_type === 'cipher' && strpos($value, self::CIPHER_PREFIX) === 0) {
			$value = base64_decode(substr($value, strlen(self::CIPHER_PREFIX)));
			return $this->_explode(static::_cipher($value, $this->key));
		}
		return parent::_decode($value);
	}

	/**
	 * Security::cipher() と同じ XOR 暗号。
	 *
	 * @param string $text Encrypted string to decrypt, normal string to encrypt
	 * @param string $key Key to use
	 * @return string Encrypted/Decrypted string
	 */
	protected static function _cipher($text, $key)
	{
		if ($key === '' || $key === null) {
			trigger_error(__d('cake_dev', 'You cannot use an empty key for %s', 'Security::cipher()'), E_USER_WARNING);
			return '';
		}

		srand(static::_cipherSeed());
		try {
			$out = '';
			$keyLength = strlen($key);
			for ($i = 0, $textLength = strlen($text); $i < $textLength; $i++) {
				$j = ord(substr($key, $i % $keyLength, 1));
				while ($j--) {
					rand(0, 255);
				}
				$mask = rand(0, 255);
				$out .= chr(ord(substr($text, $i, 1)) ^ $mask);
			}
			return $out;
		} finally {
			srand();
		}
	}

	/**
	 * cipherSeed を srand() 用の int に変換する。
	 *
	 * CakePHP 2 は (int)(float)$cipherSeed するが、30 桁の seed は int に収まらない。
	 * 64-bit PHP の従来実装と同じく、float 化して 2^64 剰余を取り、
	 * 符号付き 64-bit の範囲に正規化してから (int) する。
	 *
	 * @return int
	 */
	protected static function _cipherSeed()
	{
		if (PHP_INT_SIZE !== 8) {
			throw new RuntimeException('AppCookieComponent requires 64-bit PHP.');
		}

		$float = (float)Configure::read('Security.cipherSeed');
		if (!is_finite($float)) {
			return 0;
		}

		$two64 = 18446744073709551616.0;
		$two63 = 9223372036854775808.0;
		$normalized = fmod($float, $two64);
		if ($normalized < 0.0) {
			$normalized += $two64;
		}
		if ($normalized >= $two63) {
			$normalized -= $two64;
		}

		return (int)$normalized;
	}
}
