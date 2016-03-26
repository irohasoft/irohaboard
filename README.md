# iroha Board

iroha Board とはオープンソースのeラーニングシステム(LMS)です。  
シンプルでフラットな構造が特徴で、小規模なeラーニングシステムの構築に向いています。  

## 公式サイト
http://irohaboard.irohasoft.jp/

##動作環境
PHP : 5.3.7以上  
MySQL : 5.1以上  
CakePHP : 2.7.x  

##インストール方法
1. iroha Board のソースをダウンロードし、解凍します。
* CakePHP 2.7 のソースをダウンロードし、解凍します。  
https://github.com/cakephp/cakephp/releases/tag/2.7.11
* Webサーバ上の非公開ディレクトリに cake フォルダを作成し、CakePHP 2.7 のソースを全てアップロードします。
* 公開ディレクトリに irohaBoard をアップロードします。
* データベース(Config/database.php)の設定を行います。
  事前に空のデータベースの作成しておく必要があります。(推奨文字コード : UTF-8)  
* ディレクトリ構成が以下のようになっていない場合、設定ファイル(webroot/index.php)を書き換えます。  
/cake  
┗ /lib  
/public_html  
┣ /Config  
┣ /Controller  
┣ /Model  
┗ /webroot  
* /install を実行します。

  
