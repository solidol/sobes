# SQL Manager 2007 for MySQL 4.5.0.4
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : rda_docs


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE `rda_docs`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `rda_docs`;

#
# Structure for the `content_types` table : 
#

CREATE TABLE `content_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subcode` int(11) unsigned DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

#
# Structure for the `dates_control` table : 
#

CREATE TABLE `dates_control` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document` bigint(20) unsigned DEFAULT NULL,
  `date_control` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

#
# Structure for the `document` table : 
#

CREATE TABLE `document` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `num_prefix_0` varchar(5) DEFAULT '0',
  `num_prefix_1` varchar(5) DEFAULT NULL,
  `num_prefix_2` varchar(5) DEFAULT NULL,
  `internal_number` int(11) DEFAULT NULL,
  `external_number` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `isreapeted` varchar(10) DEFAULT 'no',
  `datestamp` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `date_create` date DEFAULT NULL,
  `date_control` date DEFAULT NULL,
  `date_in` date DEFAULT NULL,
  `control_period` varchar(50) DEFAULT NULL,
  `topicstarter` bigint(20) unsigned DEFAULT '0',
  `topicstarter_org` int(11) unsigned DEFAULT '0',
  `summary` text,
  `comment` text,
  `topicstarter_text` text,
  `created_by` int(11) unsigned DEFAULT NULL,
  `curr_user` text,
  `type` varchar(20) DEFAULT 'none',
  `status` varchar(20) DEFAULT 'created',
  `impstatus` varchar(20) DEFAULT NULL,
  `donestatus` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=448 DEFAULT CHARSET=utf8;

#
# Structure for the `document_donestr` table : 
#

CREATE TABLE `document_donestr` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `keystr` varchar(20) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=330 DEFAULT CHARSET=utf8;

#
# Structure for the `document_extnum` table : 
#

CREATE TABLE `document_extnum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `keystr` varchar(20) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=390 DEFAULT CHARSET=utf8;

#
# Structure for the `document_meta` table : 
#

CREATE TABLE `document_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `keystr` varchar(20) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `document_movings` table : 
#

CREATE TABLE `document_movings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `keystr` varchar(20) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `document_id` (`document_id`)
) ENGINE=MyISAM AUTO_INCREMENT=901 DEFAULT CHARSET=utf8;

#
# Structure for the `document_notes` table : 
#

CREATE TABLE `document_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `keystr` varchar(20) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Structure for the `document_number_elements` table : 
#

CREATE TABLE `document_number_elements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doc` bigint(20) unsigned zerofill NOT NULL DEFAULT '00000000000000000000',
  `key` varchar(20) NOT NULL DEFAULT '',
  `value` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for the `document_org` table : 
#

CREATE TABLE `document_org` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT '0',
  `organization_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `document_people` table : 
#

CREATE TABLE `document_people` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT '0',
  `people_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=368 DEFAULT CHARSET=utf8;

#
# Structure for the `movings` table : 
#

CREATE TABLE `movings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document` bigint(20) unsigned DEFAULT '0',
  `tmstamp` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `prevuser` bigint(20) unsigned DEFAULT '0',
  `nextuser` bigint(20) unsigned DEFAULT '0',
  `movtext` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for the `org_types` table : 
#

CREATE TABLE `org_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subcode` int(11) unsigned DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

#
# Structure for the `organizations` table : 
#

CREATE TABLE `organizations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `city` varchar(250) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `street` varchar(250) DEFAULT NULL,
  `building` varchar(10) DEFAULT NULL,
  `housing` varchar(10) DEFAULT NULL,
  `room` varchar(20) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Structure for the `people` table : 
#

CREATE TABLE `people` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `secondname` varchar(100) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `passport` varchar(30) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT '73000',
  `city` varchar(20) DEFAULT 'Херсон',
  `street` varchar(250) DEFAULT NULL,
  `building` varchar(10) DEFAULT NULL,
  `housing` varchar(10) DEFAULT '-',
  `room` varchar(10) DEFAULT NULL,
  `phones` varchar(250) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1049 DEFAULT CHARSET=utf8;

#
# Structure for the `people_meta` table : 
#

