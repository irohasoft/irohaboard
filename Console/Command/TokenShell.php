<?php
/**
 * AppShell file
 *
 * Ripple Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('Shell', 'Console');

/**
 * Application Shell
 * @package       app.Console.Command
 */
class TokenShell extends AppShell {
  public $uses = array('Token');

  public function main() {
    $this->Token->garbage();
    $this->out('Old tokens were deleted.');
  }
}
