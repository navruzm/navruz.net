-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 28, 2011 at 10:57 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `navruz_core`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_blocks`
--

CREATE TABLE IF NOT EXISTS `ci_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `location` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `module` varchar(50) NOT NULL DEFAULT 'all',
  `access_level` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ci_blocks`
--

INSERT INTO `ci_blocks` (`id`, `type`, `location`, `title`, `content`, `module`, `access_level`, `active`) VALUES
(1, 'file', 'right', 'Kategoriler', 'categories', 'category', 0, 1),
(3, 'file', 'right', 'Etiketler', 'tags', 'tag', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ci_block_module`
--

CREATE TABLE IF NOT EXISTS `ci_block_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `module` varchar(50) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `ci_block_module`
--

INSERT INTO `ci_block_module` (`id`, `block_id`, `weight`, `module`) VALUES
(1, 1, 1, 'category'),
(3, 1, 1, 'connect'),
(4, 1, 1, 'contact'),
(5, 1, 1, 'home'),
(6, 1, 1, 'page'),
(7, 1, 1, 'search'),
(8, 1, 1, 'user'),
(9, 1, 1, 'post'),
(10, 1, 1, 'tag'),
(21, 3, 3, 'category'),
(23, 3, 3, 'connect'),
(24, 3, 3, 'contact'),
(25, 3, 3, 'home'),
(26, 3, 3, 'page'),
(27, 3, 3, 'post'),
(28, 3, 3, 'search'),
(29, 3, 3, 'tag'),
(30, 3, 3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `ci_categories`
--

CREATE TABLE IF NOT EXISTS `ci_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_slug` varchar(150) DEFAULT NULL,
  `category_title` varchar(255) DEFAULT NULL,
  `category_description` text,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `category_slug` (`category_slug`),
  KEY `weight` (`weight`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ci_categories`
--

