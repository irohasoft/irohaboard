<?php
// Custom Config
$config['dummy']		= [];

// 受講者ログイン画面に管理者ログイン用リンクを表示する場合は以下のコメントを解除してください。
//$config['show_admin_link'] = true;


// 学習画面の理解度ボタンを「終了」と「中断」ボタンのみとする場合は以下のコメントを解除してください。
/*
// PC向け理解度ボタンラベル
Configure::delete('record_understanding_pc'); // 既存の設定を削除
$config['record_understanding_pc'] = array(
	'1'		=> '終了'
);

// スマートフォン向け理解度ボタンラベル
Configure::delete('record_understanding_spn'); // 既存の設定を削除
$config['record_understanding_spn'] = array(
	'1'		=> '終了'
);
*/

// アップロードサイズの上限を変更するには、以下のコメントを解除し、数字を変更してください。（単位：バイト）
//$config['upload_maxsize']       = 1024 * 1024 * 100; // 配布資料のアップロードサイズの上限
//$config['upload_image_maxsize'] = 1024 * 1024 *  20; // リッチテキストエディタの画像のアップロードサイズの上限
//$config['upload_movie_maxsize'] = 1024 * 1024 * 100; // 動画ファイルのアップロードサイズの上限

// 独自のテーマカラーを追加する場合には、以下のコメントを解除し、カラーコードを変更してください。
//$config['theme_colors'][] = ['#000000' => 'custom'];