CREATE TABLE `people_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `people_id` bigint(20) unsigned DEFAULT NULL,
  `keystr` varchar(20) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=283 DEFAULT CHARSET=utf8;

#
# Structure for the `resolution` table : 
#

CREATE TABLE `resolution` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document` bigint(20) unsigned DEFAULT '0',
  `rtext` text,
  `creator` int(11) DEFAULT '0',
  `nextuser` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for the `social_status` table : 
#

CREATE TABLE `social_status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keystr` varchar(20) DEFAULT NULL,
  `value` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

#
# Structure for the `state_types` table : 
#

CREATE TABLE `state_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL DEFAULT '2',
  `subcode` int(11) NOT NULL DEFAULT '0',
  `text` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for the `user_custom_fields` table : 
#

CREATE TABLE `user_custom_fields` (
  `user_id` int(11) unsigned NOT NULL,
  `attribute` varchar(50) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`attribute`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `users` table : 
#

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL DEFAULT '',
  `roles` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `time_created` int(11) unsigned NOT NULL DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `isEnabled` tinyint(1) NOT NULL DEFAULT '1',
  `confirmationToken` varchar(100) DEFAULT NULL,
  `timePasswordResetRequested` int(11) unsigned DEFAULT NULL,
  `status` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

#
# Definition for the `num_types_total` function : 
#

CREATE DEFINER = 'root'@'localhost' FUNCTION `num_types_total`(
        docid BIGINT
    )
    RETURNS text CHARSET utf8
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

  RETURN (SELECT GROUP_CONCAT(DISTINCT keystr SEPARATOR ',') AS keystrs FROM
 document_extnum 
 WHERE document_extnum.`document_id` = docid
 GROUP BY document_extnum.`document_id`);
END;

#
# Definition for the `tmp_ems_proc_123` function : 
#

CREATE DEFINER = 'root'@'localhost' FUNCTION `tmp_ems_proc_123`(
        docid BIGINT
    )
    RETURNS text CHARSET utf8
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

  RETURN (SELECT GROUP_CONCAT(kestr) AS keystrs FROM
 document_extnum 
 WHERE document_extnum.`id` = docid
 GROUP BY document_extnum.`document_id`);
END;

#
# Definition for the `date_interval` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `date_interval`(
        IN startDate DATE,
        IN endDate DATE
    )
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
declare iterDate date;
   set iterDate = startDate;

   DROP TABLE IF EXISTS dateIntervals;
   create temporary table dateIntervals (
      theDate date
   );

   label1: LOOP
     insert into dateIntervals(theDate) values (iterDate); 
     SET iterDate = DATE_ADD(iterDate, INTERVAL 1 DAY);
     IF iterDate <= endDate THEN
        ITERATE label1;
     END IF;
     LEAVE label1;
   END LOOP label1;

   select * from dateIntervals;
END;

#
# Definition for the `date_interval_month` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `date_interval_month`(
        IN startDate DATE,
        IN endDate DATE
    )
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
declare iterDate date;
   set iterDate = startDate;
   
   DROP TABLE IF EXISTS dateIntervals;
   create temporary table dateIntervals (
      theDate date
   );

   label1: LOOP
     insert into dateIntervals(theDate) values (iterDate); 
     SET iterDate = DATE_ADD(iterDate, INTERVAL 1 MONTH);
     IF iterDate <= endDate THEN
        ITERATE label1;
     END IF;
     LEAVE label1;
   END LOOP label1;

   select * from dateIntervals;
END;

#
# Definition for the `dates_control_view` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW dates_control_view AS 
  select 
    `dates_control`.`document` AS `document`,
    `dates_control`.`date_control` AS `date_control`,
    date_format(`dates_control`.`date_control`,'%d.%m.%Y') AS `date_control_text` 
  from 
    `dates_control`;

#
# Definition for the `document_archive` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_archive AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_0` AS `num_prefix_0`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    (`document`.`status` = 'archived');

#
# Definition for the `document_archive_org` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_archive_org AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_0` AS `num_prefix_0`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_0`,'-',`document`.`internal_number`,'-',`document`.`num_prefix_2`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    `document`.`date_in` AS `date_in`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    (`document` join `users` on((`users`.`id` = `document`.`created_by`))) 
  where 
    ((`document`.`status` = 'archived') and (`document`.`type` = 'org'));

