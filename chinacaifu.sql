/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : chinacaifu

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2017-04-28 17:06:43
*/


create database chinacaifu;
use chinacaifu;
set names utf8;
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bank_info`
-- ----------------------------
DROP TABLE IF EXISTS `bank_info`;
CREATE TABLE `bank_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL,
  `bank_name` varchar(10) DEFAULT NULL,
  `bank_account` varchar(20) DEFAULT NULL,
  `hoder` varchar(10) DEFAULT NULL,
  `is_bankck` int(11) DEFAULT NULL,
  `ctime` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bank_info
-- ----------------------------

-- ----------------------------
-- Table structure for `borrowlist`
-- ----------------------------
DROP TABLE IF EXISTS `borrowlist`;
CREATE TABLE `borrowlist` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `bname` varchar(10) CHARACTER SET utf8 NOT NULL,
  `bamount` int(10) NOT NULL,
  `btime_limit` int(2) NOT NULL,
  `bmobile` varchar(11) CHARACTER SET utf8 NOT NULL,
  `bhousenum` int(2) NOT NULL,
  `bvalue` int(10) NOT NULL COMMENT '单位：W',
  `buse_way` varchar(10) CHARACTER SET utf8 NOT NULL,
  `bdisc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `btype` int(1) NOT NULL COMMENT '1 代表普通借款  2紧急借款',
  `status` int(1) NOT NULL COMMENT '1 待审核 2已通过 3已拒绝',
  `ctime` datetime NOT NULL,
  `aduittime` datetime DEFAULT NULL COMMENT '审核时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of borrowlist
-- ----------------------------
INSERT INTO `borrowlist` VALUES ('1', '周松', '10', '2', '13576354652', '1', '20', '短期个人消费', '买东西，买车', '1', '2', '2017-03-17 13:38:46', '2017-03-24 11:10:09');
INSERT INTO `borrowlist` VALUES ('2', '陈晓', '10', '2', '18779873531', '1', '20', '短期个人消费', '买东西，买车', '1', '1', '2017-03-23 13:38:50', null);
INSERT INTO `borrowlist` VALUES ('3', '熊大', '1', '11', '18779873532', '1', '1', '短期资金流转', '1', '1', '2', '2017-03-17 13:38:56', '2017-03-24 14:38:30');

-- ----------------------------
-- Table structure for `code`
-- ----------------------------
DROP TABLE IF EXISTS `code`;
CREATE TABLE `code` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `wid` int(1) DEFAULT NULL,
  `sign` varchar(10) DEFAULT NULL,
  `name` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of code
-- ----------------------------
INSERT INTO `code` VALUES ('1', '1', 'person', '投资人');
INSERT INTO `code` VALUES ('2', '2', 'person', '借款人/企业');
INSERT INTO `code` VALUES ('3', '3', 'perion', '机构');
INSERT INTO `code` VALUES ('4', '1', 'pay_way', '按月付息，到期返本');
INSERT INTO `code` VALUES ('5', '2', 'pay_way', '到期一次性还本付息');
INSERT INTO `code` VALUES ('6', '3', 'pay_way', '等额本息');
INSERT INTO `code` VALUES ('7', '1', 'invest', '募集中');
INSERT INTO `code` VALUES ('8', '2', 'invest', '募集完成');
INSERT INTO `code` VALUES ('9', '3', 'invest', '流标');

-- ----------------------------
-- Table structure for `guestbook`
-- ----------------------------
DROP TABLE IF EXISTS `guestbook`;
CREATE TABLE `guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invest_id` int(5) NOT NULL,
  `uid` int(5) NOT NULL,
  `mobile` char(11) CHARACTER SET utf8 NOT NULL,
  `content` varchar(200) CHARACTER SET utf8 NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of guestbook
-- ----------------------------
INSERT INTO `guestbook` VALUES ('1', '5', '0', '18779873531', '好评啊，这是好表啊', '1487691590');
INSERT INTO `guestbook` VALUES ('2', '4', '0', '18779873531', '555', '1487778874');
INSERT INTO `guestbook` VALUES ('3', '4', '0', '18779873531', '555', '1487778889');
INSERT INTO `guestbook` VALUES ('4', '4', '0', '18779873531', ' 是哦，不错', '1487778904');
INSERT INTO `guestbook` VALUES ('5', '1', '15', '18779873531', '赶紧投资很快的', '1489558783');

-- ----------------------------
-- Table structure for `invest`
-- ----------------------------
DROP TABLE IF EXISTS `invest`;
CREATE TABLE `invest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invest_name` varchar(10) NOT NULL,
  `invest_type` int(11) NOT NULL DEFAULT '1' COMMENT '1:企业贷2：个人消费贷 ',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1 募集中 2 待还款 3已还款 4流标',
  `amount` varchar(10) NOT NULL COMMENT '募集金额',
  `rate` varchar(5) NOT NULL COMMENT '利率',
  `progress` float(4,2) NOT NULL COMMENT '募集进度',
  `ye` int(20) NOT NULL COMMENT '余额',
  `borrower` varchar(20) NOT NULL COMMENT '借款人/企业',
  `manager` varchar(20) NOT NULL DEFAULT '中国财富' COMMENT '保障机构',
  `pay_way` varchar(10) NOT NULL COMMENT '支付方式，到期按本付息',
  `time_limit` int(5) NOT NULL COMMENT '标期',
  `invest_status` int(11) NOT NULL COMMENT '标状态',
  `pay_time` date NOT NULL COMMENT '还款时间',
  `bulid_time` date NOT NULL COMMENT '建立时间',
  `description` varchar(200) DEFAULT NULL COMMENT '项目介绍',
  `info1` varchar(50) DEFAULT NULL COMMENT '保障措施',
  `info2` varchar(50) DEFAULT NULL COMMENT '保障措施',
  `info3` varchar(50) DEFAULT NULL COMMENT '抵押物',
  `risk` varchar(200) DEFAULT NULL COMMENT '风控信息',
  `endtime` datetime DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of invest
-- ----------------------------
INSERT INTO `invest` VALUES ('1', '财富1号', '1', '1', '200000', '6%', '0.34', '132300', 'zs', '中国财富金融服务有限公司', '1', '2', '1', '2017-04-01', '2017-02-01', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('2', '财富2号', '1', '1', '100000', '6%', '0.03', '97177', 'ls', '中国财富金融服务有限公司', '1', '2', '1', '2017-04-03', '2017-02-03', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('3', '理财1号', '2', '1', '300000', '5% ', '0.01', '298000', 'zs', '中国财富金融服务有限公司', '1', '1', '1', '2016-12-10', '2016-11-10', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('4', '理财2号', '2', '1', '100000', '6%', '0.01', '98900', 'ww', '中国财富金融服务有限公司', '2', '1', '1', '2016-11-13', '2016-10-13', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('5', '起航1号', '3', '3', '300000', '7%', '1.00', '0', 'wl', '中国财富金融服务有限公司', '2', '1', '1', '2017-03-07', '2017-02-07', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('6', '起航2号', '3', '1', '100000', '6%', '0.00', '100000', 'zl', '中国财富金融服务有限公司', '2', '3', '1', '2017-05-08', '2017-02-08', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('7', '企业贷01号（一期）', '3', '1', '200000', '8%', '0.00', '200000', 'sl', '中国财富金融服务有限公司', '3', '4', '1', '2017-03-14', '2017-02-14', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('8', '幸运1号', '1', '1', '1000', '6%', '0.30', '700', 'jj', '中国财富', '2', '1', '1', '2017-02-15', '2017-03-15', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('9', '幸运2号', '1', '1', '1000', '6%', '0.00', '1000', 'jj', '中国财富', '1', '1', '1', '2017-02-15', '2017-03-15', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('10', '牛牛贷1号', '1', '1', '1000', '6%', '0.00', '1000', '', '中国财富', '1', '1', '0', '2017-04-15', '2017-03-15', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('11', '测试1', '1', '1', '50000', '2%', '0.00', '50000', '', '中国财富', '1', '1', '0', '2017-04-20', '0000-00-00', null, null, null, null, null, null);
INSERT INTO `invest` VALUES ('12', '助力企业贷1号', '1', '1', '200000', '6%', '0.00', '200000', '', '中国财富', '1', '2', '0', '2017-06-22', '0000-00-00', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for `invest_record`
-- ----------------------------
DROP TABLE IF EXISTS `invest_record`;
CREATE TABLE `invest_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invest_id` int(11) NOT NULL COMMENT '标的id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `mobile` char(11) DEFAULT NULL,
  `name` varchar(10) NOT NULL,
  `invest_amount` varchar(10) DEFAULT NULL,
  `lixi` varchar(10) NOT NULL COMMENT '预期利息',
  `get_amount` varchar(10) NOT NULL,
  `rate` varchar(10) NOT NULL,
  `is_ticket` int(11) DEFAULT NULL COMMENT '1 用券 2未用券',
  `status` int(1) NOT NULL COMMENT '1待回款 2已回款 ',
  `ticket_id` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invest_id` (`invest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of invest_record
-- ----------------------------
INSERT INTO `invest_record` VALUES ('3', '5', '11', '18779873531', '', '100', '1', '101', '6%', null, '2', '0', '1487425180');
INSERT INTO `invest_record` VALUES ('4', '5', '11', '18779873531', '', '100', '1', '101', '6%', null, '2', '0', '1487425344');
INSERT INTO `invest_record` VALUES ('5', '5', '11', '18779873531', '', '100', '1', '101', '6%', null, '2', '0', '1487425721');
INSERT INTO `invest_record` VALUES ('6', '5', '11', '18779873531', '', '100', '1', '101', '6%', null, '2', '0', '1487425799');
INSERT INTO `invest_record` VALUES ('7', '5', '11', '18779873531', '', '3000', '1', '3012', '7%', null, '2', '0', '1487425816');
INSERT INTO `invest_record` VALUES ('10', '5', '11', '18779873531', '', '1000', '1', '1007', '7%', null, '2', '0', '1487487685');
INSERT INTO `invest_record` VALUES ('11', '5', '11', '18779873531', '', '1000', '1', '1007', '7%', null, '2', '0', '1487487854');
INSERT INTO `invest_record` VALUES ('12', '5', '11', '18779873531', '', '10000', '1', '10070', '7%', null, '2', '0', '1487488243');
INSERT INTO `invest_record` VALUES ('13', '5', '11', '18779873531', '', '1000', '1', '1007', '7%', null, '2', '0', '1487488251');
INSERT INTO `invest_record` VALUES ('14', '5', '11', '18779873531', '', '1000', '1', '1007', '7%', null, '2', '0', '1487488335');
INSERT INTO `invest_record` VALUES ('15', '5', '11', '18779873531', '', '100', '1', '101', '7%', null, '2', '0', '1487488355');
INSERT INTO `invest_record` VALUES ('16', '5', '11', '18779873531', '', '100', '1', '101', '7%', null, '2', '0', '1487488373');
INSERT INTO `invest_record` VALUES ('17', '5', '11', '18779873531', '', '1000', '1', '1007', '7%', null, '2', '0', '1487488531');
INSERT INTO `invest_record` VALUES ('18', '5', '11', '18779873531', '', '1000', '1', '1007', '7%', null, '2', '0', '1487488558');
INSERT INTO `invest_record` VALUES ('19', '5', '11', '18779873531', '', '100', '1', '101', '6%', null, '2', '0', '1487517181');
INSERT INTO `invest_record` VALUES ('20', '5', '11', '18779873531', '', '250000', '1', '252300', '7%', null, '2', '0', '1487517191');
INSERT INTO `invest_record` VALUES ('21', '5', '11', '18779873531', '', '3658', '1', '3675', '7%', null, '2', '0', '1487517199');
INSERT INTO `invest_record` VALUES ('22', '5', '11', '18779873531', '', '25000', '1', '25240', '7%', null, '2', '0', '1487517207');
INSERT INTO `invest_record` VALUES ('23', '5', '11', '18779873531', '', '7042', '1', '7110', '7%', null, '2', '0', '1487517316');
INSERT INTO `invest_record` VALUES ('24', '4', '11', '18779873531', '', '100', '1', '101', '6%', null, '1', '0', '1487780398');
INSERT INTO `invest_record` VALUES ('25', '4', '11', '18779873531', '', '1000', '1', '1007', '6%', null, '1', '0', '1487780409');
INSERT INTO `invest_record` VALUES ('27', '1', '11', '18779873531', '', '1300', '1', '1310', '6%', null, '1', '0', '1488464503');
INSERT INTO `invest_record` VALUES ('31', '1', '11', '18779873531', '', '1300', '1', '1310', '6%', null, '1', '0', '1488465317');
INSERT INTO `invest_record` VALUES ('32', '1', '11', '18779873531', '', '1300', '1', '1310', '6%', null, '1', '0', '1488465357');
INSERT INTO `invest_record` VALUES ('33', '1', '11', '18779873531', '', '1500', '1', '1513', '6%', null, '1', '0', '1488465553');
INSERT INTO `invest_record` VALUES ('34', '1', '11', '18779873531', '', '1300', '1', '1310', '6%', null, '1', '0', '1488465594');
INSERT INTO `invest_record` VALUES ('35', '1', '11', '18779873531', '', '12000', '1', '12120', '6%', null, '1', '0', '1488465637');
INSERT INTO `invest_record` VALUES ('36', '1', '11', '18779873531', '', '10000', '1', '10100', '6%', null, '1', '0', '1488468947');
INSERT INTO `invest_record` VALUES ('37', '1', '11', '18779873531', '', '10000', '1', '10100', '6%', null, '1', '0', '1488469017');
INSERT INTO `invest_record` VALUES ('38', '1', '11', '18779873531', '', '10000', '1', '10100', '6%', null, '1', '0', '1488469094');
INSERT INTO `invest_record` VALUES ('39', '1', '11', '18779873531', '', '10000', '1', '10000', '6%', null, '1', '2', '1488469227');
INSERT INTO `invest_record` VALUES ('40', '1', '11', '18779873531', '', '10000', '1', '10000', '6%', null, '1', '2', '1488469287');
INSERT INTO `invest_record` VALUES ('41', '1', '11', '18779873531', '', '10000', '1', '10110', '6%', '1', '1', '2', '1488469479');
INSERT INTO `invest_record` VALUES ('42', '1', '11', '18779873531', '', '1300', '1', '1300', '6%', null, '1', '0', '1488469782');
INSERT INTO `invest_record` VALUES ('43', '1', '11', '18779873531', '', '1000', '1', '1010', '6%', null, '1', '0', '1488469819');
INSERT INTO `invest_record` VALUES ('44', '1', '11', '18779873531', '', '1000', '1', '1010', '6%', null, '1', '0', '1488470492');
INSERT INTO `invest_record` VALUES ('67', '1', '11', '18779873531', '', '1300', '1', '1313', '6%', '1', '1', '2', '1488546125');
INSERT INTO `invest_record` VALUES ('68', '1', '11', '18779873531', '', '110', '1', '101', '6%', null, '1', '0', '1488546217');
INSERT INTO `invest_record` VALUES ('69', '1', '12', '18779873532', '', '1300', '1', '1013', '6%', '1', '1', '0', '1488547371');
INSERT INTO `invest_record` VALUES ('70', '1', '11', '18779873531', '', '200', '1', '202', '6%', null, '1', '0', '1488550642');
INSERT INTO `invest_record` VALUES ('71', '1', '11', '18779873531', '', '100', '1', '101', '6%', null, '1', '0', '1488551326');
INSERT INTO `invest_record` VALUES ('72', '2', '11', '18779873531', '', '1000', '1', '1007', '6%', null, '1', '0', '1489478924');
INSERT INTO `invest_record` VALUES ('73', '1', '15', '18779873531', '', '100', '1', '101', '6%', null, '1', '0', '1489558763');
INSERT INTO `invest_record` VALUES ('74', '1', '15', '18779873531', '', '100', '1', '101', '6%', null, '1', '0', '1489559001');
INSERT INTO `invest_record` VALUES ('76', '1', '15', '18779873531', '', '1000', '1', '1007', '6%', null, '1', '0', '1489561189');
INSERT INTO `invest_record` VALUES ('77', '3', '15', '18779873535', '', '2000', '8.33333333', '2008.33333', '5% ', '1', '1', '0', '1490339120');
INSERT INTO `invest_record` VALUES ('78', '2', '15', '18779873535', '', '1523', '15.23', '1538.23', '6%', '1', '1', '0', '1490339597');
INSERT INTO `invest_record` VALUES ('79', '2', '15', '18779873535', '', '400', '4', '404', '6%', null, '1', '0', '1490341841');
INSERT INTO `invest_record` VALUES ('82', '2', '15', '18779873535', '', '300', '3', '303', '6%', null, '1', '0', '1490342090');
INSERT INTO `invest_record` VALUES ('83', '2', '15', '18779873535', '', '500', '5', '505', '6%', null, '1', '0', '1490342168');
INSERT INTO `invest_record` VALUES ('84', '8', '15', '18779873535', '', '100', '0.5', '100.5', '6%', null, '1', '0', '1490343032');
INSERT INTO `invest_record` VALUES ('85', '8', '15', '18779873535', '', '100', '0.5', '100.5', '6%', null, '1', '0', '1490343036');
INSERT INTO `invest_record` VALUES ('86', '8', '15', '18779873535', '', '100', '0.5', '100.5', '6%', null, '1', '0', '1490343042');

-- ----------------------------
-- Table structure for `money_record`
-- ----------------------------
DROP TABLE IF EXISTS `money_record`;
CREATE TABLE `money_record` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `uid` int(5) NOT NULL,
  `money` int(10) NOT NULL,
  `type` varchar(2) CHARACTER SET utf8 NOT NULL,
  `ye` int(10) NOT NULL,
  `remark` varchar(10) CHARACTER SET utf8 NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of money_record
-- ----------------------------
INSERT INTO `money_record` VALUES ('1', '11', '1000', '充值', '1000', '充值，很快到账', '1487943214');
INSERT INTO `money_record` VALUES ('2', '11', '-100', '投资', '900', '投资1号表', '1487943222');
INSERT INTO `money_record` VALUES ('3', '11', '60', '充值', '960', '投资券变现', '1488546125');
INSERT INTO `money_record` VALUES ('8', '12', '60', '充值', '60', '投资券变现', '1488547371');
INSERT INTO `money_record` VALUES ('9', '10', '60', '充值', '60', '投资券变现', '1490339120');
INSERT INTO `money_record` VALUES ('10', '15', '20', '充值', '20', '投资券变现', '1490339597');
INSERT INTO `money_record` VALUES ('11', '15', '-400', '投资', '-400', '投资财富2号,400', '1490341841');
INSERT INTO `money_record` VALUES ('12', '15', '-300', '投资', '-300', '投资财富2号,300', '1490342090');
INSERT INTO `money_record` VALUES ('13', '15', '-500', '投资', '300', '投资财富2号,500', '1490342168');
INSERT INTO `money_record` VALUES ('14', '15', '-100', '投资', '200', '投资幸运1号,100', '1490343032');
INSERT INTO `money_record` VALUES ('15', '15', '-100', '投资', '100', '投资幸运1号,100', '1490343036');
INSERT INTO `money_record` VALUES ('16', '15', '-100', '投资', '0', '投资幸运1号,100', '1490343042');

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title` varchar(30) CHARACTER SET utf8 NOT NULL,
  `content` varchar(70) CHARACTER SET utf8 NOT NULL,
  `status` enum('1','2') CHARACTER SET utf8 NOT NULL COMMENT '1 已读 2未读',
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('1', '1', '', '1', '2', '2017-02-26 22:48:08.000000');
INSERT INTO `news` VALUES ('2', '11', '2017-02-26修改了支付密码', '尊敬的18779873531,你于2017-02-26 22:34:51 修改了支付密码，如不是你本', '2', '2017-02-26 22:34:51.000000');
INSERT INTO `news` VALUES ('3', '11', '2017-02-26修改了登录密码', '尊敬的18779873531,您于2017-02-26 22:45:54 修改了登录密码，如不是您本', '2', '2017-02-26 22:45:54.000000');
INSERT INTO `news` VALUES ('4', '11', '2017-03-03 22:22:17投', '尊敬的18779873531,您于2017-03-03 22:22:17 投资了一笔200元，如不是您本人操作，请联系客服！', '2', '2017-03-03 22:22:17.000000');
INSERT INTO `news` VALUES ('5', '11', '2017-03-03 22:46:28投资了100元', '尊敬的18779873531,您于2017-03-03 22:46:28 投资了一笔100元，如不是您本人操作，请联系客服！', '2', '2017-03-03 22:46:28.000000');
INSERT INTO `news` VALUES ('6', '11', '2017-03-14 16:44:08投资了1000元', '尊敬的18779873531,您于2017-03-14 16:44:08 投资了一笔1000元，如不是您本人操作，请联系客服！', '2', '2017-03-14 16:44:08.000000');
INSERT INTO `news` VALUES ('7', '15', '2017-03-15 14:23:19投资了100元', '尊敬的18779873531,您于2017-03-15 14:23:19 投资了一笔100元，如不是您本人操作，请联系客服！', '2', '2017-03-15 14:23:19.000000');
INSERT INTO `news` VALUES ('8', '15', '2017-03-15 14:21:23投资了100元', '尊敬的18779873531,您于2017-03-15 14:21:23 投资了一笔100元，如不是您本人操作，请联系客服！', '2', '2017-03-15 14:21:23.000000');
INSERT INTO `news` VALUES ('10', '15', '2017-03-15 14:49:59投资了1000元', '尊敬的18779873531,您于2017-03-15 14:49:59 投资了一笔1000元，如不是您本人操作，请联系客服！', '2', '2017-03-15 14:49:59.000000');
INSERT INTO `news` VALUES ('11', '12', '2017-03-17 11:31:04修改了密码', '尊敬的18779873532,您于2017-03-17 11:31:04 修改了登录密码，如不是您本人操作，请联系客服！', '2', '2017-03-17 11:31:04.000000');
INSERT INTO `news` VALUES ('12', '12', '2017-03-17 11:31:05修改了支付密码', '尊敬的18779873532,您于2017-03-17 11:31:05 修改了支付密码，如不是您本人操作，请联系客服！', '2', '2017-03-17 11:31:05.000000');
INSERT INTO `news` VALUES ('13', '21', '2017-03-23 11:56:03修改了密码', '尊敬的18779593522,您于2017-03-23 11:56:03 修改了登录密码，如不是您本人操作，请联系客服！', '2', '2017-03-23 11:56:03.000000');
INSERT INTO `news` VALUES ('14', '21', '2017-03-23 11:18:05修改了支付密码', '尊敬的18779593522,您于2017-03-23 11:18:05 修改了支付密码，如不是您本人操作，请联系客服！', '2', '2017-03-23 11:18:05.000000');
INSERT INTO `news` VALUES ('15', '21', '2017-03-23 11:02:10修改了密码', '尊敬的18779593522,您于2017-03-23 11:02:10 修改了登录密码，如不是您本人操作，请联系客服！', '2', '2017-03-23 11:02:10.000000');
INSERT INTO `news` VALUES ('16', '15', '2017-03-24 15:20:05投资了2000元', '尊敬的18779873535,您于2017-03-24 15:20:05 投资了一笔2000元，如不是您本人操作，请联系客服！', '2', '2017-03-24 15:20:05.000000');
INSERT INTO `news` VALUES ('17', '15', '2017-03-24 15:17:13投资了1523元', '尊敬的18779873535,您于2017-03-24 15:17:13 投资了一笔1523元，如不是您本人操作，请联系客服！', '2', '2017-03-24 15:17:13.000000');
INSERT INTO `news` VALUES ('18', '15', '2017-03-24 15:41:50投资了400元', '尊敬的18779873535,您于2017-03-24 15:41:50 投资了一笔400元，如不是您本人操作，请联系客服！', '2', '2017-03-24 15:41:50.000000');
INSERT INTO `news` VALUES ('19', '15', '2017-03-24 15:50:54投资了300元', '尊敬的18779873535,您于2017-03-24 15:50:54 投资了一笔300元，如不是您本人操作，请联系客服！', '2', '2017-03-24 15:50:54.000000');
INSERT INTO `news` VALUES ('20', '15', '2017-03-24 15:08:56投资了500元', '尊敬的18779873535,您于2017-03-24 15:08:56 投资了一笔500元，如不是您本人操作，请联系客服！', '2', '2017-03-24 15:08:56.000000');
INSERT INTO `news` VALUES ('21', '15', '2017-03-24 16:32:10投资了100元', '尊敬的18779873535,您于2017-03-24 16:32:10 投资了一笔100元，如不是您本人操作，请联系客服！', '2', '2017-03-24 16:32:10.000000');
INSERT INTO `news` VALUES ('22', '15', '2017-03-24 16:36:10投资了100元', '尊敬的18779873535,您于2017-03-24 16:36:10 投资了一笔100元，如不是您本人操作，请联系客服！', '2', '2017-03-24 16:36:10.000000');
INSERT INTO `news` VALUES ('23', '15', '2017-03-24 16:42:10投资了100元', '尊敬的18779873535,您于2017-03-24 16:42:10 投资了一笔100元，如不是您本人操作，请联系客服！', '2', '2017-03-24 16:42:10.000000');

-- ----------------------------
-- Table structure for `payway`
-- ----------------------------
DROP TABLE IF EXISTS `payway`;
CREATE TABLE `payway` (
  `payway_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '类型，1普通，2银行',
  `pay_tag` varchar(16) NOT NULL DEFAULT '' COMMENT '英文支付标签，唯一',
  `pay_name` varchar(32) NOT NULL DEFAULT '' COMMENT '支付名称 中文名',
  `pay_logo` varchar(128) NOT NULL DEFAULT '' COMMENT '支付LOGO',
  `pay_config` text COMMENT '支付配置信息',
  `pay_desc` text COMMENT '支付说明',
  `isuse` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否启用，1是，0否',
  PRIMARY KEY (`payway_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='支付方式表';

-- ----------------------------
-- Records of payway
-- ----------------------------
INSERT INTO `payway` VALUES ('1', '1', 'wxpay', '微信支付', '', 'a:4:{s:5:\"appid\";s:18:\"wx0ce15601ca221f39\";s:6:\"mch_id\";s:10:\"1272270201\";s:3:\"key\";s:32:\"JK8G539VB6V8YQ1N8M7D6j4r6c3l0i7p\";s:9:\"appsecret\";s:32:\"4e0a38f2ea4b5dc17305ee8488056a20\";}', '微信支付', '1');

-- ----------------------------
-- Table structure for `promotion`
-- ----------------------------
DROP TABLE IF EXISTS `promotion`;
CREATE TABLE `promotion` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(5) CHARACTER SET utf8 DEFAULT NULL COMMENT '注册送券',
  `start_date` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 正常 2 暂停',
  `amount` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of promotion
-- ----------------------------
INSERT INTO `promotion` VALUES ('1', '注册送券', '1477126302', '1', '60');
INSERT INTO `promotion` VALUES ('2', '注册送券', '1477126302', '1', '20');

-- ----------------------------
-- Table structure for `queue`
-- ----------------------------
DROP TABLE IF EXISTS `queue`;
CREATE TABLE `queue` (
  `queue_id` int(2) NOT NULL DEFAULT '0',
  `email` varchar(40) NOT NULL,
  `ctime` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of queue
-- ----------------------------

-- ----------------------------
-- Table structure for `sale_channel`
-- ----------------------------
DROP TABLE IF EXISTS `sale_channel`;
CREATE TABLE `sale_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `rate` varchar(10) DEFAULT NULL,
  `amount` varchar(15) DEFAULT NULL,
  `comm` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sale_channel
-- ----------------------------

-- ----------------------------
-- Table structure for `sms`
-- ----------------------------
DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL,
  `email` varchar(20) NOT NULL,
  `content` varchar(60) NOT NULL,
  `code` char(6) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 找回密码',
  `status` int(11) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sms
-- ----------------------------
INSERT INTO `sms` VALUES ('1', '', '958243544@qq.com', '您本次验证码为：', '8528', '1', '1', '2017-02-15 00:00:00');
INSERT INTO `sms` VALUES ('2', '', '958243544@qq.com', '您本次验证码为：474662。（中国财富理财平台）', '4746', '1', '1', '2017-03-13 17:00:00');
INSERT INTO `sms` VALUES ('8', '', '958243544@qq.com', '您本次注册操作验证码为：639256。（中国财富理财平台）', '639256', '2', '1', '2017-02-14 22:59:59');
INSERT INTO `sms` VALUES ('9', '', '958243544@qq.com', '您本次注册操作验证码为：110030。（中国财富理财平台）', '110030', '2', '1', '2017-03-13 14:08:58');
INSERT INTO `sms` VALUES ('10', '', '958243544@qq.com', '您本次注册操作验证码为：315375。（中国财富理财平台）', '315375', '2', '1', '2017-03-15 14:07:33');
INSERT INTO `sms` VALUES ('11', '', '958243544@qq.com', '您本次注册操作验证码为：824159。（中国财富理财平台）', '824159', '2', '1', '2017-03-17 11:09:13');
INSERT INTO `sms` VALUES ('12', '15870673130', '', '', '', '0', '0', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `sms_log`
-- ----------------------------
DROP TABLE IF EXISTS `sms_log`;
CREATE TABLE `sms_log` (
  `sms_log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `send_mobile_list` text COMMENT '发送手机号列表，英文逗号隔开',
  `send_text` varchar(255) NOT NULL DEFAULT '' COMMENT '发送内容',
  `sms_send_time` int(11) NOT NULL DEFAULT '0' COMMENT '短信发送时间',
  `sms_send_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发送状态，1成功，0失败',
  PRIMARY KEY (`sms_log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='短信发送日志表';

-- ----------------------------
-- Records of sms_log
-- ----------------------------
INSERT INTO `sms_log` VALUES ('1', '15870673130', '验证码为【527941】', '1492152104', '-3');
INSERT INTO `sms_log` VALUES ('2', '15870673130', '验证码为【361203】', '1492152253', '1');
INSERT INTO `sms_log` VALUES ('3', '15870673130', '验证码为【946497】', '1492152555', '-2');
INSERT INTO `sms_log` VALUES ('4', '15870673130', '验证码为【003109】', '1492159623', '-2');
INSERT INTO `sms_log` VALUES ('5', '15870673130', '验证码为【084901】', '1492159776', '-2');
INSERT INTO `sms_log` VALUES ('6', '15870673130', '验证码为【196197】', '1492160104', '-2');
INSERT INTO `sms_log` VALUES ('7', '15870673130', '验证码为【109846】', '1492179170', '-2');
INSERT INTO `sms_log` VALUES ('8', '15870673130', '验证码为【049798】', '1492395086', '-2');
INSERT INTO `sms_log` VALUES ('9', '15870673130', '验证码为【736858】', '1492395202', '-2');
INSERT INTO `sms_log` VALUES ('10', '15870673130', '验证码为【986638】', '1492395355', '-2');
INSERT INTO `sms_log` VALUES ('11', '15870673130', '验证码为【821414】', '1492400088', '-2');
INSERT INTO `sms_log` VALUES ('12', '15870673130', '验证码为【938618】', '1492400184', '-2');
INSERT INTO `sms_log` VALUES ('13', '15870673130', '验证码为【069758】', '1492400268', '-2');
INSERT INTO `sms_log` VALUES ('14', '15870673130', '验证码为【605922】', '1492400349', '-2');
INSERT INTO `sms_log` VALUES ('15', '15870673130', '验证码为【843550】', '1492400754', '-2');
INSERT INTO `sms_log` VALUES ('16', '15870673130', '验证码为【754202】', '1492400833', '-2');
INSERT INTO `sms_log` VALUES ('17', '15870673130', '验证码为【670200】', '1492406527', '-2');
INSERT INTO `sms_log` VALUES ('18', '15870673130', '验证码为【477803】', '1492408096', '-2');
INSERT INTO `sms_log` VALUES ('19', '15870673130', '验证码为【553059】', '1492408203', '-2');
INSERT INTO `sms_log` VALUES ('20', '15870673130', '验证码为【345456】', '1492408603', '-2');
INSERT INTO `sms_log` VALUES ('21', '15870673130', '验证码为【798286】', '1492408796', '-2');
INSERT INTO `sms_log` VALUES ('22', '15870673130', '验证码为【740416】', '1492408862', '-2');
INSERT INTO `sms_log` VALUES ('23', '15870673130', '验证码为【276033】', '1492409730', '-2');
INSERT INTO `sms_log` VALUES ('24', '15870673130', '验证码为【023503】', '1492409985', '-2');
INSERT INTO `sms_log` VALUES ('25', '15870673130', '验证码为【333918】', '1492410901', '-2');
INSERT INTO `sms_log` VALUES ('26', '15870673130', '验证码为【617174】', '1492411097', '-2');
INSERT INTO `sms_log` VALUES ('27', '15870673130', '验证码为【132934】', '1492411298', '-2');
INSERT INTO `sms_log` VALUES ('28', '15870673130', '验证码为【032867】', '1492411357', '-2');

-- ----------------------------
-- Table structure for `sms_set`
-- ----------------------------
DROP TABLE IF EXISTS `sms_set`;
CREATE TABLE `sms_set` (
  `sms_set_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `send_name` varchar(32) NOT NULL DEFAULT '' COMMENT '发送标记，英文唯一',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否使用，1是，0否',
  `to_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否抄送管理员，是1，否0，默认0',
  `sms_text` varchar(255) NOT NULL DEFAULT '' COMMENT '短信内容',
  `default_sms_text` varchar(255) NOT NULL COMMENT '短信内容的系统默认模板',
  PRIMARY KEY (`sms_set_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sms_set
-- ----------------------------
INSERT INTO `sms_set` VALUES ('1', 'verify_code', '1', '0', '验证码为【#verify_code#】', '验证码为【#verify_code#】');

-- ----------------------------
-- Table structure for `ticket`
-- ----------------------------
DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(10) NOT NULL,
  `amount` int(11) NOT NULL,
  `mininvest` int(10) NOT NULL COMMENT '最小投资额可用',
  `user_id` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '1 未使用 2已使用 ',
  `invest_id` int(11) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ticket
-- ----------------------------
INSERT INTO `ticket` VALUES ('2', '注册送券', '60', '1200', '11', '2', '0', '1487338661', '1498200000');
INSERT INTO `ticket` VALUES ('3', '注册送券', '20', '400', '11', '1', '0', '1487338661', '1487950616');
INSERT INTO `ticket` VALUES ('4', '注册送券', '60', '1200', '12', '2', '0', '1488029344', '1498200000');
INSERT INTO `ticket` VALUES ('5', '注册送券', '20', '400', '12', '1', '0', '1488029344', '1493213348');
INSERT INTO `ticket` VALUES ('6', '注册送券', '60', '1200', '15', '2', '0', '1489558175', '1494742175');
INSERT INTO `ticket` VALUES ('7', '注册送券', '20', '400', '15', '2', '0', '1489558175', '1494742175');
INSERT INTO `ticket` VALUES ('8', '注册送券', '60', '1200', '16', '1', '0', '1489721408', '1494905409');
INSERT INTO `ticket` VALUES ('9', '注册送券', '20', '400', '16', '1', '0', '1489721408', '1494905409');
INSERT INTO `ticket` VALUES ('10', '注册送券', '60', '1200', '17', '1', '0', '1490236315', '1495420316');
INSERT INTO `ticket` VALUES ('11', '注册送券', '20', '400', '17', '1', '0', '1490236315', '1495420316');
INSERT INTO `ticket` VALUES ('12', '注册送券', '60', '1200', '18', '1', '0', '1490236395', '1495420395');
INSERT INTO `ticket` VALUES ('13', '注册送券', '20', '400', '18', '1', '0', '1490236395', '1495420395');
INSERT INTO `ticket` VALUES ('14', '注册送券', '60', '1200', '19', '1', '0', '1490236513', '1495420513');
INSERT INTO `ticket` VALUES ('15', '注册送券', '20', '400', '19', '1', '0', '1490236513', '1495420513');
INSERT INTO `ticket` VALUES ('16', '注册送券', '60', '1200', '20', '1', '0', '1490236549', '1495420549');
INSERT INTO `ticket` VALUES ('17', '注册送券', '20', '400', '20', '1', '0', '1490236549', '1495420549');
INSERT INTO `ticket` VALUES ('18', '注册送券', '60', '1200', '21', '1', '0', '1490237540', '1495421540');
INSERT INTO `ticket` VALUES ('19', '注册送券', '20', '400', '21', '1', '0', '1490237540', '1495421540');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(500) NOT NULL,
  `pay_password` varchar(500) NOT NULL,
  `is_invest` int(1) NOT NULL DEFAULT '2' COMMENT '2 代表未投资 1代表已投资',
  `invest_money` float(10,2) NOT NULL DEFAULT '0.00',
  `freeze_money` float(10,2) NOT NULL DEFAULT '0.00',
  `ye` float(10,2) NOT NULL DEFAULT '5000.00' COMMENT '余额（初始，默认每个用户5K）',
  `type` int(11) DEFAULT NULL COMMENT '1 投资者 2借款人 3机构/运营人员',
  `name` varchar(10) DEFAULT NULL,
  `person_id` varchar(20) DEFAULT NULL,
  `visit_code` varchar(5) NOT NULL,
  `is_ck` int(11) DEFAULT NULL,
  `from_code` varchar(10) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1 代表正常  2冻结（不可登录）',
  `ctime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('11', '18779873531', '958243544@qq.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '1', '391210.00', '0.00', '1162.00', '1', null, null, '8888', null, '0000', '1', '1486816499');
INSERT INTO `user` VALUES ('12', '18779873532', '1549016959@qq.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '1', '1300.00', '0.00', '0.00', '1', null, null, '1234', null, '8888', '1', '1487262600');
INSERT INTO `user` VALUES ('13', '18779873533', 'b958243544@163.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '1111', null, '8888', '1', '1487338661');
INSERT INTO `user` VALUES ('14', '15960214398', '1569542654@126.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '1255', null, '1234', '1', '1488029344');
INSERT INTO `user` VALUES ('15', '18779873535', '9582435441@qq.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '1', '6223.00', '1500.00', '0.00', '3', null, null, '3057', null, '', '1', '1489558175');
INSERT INTO `user` VALUES ('16', '18779873534', '9582435442@qq.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '9389', null, '8888', '1', '1489721408');
INSERT INTO `user` VALUES ('17', '18779593545', 'a85435845@444.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '1728', null, '8888', '1', '1490236315');
INSERT INTO `user` VALUES ('18', '18079563605', '1235484535@qq.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '4804', null, '8888', '1', '1490236395');
INSERT INTO `user` VALUES ('19', '15874358736', '4548@163.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '7832', null, '1234', '1', '1490236513');
INSERT INTO `user` VALUES ('20', '13870565243', '45asd@163.com', 'df10ef8509dc176d733d59549e7dbfaf', 'b1009de1f6497ae10e0d46d05f09d3ab', '2', '0.00', '0.00', '0.00', '1', null, null, '9050', null, '1234', '1', '1490236549');
INSERT INTO `user` VALUES ('21', '18779593522', '1547865@163.com', 'b3ddd238d6184cfd458205957c535457', '6ee95f463b15818efa3ce260241befa4', '2', '0.00', '0.00', '0.00', '1', null, null, '6404', null, '1234', '1', '1490237540');

-- ----------------------------
-- Table structure for `user_ext`
-- ----------------------------
DROP TABLE IF EXISTS `user_ext`;
CREATE TABLE `user_ext` (
  `id` int(11) NOT NULL,
  `tyj` int(11) NOT NULL,
  `rate` varchar(5) DEFAULT NULL,
  `comm` varchar(3) DEFAULT NULL,
  `lottery_num` int(11) DEFAULT NULL,
  `lottey_result` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_ext
-- ----------------------------
INSERT INTO `user_ext` VALUES ('1', '0', null, null, '0', '1');

-- ----------------------------
-- Table structure for `verify_code`
-- ----------------------------
DROP TABLE IF EXISTS `verify_code`;
CREATE TABLE `verify_code` (
  `verify_code_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `verify_code` varchar(6) NOT NULL DEFAULT '' COMMENT '验证码',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `mobile` varchar(16) NOT NULL DEFAULT '' COMMENT '手机号',
  `cookie_value` varchar(32) NOT NULL DEFAULT '' COMMENT 'COOKIE值',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证码过期时间，默认有效期30分钟内',
  `isuse` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否可用，1可用，0不可用',
  `province_id` mediumint(8) unsigned NOT NULL DEFAULT '330000' COMMENT '省份ID',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '330300' COMMENT '城市ID',
  `area_id` mediumint(8) unsigned NOT NULL DEFAULT '330382' COMMENT '区/县ID',
  PRIMARY KEY (`verify_code_id`),
  KEY `tp_verify_code_user_id` (`user_id`),
  KEY `tp_verify_code_expire_time` (`expire_time`),
  KEY `tp_verify_code_cookie_value` (`cookie_value`),
  KEY `tp_city` (`city_id`),
  KEY `tp_area` (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='验证码表';

-- ----------------------------
-- Records of verify_code
-- ----------------------------
INSERT INTO `verify_code` VALUES ('1', '266465', '0', '15870673130', '', '1492111111', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('2', '433672', '0', '15870673130', '', '1492151111', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('3', '576151', '0', '15870673130', '', '1492151111', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('5', '361203', '0', '15870673130', '', '1492154052', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('6', '946497', '0', '15870673130', '', '1492154355', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('7', '003109', '0', '15870673130', '', '1492161423', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('8', '084901', '0', '15870673130', '', '1492161576', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('9', '196197', '0', '15870673130', '', '1492161903', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('10', '109846', '0', '15870673130', '', '1492180970', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('11', '049798', '0', '15870673130', '', '1492396884', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('12', '736858', '0', '15870673130', '', '1492397000', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('13', '986638', '0', '15870673130', '', '1492397155', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('14', '821414', '0', '15870673130', '', '1492401887', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('15', '938618', '0', '15870673130', '', '1492401984', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('16', '069758', '0', '15870673130', '', '1492402068', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('17', '605922', '0', '15870673130', '', '1492402148', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('18', '843550', '0', '15870673130', '', '1492402553', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('19', '754202', '0', '15870673130', '', '1492402632', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('20', '670200', '0', '15870673130', '', '1492408326', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('21', '477803', '0', '15870673130', '', '1492409896', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('22', '553059', '0', '15870673130', '', '1492410002', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('23', '345456', '0', '15870673130', '', '1492410403', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('24', '798286', '0', '15870673130', '', '1492410596', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('25', '740416', '0', '15870673130', '', '1492410662', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('26', '276033', '0', '15870673130', '', '1492411529', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('27', '023503', '0', '15870673130', '', '1492411784', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('28', '333918', '0', '15870673130', '', '1492412700', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('29', '617174', '0', '15870673130', '', '1492412896', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('30', '132934', '0', '15870673130', '', '1492413097', '1', '330000', '330300', '330382');
INSERT INTO `verify_code` VALUES ('31', '032867', '0', '15870673130', '', '1492413157', '1', '330000', '330300', '330382');

-- ----------------------------
-- Table structure for `wzgg`
-- ----------------------------
DROP TABLE IF EXISTS `wzgg`;
CREATE TABLE `wzgg` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8 NOT NULL,
  `source` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wzgg
-- ----------------------------
INSERT INTO `wzgg` VALUES ('1', '新手注册送红包', '活动：注册送券', '新手注册送2张券，一张是60的，满1200就可以使用变现。20的满400就可以变现，速来投资，红包属于你。', '2017-03-04 18:12:39');
INSERT INTO `wzgg` VALUES ('2', '投资送钱', '活动', '参与投资就可以获取相应的返现红包，可直接提现', '2017-03-15 08:56:16');
INSERT INTO `wzgg` VALUES ('3', '公司成立一周年', 'admin', '随着中国经济的发展，老百姓的收入也在不断提高，许多家庭通过努力积累起了不少财富。但是由于工作繁忙、又不懂得理财规划，许多人在如何管理自己财富的问题上犯了难。\r\n　　一、储备3～6个月的家庭备用金\r\n\r\n　　正所谓天有不测风云，生活中难免会遇到一些需要急需用钱的时候。因此，预留一定数额的家庭备用金是非常有必要的。\r\n\r\n　　理财师建议，家庭备用金一般是3～6个月的生活费，但如果以家庭为单位，多预留一些更好，比如刘先生家庭就可以预留个7、8万，以防万一。\r\n\r\n　　二、利用闲置资金进行投资理财\r\n　　三、合理利用每月结余资金\r\n  总之，像这样的拥有一定闲散资金的中产家庭，只要制定好详细而合适的理财计划，按照计划实行，相信一定可以实现自己的目标。', '2017-03-15 20:23:05');
INSERT INTO `wzgg` VALUES ('4', '你好', 'admin', '欢迎注册！！！', '2017-03-15 20:25:02');
INSERT INTO `wzgg` VALUES ('5', '该睡觉了', 'admin', '每天保持良好睡眠习惯，健康，乐观，向上！！！go.go.go', '2017-03-20 00:10:13');
