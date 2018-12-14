# iroha Board
iroha Board is a Simple and Easy-to-Use Open Source LMS.  [[Japanese / 日本語]](/README.jp.md)

## Project website
https://irohaboard.irohasoft.jp/

## Demo
https://demoib.irohasoft.com/

## System Requirements
* PHP : 5.4 or later
* MySQL : 5.1 or later
* CakePHP : 2.10.x

## Installation
1. Download the source of iroha Board.
https://github.com/irohasoft/irohaboard/releases
2. Download the source of CakePHP.
https://github.com/cakephp/cakephp/releases/tag/2.10.13
3. Make [cake] directory on your web server and upload the source of CakePHP.
4. Upload the source of iroha Board to public direcotry on your web server.  
/cake  
┗ /lib  
/public_html  
┣ /Config  
┣ /Controller  
┣ /Model  
┣ ・・・  
┣ /View  
┗ /webroot  
5. Modify the database configuration on Config/database.php file.
Make sure you have created an empty database on you MySQL server.
6. Open http://(your-domain-name)/install on your web browser.

## Features

### For students.

- Learning.
- Take tests.
- Show learning records.
- Show informations from teachers.

### For teachers.
- Manage users.
- Manage user groups.
- Manage informations.
- Manage courses.
- Manage learning contents.
- Manage tests.
- Manage records.

### For administrators.
- System setting

## License
GPLv3