#
# Definition for the `document_archive_people` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_archive_people AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    if((`document`.`isreapeted` > 0),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`,'/',`document`.`isreapeted`),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`)) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`impstatus` AS `impstatus`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`status` AS `status`,
    `users`.`name` AS `name`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    `people`.`city` AS `city`,
    `people`.`sex` AS `sex`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    ((`document`.`status` = 'archived') and (`document`.`num_prefix_1` <> 2)) 
  order by 
    `document`.`id` desc;

#
# Definition for the `document_archive_state` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_archive_state AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`num_prefix_2`,'-',`document`.`internal_number`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `state_types`.`text` AS `stateorgname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `state_types` on((`state_types`.`id` = `document`.`num_prefix_2`))) 
  where 
    ((`document`.`status` = 'archived') and (`document`.`num_prefix_1` = 2));

#
# Definition for the `document_archive_visitors` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_archive_visitors AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`impstatus` AS `impstatus`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`status` AS `status`,
    `users`.`name` AS `name`,
    `people`.`id` AS `pid`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    `people`.`city` AS `city`,
    `people`.`sex` AS `sex`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    ((`document`.`status` = 'archived') and (`document`.`type` = 'visitors')) 
  order by 
    `document`.`id` desc;

#
# Definition for the `document_view` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_view AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    if((`document`.`type` = 'people'),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`),if((`document`.`type` = 'visitors'),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`,'/',`document`.`num_prefix_2`),'')) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    `document`.`date_in` AS `date_in`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    ((`document`.`status` <> 'archived') and ((`document`.`type` = 'people') or (`document`.`type` = 'visitors')));

#
# Definition for the `document_view_org` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_view_org AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_0` AS `num_prefix_0`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_0`,'-',`document`.`internal_number`,'-',`document`.`num_prefix_2`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    `document`.`date_in` AS `date_in`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    (`document` join `users` on((`users`.`id` = `document`.`created_by`))) 
  where 
    ((`document`.`status` <> 'archived') and (`document`.`type` = 'org'));

#
# Definition for the `document_view_people` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_view_people AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    if((`document`.`isreapeted` > 0),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`,'/',`document`.`isreapeted`),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`)) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    `people`.`city` AS `city`,
    `people`.`sex` AS `sex`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    ((`document`.`type` = 'people') or (`document`.`type` = 'visitors')) 
  order by 
    `document`.`id` desc;

#
# Definition for the `document_view_state` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_view_state AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`num_prefix_2`,'-',`document`.`internal_number`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    `document`.`date_in` AS `date_in`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `state_types`.`text` AS `stateorgname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `state_types` on((`state_types`.`id` = `document`.`num_prefix_2`))) 
  where 
    ((`document`.`status` <> 'archived') and (`document`.`num_prefix_1` = 2));

#
# Definition for the `document_view_visitors` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_view_visitors AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`,'/',`document`.`num_prefix_2`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`status` AS `status`,
    `document`.`impstatus` AS `impstatus`,
    `document`.`donestatus` AS `donestatus`,
    `users`.`name` AS `name`,
    `people`.`id` AS `pid`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    `people`.`city` AS `city`,
    `people`.`sex` AS `sex`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    (`document`.`type` = 'visitors') 
  order by 
    `document`.`id` desc;

#
# Definition for the `document_work_org` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_work_org AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_0` AS `num_prefix_0`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_0`,'-',`document`.`internal_number`,'-',`document`.`num_prefix_2`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    `document`.`date_in` AS `date_in`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    (`document` join `users` on((`users`.`id` = `document`.`created_by`))) 
  where 
    ((`document`.`status` <> 'archived') and (`document`.`type` = 'org'));

#
# Definition for the `document_work_people` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_work_people AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    if((`document`.`isreapeted` > 0),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`,'/',`document`.`isreapeted`),concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`)) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    `people`.`city` AS `city`,
    `people`.`sex` AS `sex`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    (((`document`.`type` = 'people') or (`document`.`type` = 'visitors')) and (`document`.`status` <> 'archived') and (`document`.`status` <> 'removed')) 
  order by 
    `document`.`id` desc;

