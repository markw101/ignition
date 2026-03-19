/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: ignitionbasic
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `author_pub_date` varchar(50) DEFAULT NULL,
  `author_featured` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `author_comments` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `author_languages` varchar(255) DEFAULT NULL,
  `author_about` longtext DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES
(1,'2024-01-05 04:54:59','2024-01-04 21:54:59',NULL,'Eric Tales','January 2023',1,1,'English, Spanish, French','',1);
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `blog_name` varchar(255) DEFAULT NULL,
  `blog_pub_date` varchar(50) DEFAULT NULL,
  `blog_justify` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `blog_featured` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `blog_comments` int(11) unsigned NOT NULL DEFAULT 0,
  `blog_languages` varchar(255) DEFAULT NULL,
  `blog_notes` longtext DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` VALUES
(1,'2026-03-15 23:14:04','2026-03-18 19:35:40',NULL,'README',NULL,1,1,0,'\'en\'','',1);
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloglangs`
--

DROP TABLE IF EXISTS `bloglangs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloglangs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `id_parent` int(10) unsigned DEFAULT 0,
  `bloglang_lang` varchar(10) DEFAULT '',
  `bloglang_slug` varchar(255) DEFAULT '',
  `bloglang_title` varchar(255) DEFAULT '',
  `bloglang_description` varchar(255) DEFAULT '',
  `bloglang_text` longtext DEFAULT '',
  `bloglang_category` varchar(100) DEFAULT '',
  `bloglang_tags` mediumtext DEFAULT '',
  `bloglang_author` varchar(255) DEFAULT '',
  `bloglang_views` int(11) unsigned NOT NULL DEFAULT 1,
  `bloglang_featured` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `bloglang_refreshdate` datetime DEFAULT '0000-00-00 00:00:00',
  `bloglang_external_domain` varchar(255) DEFAULT '',
  `bloglang_external_code` varchar(255) DEFAULT '',
  `bloglang_authorid` int(10) unsigned DEFAULT 0,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloglangs`
--

