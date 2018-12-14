# iroha Board

iroha Board とはオープンソースのeラーニングシステム(LMS)です。
シンプルでフラットな構造が特徴で、小規模なeラーニングシステムの構築に向いています。
商用、非商用問わず自由にカスタマイズして利用することが可能です。

## 公式サイト
https://irohaboard.irohasoft.jp/

## デモサイト
https://demoib.irohasoft.com/

## 動作環境
PHP : 5.4以上
MySQL : 5.1以上
CakePHP : 2.10.x

## インストール方法
1. iroha Board のソースをダウンロードし、解凍します。
https://github.com/irohasoft/irohaboard/releases
2. CakePHP 2.10 のソースをダウンロードし、解凍します。
https://github.com/cakephp/cakephp/releases/tag/2.10.13
3. Webサーバ上の非公開ディレクトリに cake フォルダを作成し、CakePHP 2.10 のソースを全てアップロードします。
4. 公開ディレクトリに irohaBoard をアップロードします。
5. データベース(Config/database.php)の設定を行います。  
   ※事前に空のデータベースを作成しておく必要があります。(推奨文字コード : UTF-8)  
6. ディレクトリ構成が以下のようになっていない場合、設定ファイル(webroot/index.php)を書き換えます。  
/cake  
┗ /lib  
/public_html  
┣ /Config  
┣ /Controller  
┣ /Model  
┣ ・・・  
┣ /View  
┗ /webroot  
7. ブラウザを開き、http://(your-domain-name)/install にてインストールを実行します。  
画面上にインストール完了のメッセージが表示されればインストールは完了です。

## 主な機能
### 受講者側
* 学習機能
* テスト実施機能
* 自動採点／結果表示機能
* 学習履歴の表示
* お知らせの表示

### 管理者側
* ユーザ管理
* グループ管理
* お知らせ管理
* コース管理  
　- 学習コンテンツの作成  
　- テストの作成  
　- 配布資料の登録  
* 学習履歴の閲覧
* システム設定
  

## License
GPLv3
