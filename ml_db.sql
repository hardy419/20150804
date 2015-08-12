-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 08 月 05 日 06:37
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `ml_db`
--

-- --------------------------------------------------------

--
-- 表的结构 `ml_suburb_en`
--

CREATE TABLE IF NOT EXISTS `ml_suburb_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_city_en`
--

CREATE TABLE IF NOT EXISTS `ml_city_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_country_en`
--

CREATE TABLE IF NOT EXISTS `ml_country_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_schooltype_en`
--

CREATE TABLE IF NOT EXISTS `ml_schooltype_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_education_en`
--

CREATE TABLE IF NOT EXISTS `ml_education_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `school_type` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `pdf` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `suburb_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`country_id`,`city_id`,`suburb_id`),
  KEY `name` (`name`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  KEY `school_type` (`school_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_news_en`
--

CREATE TABLE IF NOT EXISTS `ml_news_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `link` varchar(255) NOT NULL,
  `pdf` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `order` int(11) NOT NULL,
  `info` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ORDER` (`order`),
  KEY `STATUS` (`status`),
  KEY `DATE` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_page_en`
--

CREATE TABLE IF NOT EXISTS `ml_page_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `menu` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_property_en`
--

CREATE TABLE IF NOT EXISTS `ml_property_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `suburb_id` int(11) NOT NULL,
  `school_near1` int(11) NOT NULL,
  `school_near2` int(11) NOT NULL,
  `school_near3` int(11) NOT NULL,
  `description_environment` varchar(255) NOT NULL,
  `description_property` varchar(255) NOT NULL,
  `price_range_from` int(11) NOT NULL,
  `price_range_to` int(11) NOT NULL,
  `completion_date` varchar(64) NOT NULL,
  `info_last_updated_date` varchar(64) NOT NULL,
  `document_download1` varchar(255) NOT NULL,
  `document_download2` varchar(255) NOT NULL,
  `document_download3` varchar(255) NOT NULL,
  `google_map` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`country_id`,`city_id`,`suburb_id`),
  KEY `name` (`name`),
  KEY `order` (`order`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ml_propertyphoto_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `sid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_ebuy_en`
--

CREATE TABLE IF NOT EXISTS `ml_ebuy_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_testimonial_en`
--

CREATE TABLE IF NOT EXISTS `ml_testimonial_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ml_member_enquiry_details`
--

CREATE TABLE IF NOT EXISTS `ml_member_enquiry_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `header_id` (`header_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `ml_member_enquiry_header`
--

CREATE TABLE IF NOT EXISTS `ml_member_enquiry_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `enquiry_type` varchar(32) NOT NULL,
  `confirmed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `enquiry_type` (`enquiry_type`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `ml_user`
--

CREATE TABLE IF NOT EXISTS `ml_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acct_name` varchar(32) NOT NULL,
  `account` varchar(50) NOT NULL,
  `login_count` varchar(50) NOT NULL,
  `last_login_time` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `nickname` varchar(255) NOT NULL,
  `last_login_ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ml_user`
--

INSERT INTO `ml_user` (`id`, `acct_name`, `account`, `login_count`, `last_login_time`, `password`, `status`, `nickname`, `last_login_ip`) VALUES
(1, 'CEO', 'admin', '223', '1438741109', 'b59c67bf196a4758191e42f76670ceba', 1, '', '127.0.0.1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
