<?php
 /**
  * Ripple Project
  *
  * @author        Osamu Miyazawa
  * @copyright     NPO Organization uec support
  * @link          http://uecsupport.dip.jp/
  * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
  */
App::uses('AppHelper', 'View/Helper');

class ImageHelper extends AppHelper {
  // インラインイメージを作る
  public function makeInlineImage($img_path){
    $img_base64 = base64_encode(file_get_contents($img_path));
    $img_info = getimagesize('data:application/octet-stream;base64,' . $img_base64);
    $img_src = 'data: ' . $img_info['mime'] . ';base64,' . $img_base64;
    return $img_src;
  }
}
