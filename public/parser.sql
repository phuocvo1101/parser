/*
SQLyog Ultimate v9.10 
MySQL - 5.1.73 : Database - parser
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('admin','agent') COLLATE utf8_unicode_ci DEFAULT 'agent',
  `status` int(11) DEFAULT NULL,
  `created_day` int(11) DEFAULT NULL,
  `modified_day` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `accounts` */

insert  into `accounts`(`id`,`name`,`type`,`status`,`created_day`,`modified_day`) values (3,'admin','admin',1,235,23523),(6,'agent1','agent',1,1433716441,1433716441),(7,'agenttest','admin',0,1433858539,1433858539);

/*Table structure for table `pushnotifications` */

DROP TABLE IF EXISTS `pushnotifications`;

CREATE TABLE `pushnotifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `target` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pushnotifications` */

insert  into `pushnotifications`(`id`,`status`,`target`,`name`,`time`) values (1,1,'everyone','new message text',1433569623),(2,1,'abc','message channel',1433570448),(3,1,'everyone','message new',1433573074),(4,1,'everyone','message new1 11',1433573143),(5,1,'everyone','message new1 11',1433573273),(6,1,'everyone','test mesage new',2015),(7,1,'everyone','test mesage new',1433573929),(8,1,'everyone','test mesage new',1433573955),(9,1,'everyone','test mesage new',1433574006),(10,1,'everyone','test mesage new',1433574056),(11,1,'everyone','test mesage new',1433574460),(12,1,'everyone','test mesage new',1433574774),(13,1,'everyone','new message test11',1433575075),(14,1,'everyone','new message test11',1433575170),(15,1,'everyone','test',1433604271),(16,1,'everyone','Demo test message',1433776760),(17,1,'everyone','test message 1',1433780007);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `status` int(11) DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`email`,`fullname`,`account_id`,`status`,`group_id`) values (1,'admin','e10adc3949ba59abbe56e057f20f883e','abc@gmail.com','sdgsd',3,1,NULL),(2,'agent1','e10adc3949ba59abbe56e057f20f883e','agent1@gmail.com','agent1',6,1,NULL),(3,'quangtest','e10adc3949ba59abbe56e057f20f883e','abcde@gmail.com','sdsg',6,1,NULL),(4,'agenttest','e10adc3949ba59abbe56e057f20f883e','abc@gmail.com','sdgs',3,1,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
