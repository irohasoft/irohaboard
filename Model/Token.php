<?php
/**
 * Ripple  Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');

 class Token extends AppModel
{
  // トークンを生成し，データと共に格納．
  public function generate($data = null){
    $data = array(
      'token' => sha1(uniqid(rand(), true)),
      'data' => serialize($data),
    );
    if ($this->save($data)) {
      return $data['token'];
    }
    return false;
  }

  // トークンがあれば対応するデータを，そうでなければfalseを返す
  public function get($token){
    $this->garbage();
    $token = $this->find('first', array(
      'recursive' => -1,
      'conditions' => array('Token.token' => $token),
    ));
    if ($token){
      $this->delete($token['Token']['id']);
      return unserialize($token['Token']['data']);
    }
    return false;
  }

  // 1時間以上前に作られたトークンを削除．
  public function garbage() {
    return $this->deleteAll(array('created < INTERVAL -1 HOUR + NOW()'));
  }
}
