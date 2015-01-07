-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 12 月 19 日 06:23
-- 服务器版本: 5.1.33-community
-- PHP 版本: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 如果将0保存到表的AUTO_INCREMENT列，该模式会很有用。
--

SET time_zone = "+08:00";

--
-- 【SET time_zone = "+08:00";】没必要
-- 【时区改成北京了】没必要
--

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `/*此处省略*/`
--

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `key` int(10) unsigned zerofill NOT NULL,
  `time` int(1) NOT NULL,
  `point` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`uid`, `key`, `time`, `point`, `status`) VALUES
(0000000000, FLOOR(2147483647*RAND()), 0, 0, 0);

--
-- 【注意导入后要把AUTO_INCREAMENT重置为1】没必要
-- 【ALTER TABLE `user` AUTO_INCREMENT=1;】没必要
-- NO_AUTO_VALUE_ON_ZERO禁用0，因此只有NULL可以生成下一个序列号。
--

-- --------------------------------------------------------

--
-- 表的结构 `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `vid` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned zerofill NOT NULL,
  `btih` binary(20) NOT NULL,
  `time` int(1) NOT NULL,
  `view` int(1) NOT NULL,
  `reply` int(1) NOT NULL,
  `comment` longtext COLLATE utf8_unicode_ci NOT NULL,
  `c_index` longtext COLLATE utf8_unicode_ci NOT NULL,
  `linkage` longtext COLLATE utf8_unicode_ci NOT NULL,
  `l_index` longtext COLLATE utf8_unicode_ci NOT NULL,
  `dislike` longtext COLLATE utf8_unicode_ci NOT NULL,
  `d_index` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`vid`),
  UNIQUE KEY `btih` (`btih`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

--
-- 转存表中的数据 `video`
--

INSERT INTO `video` (`vid`, `uid`, `btih`, `time`, `view`, `reply`, `comment`, `c_index`, `linkage`, `l_index`, `dislike`, `d_index`) VALUES
(0000000000, 0000000000, x'0000000000000000000000000000000000000000', 0, 0, 1, '{"c":"0,FFFFFF,1,25,0,0","m":"Test","cid":1},', '[[0,0,45]]', '{}', '{}', '{"0":[0]}', '{"0":1}');

--
-- 【注意导入后要把AUTO_INCREAMENT重置为1】没必要
-- 【ALTER TABLE `user` AUTO_INCREMENT=1;】没必要
-- NO_AUTO_VALUE_ON_ZERO禁用0，因此只有NULL可以生成下一个序列号。
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
