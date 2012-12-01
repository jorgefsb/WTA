/*
SQLyog Enterprise - MySQL GUI v8.02 RC
MySQL - 5.5.15-log : Database - joyeria
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`joyeria` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `joyeria`;

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` char(80) DEFAULT NULL,
  `category_public` tinyint(1) DEFAULT NULL,
  `category_description` char(200) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `category` */

LOCK TABLES `category` WRITE;

insert  into `category`(`category_id`,`category_name`,`category_public`,`category_description`) values (1,'nueva asasdad',0,'hola'),(2,'asdasd',1,'asdasdasdad'),(3,'asdasd',1,'asdasdasdasd');

UNLOCK TABLES;

/*Table structure for table `image` */

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_name` char(50) DEFAULT NULL,
  `image_id_table` int(11) DEFAULT NULL,
  `image_type` char(11) DEFAULT NULL,
  `image_description` char(200) DEFAULT NULL,
  PRIMARY KEY (`image_id`),
  KEY `FK_image_producto` (`image_id_table`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `image` */

LOCK TABLES `image` WRITE;

insert  into `image`(`image_id`,`image_name`,`image_id_table`,`image_type`,`image_description`) values (1,'1.jpg',6,'product',NULL),(2,'1.jpg',7,'product',NULL),(3,'1.jpg',8,'product',NULL),(4,'1.jpg',9,'product',NULL),(5,'1.jpg',10,'product',NULL);

UNLOCK TABLES;

/*Table structure for table `menber` */

DROP TABLE IF EXISTS `menber`;

CREATE TABLE `menber` (
  `menber_id` int(11) NOT NULL AUTO_INCREMENT,
  `menber_name` char(80) DEFAULT NULL,
  `menber_last_name` char(80) DEFAULT NULL,
  `menber_mail` char(80) DEFAULT NULL,
  `menber_password` char(50) DEFAULT NULL,
  `menber_link_confirm` text,
  `menber_id_confirm` char(50) DEFAULT NULL,
  `menber_create_date` datetime DEFAULT NULL,
  `menber_confirm_date` datetime DEFAULT NULL,
  `menber_active` tinyint(4) DEFAULT NULL,
  `menber_confirm` tinyint(4) DEFAULT NULL,
  `menber_avatar` char(50) DEFAULT NULL,
  `menbar_password_reset` char(10) DEFAULT NULL,
  PRIMARY KEY (`menber_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `menber` */

LOCK TABLES `menber` WRITE;

