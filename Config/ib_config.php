<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

$config['group_status']		= array('1' => '公開', '0' => '非公開');
$config['course_status']	= array('1' => '有効', '0' => '無効');
$config['content_status']	= array('1' => '公開', '0' => '非公開');
$config['content_kind']		= array(
	'label'		=> 'ラベル',
//	'text'		=> 'テキスト',
	'html'		=> 'リッチテキスト',
	'movie'		=> '動画',
	'url'		=> 'URL',
	'file'		=> '配布資料',
	'test'		=> 'テスト'
);

$config['content_kind_comment']		= array(
	'label'		=> 'ラベル <span>(実際の学習項目とならない章名の表示などに使用します)</span>',
//	'text'		=> 'テキスト <span>(テキスト文章のみで学習項目を作成します。)</span>',
	'html'		=> 'リッチテキスト <span>(HTML形式で学習項目を作成します。YouTubeなどの動画の埋め込みなどにも使用可能です。)</span>',
	'movie'		=> '動画 <span>(動画をアップロードします。HTML5のVIDEOタグで再生できるものに限られます。)</span>',
	'url'		=> 'URL <span>(外部のWebページを学習項目として追加します。)</span>',
	'file'		=> '配布資料 <span>(配布したいファイルをアップロードします。)</span>',
	'test'		=> 'テスト <span>(テストを作成します。問題はテスト作成後、別画面にて追加します。)'
);

$config['content_category']	= array(
	'study'		=> '学習',
	'test'		=> 'テスト'
);

$config['record_result'] = array('-1' => '', '1' => '合格', '0' => '不合格');
$config['record_complete'] = array('1' => '完了', '0' => '未完了');

// 理解度
$config['record_understanding'] = array('0' => '中断', '1' => '終了', '2' => '×', '3' => '△', '4' => '〇', '5' => '◎');

// PC向け理解度ボタンラベル
$config['record_understanding_pc'] = array(
//	'1'		=> '終了',
	'2'		=> '✕全く理解できなかった',
	'3'		=> '△あまりよく理解できなかった',
	'4'		=> '〇まあまあ理解できた',
	'5'		=> '◎よく理解できた',
);

// スマートフォン向け理解度ボタンラベル
$config['record_understanding_spn'] = array(
//	'1'		=> '終了',
	'2'		=> '✕',
	'3'		=> '△',
	'4'		=> '〇',
	'5'		=> '◎',
);

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
	'.txt',
	'.mov',
	'.mp4',
	'.wmv',
	'.asx',
	'.mp3',
	'.wma',
	'.m4a',
);

$config['upload_image_extensions'] = array(
	'.png',
	'.gif',
	'.jpg',
	'.jpeg',
);

$config['upload_movie_extensions'] = array(
	'.mov',
	'.mp4',
	'.wmv',
	'.asx',
);

// アップロードサイズの上限（別途 php.ini で upload_max_filesize を設定する必要があります）
$config['upload_maxsize']		= 1024 * 1024 * 10;
$config['upload_image_maxsize'] = 1024 * 1024 *  2;
$config['upload_movie_maxsize'] = 1024 * 1024 * 10;

// select2 項目選択時の自動クローズの設定 (true ; 自動的にメニューを閉じる, false : 閉じない)
$config['close_on_select'] = true;

// リッチテキストエディタの画像アップロード機能の設定 (true ; 使用する, false : 使用しない)
$config['use_upload_image'] = true;

// デモモード (true ; 設定する, false : 設定しない)
$config['demo_mode'] = false;

// デモユーザのログインIDとパスワード
$config['demo_login_id'] = "demo001";
$config['demo_password'] = "pass";

// フォームのスタイル(BoostCake)の基本設定
$config['form_defaults'] = array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'label' => array(
			'class' => 'col col-sm-3 control-label'
		),
		'wrapInput' => 'col col-sm-9',
		'class' => 'form-control'
	),
	'class' => 'form-horizontal'
);

$config['form_submit_defaults'] = array(
	'div' => false,
	'class' => 'btn btn-primary'
);


$config['theme_colors'] = array(
	'#337ab7' => 'default',
	'#003f8e' => 'ink blue',
	'#4169e1' => 'royal blue',
	'#006888' => 'marine blue',
	'#00bfff' => 'deep sky blue',
	'#483d8b' => 'dark slate blue',
	'#00a960' => 'green',
	'#006948' => 'holly green',
	'#288c66' => 'forest green',
	'#556b2f' => 'dark olive green',
	'#8b0000' => 'dark red',
	'#d84450' => 'poppy red',
	'#c71585' => 'medium violet red',
	'#a52a2a' => 'brown',
	'#ee7800' => 'orange',
	'#fcc800' => 'chrome yellow',
	'#7d7d7d' => 'gray',
	'#696969' => 'dim gray',
	'#2f4f4f' => 'dark slate gray',
	'#000000' => 'black'
);

$config['import_group_count']  = 10;
$config['import_course_count'] = 20;

