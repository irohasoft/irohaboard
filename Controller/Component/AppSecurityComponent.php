<?php
/**
 * CakePHP 4 相当のフォーム改ざん検証付き SecurityComponent
 *
 * CSRF（_Token.key）は親クラスのまま。
 * _Token.fields のみ HMAC + sessionId 方式に切り替える。
 *
 * AppController で className を指定し、各コントローラは
 * 'Security' => [...] と書くだけでこのクラスが使われる。
 * Security を宣言していないコントローラでは検証を行わない（従来どおり）。
 */
App::uses('SecurityComponent', 'Controller/Component');
App::uses('FormToken', 'Lib');
App::uses('Hash', 'Utility');

class AppSecurityComponent extends SecurityComponent
{
	/**
	 * 当該コントローラ自身が Security を宣言しているか
	 *
	 * @param Controller $controller
	 * @return bool
	 */
	protected function _isOptedIn(Controller $controller)
	{
		$vars = get_class_vars(get_class($controller));
		if (empty($vars['components']) || !is_array($vars['components'])) {
			return false;
		}
		$components = Hash::normalize($vars['components']);
		return isset($components['Security']);
	}

	/**
	 * Component startup. All security checking happens here.
	 *
	 * @param Controller $controller Instantiating controller
	 * @return void
	 */
	public function startup(Controller $controller)
	{
		if (!$this->_isOptedIn($controller)) {
			return;
		}
		parent::startup($controller);
	}

	/**
	 * 送信フォームの改ざん検証
	 *
	 * @param Controller $controller Instantiating controller
	 * @throws AuthSecurityException
	 * @return bool true if submitted form is valid
	 */
	protected function _validatePost(Controller $controller)
	{
		if (!FormToken::useCake4Style()) {
			return parent::_validatePost($controller);
		}

		$token = $this->_validToken($controller);
		$fieldList = $this->_fieldsList($controller->request->data);
		$unlocked = $this->_sortedUnlocked($controller->request->data);
		$url = $controller->request->here();
		$sessionId = FormToken::sessionId();

		$check = FormToken::hash($fieldList, $unlocked, $url, $sessionId);
		if (FormToken::equals($token, $check)) {
			return true;
		}

		// debug 用メッセージは親と同じ比較材料を渡す（第4要素は salt の代わりに sessionId）
		$hashParts = [
			$url,
			serialize($fieldList),
			$unlocked,
			$sessionId,
		];

		$msg = self::DEFAULT_EXCEPTION_MESSAGE;
		if (Configure::read('debug')) {
			$msg = $this->_debugPostTokenNotMatching($controller, $hashParts);
		}

		throw new AuthSecurityException($msg);
	}
}
