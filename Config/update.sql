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
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;

ALTER TABLE ib_courses ADD COLUMN introduction text AFTER title;

