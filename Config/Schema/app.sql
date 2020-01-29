SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ib_users_groups`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_users_groups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL DEFAULT '0',
  `group_id` int(8) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `idx_user_group_id` (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_users_courses`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_users_courses` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL DEFAULT '0',
  `course_id` int(8) NOT NULL DEFAULT '0',
  `started` date DEFAULT NULL,
  `ended` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `idx_user_course_id` (`user_id`,`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_users`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `role` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `comment` text,
  `last_logined` datetime DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `ended` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_id` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_settings`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_records_questions`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_records_questions` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `record_id` int(8) NOT NULL DEFAULT '0',
  `question_id` int(8) NOT NULL DEFAULT '0',
  `answer` varchar(200) DEFAULT NULL,
  `correct` varchar(200) DEFAULT NULL,
  `is_correct` smallint(1) DEFAULT '0',
  `score` int(8) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_records`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_records` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `course_id` int(8) NOT NULL DEFAULT '0',
  `user_id` int(8) NOT NULL DEFAULT '0',
  `content_id` int(8) NOT NULL,
  `full_score` int(3) DEFAULT '0',
  `pass_score` int(3) DEFAULT NULL,
  `score` int(3) DEFAULT NULL,
  `is_passed` smallint(1) DEFAULT '0',
  `is_complete` smallint(1) DEFAULT NULL,
  `progress` smallint(1) DEFAULT '0',
  `understanding` smallint(1) DEFAULT NULL,
  `study_sec` int(3) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_course_user_content_id` (`course_id`,`user_id`,`content_id`),
  KEY `idx_created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_infos`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_infos` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `body` text,
  `opened` datetime DEFAULT NULL,
  `closed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `user_id` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `ib_infos_groups`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_infos_groups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `info_id` int(8) NOT NULL DEFAULT '0',
  `group_id` int(8) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `ib_groups`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_groups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `comment` text,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `logo` varchar(200) DEFAULT NULL,
  `copyright` varchar(200) DEFAULT NULL,
  `module` varchar(50) DEFAULT '00000000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_groups_courses`
-- ----------------------------
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

-- ----------------------------
-- Table structure for `ib_courses`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_courses` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `introduction` text,
  `opened` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `sort_no` int(8) NOT NULL DEFAULT '0',
  `comment` text,
  `user_id` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_contents_questions`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_contents_questions` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `content_id` int(8) NOT NULL DEFAULT '0',
  `question_type` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `options` varchar(2000) DEFAULT NULL,
  `correct` varchar(200) NOT NULL DEFAULT '',
  `score` int(8) NOT NULL DEFAULT '0',
  `explain` text,
  `comment` text,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `sort_no` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_logs`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` varchar(50) DEFAULT NULL,
  `log_content` varchar(1000) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(50) DEFAULT NULL,
  `user_agent` varchar(1000) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `ib_contents`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_contents` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `course_id` int(8) NOT NULL DEFAULT '0',
  `user_id` int(8) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) DEFAULT NULL,
  `file_name` varchar(200) DEFAULT NULL,
  `kind` varchar(20) NOT NULL DEFAULT '',
  `body` text,
  `timelimit` int(8) DEFAULT NULL,
  `pass_rate` int(8) DEFAULT NULL,
  `question_count` int(8) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `opened` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `sort_no` int(8) NOT NULL DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ib_cake_sessions`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ib_cake_sessions` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ib_settings` VALUES ('1', 'title', 'システム名', 'iroha Board');
INSERT INTO `ib_settings` VALUES ('2', 'copyright', 'コピーライト', 'Copyright (C) 2016-2019 iroha Soft Co.,Ltd. All rights reserved.');
INSERT INTO `ib_settings` VALUES ('3', 'color', 'テーマカラー', '#337ab7');
INSERT INTO `ib_settings` VALUES ('4', 'information', 'お知らせ', '全体のお知らせを表示します。\r\nこのお知らせは管理機能の「システム設定」にて変更可能です。');
