<?php
/**
 * CakePHP 4 相当のフォームトークン生成付き FormHelper
 *
 * BoostCake の見た目はそのまま、secure() の署名だけ差し替える。
 */
App::uses('BoostCakeFormHelper', 'BoostCake.View/Helper');
App::uses('FormToken', 'Lib');

class AppBoostCakeFormHelper extends BoostCakeFormHelper
{
	/**
	 * SecurityComponent 用の _Token を出力
	 *
	 * @param array $fields フィールド一覧
	 * @param array $secureAttributes hidden の HTML 属性
	 * @return string|null
	 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::secure
	 */
	public function secure($fields = [], $secureAttributes = [])
	{
		if (!FormToken::useCake4Style()) {
			return parent::secure($fields, $secureAttributes);
		}

		if (!isset($this->request['_Token']) || empty($this->request['_Token'])) {
			return null;
		}

		$debugSecurity = Configure::read('debug');
		if (isset($secureAttributes['debugSecurity'])) {
			$debugSecurity = $debugSecurity && $secureAttributes['debugSecurity'];
			unset($secureAttributes['debugSecurity']);
		}

		$originalFields = $fields;
		$locked = [];
		$unlockedFields = $this->_unlockedFields;

		foreach ($fields as $key => $value) {
			if (!is_int($key)) {
				$locked[$key] = $value;
				unset($fields[$key]);
			}
		}

		sort($unlockedFields, SORT_STRING);
		sort($fields, SORT_STRING);
		ksort($locked, SORT_STRING);
		$fields += $locked;

		$lockedNames = implode('|', array_keys($locked));
		$unlocked = implode('|', $unlockedFields);
		$hash = FormToken::hash(
			$fields,
			$unlocked,
			$this->_lastAction,
			FormToken::sessionId()
		);

		$tokenFields = array_merge($secureAttributes, [
			'value' => urlencode($hash . ':' . $lockedNames),
			'id' => 'TokenFields' . mt_rand(),
			'secure' => static::SECURE_SKIP,
			'autocomplete' => 'off',
		]);
		$out = $this->hidden('_Token.fields', $tokenFields);

		$tokenUnlocked = array_merge($secureAttributes, [
			'value' => urlencode($unlocked),
			'id' => 'TokenUnlocked' . mt_rand(),
			'secure' => static::SECURE_SKIP,
			'autocomplete' => 'off',
		]);
		$out .= $this->hidden('_Token.unlocked', $tokenUnlocked);

		if ($debugSecurity) {
			$tokenDebug = array_merge($secureAttributes, [
				'value' => urlencode(json_encode([
					$this->_lastAction,
					$originalFields,
					$this->_unlockedFields,
				])),
				'id' => 'TokenDebug' . mt_rand(),
				'secure' => static::SECURE_SKIP,
			]);
			$out .= $this->hidden('_Token.debug', $tokenDebug);
		}

		return $this->Html->useTag('hiddenblock', $out);
	}
}
