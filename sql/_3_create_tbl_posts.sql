USE `site`;

CREATE TABLE IF NOT EXISTS `tbl_posts` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;