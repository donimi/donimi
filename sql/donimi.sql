CREATE TABLE IF NOT EXISTS `cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app` varchar(20) NOT NULL,
  `param` text NOT NULL,
  `next` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'seconds',
  `status` enum('waiting','processing','finished','failed') NOT NULL DEFAULT 'waiting',
  `start` int(10) unsigned NOT NULL COMMENT 'start time',
  `created` int(10) unsigned NOT NULL COMMENT 'created timestamp',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `start` (`start`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='cmd' AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `content_hash` (
  `code` char(40) NOT NULL,
  `iid` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `iid` (`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='content hash code';
CREATE TABLE IF NOT EXISTS `feed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_full` enum('yes','no') NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_full` (`is_full`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='feed' AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL COMMENT 'feed id',
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `link` varchar(255) NOT NULL,
  `tm` datetime NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tm` (`tm`,`created`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='item' AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `title_hash` (
  `code` char(40) NOT NULL,
  `iid` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `iid` (`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='title hash code';
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `pass` char(40) NOT NULL,
  `status` enum('normal','deleted') NOT NULL DEFAULT 'normal',
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `created` (`created`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user' AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `user_feed` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`fid`),
  KEY `uid` (`uid`,`fid`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user feed';
CREATE TABLE IF NOT EXISTS `user_login` (
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `ip` int(11) NOT NULL COMMENT 'ip address',
  `created` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user login info';
CREATE TABLE IF NOT EXISTS `user_star` (
  `uid` int(10) unsigned NOT NULL,
  `iid` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`iid`),
  KEY `uid` (`uid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user star';
CREATE TABLE IF NOT EXISTS `waiting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `pass` char(40) NOT NULL,
  `status` enum('waiting','sent','fail') NOT NULL DEFAULT 'waiting',
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='waitting list' AUTO_INCREMENT=1 ;