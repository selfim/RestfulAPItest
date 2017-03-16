/*
Navicat MySQL Data Transfer

Source Server         : bendi
Source Server Version : 50550
Source Host           : localhost:3306
Source Database       : apitest

Target Server Type    : MYSQL
Target Server Version : 50550
File Encoding         : 65001

Date: 2017-03-17 00:19:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `article_id` mediumint(10) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  `user_id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`article_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `createAt` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 'admin', '0');
INSERT INTO `user` VALUES ('2', 'admin1', '10c36e4ae076f5296c0cd60ee56a1736', '1489680716');
INSERT INTO `user` VALUES ('3', 'admin2', '380cb352df0dc0c9ec7958a06e99eeb4', '1489680740');
