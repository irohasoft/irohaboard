SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `ib_categories` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `introduction` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `sort_no` int(8) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;