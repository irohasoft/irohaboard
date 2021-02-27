<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('View', 'View');

/**
 * AppView 全てのビューの基底クラス
 */
class AppView extends View
{
	/**
	 * セッションの取得
	 * @param string $key キー
	 * @return string セッションの値
	 */
	protected function readSession($key)
	{
		return $this->Session->read($key);
	}

	/**
	 * セッションの削除
	 * @param string $key キー
	 */
	protected function deleteSession($key)
	{
		$this->Session->delete($key);
	}

	/**
	 * セッションの存在確認
	 * @param string $key キー
	 * @return bool true : 存在する
	 */
	protected function hasSession($key)
	{
		return $this->Session->check($key);
	}

	/**
	 * セッションの保存
	 * @param string $key キー
	 * @param string $value 値
	 */
	protected function writeSession($key, $value)
	{
		$this->Session->write($key, $value);
	}

	/**
	 * ログインユーザ情報の取得
	 * @param string $key キー（省略した場合全て）
	 * @return array|string ログインユーザ情報
	 */
	protected function readAuthUser($key = null)
	{
		$user = $this->Session->read('Auth.User');
		
		if(!$user)
			return null;
		
		if(!$key)
			return $user;
		
		return $user[$key];
	}

	/**
	 * ログイン状態の確認
	 * @param string $key キー
	 * @return bool true : ログイン済み
	 */
	protected function isLogined()
	{
		$user = $this->readAuthUser();
		
		return isset($user);
	}

	/**
	 * 管理画面へのアクセスかを確認
	 * @return bool true : 管理画面, false : 受講者画面
	 */
	protected function isAdminPage()
	{
		return (isset($this->request->params['admin']));
	}

	/**
	 * 編集画面へのアクセスかを確認
	 */
	protected function isEditPage()
	{
		return (($this->action == 'edit') || ($this->action == 'admin_edit'));
	}

	/**
	 * テスト結果画面へのアクセスかを確認
	 */
	protected function isRecordPage()
	{
		return (($this->action == 'record') || ($this->action == 'admin_record'));
	}

	/**
	 * ログイン画面へのアクセスかを確認
	 */
	protected function isLoginPage()
	{
		return (($this->action == 'login') || ($this->action == 'admin_login'));
	}
}