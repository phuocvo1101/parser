/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.1.73 : Database - parser
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`parser` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `parser`;

/*Table structure for table `pushnotifications` */

DROP TABLE IF EXISTS `pushnotifications`;

CREATE TABLE `pushnotifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `target` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pushnotifications` */

insert  into `pushnotifications`(`id`,`status`,`target`,`name`,`time`) values (1,1,'everyone','new message text',1433569623),(2,1,'abc','message channel',1433570448),(3,1,'everyone','message new',1433573074),(4,1,'everyone','message new1 11',1433573143),(5,1,'everyone','message new1 11',1433573273),(6,1,'everyone','test mesage new',2015),(7,1,'everyone','test mesage new',1433573929),(8,1,'everyone','test mesage new',1433573955),(9,1,'everyone','test mesage new',1433574006),(10,1,'everyone','test mesage new',1433574056),(11,1,'everyone','test mesage new',1433574460),(12,1,'everyone','test mesage new',1433574774),(13,1,'everyone','new message test11',1433575075),(14,1,'everyone','new message test11',1433575170);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
