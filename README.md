# iroha Board

iroha Board は日本で生まれたオープンソースのeラーニングシステム（LMS）です。
シンプルでフラットな構造と、使いやすいユーザインターフェイスが特徴で、手軽に独自のeラーニングシステムが構築できます。

## 公式サイト
https://irohaboard.irohasoft.jp/

## デモサイト
https://demoib.irohasoft.com/

## 動作環境
* PHP : 5.4以上
* MySQL : 5.1以上
* CakePHP : 2.10

## インストール方法
1. CakePHP のソースをダウンロードし、解凍します。
https://github.com/cakephp/cakephp/releases/tag/2.10.24
2. iroha Board のソースをダウンロードし、解凍します。
https://github.com/irohasoft/irohaboard/releases
3. CakePHP の app ディレクトリ内のソースを iroha Board のソースに差し替えます。
4. データベース(app/Config/database.php)の設定を行います。
   ※事前に空のデータベースを作成しておく必要があります。(推奨文字コード : UTF-8)
5. 公開ディレクトリに全ソースをアップロードします。
6. ブラウザを開き、http://(your-domain-name)/install にてインストールを実行します。

## 主な機能
### 受講者側
* 学習機能
* テスト実施機能
* テストの自動採点および結果表示
* 学習履歴の表示
* お知らせの表示

### 管理者側
* ユーザ管理
* グループ管理
* お知らせ管理
* コース管理  
　- 学習コンテンツの作成  
　- テストの作成  
　- 配布資料のアップロード
* 学習履歴の閲覧
* システム設定


## License
GPLv3
