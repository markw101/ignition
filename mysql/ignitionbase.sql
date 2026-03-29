/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: ignitionbase
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
(1,'2024-01-05 04:54:59','2026-03-21 08:42:00',NULL,'Biggish Tales','January 2023',1,1,'English, Spanish, French','',1);
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
(1,'2026-03-15 23:14:04','2026-03-28 17:57:38',NULL,'README',NULL,1,1,0,'\'en\'','* System Overview\r\n\r\n	* Most site configuration is contained in two files: SiteConfig.php and SiteConstants.php. \r\n		* Set login URL in SiteConstants\r\n	* Explain how themes work. \r\n		* Set theme file in $mainLayoutFile in BaseController.php\r\n		* Function RenderTheme() within BaseController is called instead of CI view()\r\n	* Explain about base controller and model\r\n	* Explain CI integration:\r\n		* Configuration for application is in appXXXXX/Config\r\n		* CI4 is in /ci4XXXXXXX\r\n		* index.php setup\r\n		* the writable directory for the site is in writeXXXXX which includes the user content upload directory called uploadXXXXXX. In the standard Ignition configuration this directory is auto mounted using the web/mountassets directory.\r\n* Blog\r\n	* Set image for article by including main at the start of the file name\r\n	* All images for an article with get auto named to add a prefix that includes the table record id number. In this way attachment files can be easily found in the writeXXXXX/uploadsXXXXXX/blog directory\r\n* What is Taz? utility functions and scripts. independent database that stores logins and server data such as page views for the blog articles\r\n* What is the autoform system and how can it save you enormous time in your dev efforts? (pull info from simpleeasy)\r\n	* Can create an entire MVC system with just ONE FILE\r\n	* files are typically named Subject.autoform.php\r\n	* The ignition autoform file consists of 4 parts: 1 setup variables 2 index array 3 form array 4 supporting functions. These are all called and managed in the web/appXXXXXXX/Ignition/AutoForm/MC/Controller.php program file. To add an autoform to the system will need to add it to the SiteConfig.php file.\r\n	* Many examples in basecontroller array variable $baseIChannels. But you should add yours to SiteConfig.php $siteIChannels array variable\r\n	    public $baseIChannels = [			// autoforms for Ignition\r\n        \'asset\' => \'Ignition/Asset/Asset.autoform.php\',\r\n        \'category\' => \'Ignition/Blog/Category.autoform.php\',\r\n        \'usertype\' => \'Ignition/UserType/UserType.autoform.php\',\r\n        \'logs\' => \'Ignition/System/MC/Logging.autoform.php\',\r\n		\'authoradmin\' => \'Ignition/Author/Author.autoform.php\',\r\n        \'logins\' => \'Ignition/System/MC/Logins.autoform.php\'\r\n    ];\r\n* How is security handled and and what is the RBAC system? (pull info form simpleeasy)\r\n	* Discuss SAN\r\n	* Authentication is handled universally in the BaseController __construct function\r\n* Channels. < user added modules\r\n* Control panels set in Site/ControlPanel. This is where the UI for the various user types gets set\r\n',1);
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
(1,'2026-01-16 23:14:04','2026-03-28 17:57:38',NULL,1,'en','ignitionbase-en','Ignition Base Readme','README.MD','<h2>Welcome to Ignition: the CodeIgniter 4 extension that will give you the fastest and most extensible headstart on your website and web application development projects!</h2>\r\n<h4>Download and install Ignition and jump on the fast track to a fully functioning CodeIgniter 4 website. Build an entire website application with a menu system, Clean Blog home page banner, footer, blogging system and user management functions within just a few hours.</h4>\r\n\r\nFor a sample website running a stock version of this open source software visit the example implementation here:<br>en.ignitionbase.williamsonsoftware.com\r\n\r\nAttribution:<br>✩ CodeIgniter 4; CodeIgniter Foundation; https://codeigniter.com; MIT License<br>✩ Colorlib; Colorlib; https://github.com/colorlibhq/; MIT License<br>✩ Fontawesome; https://fontawesome.com/; https://fontawesome.com/support; CC 4.0<br>✩ jQuerry; John Resig; https://jquery.com; CC0<br>✩ Clean Blog by Bootstrap; Matt Harzewski; http://jekyllthemes.org/themes/clean-blog/; GPL 3.0<br>✩ Twitter Typeahead.js; Jake Harding; https://github.com/twitter/typeahead.js/; MIT\r\n\r\n<blockquote>Simple vs Easy<br>What is simple is usually easy, what is easy is often not simple.  Simplicity and ease should not be confused.  Simplicity should be the target which shall increase functional efficiency, but shall not bolster ease to the detriment simplicity.  Excessive complexity limits design potentials.</blockquote>\r\n<h2>Features List</h2>\r\nIgnition gives you the following:<br>✔ A built-in blogging system that includes the following: Categories; Popular Posts; Authors table; and a Tag Cloud<br>✔ Extensive multi language support<br>✔ Fastest path to a CodeIgniter 4 website<br>✔ App security with numerous security hardening modifications<br>✔ An app control panel for Administrators and logged-in Users<br>✔ A flexible but not overly complex site theming system<br>✔ Built in Twitter typeahead bundle<br>✔ Ignition AutoForm system with RAD (Rapid Application Development) which allows one to create an entire MVC system (Model, View, Control), with database storage with ability to index and edit records with just one file in less than one hour.<br>✔ User support with user table, user types, user profile record and login/logout capabilities\r\n\r\n<b>Instructions</b>\r\nDownload Ignition source code from Github by either cloning the repository or go to Download Zip file (usually a button with a download option). For a really quick-start, jump to Getting Started below.\r\n\r\n<b>Ignition File Structure</b>\r\nOne very important key to knowing your way around Ignition is in learning the layout of the file system. Knowing where your application files are located and how to access and modify them shall be the basis for much of your development work in Ignition.\r\n\r\nIgnition is built around a directory structure as follows:<table><tr><td colspan=3>web/</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">appCODEUSET</td><td style=\"width: 500px;\">❮ Directory.  Main application directory</td></tr><tr><td></td><td style=\"vertical-align: top\">ci4CODEUSET</td><td>❮ Directory.  CodeIgniter application files</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top\">.env</td><td>❮ File.  Set environment variables, especially database names and passwords</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top\">mountassets</td><td>❮ File.  A shell script that mounts the web/public/assets dir, giving access to uploads</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top\">public</td><td>❮ Directory.  Public facing directory, designated as document root in Nginx/Apache config</td></tr><tr><td style=\"width: 30px;\"></td><td style=\"vertical-align: top;\">writeCODEUSET</td><td>❮ Directory.  A directory that contains writable directories for your application</td></tr></table>\r\n\r\nCODEUSET: This is a code that gets added to program directory names as a security measure.  When your website goes live and is moved to a public web server make sure and set this code to a random number and name the above directories using it.  IMPORTANT: You must maintain the prefixes as follows: app, ci4, write and upload (in the write directory) - when renaming the directories with the new site code.  This code is used to obscure the location of your application files and help reduce the attack surface area within your production web applications.  By keeping secret the program directories it becomes more difficult for an attacker to do directory traversal from inside of your app and upload malicious code or access specific files.  This code is set in the web/public/index.php file and is repeated in several places.  Set the code in the program as a constant in index.php, variable name IGNITIONCD and elsewhere in this file.\r\n\r\nEach of the above directories will now be covered.\r\n\r\nDirectory: appCODEUSET<br>This directory is the main application directory and contains the following structure:<br>   Config<br>   Ignition<br>   Language<br>   Site<br>   Views\r\n\r\nThe directories contained in this directory are application specific but should ordinarily be essentially left in tact.  In the future, modules added to Ignition such as DNA will go here.  The Config directory contains CodeIgniter configuration files which have many options for modifying the CodeIgniter framework and your Ignition application.  A handful of files, the most important files, that must be modified for each application are covered below in the section titled Configure Ignition.\r\n\r\nThe Ignition directory contains the Ignition framework.  It is a good idea to avoid making changes to the files in the Ignition directory if at all possible.  That way, as one upgrades the Ignition framework, they may overwrite this directory with a newer version (remember to update the ci4CODEUSET directory also as the CodeIgniter and Ignition versions are matched together).  The developer is encouraged to get to know what is contained in these directories and files to better understand how Ignition functions.\r\n\r\nThe Language directory contains the language files for each of the languages you may wish to include in your application.  Many of these files link to the default language files within Ignition.  For application specific translations it is recommended to modify the config.php and the ignitionbasic.php files in the respective local sub directories.  The ignitionbasic.php file can be renamed to the name of your application.\r\n\r\nThe Site directory contains site specific programs and configuration files.  This is where you will configure Ignition for each of your applications as well as add custom modules for your application.  The two main files that require modification are SiteConfig.php and SiteConstants.php.  See the below section titled Configure Ignition for more information about the Site directory as this is where you may best add site specific modules to your application.  These will be added to the Channels directory inside of Site.\r\n\r\nThe Views directory contains CodeIgniter code that is used for the pager functions and error handling.  Normally, modification of these files is not going to be required.\r\n\r\nDirectory: ci4CODEUSET\r\nThis directory contains the CodeIgniter application.  Occasionally you may wish to access this directory in your application development efforts.  This will likely not often occur as CodeIgniter is a complex library with many internal moving parts that must work together in concert.  However, it is fairly frequently a good practice to study this code and view its internal functions to better understand various error messages or issues that you may encounter in your development work.  Minor modifications in just a few places must be made to CodeIgniter for it to work correctly with Ignition.  These are signified by Ignition comments in the CI source code.\r\n\r\nDirectory: public\r\nThis directory was added as a part of the CodeIgniter framework and is the document root for the web server, usually Nginx or Apache.<br><table><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">assets</td><td style=\"width: 500\">❮ a read only mount of the uploads directory (see uploadCODEUSET below, and mountassets script)</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">blogvista</td><td>❮ css and js files related to the blogvista (a theme option in Ignition)</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">css</td><td>❮ css file for Ignition, bootstrap cleanblog</td></tr><td></td><td style=\"width: 200px; vertical-align: top\">fonts</td><td>❮ fonts required by Ignition</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">.htaccess</td><td>❮ CodeIgniter web server configuration</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">images</td><td>❮ images required for web application design</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">index.php</td><td>❮ CodeIgniter index with modifications.  MUST MODIFY THIS FILE FOR EACH SITE</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">js</td><td>❮ cleanblog js files</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">robots.txt</td><td>❮ standard search engine robots where you may disallow the search spiders</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">themes</td><td>❮ the majority of the js and css files required by Ignition app</td></tr></table>\r\n\r\nDirectory: writeCODEUSET\r\nThis directory contains directories with write permissions required by the web server.  You must always carefully plan and set write permissions in directories on a public server, especially those in the program execution path (in this case PHP) which is set and limited in the web server.<br><table><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">cache</td><td style=\"width: 500\">❮ CodeIgniter, web cache (if enabled)</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">debugbar</td><td>❮ CodeIgniter, used in debug mode</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">logs</td><td>❮ CodeIgniter logging, normally disabled</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">session</td><td>❮ CodeIgniter sessions if using file system session system (vs database)</td></tr><td></td><td style=\"width: 200px; vertical-align: top\">uploadCODEUSET</td><td>❮ User content upload.  Set in index.php</td></tr></table>\r\n\r\n<b>GETTING STARTED:</b>\r\nSystem Requirements: You must be running a suitable web server such as a modern version of Nginx or Apache. You must have installed and working with your web server PHP 8.1 - PHP 8.3. Many distributions are already running 8.4 PHP. This may present issues. If you have such issues, see below for downgrading to 8.3 PHP which should be fairly straitforward especially for Debian users. Additionally, the database that you choose must be compatible with the PHP version that you are running (for example 11.6.2-MariaDB). This is not normally an issue if your database is fairly recent. Additionally, PHP requires several extensions in order to function with Ignition. For a Debian based install (including most Ubuntu distributions), see below section titled Install PHP for Ignition. If you are running Arch or some other OS variant, simply search for the equivalent packages using pacman/rpm, etc, based on the package list below. If you wait to do this, you will likely receive warnings and errors after you activate the system. Simply add the missing modules following the instructions below.\r\n1) Download the Ignition source code as zip or clone the Github repository\r\n2) Move the source code into your web sites, application directory. I like to use /home/web/myignition.com/web\r\n3) Create two databases, set the passwords and read-in the databases located in the web/mysql directory.  See the file named web/.env for the database names and passwords.  They are named ignitionbase.sql and ignition_taz.sql.  If you are runing mariadb, there is a bash script in the web/mysql directory named setupdb which will create the two databases, set the default passwords and populate them with the supplied data.  From the command line cd into the /home/web/myignition.com/web/mysql directory and run the script ./setupdb\r\n4) Set your web server configuration (usually nginx or apache) to the document root which is web/public and index.php. This usually involves changing the default values in the sites-available and sites-enabled folders to the location where you installed your application. If you used the default this would be /home/web/myignition.com/web/public. The public subdirectory is added for security purposes as a part of CodeIgniter4.\r\n5) You must set the name of your website domain name in web/appCODEUSET/Site/SiteConfig.php using the SERVERBASENAME variable.  For example myignition.com.  This must match the name given for your web server.  Simply edit the file SiteConfig.php and input your domain name where it says SERVERBASENAME.\r\n6) Edit and set the web/mountassets scrtipt. In UNIX this script shall mount <em>read only</em> the web/writeCODEUSET/uploadCODEUSET directory on the web/public/assets directory. This is necessary in order to not allow direct write access to the assets directory. By default this file does nothing. Edit and run this file to mount this directory. If you set up the site app directory to /home/web/myignition.com, simply uncomment the sudo mount command and run it as it is.\r\n7) Open your web browser and point it to <b>http://myignition.com</b>.  See Trouble shooting if you receive an error or this does not work.  You will also likely need to set this site name in your local hosts file in order for your operating system to identify the domain name myignition.com as being a localhost entry. In order to login as super admin go to the login url: <b>http://myignition.com/login</b> and enter the username of <b>admin@myignition.com</b> and leave the password empty or set to any value. WARNING: You must please never deploy this site to a public server without setting a password first which you may do in the user profile screen.  Additionally, you may set the login url (\"login\" by default) to a custom or obscure and hard to guess value such as loginX5T3A2Z1, in SiteConstants.php by setting the variable named LOGINURL.\r\n\r\n<b>Trouble shooting:</b> issues at this point, unfortunately, are extremely common. They can range the gambit from web server misconfigurations, missing binaries to a database server not properly set up or missing data. Fortunately, there is an eager and supportive community awaiting your questions, which probably have already been answered many times. Type a description of your next issue into your favorite search engine and you will likely find your answer already given and ready for you. This really is your opportunity to learn new stuff!\r\n\r\n8) Go to work customizing the default Ignition site, adding your custom banner (web/public/images/site-banner.jpg), menu entries (web/appCODEUSET/Site/MainMenu.php) and home page (web/appCODEUSET/Site/Channels/Home). If you skipped down to GETTING STARTED and you find yourself customizing the Ignition system, please take the additional time to read this entire README file. The time taken will likely save you time and energy in the long run. Learn about how the RAD system called AutoForm can save you a great many programming hours in the future and read the file directory structure above so that you will know your way around your new application development tool.\r\n\r\n<b>Configure Ignition</b>\r\n\r\nYou will find your web application configurations to be fairly simple and straightforward with Ignition. Most Ignition configuration values are set in the two files named SiteConfig.php and SiteConstants.php, both located in the web/appCODEUSET/Site directory. Also in this directory are other application specific files which you may customize as required as follows: AdminMenu.php; MainLayout.php; and MainMenu.php. As their names imply it should be fairly easy to figure out how they effect the application.\r\n\r\n<b>GET RAD WITH AUTOFORM</b>\r\n<em>GET STARTED WITH IGNITION <b>RAD</b> (Rapid Application Development)</em>\r\n\r\nGetting started with RAD in Ignition is fun and easy.  Use this RAD tool, called AutoForm, to build a fully functioning app with database in just one hour!\r\nWith this RAD tool you shall greatly increase your coding productivity by building in minutes what would normally take several hours to complete.  Ignition IForm plus AutoForm allows one to make a simple MVC module with a single database table including a record index listing and input form fast and easy.\r\n\r\nAs your new application module develops your may easily increase functionality by replacing the AutoForm system with the IForm object form methods.\r\n\r\nExamples of AutoForm employed within the Ignition Framework include the following:<br>Asset<br>Cateogry<br>UserType<br>Error Logs<br>AuthorAdmin<br>Logins<br>Tazuserlogin\r\nSee web/appCODEUSET/Ignition/Base/BaseController.php for the $baseIChannels array and check out each of these examples to better understand how the AutoForm system functions.\r\n\r\nFor your application specific AutoForms you will add to a similar array located in web/appCODEUSET/Site/SiteConfig.php.  Look in this file for the array named $siteIChannels.  There you will find the sample AutoForm already set and waiting which you may modify to suit your application requirements.<br>public $siteIChannels =[...];\r\n\r\n<b>Steps for building a fully functional AutoForm system using just one file that you will customize:</b>\r\n1) Add an array entry in the $siteIChannels array.  This will be the name of your AutoForm. Add, for example, \'employees\' in this array variable (open and edit the file named web/appCODEUSET/Site/SiteConfig.php):\r\n    public $siteIChannels = [<br>  \'sample\'    => \'Site/Channels/Sample/MC/Sample.autoform.php\',<br>  \'employees\'    => \'Site/Channels/Employees/MC/Employees.autoform.php\'<br>];\r\n2) Add an entry in the routes located in web/appCODEUSET/Site/Routes/SiteRoutes.php (copy and paste from the sample AutoForm). For example:<br>$routes->add(\'employees\' . $GLOBALS[\'KEYCODE\'] . \'/(:segment)\', \'\\Ignition\\AutoForm\\MC\\Controller::$1\');<br>$routes->add(\'employees\' . $GLOBALS[\'KEYCODE\'] . \'/(:segment)/(:any)\', \'\\Ignition\\AutoForm\\MC\\Controller::$1/$2\');<br>$routes->add(\'employees\' . $GLOBALS[\'KEYCODE\'], \'\\Ignition\\AutoForm\\MC\\Controller::index\');\r\n3) Create a new table in your database (default: mysql/ignitionbase.sql).  The table structure would include the fields required for your new RAD app module.  In the employee example you may wish to create an employees table and include fields such as employee_firstname, employee_lastname, employee_city, employee_state, employee_zip.  In summary you may use the mariadb-dump command on the command line: <em>mariadb-dump ignitionbase > ignitionbase-modify.sql</em>, and edit in the new table using your text editor (edit: ignitionbase-modify.sql).  Then read the table back into the running database using the mariadb command, also on the command line: <em>mariadb ignitionbase < ignitionbase-modify.sql</em>. \r\n4) Build the one file to contain the 3 required elemental areas for an AutoForm.  Open the file named Site/Channels/Sample/MC/Sample.autoform.php and use save as to save it to Site/Channels/Employees/Employees.autoform.php. Next, change these specific file areas as required: 1) AutoForm variables, 2) Index array entry <em>$iIndex = [...]</em> and 3) Form aray entry <em>$iForm = [...]</em>.  Study the forenamed example file for a better understanding of these required areas.\r\n5) Add your new AutoForm module to the RBAC list contained in the SiteConfig.php file. If you do not, the application will return an error, access not authorized. Open web/appCODEUSET/Site/SiteConfig.php. Scroll down to the array titled rbacLoginAdmin. Find the end of the array and add in your new AutoForm, in the example this would be called employees and should match the name set in your Routes. You can usually just copy and paste from another entry, for example the system entry. However, it is very important from a security standpoint to understand the settings in the RBAC entries. See below RBAC Application Security for more information about this key security system.\r\n\r\nNow, you may access your new, fully functioning AutoForm by pointing your browser to the name you gave and set in Routes. For example:\r\nhttp://myignition.com/employees-[KEYCODE]/\r\nKEYCODE is your session key which is generated automatically after you login. In the example given you must login in order to access this new site module system you just added. For example if you just logged in, you will se the following URL:\r\nhttp://myignition.com/controlpanel-[KEYCODE]/\r\n\r\nSimply replace the word controlpanel in the url with the word employees. If you followed these instructions and were able to get this running in just a few hours, you will save yourself many hours in the future by applying this same system when building new modules for your applications.\r\n\r\nAdditionally, what makes AutoForm a lasting and extremely useful addition to your development toolbox is the fact that it is built on top of the IForm system. The IForm is an object class for making form systems that maybe directly employed as yet another RAD tool. Examples of the implementation of IForm is in the following Ignition modules: Assets; Author; Blog; Login; Page; and User. Study each of these modules located in the Ignition directory and look for the IForm implementation, usually located in the CI view file which you may find located in the Views subdirectory for each of the named program modules.\r\n\r\nIForm will likely become a welcome addition to your application development projects due to its time saving, built-in functions. Furthermore, because AutoForm is built ON TOP of IForm, you may remove all or just portions of your AutoForm system for each module that you create with it. In this way AutoForm is open ended and never a dead end if further customization, expansion or modification is required.\r\n\r\n<b>Theme Setup in Ignition</b>\r\nThemes are easily applied to your Ignition application. There are two categories of themes in Ignition, main and app (also called admin). The default main theme is the file web/Site/MainLayout.php file. The app theme file is set by default to Ignition/Base/app-layout.php file. The main theme file name may be changed at any time by changing the value stored in $mainLayoutFile in the Ignition/Base/BaseController.php file. This can be changed dynamicaly during run time. This would usually be done in the constructor of the controller that instantiates the BaseController. This allows one to build their app with more than one theme file. Study the built-in modules such as User (located in the Ignition directory) for an implementation example of how to change the theme in the constructor during execution. If only one theme is required this is not necessary.\r\n\r\nThe main theme is called by default. This is done automatically when the CodeIgniter view is invoked in the controller. The standard CI view is called from the Ignition wrapper function RenderTheme() which incorporates the various theme attributes and security features of Ignition. RenderTheme() is the central calling function to activate and call web pages and applications throughout Ignition. If the controller is to call the app (vs main) function then the variable AppLayout should be set to TRUE. Example from the Ignition Blog controller:\r\n\r\n return $this->RenderTheme(\'form\', [<br>  \'model\' => $model,<br>  \'formType\' => \'new\',<br>  \'AppLayout\' => true,<br>  \'errors\' => $model->validationErrors<br> ]);\r\n\r\n<b>RBAC Application Security</b>\r\n\r\n<em>RBAC Application Security System Greatly Hardens Your Web App Against Malicious Probes</em>\r\n\r\nThe RBAC system consists of a variable array set in SiteConfig.php that limits controller access to only those controller names listed in the array. Furthermore, the RBAC system incorporates a global variable named KEYCODE, which is a 25 character random string. This value must be passed in the URL and must match the value stored in the server session values in order to grant access to the called controller. This greatly limits access to only controllers where the KEYCODE is known and supplied by the site visitor. Vulnerable application URLs cannot be called without this code. This greatly limits the attacker\'s options to only public site URLs and not application URLs.\r\n\r\nThis entire system is implemented automatically with the exception of having to maintain the arrays in SiteConfig.php that are authorized routes for the application.\r\n\r\n<b>Multi-Language Support</b>\r\n\r\nIgnition offers two options for providing visitor site support for multiple languages. The two options are as follows: 1) language selection based on locale specific URLs. For example: en.williamsonsoftware.com and es.williamsonsoftware.com. 2) language selection based on SiteConfig->userLocale which can be set based on user preferences (in the user record) or the default site setting in SiteConfig which can be dynamically adjusted based on the visitor\'s IP address.\r\n\r\nIf multi language support is not required, simply set the $multiLanguage variable in SiteConfig to FALSE. To set your application to URL based language support set the AUTOLANG constant in SiteConstants to TRUE.\r\n\r\n<em>Setting Up Multi-Languages</em>\r\n\r\nThe two variables to set in SetContants are as follows:<br>  define(\"AUTOLANG\", TRUE);		   // if true, will determine language based on URL otherwise SiteConfig->userLocale<br>  define(\"DEFAULTLOCALE\", \"en\");       // default locale, localized language, for site\r\n\r\nThe two variables to set in SiteConfig are as follows:<br>  public $languages = [\'en\' => \"English\", \'es\' => \"Spanish\", \'zh\' => \"Chinese\"];     // this tells ignition which languages to support and provides options on the language selection menu<br>  public $multiLanguage = true; // general setting to turn multi-languages support on and off\r\n\r\n<b>HREFLANG Link Support</b>\r\nIf multi-language support is activated and AUTOLANG is set to TRUE, then the Ignition layout rendering engine will automatically generate SEO friendly hreflang meta data in the header. This tells the search engines how to locate the various languages for your site based on the locale for each language. This only works when the site is set to option 1, language selection based on locale specific URLs (i.e. AUTOLANG is set to TRUE).\r\n\r\n<b>Create a New Ignition Application</b>\r\nIn order to create a new Ignition application, you must copy the entire application top level directory structure into a new application directory (see Ignition File Structure above). After you copy all files into a new directory you will need to modify several CodeIgniter configuration files. These are located in the web/appCODEUSET/Config directory, as follows:<br><table><tr><td style=\"width: 30px;\"></td><td style=\"width: 200px; vertical-align: top\">Database.php</td><td style=\"width: 500\">❮ Set new database name</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">Session.php</td><td>❮ You may wish to modify your session type (file or database) and timeout length as well as site cookie name</td></tr><tr><td></td><td style=\"width: 200px; vertical-align: top\">Routes.php</td><td>❮ If the Site/Routes/SiteRoutes.php is constraining, add new routes</td></tr></table>\r\n\r\nAdditionaly, you will need to set your database values in the web/.env file. This is where you will set your databases names, user names and passwords.\r\n\r\nConsult the CodeIgniter documentation for more information. You must also set the database name, DBGROUP, and IGNITIONCD in index.php and the basic configuration in SiteConfig.php and SiteConstants.php. See Configure Ignition and Theme Setup in Ignition above.\r\n\r\n<b>Install PHP for Ignition</b>\r\n<em>If you are running the wrong version of PHP you may switch between versions using the following instructions to change your distribution to run PHP 8.3, for example:</em>\r\n# modify debian 13 to use php 8.3<br>apt-get install -y apt-transport-https lsb-release ca-certificates wget <br>wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg<br>echo \"deb https://packages.sury.org/php/ $(lsb_release -sc) main\" | sudo tee /etc/apt/sources.list.d/php.list <br>apt-get update<br>apt-get install -y php8.3\r\n\r\n<em>You must install several PHP extensions in order to run Ignition</em>\r\n\r\n# php and web server related<br>apt-get install php8.3-xml php8.3-intl php8.3-curl php8.3-gmp<br>apt-get install php8.3-mysql libaprutil1-dbd-mysql php8.3-http php-memcache php-memcached php8.3-gd php8.3-fpm<br>apt-get install php8.3-mcrypt php-apcu php8.3-cli php-pear php8.3-mbstring # not available php-fdomdocument php-dompdf\r\n\r\n# install certbot in order to add SSL to your web server<br>apt-get install python3-certbot<br>apt-get install python3-certbot-nginx\r\n\r\n<b>Tips and Tricks</b>\r\n\r\nAmong the most valuable tools that I use for application development are the command line tools <em>find</em> and <em>egrep</em>. These are valuable to me because I often need to search for bits of code, names of functions, files and the like within the hundreds of application files that I must manage for any given project. For example, I may remember that I have a program, a function, that searches for an item in a string list and that has items that are delimited by a | (bar), but I may be unable to remember the name of the function that does this. The following command can then be used to search, traversing all sub directories:<br>egrep -R delimit | grep \'|\'\r\nThis returns the following:<br>Library/IHelper.php:// ----- check and see if one value exists within a list of values delimit by |<br>Library/IHelper.php:    function array2string($array, $delimiter = \'|\', $showKeys = FALSE)\r\n\r\nAfter a quick search in the IHelper.php file, I find my tool is named \'InList()\'. Egrep is particularly useful when trying to work with a new library that was written by someone else where I may know little to nothing about how it is designed or how it functions. By judiciously employing the egrep command I can locate text fragments or clues within perhaps thousands of program files to track down the ability to perform some bit of magic or function within the greater application.\r\n\r\nThe find command is useful for locating files and directories with a specific name. For example, I would like to find a file AdminMenu.php, but cannot remember where it was located. This is quickly accomplished with the following command:<br>find . -name \"AdminMenu.php\"\r\n\r\n<b>Write out your code in plain words first</b>\r\n\r\nOne of the greatest coding tips that I ever received was also one of the simplest: \"Write out your program in plain words before writing the code\". This simply means before beginning to input code, write out the basic objective of the program and what are the steps it will take in a simple, clear way. For example: \"a program that allows visitors to fill out a form and click on a button to buy something\". Then you can break down the project into the following steps: \r\n1) Present a form that has purchase options and includes a Confirm Order button.<br>2) Give them a pull down list of product items to select from and a field to input the quantity.<br>3) They must input their name, email and address.<br>4) When they click the Confirm Order button it will total the order and give them a new screen with various payment options.\r\n\r\nWithout wasting time trying to hold all of these various functions in one\'s mind and trying to keep them in order, we have a clear building plan that can be further broken down into more steps if necessary. Now, one only must think about writing code for each step, knowing that the complete objective is being built one piece at a time.\r\n\r\n<b>Use the nmap command frequently to determine what listeners you may have running on your localhost</b>\r\n\r\nNmap is not just for penetration testing. It can also be used to check to see if your web server, for example nginx, is alive and responsive as well as your database server. For example the command may be entered\r\n> nmap localhost\r\n\r\nWhich may return the following output:\r\n\r\nStarting Nmap 7.95 ( https://nmap.org ) at 2026-03-28 12:36 MST<br>Nmap scan report for localhost (127.0.0.1)<br>Host is up (0.00024s latency).<br>Other addresses for localhost (not scanned): ::1<br>Not shown: 996 closed tcp ports (conn-refused)<br>PORT     STATE    SERVICE<br>80/tcp   open     http<br>443/tcp  filtered https<br>3306/tcp open     mysql\r\n\r\nNmap done: 1 IP address (1 host up) scanned in 1.38 seconds\r\n\r\nThis shows us that we have 3 ports that have responded with one port filtered, 443, possibly by a firewall. Port 80 is open and likely a web server and port 3306 is open and likely an sql server. If this was run locally on my computer it would confirm to me that my webserver and sql servers are alive and listening on their respective ports.\r\n\r\n<b>Use the ps command to get insights into processes running on your system</b>\r\n\r\nThe ps command can be very useful in Linux when one may be looking to determine if a process is running, what its process number is and who the owner is of the process. You may ignore the final entry in each as these are just the ps command returning the process name entry within the actual grep command. Here are just a few examples.\r\n\r\n> ps aux | grep mariadb\r\nmysql       1672  0.0  1.5 694768 92648 ?        Ssl  03:38   0:09 /usr/bin/mariadbd<br>mark       18048  0.0  0.0   6528  3784 pts/3    S+   12:43   0:00 grep mariadb\r\n> ps aux | grep nginx\r\nroot        1640  0.0  0.0  18180  1868 ?        Ss   03:38   0:00 nginx: master process /usr/bin/nginx<br>http        1641  0.0  0.0  18184  5296 ?        S    03:38   0:00 nginx: worker process<br>mark       18062  0.0  0.0   6528  3908 pts/3    S+   12:43   0:00 grep nginx\r\n> ps aux | grep php\r\nroot        1692  0.0  0.2  84776 13492 ?        Ss   03:38   0:01 php-fpm: master process (/etc/php/php-fpm.conf)<br>http       17077  0.3  0.3  91528 19636 ?        S    12:15   0:05 php-fpm: pool www<br>http       17080  0.3  0.2  89464 17316 ?        S    12:15   0:05 php-fpm: pool www<br>http       17088  0.2  0.3  91724 19624 ?        S    12:15   0:04 php-fpm: pool www<br>mark       18076  0.0  0.0   6528  3920 pts/3    S+   12:43   0:00 grep php\r\n\r\nIn the final entries for nginx and php, the users listed are root and a regular user named http. This is because these applications run initially as root but then spawn under the name of a regular user as a security mechanism. The http user has fewer privileges then root and a compromise of either service resulting in the running a system shell or other potentially dangerous application would likely be less harmful.\r\n\r\nKnowing the process number you can also kill it if it goes rouge or you are unable to stop it using your system control mechanism. For example the following command would stop the web server /usr/bin/nginx:\r\n> kill 1640\r\n\r\nIf this did not work because a process has gone zombie the following command usually works:\r\n> kill -s 9 1640\r\n\r\nThe -s is part of the kill command and calls it with signal value of 9 which usually brings about a less graceful but more absolute forced halt to the process.\r\n\r\nIf you do not know the process number or are too lazy to look for it, the killall command actually finds the process for you searching by name and then kills it, or all of the processes by that name. For example the above nginx server could also be stopped with the following command:\r\n> killall nginx\r\n\r\n','Ignition','\'Ignition Open Source\' \'Ignition REAME.md\'','ME Williamson',11,1,'2026-03-15 16:14:04','','',1,1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

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
(5,'2023-11-16 11:18:42','2026-03-21 14:06:36',NULL,3,'About','en','about','<div class=\"container\"><div class=\"row\"> <div class=\"mx-auto\" style=\"margin-top:0px; margin-left: 25px; margin-right: 25px; margin-bottom:40px\"><h1>ABOUT</h1><br>\r\n</div></div></div>','','',1),
(6,'2023-11-16 11:19:12','2026-03-21 14:06:36',NULL,3,'Acerca De','es','acercade','','','',1),
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
(1,'2025-12-03 10:41:32','2026-03-21 14:00:42',NULL,0,'','¦-\'f\n\0\0','°\0\0\0','0�','½@^r9mÉ§ÞA&','Maxwell','H','Ignition','','admin@myignition.com','*IAMMAGIC*','�I��0&��K\'8)�����2��','2025-12-03 10:41:32','ðäAwªck	\r~bý3&ôG¼ÃN­ÖWùìeòGBÈìz((£l','O±X(`ïr7ä´m I»}¨Õ$úÓaþþÿ3·VBè�',NULL,1,'','','','USA','','','Dä4ÏÎó»¦L\0áô}>M©4r ~Íi;»çe»ñÖ#+','C44376Y4C1I6J2G6P1Q8X515X6P177Z1','C2N64738D1E1J1V8H8U216X4C35395F8','','myworld.cash','P\0\0\0','','','','','','','','',1,1);
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

-- Dump completed on 2026-03-28 18:01:28
