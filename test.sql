/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50715
Source Host           : 192.168.80.129:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50715
File Encoding         : 65001

Date: 2016-11-22 20:14:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for php_session
-- ----------------------------
DROP TABLE IF EXISTS `php_session`;
CREATE TABLE `php_session` (
  `id` char(32) NOT NULL,
  `data` longblob,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of php_session
-- ----------------------------
INSERT INTO `php_session` VALUES ('0d4m8ab5hnalgra4cod5co7cs7', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:33:54');
INSERT INTO `php_session` VALUES ('2rmpb685h8qipkjaboidsu5n20', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:29:38');
INSERT INTO `php_session` VALUES ('4q2s74h7nsjguu1hhos3i9aa95', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:33:34');
INSERT INTO `php_session` VALUES ('7ripugstgngop038938ur8hqt4', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:34:54');
INSERT INTO `php_session` VALUES ('87hrb6p1mvvsfa2286o9j5hnd3', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:29:40');
INSERT INTO `php_session` VALUES ('lu23qbbq1fstoc67878r8b9uc7', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:35:54');
INSERT INTO `php_session` VALUES ('r3vvisbp1p4jnt8ps3qn3fno76', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:35:08');
INSERT INTO `php_session` VALUES ('sm3ra5r6une8sh6g7ec1tj13h4', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:31:31');
INSERT INTO `php_session` VALUES ('turjuoqq9n5fds5ihtfl5vldr6', 0x74696D657374616D707C733A333A22646464223B, '2016-09-25 01:35:53');

-- ----------------------------
-- Table structure for poll_vote
-- ----------------------------
DROP TABLE IF EXISTS `poll_vote`;
CREATE TABLE `poll_vote` (
  `poll_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `candidate_id` int(10) unsigned NOT NULL,
  `vote_count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`poll_id`,`candidate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of poll_vote
-- ----------------------------
INSERT INTO `poll_vote` VALUES ('14', '3', '3');

-- ----------------------------
-- Table structure for poll_vote_like
-- ----------------------------
DROP TABLE IF EXISTS `poll_vote_like`;
CREATE TABLE `poll_vote_like` (
  `poll_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `candidate_id` int(10) unsigned NOT NULL,
  `vote_count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`poll_id`,`candidate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of poll_vote_like
-- ----------------------------

-- ----------------------------
-- Table structure for t
-- ----------------------------
DROP TABLE IF EXISTS `t`;
CREATE TABLE `t` (
  `i` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of t
-- ----------------------------
INSERT INTO `t` VALUES (0x31);
INSERT INTO `t` VALUES (0x32);

-- ----------------------------
-- Table structure for visual_map_page_sub
-- ----------------------------
DROP TABLE IF EXISTS `visual_map_page_sub`;
CREATE TABLE `visual_map_page_sub` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `map_project_id` int(11) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of visual_map_page_sub
-- ----------------------------
INSERT INTO `visual_map_page_sub` VALUES ('1', '1', 'http://192.168.40.1:8899/assets/img/22.png');
INSERT INTO `visual_map_page_sub` VALUES ('2', '1', 'http://192.168.40.1:8899/assets/img/33.png');

-- ----------------------------
-- Table structure for visual_map_page_sub_point
-- ----------------------------
DROP TABLE IF EXISTS `visual_map_page_sub_point`;
CREATE TABLE `visual_map_page_sub_point` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `map_page_sub_id` int(11) DEFAULT NULL,
  `point_x` int(255) DEFAULT NULL,
  `point_y` int(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of visual_map_page_sub_point
-- ----------------------------
INSERT INTO `visual_map_page_sub_point` VALUES ('1', '2', '1105', '154', null);
INSERT INTO `visual_map_page_sub_point` VALUES ('2', '2', '981', '55', null);

-- ----------------------------
-- Table structure for visual_map_point
-- ----------------------------
DROP TABLE IF EXISTS `visual_map_point`;
CREATE TABLE `visual_map_point` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `map_page_sub_point_id` int(11) DEFAULT '0',
  `type_name` varchar(255) DEFAULT NULL,
  `type_name_index` int(255) DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of visual_map_point
-- ----------------------------

-- ----------------------------
-- Table structure for visual_map_project
-- ----------------------------
DROP TABLE IF EXISTS `visual_map_project`;
CREATE TABLE `visual_map_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `project_name_index` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_name_index` (`project_name_index`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of visual_map_project
-- ----------------------------
INSERT INTO `visual_map_project` VALUES ('1', '首页', '1');
INSERT INTO `visual_map_project` VALUES ('2', '列表页', '1');
