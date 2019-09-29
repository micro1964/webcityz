CREATE TABLE IF NOT EXISTS `#__jxtc_readinglist` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(4) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  `item_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `component` varchar(100) NOT NULL,
  `entry_date` datetime NOT NULL,
  `read` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`item_id`,`user_id`,`component`)
) DEFAULT CHARSET=utf8;