#
# Definition for the `document_work_state` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_work_state AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`num_prefix_2`,'-',`document`.`internal_number`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    `document`.`date_in` AS `date_in`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`status` AS `status`,
    `document`.`donestatus` AS `donestatus`,
    `document`.`impstatus` AS `impstatus`,
    `users`.`name` AS `name`,
    `state_types`.`text` AS `stateorgname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `state_types` on((`state_types`.`id` = `document`.`num_prefix_2`))) 
  where 
    ((`document`.`status` <> 'archived') and (`document`.`num_prefix_1` = 2) and (`document`.`status` <> 'removed'));

#
# Definition for the `document_work_visitors` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW document_work_visitors AS 
  select 
    `document`.`id` AS `id`,
    `document`.`num_prefix_1` AS `num_prefix_1`,
    `document`.`num_prefix_2` AS `num_prefix_2`,
    `document`.`internal_number` AS `internal_number`,
    concat(`document`.`num_prefix_1`,'-',`document`.`internal_number`,'/',`document`.`num_prefix_2`) AS `fullnum`,
    `document`.`external_number` AS `external_number`,
    `document`.`year` AS `year`,
    `document`.`datestamp` AS `datestamp`,
    `document`.`date_create` AS `date_create`,
    `document`.`date_control` AS `date_control`,
    date_format(`document`.`date_control`,'%d.%m.%Y') AS `date_control_text`,
    date_format(`document`.`date_in`,'%d.%m.%Y') AS `date_in_text`,
    `document`.`date_in` AS `date_in`,
    `document`.`topicstarter` AS `topicstarter`,
    `document`.`comment` AS `comment`,
    `document`.`summary` AS `summary`,
    `document`.`created_by` AS `created_by`,
    `document`.`type` AS `type`,
    `document`.`isreapeted` AS `isreapeted`,
    `document`.`status` AS `status`,
    `document`.`impstatus` AS `impstatus`,
    `document`.`donestatus` AS `donestatus`,
    `users`.`name` AS `name`,
    `people`.`id` AS `pid`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    `people`.`city` AS `city`,
    `people`.`sex` AS `sex`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `num_types_total`(`document`.`id`) AS `num_types` 
  from 
    ((`document` join `users` on((`users`.`id` = `document`.`created_by`))) join `people` on((`people`.`id` = `document`.`topicstarter`))) 
  where 
    ((`document`.`type` = 'visitors') and (`document`.`status` <> 'archived') and (`document`.`status` <> 'removed')) 
  order by 
    `document`.`id` desc;

#
# Definition for the `movings_view` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW movings_view AS 
  select 
    `movings`.`id` AS `id`,
    `movings`.`document` AS `document`,
    `movings`.`tmstamp` AS `tmstamp`,
    date_format(`movings`.`tmstamp`,'%d.%m.%Y') AS `dateformated`,
    `movings`.`prevuser` AS `prevuser`,
    `movings`.`nextuser` AS `nextuser`,
    `movings`.`movtext` AS `movtext`,
    (
  select 
    `users`.`name` 
  from 
    `users` 
  where 
    (`users`.`id` = `movings`.`prevuser`)) AS `prevusername`,(
  select 
    `users`.`name` 
  from 
    `users` 
  where 
    (`users`.`id` = `movings`.`nextuser`)) AS `nextusername` 
  from 
    `movings` 
  order by 
    `movings`.`tmstamp`;

#
# Definition for the `people_view` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW people_view AS 
  select 
    `people`.`id` AS `id`,
    `people`.`lastname` AS `lastname`,
    `people`.`firstname` AS `firstname`,
    `people`.`secondname` AS `secondname`,
    concat(`people`.`lastname`,' ',`people`.`firstname`,' ',`people`.`secondname`) AS `fullname`,
    `people`.`passport` AS `passport`,
    `people`.`zipcode` AS `zipcode`,
    `people`.`city` AS `city`,
    `people`.`street` AS `street`,
    `people`.`building` AS `building`,
    `people`.`housing` AS `housing`,
    `people`.`room` AS `room`,
    `people`.`sex` AS `sex`,
    `people`.`phones` AS `phones`,
    `people`.`comment` AS `comment` 
  from 
    `people`;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;