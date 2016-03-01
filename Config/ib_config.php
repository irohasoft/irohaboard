<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */


$config['group_status']		= array('1' => '公開', '0' => '非公開');
$config['course_status']	= array('1' => '有効', '0' => '無効');
$config['content_kind']		= array(
	'label'		=> 'ラベル',
	'text'		=> 'テキスト',
	'html'		=> 'HTML',
	'movie'		=> '動画',
	'url'		=> 'URL',
	'file'		=> '配布資料',
	'test'		=> 'テスト'
);
$config['record_result'] = array('1' => '合格', '0' => '不合格');
$config['record_complete'] = array('1' => '完了', '0' => '未完了');
$config['record_understanding'] = array('1' => '', '2' => '×', '3' => '△', '4' => '〇', '4' => '◎');
$config['user_role'] = array('admin' => '管理者', 'user' => '受講者');


$config['upload_extensions'] = array(
	'.png',
	'.gif',
	'.jpg',
	'.jpeg',
	'.pdf',
	'.zip',
	'.ppt',
	'.pptx',
	'.doc',
	'.docx',
	'.xls',
	'.xlsx',
	'.mov',
	'.mp4',
	'.wmv',
	'.asx',
);

$config['upload_maxsize'] = 2000000;

// フォームのスタイル(BoostCake)の基本設定
$config['form_defaults'] = array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'label' => array(
			'class' => 'col col-md-3 col-sm-4 control-label'
		),
		'wrapInput' => 'col col-md-9 col-sm-8',
		'class' => 'form-control'
	),
	'class' => 'form-horizontal'
);

$config['form_submit_defaults'] = array(
	'div' => false,
	'class' => 'btn btn-primary'
);
