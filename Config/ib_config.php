<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

$config['group_status']		= ['1' => '公開', '0' => '非公開'];
$config['course_status']	= ['1' => '有効', '0' => '無効'];
$config['content_status']	= ['1' => '公開', '0' => '非公開'];
$config['content_kind']		= [
	'label'		=> 'ラベル',
//	'text'		=> 'テキスト',
	'html'		=> 'リッチテキスト',
	'movie'		=> '動画',
	'url'		=> 'URL',
	'file'		=> '配布資料',
	'test'		=> 'テスト',
	'enquete'	=> 'アンケート',
];

$config['content_kind_comment']		= [
	'label'		=> 'ラベル <span>(実際の学習項目とならない章名の表示などに使用します)</span>',
//	'text'		=> 'テキスト <span>(テキスト文章のみで学習項目を作成します。)</span>',
	'html'		=> 'リッチテキスト <span>(HTML形式で学習項目を作成します。YouTubeなどの動画の埋め込みなどにも使用可能です。)</span>',
	'movie'		=> '動画 <span>(動画をアップロードします。HTML5のVIDEOタグで再生できるものに限られます。)</span>',
	'url'		=> 'URL <span>(外部のWebページを学習項目として追加します。)</span>',
	'file'		=> '配布資料 <span>(配布したいファイルをアップロードします。)</span>',
	'test'		=> 'テスト <span>(テストを作成します。問題はテスト作成後、別画面にて追加します。)</span>',
//	'enquete'	=> 'アンケート <span>(アンケートを作成します。質問はアンケート作成後、別画面にて追加します。)</span>',
];

$config['content_category']	= [
	'study'		=> '学習',
	'test'		=> 'テスト',
	'enquete'	=> 'アンケート',
];

$config['question_type']	= [
	'single'	=> '選択形式',
	'text'		=> '記述式',
];

$config['wrong_mode']	= ['0' => '正解と解説を表示しない', '1' => '正解と解説を表示する', '2' => '解説のみ表示する'];

$config['record_result'] = ['-1' => '', '1' => '合格', '0' => '不合格', '2' => '回答'];
$config['record_complete'] = ['1' => '完了', '0' => '未完了'];
$config['is_correct'] = ['1' => '正解', '0' => '不正解'];

// 理解度 短縮ラベル（コース目次、学習履歴一覧画面に表示）
$config['record_understanding'] = ['0' => '中断', '1' => '終了', '2' => '×', '3' => '△', '4' => '〇', '5' => '◎'];

// PC向け理解度ボタンラベル
$config['record_understanding_pc'] = [
//	'1'		=> '終了',
	'2'		=> '✕理解できなかった',
	'3'		=> '△あまり理解できなかった',
	'4'		=> '〇まあまあ理解できた',
	'5'		=> '◎よく理解できた',
];

// スマートフォン向け理解度ボタンラベル
$config['record_understanding_spn'] = [
//	'1'		=> '終了',
	'2'		=> '✕',
	'3'		=> '△',
	'4'		=> '〇',
	'5'		=> '◎',
];

$config['user_role'] = ['admin' => '管理者', 'user' => '受講者'];


$config['upload_extensions'] = [
	'.png',
	'.gif',
	'.jpg',
	'.jpeg',
	'.pdf',
	'.zip',
	'.ppt',
	'.pptx',
	'.pps',
	'.ppsx',
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
];

$config['upload_image_extensions'] = [
	'.png',
	'.gif',
	'.jpg',
	'.jpeg',
];

$config['upload_movie_extensions'] = [
	'.mov',
	'.mp4',
	'.wmv',
	'.asx',
];

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
$config['form_defaults'] = [
	'inputDefaults' => [
		'div' => 'form-group',
		'label' => [
			'class' => 'col col-sm-3 control-label'
		],
		'wrapInput' => 'col col-sm-9',
		'class' => 'form-control'
	],
	'class' => 'form-horizontal'
];

$config['form_submit_defaults'] = [
	'div' => false,
	'class' => 'btn btn-primary'
];

$config['form_submit_before'] = 
	 '<div class="form-group">'
	.'  <div class="col col-sm-9 col-sm-offset-3">';

$config['form_submit_after'] = 
	 '  </div>'
	.'</div>';

$config['theme_colors'] = [
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
];

$config['import_group_count']  = 10;
$config['import_course_count'] = 20;

$config['show_admin_link'] = false;
$config['open_link_same_window'] = false;

// webroot/index.php でアプリケーション名が設定されていない場合、ここで設定
if (!defined('APP_NAME')) {
	define('APP_NAME', 'iroha Board');
}
