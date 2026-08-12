SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `ib_infos_groups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `info_id` int(8) NOT NULL DEFAULT '0',
  `group_id` int(8) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ib_groups_courses` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `group_id` int(8) NOT NULL DEFAULT '0',
  `course_id` int(8) NOT NULL DEFAULT '0',
  `started` date DEFAULT NULL,
  `ended` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;

ALTER TABLE ib_courses ADD COLUMN introduction text AFTER title;
ALTER TABLE ib_contents ADD COLUMN file_name varchar(200) AFTER url;

ALTER TABLE ib_contents ADD COLUMN status int(1) NOT NULL DEFAULT '1' AFTER pass_rate;
ALTER TABLE ib_contents ADD question_count int(8) AFTER pass_rate;

UPDATE ib_contents SET status = 1 WHERE status IS NULL;

ALTER TABLE ib_users_groups  ADD INDEX idx_user_group_id(user_id, group_id);
ALTER TABLE ib_users_courses ADD INDEX idx_user_course_id(user_id, course_id);
ALTER TABLE ib_records ADD INDEX idx_group_course_user_content_id(group_id, course_id, user_id, content_id);
ALTER TABLE ib_records ADD INDEX idx_created(created);

ALTER TABLE ib_contents ADD COLUMN wrong_mode int(1) NOT NULL DEFAULT 1 AFTER question_count;

ALTER TABLE ib_contents_questions MODIFY options varchar(2000);
ALTER TABLE ib_records_questions MODIFY answer varchar(2000);

ALTER TABLE ib_users MODIFY COLUMN created datetime DEFAULT NULL;
ALTER TABLE ib_users_courses MODIFY COLUMN created datetime DEFAULT NULL;
ALTER TABLE ib_users_groups MODIFY COLUMN created datetime DEFAULT NULL;
ALTER TABLE ib_groups_courses MODIFY COLUMN created datetime DEFAULT NULL;
ALTER TABLE ib_records_questions MODIFY COLUMN created datetime DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `ib_user_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token_type` varchar(20) NOT NULL COMMENT 'remember / trusted_device',
  `token_selector` varchar(32) NOT NULL COMMENT 'Cookie公開部（検索用）',
  `token_hash` varchar(255) NOT NULL COMMENT 'Cookie秘密部のハッシュ',
  `expired` datetime NOT NULL,
  `last_used` datetime DEFAULT NULL,
  `revoked` datetime DEFAULT NULL,
  `user_ip` varchar(50) DEFAULT NULL,
  `user_agent` varchar(1000) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_token_selector` (`token_selector`),
  KEY `idx_user_type` (`user_id`, `token_type`),
  KEY `idx_expired` (`expired`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# 管理者アカウントのパスワードの復旧方法
# 1. UPDATE文の前の#を削除し、「復旧したい管理者のログインID」と「パスワード」を対象のものに置換します。
# 2. ファイルを保存後、ブラウザで /update を実行します。
# 3. #を元の状態に戻し、ファイルを保存します。
#UPDATE ib_users SET `password` = SHA1(CONCAT('%salt%', '新しいパスワード')) WHERE username = '復旧したい管理者のログインID';

