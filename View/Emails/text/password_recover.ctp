<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Emails.text
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

<?php echo $user['User']['name']; ?>様

パスワード再設定メールが送信されました．

パスワードを再設定するには，1時間以内に下記のリンクからアクセスしてください．

<?php echo Router::url(array('controller' => 'users', 'action' => 'password_verify', $token), true); ?>

このメールに心当たりがない場合は，破棄してください．
以下のリンクからログインして，パスワードを変更できます．

<?php echo Router::url(array('controller' => 'users', 'action' => 'login'), true); ?>

Ripple System