INSERT INTO `ci_categories` (`category_id`, `category_slug`, `category_title`, `category_description`, `meta_title`, `meta_description`, `meta_keywords`, `weight`) VALUES
(1, 'kategorilenmemis', 'Kategorilenmemiş', 'Açıklama', '', '', '', 1),
(2, 'ornek-kategori', 'Örnek Kategori', 'Kategori Açıklaması', '', '', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ci_files`
--

CREATE TABLE IF NOT EXISTS `ci_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_title` varchar(100) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_date_add` datetime DEFAULT NULL,
  `file_download_count` bigint(20) DEFAULT '0',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ci_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_navigations`
--

CREATE TABLE IF NOT EXISTS `ci_navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `title` varchar(25) NOT NULL,
  `target` varchar(10) NOT NULL,
  `access_level` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ci_navigations`
--

INSERT INTO `ci_navigations` (`id`, `group`, `link`, `title`, `target`, `access_level`, `weight`) VALUES
(1, 1, '/', 'Anasayfa', '', 0, 0),
(2, 1, '/s/ornek-sayfa', 'Örnek Sayfa', '', 0, 1),
(3, 1, 'contact', 'İletişim', '', 0, 3),
(4, 1, 'admin', 'Yönetim', '', 3, 5),
(5, 1, 'archive', 'Arşiv', '', 0, 2),
(6, 1, 'user', 'Üye Paneli', '', 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `ci_navigation_groups`
--

CREATE TABLE IF NOT EXISTS `ci_navigation_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `tag` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_navigation_groups`
--

INSERT INTO `ci_navigation_groups` (`id`, `title`, `tag`) VALUES
(1, 'Üst Menü', '{HEAD_MENU}');

-- --------------------------------------------------------

--
-- Table structure for table `ci_options`
--

CREATE TABLE IF NOT EXISTS `ci_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL,
  `module` varchar(25) NOT NULL,
  `autoload` varchar(5) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `ci_options`
--

INSERT INTO `ci_options` (`option_id`, `option_name`, `option_value`, `module`, `autoload`) VALUES
(1, 'site_name', 'Site Adı', '', 'yes'),
(2, 'site_email', 'mail@siteadi.com', '', 'yes'),
(3, 'site_description', 'Site Açıklaması', '', 'yes'),
(4, 'site_keywords', 'anahtar kelimeler', '', 'yes'),
(5, 'google_verify', '', '', 'yes'),
(6, 'yahoo_verify', '', '', 'yes'),
(7, 'bing_verify', '', '', 'yes'),
(8, 'per_page', '10', '', 'yes'),
(9, 'debug', '0', '', 'yes'),
(21, 'bitly_apikey', '', '', 'yes'),
(20, 'bitly_login', '', '', 'yes'),
(23, 'analytics_id', '', '', 'yes'),
(22, 'feedburner_username', '', '', 'yes'),
(25, 'per_page_admin', '15', '', 'yes'),
(26, 'maintenance', '0', '', 'yes'),
(27, 'maintenance-end', '0', '', 'yes'),
(28, 'maintenance-message', '', '', 'yes'),
(32, 'disqus', '', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `ci_pages`
--

CREATE TABLE IF NOT EXISTS `ci_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `meta_title` varchar(255) DEFAULT '',
  `meta_description` varchar(255) DEFAULT '',
  `meta_keywords` varchar(255) DEFAULT '',
  `short_url` varchar(100) DEFAULT NULL,
  `comments_enabled` int(11) NOT NULL DEFAULT '0',
  `counter` int(11) NOT NULL DEFAULT '0',
  `updated_on` varchar(15) DEFAULT NULL,
  `created_on` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_pages`
--

INSERT INTO `ci_pages` (`id`, `author`, `slug`, `title`, `content`, `meta_title`, `meta_description`, `meta_keywords`, `short_url`, `comments_enabled`, `counter`, `updated_on`, `created_on`) VALUES
(1, 1, 'ornek-sayfa', 'Örnek Sayfa', '<p>\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n', '', '', '', 'http://bit.ly/dFA6vA', 1, 12, NULL, '1305145737');

-- --------------------------------------------------------

--
-- Table structure for table `ci_posts`
--

CREATE TABLE IF NOT EXISTS `ci_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `meta_title` varchar(255) NOT NULL DEFAULT '',
  `meta_description` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '',
  `pinged` text NOT NULL,
  `short_url` varchar(100) NOT NULL,
  `counter` int(11) NOT NULL,
  `comments_enabled` int(11) NOT NULL DEFAULT '1',
  `updated_on` varchar(15) NOT NULL,
  `created_on` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_posts`
--

INSERT INTO `ci_posts` (`id`, `author`, `slug`, `title`, `summary`, `content`, `image`, `meta_title`, `meta_description`, `meta_keywords`, `pinged`, `short_url`, `counter`, `comments_enabled`, `updated_on`, `created_on`) VALUES
(1, 1, 'ornek-yazi', 'Örnek Yazı', 'Buraya yazı özetini yazabilirsiniz. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '<p>\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n', '', '', '', '', '', 'http://bit.ly/h5Eqnu', 18, 1, '', '1305145737');

-- --------------------------------------------------------

--
-- Table structure for table `ci_post_files`
--

CREATE TABLE IF NOT EXISTS `ci_post_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ci_post_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_post_relationship`
--

CREATE TABLE IF NOT EXISTS `ci_post_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`category_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_post_relationship`
--

INSERT INTO `ci_post_relationship` (`id`, `post_id`, `category_id`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ci_redirect`
--

CREATE TABLE IF NOT EXISTS `ci_redirect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(20) NOT NULL,
  `old_slug` varchar(150) NOT NULL,
  `new_slug` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `old_slug` (`old_slug`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ci_redirect`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_tags`
--

CREATE TABLE IF NOT EXISTS `ci_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(30) NOT NULL DEFAULT '',
  `raw_tag` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ci_tags`
--

INSERT INTO `ci_tags` (`id`, `tag`, `raw_tag`) VALUES
(1, 'etiket', 'Etiket'),
(2, 'ornek', 'Örnek'),
(3, 'ilk-yazi', 'İlk Yazı');

-- --------------------------------------------------------

--
-- Table structure for table `ci_tags_object`
--

CREATE TABLE IF NOT EXISTS `ci_tags_object` (
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tagger_id` int(10) unsigned NOT NULL DEFAULT '0',
  `object_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tagged_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_tags_object`
--

INSERT INTO `ci_tags_object` (`tag_id`, `tagger_id`, `object_id`, `tagged_on`) VALUES
(1, 1, 1, '2011-02-19 22:02:18'),
(2, 1, 1, '2011-02-19 22:02:18'),
(3, 1, 1, '2011-02-19 22:02:18');

-- --------------------------------------------------------

--
-- Table structure for table `ci_users`
--

CREATE TABLE IF NOT EXISTS `ci_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) DEFAULT NULL,
  `new_password_key` varchar(50) DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) DEFAULT NULL,
  `new_email_key` varchar(50) DEFAULT NULL,
  `last_ip` varchar(40) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_online` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_users`
--

INSERT INTO `ci_users` (`id`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `last_online`, `created`, `modified`, `user_group`) VALUES
(1, 'admin', '$2a$08$QWf6uaphmTOSApUwQB8ZBezAgGl0niX5oPcvLiPkoDoeUnBg.QK0i', 'mail@siteadi.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2011-07-28 22:50:08', '2011-07-28 22:55:46', '2011-02-19 20:07:04', '2011-07-28 22:55:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ci_user_autologin`
--

CREATE TABLE IF NOT EXISTS `ci_user_autologin` (
  `key_id` char(32) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) NOT NULL,
  `last_ip` varchar(40) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_user_autologin`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_user_groups`
--

CREATE TABLE IF NOT EXISTS `ci_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ci_user_groups`
--

INSERT INTO `ci_user_groups` (`id`, `title`, `name`, `description`, `color`) VALUES
(1, 'Yönetici', 'administrator', '', 'red'),
(2, 'Üye', 'user', '', 'violett');

-- --------------------------------------------------------

--
-- Table structure for table `ci_user_login_attempts`
--

CREATE TABLE IF NOT EXISTS `ci_user_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ci_user_login_attempts`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_user_permissions`
--

CREATE TABLE IF NOT EXISTS `ci_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permissions` text NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_user_permissions`
--

INSERT INTO `ci_user_permissions` (`user_id`, `permissions`) VALUES
(1, 'a:11:{s:14:"category/admin";s:1:"1";s:10:"post/admin";s:1:"1";s:13:"comment/admin";s:1:"1";s:16:"navigation/admin";s:1:"1";s:10:"page/admin";s:1:"1";s:19:"admin/options_admin";s:1:"1";s:14:"admin/database";s:1:"1";s:17:"admin/clear_cache";s:1:"1";s:10:"user/admin";s:1:"1";s:16:"permission/admin";s:1:"1";s:11:"block/admin";s:1:"1";}');

-- --------------------------------------------------------

--
-- Table structure for table `ci_user_profiles`
--

CREATE TABLE IF NOT EXISTS `ci_user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthday` varchar(10) NOT NULL,
  `job` varchar(100) NOT NULL,
  `location` varchar(50) NOT NULL,
  `gender` enum('','m','f') NOT NULL,
  `bio` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_user_profiles`
--

INSERT INTO `ci_user_profiles` (`id`, `user_id`, `facebook_id`, `first_name`, `last_name`, `birthday`, `job`, `location`, `gender`, `bio`) VALUES
(1, 1, 0, 'Ad', 'Soyad', '495234000', 'İş', 'İstanbul', 'm', 'Kısa tanıtım');
