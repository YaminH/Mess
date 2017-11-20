/*
Navicat MySQL Data Transfer

Source Server         : Han
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : mess

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-11-20 17:10:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `table_admin`
-- ----------------------------
DROP TABLE IF EXISTS `table_admin`;
CREATE TABLE `table_admin` (
  `admin_id` char(16) NOT NULL COMMENT '管理员ID',
  `admin_name` varchar(32) DEFAULT NULL COMMENT '管理员名称',
  `pass_word` varchar(32) DEFAULT NULL COMMENT '密码',
  `creat_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of table_admin
-- ----------------------------
INSERT INTO `table_admin` VALUES ('1', 'admin', 'admin', '2017-11-10 16:09:24');

-- ----------------------------
-- Table structure for `table_append_mess`
-- ----------------------------
DROP TABLE IF EXISTS `table_append_mess`;
CREATE TABLE `table_append_mess` (
  `ap_mess_id` char(16) NOT NULL COMMENT '附属留言ID',
  `ap_parent_id` char(16) NOT NULL COMMENT '宿主留言ID',
  `ap_mess_content` varchar(255) DEFAULT NULL COMMENT '留言内容',
  `ap_user_id` char(16) DEFAULT NULL COMMENT '留言者ID',
  `ap_user_name` varchar(32) DEFAULT NULL COMMENT '留言者名称',
  `creat_time` datetime DEFAULT NULL COMMENT '创建时间',
  `operate_time` datetime DEFAULT NULL COMMENT '操作时间',
  `audit` tinyint(1) DEFAULT NULL COMMENT '审核状态',
  PRIMARY KEY (`ap_mess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附属留言表';

-- ----------------------------
-- Records of table_append_mess
-- ----------------------------
INSERT INTO `table_append_mess` VALUES ('15108011361497', '15108009773845', 'hello world.', '124', 'han', '2017-11-16 10:58:56', '2017-11-16 10:58:56', '0');
INSERT INTO `table_append_mess` VALUES ('15109081564486', '15108076188535', 'hello', '124', 'han', '2017-11-17 16:42:36', '2017-11-17 16:42:36', '0');
INSERT INTO `table_append_mess` VALUES ('15109184536394', '15109184452142', 'hello', '124', 'han', '2017-11-17 19:34:13', '2017-11-17 19:34:13', '0');
INSERT INTO `table_append_mess` VALUES ('15111676053074', '15111667534310', 'world', '124', 'han', '2017-11-20 16:46:45', '2017-11-20 16:46:45', '0');
INSERT INTO `table_append_mess` VALUES ('15111687068236', '15111686968221', '1221', '124', 'han', '2017-11-20 17:05:06', '2017-11-20 17:05:06', '0');

-- ----------------------------
-- Table structure for `table_message`
-- ----------------------------
DROP TABLE IF EXISTS `table_message`;
CREATE TABLE `table_message` (
  `mess_id` char(16) NOT NULL COMMENT '留言ID',
  `mess_content` varchar(255) DEFAULT NULL COMMENT '留言内容',
  `user_id` char(16) DEFAULT NULL COMMENT '用户ID',
  `user_name` varchar(32) DEFAULT NULL COMMENT '用户名称',
  `creat_time` datetime DEFAULT NULL COMMENT '创建时间',
  `operate_time` datetime DEFAULT NULL COMMENT '操作时间',
  `audit` tinyint(1) DEFAULT NULL COMMENT '审核状态',
  PRIMARY KEY (`mess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';

-- ----------------------------
-- Records of table_message
-- ----------------------------
INSERT INTO `table_message` VALUES ('15106312903502', '下楼梯交钥匙给阿姨的时候，流眼泪了。', '124', 'han', '0000-00-00 00:00:00', '2017-11-16 16:12:02', '0');
INSERT INTO `table_message` VALUES ('15106436398955', '周杰伦把爱情比喻成龙卷风，我觉得特别贴切，因为很多人，像我，一辈子都没见过龙卷风。', '124', 'han', '2017-11-14 15:13:59', '2017-11-14 15:13:59', '0');
INSERT INTO `table_message` VALUES ('15107139131210', '阳光明媚，岁月静好！', '15108073014110', 'aa', '2017-11-15 10:45:13', '2017-11-15 10:45:13', '0');
INSERT INTO `table_message` VALUES ('15108076188535', 'I am B', '15108081254615', 'ee', '2017-11-16 12:46:58', '2017-11-16 12:46:58', '1');
INSERT INTO `table_message` VALUES ('15108232356000', 'alert(\"111\");', '124', 'han', '2017-11-16 17:07:15', '2017-11-16 17:07:15', '1');
INSERT INTO `table_message` VALUES ('15108236199934', 'alert(1116);', '124', 'han', '2017-11-16 17:13:39', '2017-11-16 17:13:39', '1');
INSERT INTO `table_message` VALUES ('15111472454560', '“你还记得她吗？”\n “早忘了，哈哈” \n “我还没说是谁。”', '15111470864167', '幻影如沫', '2017-11-20 11:07:25', '2017-11-20 11:07:25', '0');
INSERT INTO `table_message` VALUES ('15111645681450', 'hello 111', '124', 'han', '2017-11-20 15:56:08', '2017-11-20 15:59:48', '1');
INSERT INTO `table_message` VALUES ('15111664938905', '111', '124', 'han', '2017-11-20 16:28:13', '2017-11-20 16:28:13', null);
INSERT INTO `table_message` VALUES ('15111665479650', '1212', '124', 'han', '2017-11-20 16:29:07', '2017-11-20 16:29:07', null);
INSERT INTO `table_message` VALUES ('15111667534310', 'hello', '124', 'han', '2017-11-20 16:32:33', '2017-11-20 16:32:33', '0');

-- ----------------------------
-- Table structure for `table_user`
-- ----------------------------
DROP TABLE IF EXISTS `table_user`;
CREATE TABLE `table_user` (
  `user_id` char(16) NOT NULL COMMENT '用户ID',
  `user_name` varchar(32) DEFAULT NULL COMMENT '用户名称',
  `pass_word` varchar(32) DEFAULT NULL COMMENT '密码',
  `creat_time` datetime DEFAULT NULL COMMENT '创建时间',
  `audit` tinyint(1) DEFAULT NULL COMMENT '审核状态',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of table_user
-- ----------------------------
INSERT INTO `table_user` VALUES ('124', 'han', '83832391027a1f2f4d46ef882ff3a47d', '2017-11-14 14:09:19', '0');
INSERT INTO `table_user` VALUES ('15108073014110', 'aa', '4124bc0a9335c27f086f24ba207a4912', '2017-11-16 12:41:41', '0');
INSERT INTO `table_user` VALUES ('15108081254615', 'ee', '08a4415e9d594ff960030b921d42b91e', '2017-11-16 12:55:25', '0');
INSERT INTO `table_user` VALUES ('15111470864167', '幻影如沫', '3e6d409c02488c9353216dac650b57a9', '2017-11-20 11:04:46', '0');
INSERT INTO `table_user` VALUES ('15111687395077', '11', '6512bd43d9caa6e02c990b0a82652dca', '2017-11-20 17:05:39', '0');

-- ----------------------------
-- Table structure for `table_user_info`
-- ----------------------------
DROP TABLE IF EXISTS `table_user_info`;
CREATE TABLE `table_user_info` (
  `user_id` char(16) NOT NULL COMMENT '用户ID',
  `user_real_name` varchar(32) DEFAULT NULL COMMENT '真实姓名',
  `city` varchar(32) DEFAULT NULL COMMENT '城市',
  `school` varchar(32) DEFAULT NULL COMMENT '学校',
  `picture` varchar(64) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of table_user_info
-- ----------------------------
INSERT INTO `table_user_info` VALUES ('124', '11', '11', '11', 'u2.jpg');
INSERT INTO `table_user_info` VALUES ('15108073014110', 'aa', 'aa', 'aa', 'u3.jpg');
INSERT INTO `table_user_info` VALUES ('15108081254615', 'ee', 'ee', 'ee', '15108081079404.jpg');
INSERT INTO `table_user_info` VALUES ('15111470864167', '韩亚敏', '天津', '天津工业大学', '15111470304610.jpg');
INSERT INTO `table_user_info` VALUES ('15111682427417', '笑笑', '北京', '北京邮电大学', '15111681749229.jpg');
INSERT INTO `table_user_info` VALUES ('15111687395077', '11', '11', '11', '15111687322621.jpg');

-- ----------------------------
-- View structure for `view_table_user_info`
-- ----------------------------
DROP VIEW IF EXISTS `view_table_user_info`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_table_user_info` AS select `table_user_info`.`user_id` AS `user_id`,`table_user_info`.`user_real_name` AS `user_real_name`,`table_user_info`.`city` AS `city`,`table_user_info`.`school` AS `school`,`table_user_info`.`picture` AS `picture`,`table_user`.`pass_word` AS `pass_word`,`table_user`.`creat_time` AS `creat_time`,`table_user`.`audit` AS `audit` from (`table_user_info` left join `table_user` on((`table_user_info`.`user_id` = `table_user`.`user_id`))) ;