insert  into `menber`(`menber_id`,`menber_name`,`menber_last_name`,`menber_mail`,`menber_password`,`menber_link_confirm`,`menber_id_confirm`,`menber_create_date`,`menber_confirm_date`,`menber_active`,`menber_confirm`,`menber_avatar`,`menbar_password_reset`) values (7,'nazart','huaman','nazartj@gmail.com','474$$179$$101193d7181cc88340ae5b2b17bba8a1',NULL,'b27dac12be','2012-11-25 13:19:21',NULL,1,1,NULL,''),(8,'nazart','huaman','nazsaartj@gmail.com','339$$525$$a8f5f167f44f4964e6c998dee827110c',NULL,'578fcdcf86','2012-11-25 13:58:34',NULL,1,NULL,NULL,NULL),(9,'nazart','huaman','nazaasdasdrtj@gmail.com','629$$125$$289dff07669d7a23de0ef88d2f7129e7',NULL,'65426bf7fb','2012-11-25 14:03:35',NULL,1,NULL,NULL,NULL),(10,'nazart','huaman','nazaasdasdddrtj@gmail.com','507$$481$$4297f44b13955235245b2497399d7a93',NULL,'975ce3b7ca03ea0bb3ee31c039c623cf','2012-11-25 14:19:16',NULL,1,NULL,NULL,NULL),(11,'nazart','huaman','nazasdasdddrtj@gmail.com','219$$834$$4297f44b13955235245b2497399d7a93',NULL,'4812c15eda3c6046970ef16fddba6e12','2012-11-25 14:36:13',NULL,1,NULL,NULL,NULL),(12,'asdasd','huaman','saddddddnazartj@gmail.com','330$$3$$4297f44b13955235245b2497399d7a93',NULL,'28d662ea8bed47f15a89eddb530ff248','2012-11-25 14:39:06',NULL,1,NULL,NULL,NULL),(13,'nazart','huaman','nazassssartj@gmail.com','633$$614$$086952e914ac6091da73afe0b385726c',NULL,'9333ef760057ef565a1a4b3a9c193b85','2012-11-25 14:42:23',NULL,1,NULL,NULL,NULL),(14,'nazart','huaman','naasdzartj@gmail.com','404$$353$$4297f44b13955235245b2497399d7a93',NULL,'7a47f1e1eb26a4b28d994aa609b4506c','2012-11-25 14:45:39',NULL,1,1,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` char(80) DEFAULT NULL,
  `product_category` int(11) DEFAULT NULL,
  `product_description` text,
  `product_publish_date` datetime DEFAULT NULL,
  `product_price` float DEFAULT NULL,
  `product_in_stock` tinyint(4) DEFAULT NULL,
  `product_limited_quantity` tinyint(1) DEFAULT '0',
  `product_create_date` datetime DEFAULT NULL,
  `product_public` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `FK_product_category` (`product_category`),
  CONSTRAINT `FK_product_category` FOREIGN KEY (`product_category`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `product` */

LOCK TABLES `product` WRITE;

insert  into `product`(`product_id`,`product_name`,`product_category`,`product_description`,`product_publish_date`,`product_price`,`product_in_stock`,`product_limited_quantity`,`product_create_date`,`product_public`) values (1,'asdasd',2,'asdasdads',NULL,123,NULL,0,'2012-11-26 20:39:11',1),(2,'nazart jhonn bryam jara huaman',2,'asdasd',NULL,23234,NULL,1,'2012-11-27 00:14:58',1),(3,'nazart jhonn bryam jara huaman',2,'asdasd',NULL,23234,NULL,1,'2012-11-27 00:16:19',1),(4,'nazart jhonn bryam jara huaman',2,'asdasd',NULL,23234,NULL,1,'2012-11-27 00:21:33',1),(5,'asdasdsad',1,'asdasd',NULL,123,NULL,1,'2012-11-27 00:22:53',1),(6,'asdasdsad',1,'asdasd',NULL,123,NULL,1,'2012-11-27 00:24:03',1),(7,'asdasd',1,'asdasd',NULL,234,NULL,1,'2012-11-27 00:25:34',1),(8,'asdasd',1,'asdasd',NULL,2344,NULL,1,'2012-11-27 00:27:22',1),(9,'asdasd',1,'asdasd',NULL,23424,NULL,1,'2012-11-27 00:41:18',1),(10,'asdasd',1,'asdasd',NULL,100,NULL,1,'2012-11-27 00:43:17',1);

UNLOCK TABLES;

/*Table structure for table `menberautentificate` */

DROP TABLE IF EXISTS `menberautentificate`;

/*!50001 DROP VIEW IF EXISTS `menberautentificate` */;
/*!50001 DROP TABLE IF EXISTS `menberautentificate` */;

/*!50001 CREATE TABLE `menberautentificate` (
  `menber_id` int(11) NOT NULL DEFAULT '0',
  `menber_name` char(80) DEFAULT NULL,
  `menber_last_name` char(80) DEFAULT NULL,
  `menber_mail` char(80) DEFAULT NULL,
  `menber_password` char(50) DEFAULT NULL,
  `menber_link_confirm` text,
  `menber_id_confirm` char(50) DEFAULT NULL,
  `menber_create_date` datetime DEFAULT NULL,
  `menber_confirm_date` datetime DEFAULT NULL,
  `menber_active` tinyint(4) DEFAULT NULL,
  `menber_confirm` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 */;

/*View structure for view menberautentificate */

/*!50001 DROP TABLE IF EXISTS `menberautentificate` */;
/*!50001 DROP VIEW IF EXISTS `menberautentificate` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `menberautentificate` AS (select `menber`.`menber_id` AS `menber_id`,`menber`.`menber_name` AS `menber_name`,`menber`.`menber_last_name` AS `menber_last_name`,`menber`.`menber_mail` AS `menber_mail`,`menber`.`menber_password` AS `menber_password`,`menber`.`menber_link_confirm` AS `menber_link_confirm`,`menber`.`menber_id_confirm` AS `menber_id_confirm`,`menber`.`menber_create_date` AS `menber_create_date`,`menber`.`menber_confirm_date` AS `menber_confirm_date`,`menber`.`menber_active` AS `menber_active`,`menber`.`menber_confirm` AS `menber_confirm` from `menber` where ((`menber`.`menber_active` = 1) and (`menber`.`menber_confirm` = 1))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