LOCK TABLES `bloglangs` WRITE;
/*!40000 ALTER TABLE `bloglangs` DISABLE KEYS */;
INSERT INTO `bloglangs` VALUES
(1,'2026-01-16 23:14:04','2026-03-18 19:35:40',NULL,1,'en','ignition-ai-introduction','Ignition Base Readme','README.MD','<h2>Welcome to Ignition: the CodeIgniter 4 extension that will give you the fastest and most extensible headstart on your website and web application development projects!</h2>\r\n<h4>Download and install Ignition and jump on the fast track to a fully functioning CodeIgniter 4 website. Build an entire website application with a menu system, Clean Blog home page banner, footer, blogging system and user management functions within just a few hours.</h4>\r\nAttribution:<br>✩ CodeIgniter 4; CodeIgniter Foundation; https://codeigniter.com; MIT License<br>✩ Colorlib; Colorlib; https://github.com/colorlibhq/; MIT License<br>✩ Fontawesome; https://fontawesome.com/; https://fontawesome.com/support; CC 4.0<br>✩ jQuerry; John Resig; https://jquery.com; CC0<br>✩ Clean Blog by Bootstrap; Matt Harzewski; http://jekyllthemes.org/themes/clean-blog/; GPL 3.0<br>✩ Twitter Typeahead.js; Jake Harding; https://github.com/twitter/typeahead.js/; MIT\r\n\r\n<blockquote>Simple vs Easy<br>What is simple is usually easy, what is easy is often not simple.  Simplicity and ease should not be confused.  Simplicity should be the target which shall increase functional efficiency, but shall not bolster ease to the detriment simplicity.  Excessive complexity limits design potentials.</blockquote>\r\n<h2>Features List</h2>\r\nIgnition gives you the following:<br>✔ A built-in blogging system that includes the following: Categories; Popular Posts; Authors table; and a Tag Cloud<br>✔ Extensive multi language support<br>✔ Fastest path to a Codeigniter 4 website<br>✔ App security with numerous security hardening modifications<br>✔ An app control panel for Adminstrators and logged-in Users<br>✔ A flexible but not overly complex site theming system<br>✔ Built in Twitter typeahead bundle<br>✔ Ignition AutoForm system which allows one to create an entire MVC system (Model, View, Control), with database storage with ability to index and edit records with just one file in less than one hour.\r\n\r\n<center><font style=\"font-weight: 600\">Instructions</font></center>\r\n\r\n<font style=\"font-weight: 600\">Getting Started</font>\r\nDownload Ignition source code from Github by either cloning the repository or go to Download Zip file (usually a button with a download option).\r\n\r\n<font style=\"font-weight: 600\">Ignition File Structure</font>\r\nOne very important key to understanding Ignition is in knowing and understanding the layout of the file system. Knowing where your application files are located and how to access and modify them shall be the basis for much of your development work in Ignition.\r\n\r\nIgnition is built around a directory structure as follows:<table><tr><td colspan=3>web/</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">appCODEUSET</td><td style=\"width: 500px;\">❮ Directory.  Main application directory</td></tr><tr><td></td><td style=\"vertical-align: top\">ci4CODEUSET</td><td>❮ Directory.  Codeigniter application files</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top\">.env</td><td>❮ File.  Set environment variables, especially database names and passwords</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top\">mountassets</td><td>❮ File.  A shell script that mounts the web/public/assets dir, giving access to uploads</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top\">public</td><td>❮ Directory.  Public facing directory, designated as document root in Nginx/Apache config</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top;\">writeCODEUSET</td><td>❮ Directory.  A directory that contains writable directories for your application</td></tr></table>\r\n\r\nCODEUSET: This is a code that gets added to program directories as a security measure.  Set this code to a random number and name the directories using it.  IMPORTANT: You must maintain the prefixes as follows: app, ci4, write and upload (in the write directory) - when renaming the directories with the new site code.  This code is used to obscure the location of your application files and help reduce the attack surface area within your application.  By keeping secret the program directories it becomes more difficult for an attacker to do directory traversal from inside of your app and upload malicious code or access specific files.  This code is set in the web/public/index.php file and is repeated in several places.  Set the code in the program as a constant in index.php, variable name IGNITIONCD and elsewhere in this file.\r\n\r\nEach of the above directories will now be covered.\r\n\r\nDirectory: appCODEUSET<br>This directory is the main application directory and contains the following structure:<br>   Config<br>   Ignition<br>   Language<br>   Site<br>   Views\r\n\r\nThe directories contained in this directory are application specific but should ordinarily be essentially left in tact.  In the future, modules added to Ignition such as DNA will go here.  The Config directory contains Codeigniter configuration files which have many options for modifying the Codeigniter framework and your Ignition application.  A handful of files, the most important files, that must be modified for each application are covered below in the section titled Configure Ignition.\r\n\r\nThe Ignition directory contains the Ignition framework.  It is a good idea to avoid making changes to the files in the Ignition directory if at all possible.  That way, as one upgrades the Ignition framework, they may overwrite this directory with a newer version (remember to update the ci4CODEUSET directory also as the Codeigniter and Ignition versions are matched together).  The developer is encouraged to get to know what is contained in these directories and files to better understand how Ignition functions.\r\n\r\nThe Language directory contains the language files for each of the languages you may wish to include in your application.  Many of these files link to the default language files within Ignition.  For application specific translations it is recommended to modify the config.php and the ignitionbasic.php files in the respective local sub directories.  The ignitionbasic.php file can be renamed to the name of your application.\r\n\r\nThe Site directory contains site specific programs and configuration files.  This where you will configure Ignition for each of your applications.  The two main files that require modification are SiteConfig.php and SiteConstants.php.  See the below section titled Configure Ignition for more information about the Site directory as this is where you may best add site specific modules to your application.  These will be added to the Channels directory inside of Site.\r\n\r\nThe Views directory contains Codeigniter code that is used for the pager functions and error handling.  Normally, modification of these files is not going to be required.\r\n\r\nDirectory: ci4CODEUSET\r\nThis directory contains the Codeigniter application.  Occasionally you may wish to access this directory in your application development efforts.  This will likely not often occur as Codeigniter is a complex library with many internal moving parts that must work together in concert.  However, it is fairly frequently a good practice to study this code and view its internal functions to better understand various error messages or issues that you may encounter in your development work.  Minor modifications in just a few places must be made to Codeigniter for it to work correctly with Ignition.  These are signified by Ignition comments in the CI source code.\r\n\r\nDirectory: public\r\nThis directory was added as a part of the Codeigniter framework and is the document root for the web server, usually Nginx or Apache.<br><table><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">assets</td><td style=\"width: 500\">❮ a read only mount of the uploads directory (see uploadCODEUSET below, and mountassets script)</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">blogvista</td><td>❮ css and js files related to the blogvista (a theme choice in Ignition)</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">css</td><td>❮ css file for Ignition, bootstrap cleanblog</td></tr><td></td><td style=\"width: 200px; vertical-align: top\">fonts</td><td>❮ fonts required by Ignition</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">.htaccess</td><td>❮ Codeigniter web server configuration</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">images</td><td>❮ images required for web application design</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">index.php</td><td>❮ Codeigniter index with modifications.  MUST MODIFY THIS FILE FOR EACH SITE</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">js</td><td>❮ cleanblog js files</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">robots.txt</td><td>❮ standard search engine robots where you may disallow the search spiders</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">themes</td><td>❮ the majority of the js and css files required by Ignition app</td></tr></table>\r\n\r\nDirectory: writeCODEUSET\r\nThis directory contains directories with write permissions required by the web server.  You must always carefully plan and set write permissions in directories on a public server, especially those in the program execution path (in this case PHP) which is set and limited in the web server.<br><table><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">cache</td><td style=\"width: 500\">❮ Codeigniter, web cache (if enabled)</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">debugbar</td><td>❮ Codeigniter, used in debug mode</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">logs</td><td>❮ Codeigniter logging, normally disabled</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">session</td><td>❮ Codeigniter sessions if using file system session system (vs database)</td></tr><td></td><td style=\"width: 200px; vertical-align: top\">uploadCODEUSET</td><td>❮ User content upload.  Set in index.php</td></tr></table>\r\n\r\n','Ignition','\'Ignition Open Source\' \'Ignition REAME.md\'','ME Williamson',11,1,'2026-03-15 16:14:04','','',1,1);
/*!40000 ALTER TABLE `bloglangs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `category_lang` varchar(10) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_code` varchar(25) NOT NULL,
  `category_tags` varchar(255) DEFAULT NULL,
  `category_description` varchar(255) DEFAULT NULL,
  `category_notes` mediumtext DEFAULT NULL,
  `category_featured` varchar(1) DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES
(1,'2022-07-05 12:14:46','2023-11-21 17:00:41',NULL,'en','Physics','Physics','Physics Flow Theory','Leading edge theoretical physics','','1',1),
(2,'2022-07-05 12:27:18','2022-08-21 06:22:14',NULL,'en','Technology','Technology','\'Computer Programming\' \'Defence\'','Technological trends','','1',1),
(3,'2022-07-05 12:42:27','2022-08-21 06:24:59',NULL,'en','Politics','Politics','Political trends shaping our lives','Political trends shaping our lives','Political trends shaping our lives','1',1),
(4,'2022-07-05 17:21:54','2022-07-28 04:29:52',NULL,'en','Religion','Religion','Relgion God','Enlightened Religion','','0',1),
(5,'2022-07-05 17:22:30','2022-07-28 04:30:15',NULL,'en','Cryptocurrency','Crypto','Cryptocurrency Bitcoin Centillion Voting Blockchain','','','0',1),
(6,'2022-08-09 12:39:00','2022-08-13 05:22:16',NULL,'en','Anti-Aging','Age','\'Aging\' \'Anti-Aging\' \'Reverse Aging\'','Articles that impact the aging process from a genomic point of view','','0',1),
(7,'2023-11-21 18:25:43','2023-11-26 01:35:49',NULL,'es','Física','Física',NULL,'Física teórica de vanguardia','','1',1),
(8,'2023-11-21 18:25:55','2023-11-26 01:36:29',NULL,'es','Tecnología','Tecnología',NULL,'Tendencias tecnológicas','','1',1),
(9,'2023-11-21 18:26:17','2023-11-26 01:37:04',NULL,'es','Política','Política',NULL,'Tendencias políticas que determinan nuestras vidas','','1',1),
(10,'2023-11-21 18:26:30','2023-11-26 01:21:58',NULL,'es','Religión','Religión',NULL,'','','0',1);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `config_property` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `logs_code` varchar(255) DEFAULT '0',
  `logs_desc` varchar(255) DEFAULT '',
  `logs_username` varchar(50) DEFAULT '',
  `logs_host` varchar(50) DEFAULT '',
  `logs_ip` varchar(50) DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `pagelangs`
--

DROP TABLE IF EXISTS `pagelangs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagelangs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `id_parent` int(10) unsigned DEFAULT 0,
  `pagelang_title` varchar(255) DEFAULT '',
  `pagelang_lang` varchar(10) DEFAULT '',
  `pagelang_slug` varchar(255) DEFAULT '',
  `pagelang_text` longtext DEFAULT '',
  `pagelang_narrow_text` varchar(255) DEFAULT '',
  `pagelang_tags` mediumtext DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagelangs`
--

LOCK TABLES `pagelangs` WRITE;
/*!40000 ALTER TABLE `pagelangs` DISABLE KEYS */;
INSERT INTO `pagelangs` VALUES
(1,'2023-11-14 12:29:48','2023-11-21 16:37:27',NULL,1,'Error 404','en','404','<center>\r\n<h1>Page not found</h1><br>\r\n</center>\r\n','Error 404','',1),
(2,'2023-11-21 16:37:27','2023-11-21 16:37:27',NULL,1,'Error 404','es','404esp','<center>\r\n<h1>Página no encontrada</h1><br>\r\n</center>','','',1),
(3,'2023-11-14 15:30:09','2023-11-16 04:47:13',NULL,2,'Blog','en','blog','','','',1),
(4,'2023-11-16 11:21:24','2023-11-16 04:47:13',NULL,2,'Blog (es)','es','bloges','','','',1),
(5,'2023-11-16 11:18:42','2026-02-21 07:39:11',NULL,3,'About','en','about','<div class=\"container\"><div class=\"row\"> <div class=\"mx-auto\" style=\"margin-top:0px; margin-left: 25px; margin-right: 25px; margin-bottom:40px\"><h1>ABOUT</h1><br>Williamson Software is now the fully empowered and authorized technical agency to provide the application tools and support to the DNA network as voted into this newly established office in 2025 by the DNA peer members.\r\n\r\nLearn more about this decentralized, worldwide database system at the Earthica Society which provides membership and company support services at <u><a href=\"https://earthica.world\">https://earthica.world</a></u>.\r\n\r\n<font style=\"font-weight:700\">Company Overview</font>\r\n\r\nWilliamson Software was founded by Mark E. Williamson in Santa Barbara County, California in the year 1990. The company developed custom software within <u><a href=\"https://history.williamsonsoftware.com/comp-hist.html\">two industry areas for its first 20 years</a></u> and sold three software product titles that it developed and maintained. \r\n\r\nIn 2006 Mr. Williamson developed a series of applications for a new startup named InfoShare that lead to the inspiration behind Google Drive.\r\n\r\nIn 2007 Mr. Williamson began development of a new, decentralized file system called ideaOS. The sequential hashing mechanism and distributed file system master node design was then applied to work being done by Hal Finney, also a Santa Barbara resident, to develop a cryptocurrency system. This file system became Blockchain which is used today in many cryptocurrencies including Bitcoin. This also lead to the design behind the IPFS project some years later which is very similar to the ideaOS design of 2007.\r\n\r\nIn 2018 Mr. Williamson developed software for the solar power industry which is maintained exclusively now for SolarSnap, Inc.\r\n\r\nIn 2026 Mr. Williamson, in conjunction with Williamson Software, released the first version of Ignition, an open source extension to the CodeIgniter framework. The company has refocused its strategy to support the open source community and to maintain the Ignition framework and the open source DNA worldwide, decentralized asset system.\r\n\r\nThe company also supports the MyWorld Portfolio application as well as the Robotica browser. The Robotica browser features a 3-in-1 application which quickly switches between 3 modes of operation. The browser changes between a standard browser, to a fully functional and capable IDE (Integrated Development Environment) for building DNA eRobots and, third, changes to become the MyWorld Portfolio. The MyWorld Portfolio is a web app that may be likened to a cryptocurrency wallet, yet encompasses the full lexicon of DNA functions. The Robotica IDE utilizes the Robotica scripting language which is used to control the eRobots that maintain and support the DNA network.\r\n\r\nThe DNA mainnet functions are based on the 8 types of assets and 3 types of entities available upon this new \"Citizens Database\" and present numerous financial and business opportunities to all DNA peers. With the Robotica browser the peers may design and deploy eRobots, create eCompanies, launch eTokens and present themselves with irrefutable identity or \"citizenship\" to the world using their ePassport.\r\n\r\nFor additional information about these functions see the <em>DNA FAQ and Glossary</em> located at the following URL:<br><u><a href=\"https://myworld.cash/content/dnafaq\">https://myworld.cash/content/dnafaq</a></u>\r\nYou may also learn more about DNA and the dream that started it all in 2007 from Santa Barabara, California, Bitcoin:<br><u><a href=\"https://earthica.world/post/dna-worldwide-network\">https://earthica.world/post/dna-worldwide-network</a></u>\r\n\r\n</div></div></div>','','',1),
(6,'2023-11-16 11:19:12','2026-02-21 07:39:11',NULL,3,'Acerca De','es','acercade','','','',1),
(7,'2023-11-14 15:30:09','2023-11-16 04:47:13',NULL,2,'Help','en','help','','','',1),
(8,'2023-11-16 11:21:24','2023-11-16 04:47:13',NULL,2,'Ayuda','es','ayuda','','','',1),
(9,'2026-03-16 11:18:14','2026-03-16 04:18:14',NULL,4,'Help','en','help','<div class=\"container\"><div class=\"row\"> <div class=\"mx-auto\" style=\"margin-top:0px; margin-left: 25px; margin-right: 25px; margin-bottom:40px\"><h1>___COMMAND___lang(\'base.help\');</h1>\r\n___COMMAND___lang(\'help.general\');\r\n</div></div></div>','','',1);
/*!40000 ALTER TABLE `pagelangs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `page_narrow_banner` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `page_languages` varchar(255) DEFAULT NULL,
  `page_justify` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `page_views` int(11) unsigned NOT NULL DEFAULT 1,
  `page_no_carriage_returns` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES
(1,NULL,'2023-11-14 12:29:48','0000-00-00 00:00:00','Error_404',1,'\'en\' \'es\'',1,0,0,1),
(2,NULL,'2023-11-14 12:46:02','0000-00-00 00:00:00','Blog',0,'\'en\' \'es\'',0,0,0,1),
(3,NULL,'2023-11-16 11:18:42','2024-04-02 12:00:00','about',0,'\'en\' \'es\' \'zh\'',1,0,0,1),
(4,NULL,'2025-11-10 14:22:55','0000-00-00 00:00:00','HELP',0,'\'en\'',1,0,0,1);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `user_anon` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `user_accountnum` varbinary(5) DEFAULT '',
  `user_ecoin_acct` varbinary(9) DEFAULT '',
  `user_peernum` varbinary(5) DEFAULT '',
  `user_priv_id` varbinary(5) DEFAULT '',
  `user_secret_salt` varbinary(24) DEFAULT '',
  `user_fname` varchar(30) DEFAULT '',
  `user_mname` varchar(30) DEFAULT '',
  `user_lname` varchar(30) DEFAULT '',
  `user_oname` varchar(30) DEFAULT '',
  `user_email` varchar(75) DEFAULT '',
  `user_password_hash` varbinary(64) DEFAULT '',
  `user_password_salt` varbinary(24) DEFAULT '',
  `user_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_password_reset_token` varbinary(64) DEFAULT '',
  `user_verification_token` varbinary(64) DEFAULT '',
  `user_verified_at` datetime DEFAULT NULL,
  `user_type` int(2) DEFAULT 10,
  `user_roles` varchar(50) DEFAULT '',
  `user_language` varchar(50) DEFAULT '',
  `user_session_len` varchar(10) DEFAULT '',
  `user_country_code` varchar(5) DEFAULT '',
  `user_ownercontrol` varbinary(150) DEFAULT '',
  `user_phone` varchar(18) DEFAULT '',
  `user_privkey` varbinary(64) DEFAULT '',
  `user_peer_root_secret_key` varbinary(32) DEFAULT '',
  `user_peer_home_secret_key` varbinary(32) DEFAULT '',
  `user_ownercontrol_backup` varbinary(150) DEFAULT '',
  `user_dm_web` varchar(25) DEFAULT '',
  `user_dm_cnum` varbinary(5) DEFAULT '',
  `user_lnm_web` varchar(25) DEFAULT '',
  `user_lnm_cnum` varbinary(5) DEFAULT '',
  `user_time_web` varchar(25) DEFAULT '',
  `user_time_cnum` varbinary(5) DEFAULT '',
  `user_dns1_web` varchar(25) DEFAULT '',
  `user_dns1_cnum` varbinary(5) DEFAULT '',
  `user_dns2_web` varchar(25) DEFAULT '',
  `user_dns2_cnum` varbinary(5) DEFAULT '',
  `user_dna_activated` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'2025-12-03 10:41:32','2026-02-11 08:56:28',NULL,0,'','¦-\'f\n\0\0','°\0\0\0','0�','½@^r9mÉ§ÞA&','Mark','Edward','Williamson','','mark@solarsnap.com','*IAMMAGIC*','a\'z�O�-�+q��E[s\n�Q��`','2025-12-03 10:41:32','ðäAwªck	\r~bý3&ôG¼ÃN­ÖWùìeòGBÈìz((£l','O±X(`ïr7ä´m I»}¨Õ$úÓaþþÿ3·VBè�',NULL,1,'','','','USA','','','Dä4ÏÎó»¦L\0áô}>M©4r ~Íi;»çe»ñÖ#+','C44376Y4C1I6J2G6P1Q8X515X6P177Z1','C2N64738D1E1J1V8H8U216X4C35395F8','','myworld.cash','P\0\0\0','','','','','','','','',1,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_num` int(6) DEFAULT NULL,
  `type_name` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `max_session` int(6) DEFAULT NULL,
  `redirectLogin` varchar(100) DEFAULT NULL,
  `notes` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES
(1,1,'superadmin','2022-03-11 18:43:47','2022-03-11 18:43:47',NULL,0,'controlpanel',''),
(2,2,'admin','2022-03-11 18:43:47','2022-03-11 18:43:47',NULL,3600,'controlpanel',''),
(3,3,'administracion','2022-03-11 18:43:47','2022-03-11 18:43:47',NULL,7200,'controlpanel',''),
(4,4,'security','2022-03-11 18:43:47','2022-03-11 18:43:47',NULL,0,'controlpanel',''),
(5,5,'residente','2022-03-11 18:43:47','2022-03-11 18:43:47',NULL,1800,'controlpanel',''),
(6,6,'contributor','2023-05-17 21:15:20','2023-05-17 14:15:20',NULL,300,'controlpanel',''),
(7,10,'Citizen','2023-11-30 18:24:56','2024-03-10 11:11:25',NULL,10,'controlpanel','This user only has access to functions sufficient to view and work with their portfolio');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-03-18 19:40:28
