CREATE TABLE `redirect` (
  `slug` varchar(14) collate utf8_unicode_ci NOT NULL,
  `url` varchar(620) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `hits` bigint(20) NOT NULL default '0',
  PRIMARY KEY (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Used for the URL shortener';

INSERT INTO `redirect` VALUES ('daddy', 'https://github.com/mathiasbynens/php-url-shortener', NOW(), 1);

CREATE TABLE `hits` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `request_headers` mediumtext NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;