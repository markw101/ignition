<h2>Welcome to Ignition: the CodeIgniter 4 extension that will give you the fastest and most extensible headstart on your website and web application development projects!</h2>
<h4>Download and install Ignition and jump on the fast track to a fully functioning CodeIgniter 4 website. Build an entire website application with a menu system, Clean Blog home page banner, footer, blogging system and user management functions within just a few hours.</h4>

For a sample website running a stock version of this open source software visit the example implementation here:<br>en.ignitionbase.williamsonsoftware.com

Attribution:<br>✩ CodeIgniter 4; CodeIgniter Foundation; https://codeigniter.com; MIT License<br>✩ Colorlib; Colorlib; https://github.com/colorlibhq/; MIT License<br>✩ Fontawesome; https://fontawesome.com/; https://fontawesome.com/support; CC 4.0<br>✩ jQuerry; John Resig; https://jquery.com; CC0<br>✩ Clean Blog by Bootstrap; Matt Harzewski; http://jekyllthemes.org/themes/clean-blog/; GPL 3.0<br>✩ Twitter Typeahead.js; Jake Harding; https://github.com/twitter/typeahead.js/; MIT

<blockquote>Simple vs Easy<br>What is simple is usually easy, what is easy is often not simple.  Simplicity and ease should not be confused.  Simplicity should be the target which shall increase functional efficiency, but shall not bolster ease to the detriment simplicity.  Excessive complexity limits design potentials.</blockquote>
<h2>Features List</h2>
Ignition gives you the following:<br>✔ A built-in blogging system that includes the following: Categories; Popular Posts; Authors table; and a Tag Cloud<br>✔ Extensive multi language support<br>✔ Fastest path to a CodeIgniter 4 website<br>✔ App security with numerous security hardening modifications<br>✔ An app control panel for Administrators and logged-in Users<br>✔ A flexible but not overly complex site theming system<br>✔ Built in Twitter typeahead bundle<br>✔ Ignition AutoForm system with RAD (Rapid Application Development) which allows one to create an entire MVC system (Model, View, Control), with database storage with ability to index and edit records with just one file in less than one hour.<br>✔ User support with user table, user types, user profile record and login/logout capabilities

<b>Instructions</b>
Download Ignition source code from Github by either cloning the repository or go to Download Zip file (usually a button with a download option). For a really quick-start, jump to Getting Started below.

<b>Ignition File Structure</b>
One very important key to knowing your way around Ignition is in learning the layout of the file system. Knowing where your application files are located and how to access and modify them shall be the basis for much of your development work in Ignition.

Ignition is built around a directory structure as follows:<table><tr><td colspan=3>web/</td></tr><tr><td style="width: 30px;"></td><td style="width: 200px; vertical-align: top">appCODEUSET</td><td style="width: 500px;">❮ Directory.  Main application directory</td></tr><tr><td></td><td style="vertical-align: top">ci4CODEUSET</td><td>❮ Directory.  CodeIgniter application files</td></tr><tr><td style="width: 30px;"></td><td style="vertical-align: top">.env</td><td>❮ File.  Set environment variables, especially database names and passwords</td></tr><tr><td style="width: 30px;"></td><td style="vertical-align: top">mountassets</td><td>❮ File.  A shell script that mounts the web/public/assets dir, giving access to uploads</td></tr><tr><td style="width: 30px;"></td><td style="vertical-align: top">public</td><td>❮ Directory.  Public facing directory, designated as document root in Nginx/Apache config</td></tr><tr><td style="width: 30px;"></td><td style="vertical-align: top;">writeCODEUSET</td><td>❮ Directory.  A directory that contains writable directories for your application</td></tr></table>

CODEUSET: This is a code that gets added to program directory names as a security measure.  When your website goes live and is moved to a public web server make sure and set this code to a random number and name the above directories using it.  IMPORTANT: You must maintain the prefixes as follows: app, ci4, write and upload (in the write directory) - when renaming the directories with the new site code.  This code is used to obscure the location of your application files and help reduce the attack surface area within your production web applications.  By keeping secret the program directories it becomes more difficult for an attacker to do directory traversal from inside of your app and upload malicious code or access specific files.  This code is set in the web/public/index.php file and is repeated in several places.  Set the code in the program as a constant in index.php, variable name IGNITIONCD and elsewhere in this file.

Each of the above directories will now be covered.

Directory: appCODEUSET<br>This directory is the main application directory and contains the following structure:<br>   Config<br>   Ignition<br>   Language<br>   Site<br>   Views

The directories contained in this directory are application specific but should ordinarily be essentially left in tact.  In the future, modules added to Ignition such as DNA will go here.  The Config directory contains CodeIgniter configuration files which have many options for modifying the CodeIgniter framework and your Ignition application.  A handful of files, the most important files, that must be modified for each application are covered below in the section titled Configure Ignition.

The Ignition directory contains the Ignition framework.  It is a good idea to avoid making changes to the files in the Ignition directory if at all possible.  That way, as one upgrades the Ignition framework, they may overwrite this directory with a newer version (remember to update the ci4CODEUSET directory also as the CodeIgniter and Ignition versions are matched together).  The developer is encouraged to get to know what is contained in these directories and files to better understand how Ignition functions.

The Language directory contains the language files for each of the languages you may wish to include in your application.  Many of these files link to the default language files within Ignition.  For application specific translations it is recommended to modify the config.php and the ignitionbasic.php files in the respective local sub directories.  The ignitionbasic.php file can be renamed to the name of your application.

The Site directory contains site specific programs and configuration files.  This is where you will configure Ignition for each of your applications as well as add custom modules for your application.  The two main files that require modification are SiteConfig.php and SiteConstants.php.  See the below section titled Configure Ignition for more information about the Site directory as this is where you may best add site specific modules to your application.  These will be added to the Channels directory inside of Site.

The Views directory contains CodeIgniter code that is used for the pager functions and error handling.  Normally, modification of these files is not going to be required.

Directory: ci4CODEUSET
This directory contains the CodeIgniter application.  Occasionally you may wish to access this directory in your application development efforts.  This will likely not often occur as CodeIgniter is a complex library with many internal moving parts that must work together in concert.  However, it is fairly frequently a good practice to study this code and view its internal functions to better understand various error messages or issues that you may encounter in your development work.  Minor modifications in just a few places must be made to CodeIgniter for it to work correctly with Ignition.  These are signified by Ignition comments in the CI source code.

Directory: public
This directory was added as a part of the CodeIgniter framework and is the document root for the web server, usually Nginx or Apache.<br><table><tr><td style="width: 30px;"></td><td style="width: 200px; vertical-align: top">assets</td><td style="width: 500">❮ a read only mount of the uploads directory (see uploadCODEUSET below, and mountassets script)</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">blogvista</td><td>❮ css and js files related to the blogvista (a theme option in Ignition)</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">css</td><td>❮ css file for Ignition, bootstrap cleanblog</td></tr><td></td><td style="width: 200px; vertical-align: top">fonts</td><td>❮ fonts required by Ignition</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">.htaccess</td><td>❮ CodeIgniter web server configuration</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">images</td><td>❮ images required for web application design</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">index.php</td><td>❮ CodeIgniter index with modifications.  MUST MODIFY THIS FILE FOR EACH SITE</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">js</td><td>❮ cleanblog js files</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">robots.txt</td><td>❮ standard search engine robots where you may disallow the search spiders</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">themes</td><td>❮ the majority of the js and css files required by Ignition app</td></tr></table>

Directory: writeCODEUSET
This directory contains directories with write permissions required by the web server.  You must always carefully plan and set write permissions in directories on a public server, especially those in the program execution path (in this case PHP) which is set and limited in the web server.<br><table><tr><td style="width: 30px;"></td><td style="width: 200px; vertical-align: top">cache</td><td style="width: 500">❮ CodeIgniter, web cache (if enabled)</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">debugbar</td><td>❮ CodeIgniter, used in debug mode</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">logs</td><td>❮ CodeIgniter logging, normally disabled</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">session</td><td>❮ CodeIgniter sessions if using file system session system (vs database)</td></tr><td></td><td style="width: 200px; vertical-align: top">uploadCODEUSET</td><td>❮ User content upload.  Set in index.php</td></tr></table>

<b>GETTING STARTED:</b><br>
System Requirements: You must be running a suitable web server such as a modern version of Nginx or Apache. You must have installed and working with your web server PHP 8.1 - PHP 8.3. Many distributions are already running 8.4 PHP. This may present issues. If you have such issues, see below for downgrading to 8.3 PHP which should be fairly straitforward especially for Debian users. Additionally, the database that you choose must be compatible with the PHP version that you are running (for example 11.6.2-MariaDB). This is not normally an issue if your database is fairly recent. Additionally, PHP requires several extensions in order to function with Ignition. For a Debian based install (including most Ubuntu distributions), see below section titled Install PHP for Ignition. If you are running Arch or some other OS variant, simply search for the equivalent packages using pacman/rpm, etc, based on the package list below. If you wait to do this, you will likely receive warnings and errors after you activate the system. Simply add the missing modules following the instructions below.
1) Download the Ignition source code as zip or clone the Github repository
2) Move the source code into your web sites, application directory. I like to use /home/web/myignition.com/web
3) Create two databases, set the passwords and read-in the databases located in the web/mysql directory.  See the file named web/.env for the database names and passwords.  They are named ignitionbase.sql and ignition_taz.sql.  If you are runing mariadb, there is a bash script in the web/mysql directory named setupdb which will create the two databases, set the default passwords and populate them with the supplied data.  From the command line cd into the /home/web/myignition.com/web/mysql directory and run the script ./setupdb
4) Set your web server configuration (usually nginx or apache) to the document root which is web/public and index.php. This usually involves changing the default values in the sites-available and sites-enabled folders to the location where you installed your application. If you used the default this would be /home/web/myignition.com/web/public. The public subdirectory is added for security purposes as a part of CodeIgniter4.
5) You must set the name of your website domain name in web/appCODEUSET/Site/SiteConfig.php using the SERVERBASENAME variable.  For example myignition.com.  This must match the name given for your web server.  Simply edit the file SiteConfig.php and input your domain name where it says SERVERBASENAME.
6) Edit and set the web/mountassets scrtipt. In UNIX this script shall mount <em>read only</em> the web/writeCODEUSET/uploadCODEUSET directory on the web/public/assets directory. This is necessary in order to not allow direct write access to the assets directory. By default this file does nothing. Edit and run this file to mount this directory. If you set up the site app directory to /home/web/myignition.com, simply uncomment the sudo mount command and run it as it is.
7) Open your web browser and point it to <b>http://myignition.com</b>.  See Trouble shooting if you receive an error or this does not work.  You will also likely need to set this site name in your local hosts file in order for your operating system to identify the domain name myignition.com as being a localhost entry. In order to login as super admin go to the login url: <b>http://myignition.com/login</b> and enter the username of <b>admin@myignition.com</b> and leave the password empty or set to any value. WARNING: You must please never deploy this site to a public server without setting a password first which you may do in the user profile screen.  Additionally, you may set the login url ("login" by default) to a custom or obscure and hard to guess value such as loginX5T3A2Z1, in SiteConstants.php by setting the variable named LOGINURL.

<b>Trouble shooting:</b> issues at this point, unfortunately, are extremely common. They can range the gambit from web server misconfigurations, missing binaries to a database server not properly set up or missing data. Fortunately, there is an eager and supportive community awaiting your questions, which probably have already been answered many times. Type a description of your next issue into your favorite search engine and you will likely find your answer already given and ready for you. This really is your opportunity to learn new stuff!

8) Go to work customizing the default Ignition site, adding your custom banner (web/public/images/site-banner.jpg), menu entries (web/appCODEUSET/Site/MainMenu.php) and home page (web/appCODEUSET/Site/Channels/Home). If you skipped down to GETTING STARTED and you find yourself customizing the Ignition system, please take the additional time to read this entire README file. The time taken will likely save you time and energy in the long run. Learn about how the RAD system called AutoForm can save you a great many programming hours in the future and read the file directory structure above so that you will know your way around your new application development tool.

<b>Configure Ignition</b>

You will find your web application configurations to be fairly simple and straightforward with Ignition. Most Ignition configuration values are set in the two files named SiteConfig.php and SiteConstants.php, both located in the web/appCODEUSET/Site directory. Also in this directory are other application specific files which you may customize as required as follows: AdminMenu.php; MainLayout.php; and MainMenu.php. As their names imply it should be fairly easy to figure out how they effect the application.

<b>GET RAD WITH AUTOFORM</b>
<em>GET STARTED WITH IGNITION <b>RAD</b> (Rapid Application Development)</em>

Getting started with RAD in Ignition is fun and easy.  Use this RAD tool, called AutoForm, to build a fully functioning app with database in just one hour!
With this RAD tool you shall greatly increase your coding productivity by building in minutes what would normally take several hours to complete.  Ignition IForm plus AutoForm allows one to make a simple MVC module with a single database table including a record index listing and input form fast and easy.

As your new application module develops your may easily increase functionality by replacing the AutoForm system with the IForm object form methods.

Examples of AutoForm employed within the Ignition Framework include the following:<br>Asset<br>Cateogry<br>UserType<br>Error Logs<br>AuthorAdmin<br>Logins<br>Tazuserlogin
See web/appCODEUSET/Ignition/Base/BaseController.php for the $baseIChannels array and check out each of these examples to better understand how the AutoForm system functions.

For your application specific AutoForms you will add to a similar array located in web/appCODEUSET/Site/SiteConfig.php.  Look in this file for the array named $siteIChannels.  There you will find the sample AutoForm already set and waiting which you may modify to suit your application requirements.<br>public $siteIChannels =[...];

<b>Steps for building a fully functional AutoForm system using just one file that you will customize:</b>
1) Add an array entry in the $siteIChannels array.  This will be the name of your AutoForm. Add, for example, 'employees' in this array variable (open and edit the file named web/appCODEUSET/Site/SiteConfig.php):
    public $siteIChannels = [<br>  'sample'    => 'Site/Channels/Sample/MC/Sample.autoform.php',<br>  'employees'    => 'Site/Channels/Employees/MC/Employees.autoform.php'<br>];
2) Add an entry in the routes located in web/appCODEUSET/Site/Routes/SiteRoutes.php (copy and paste from the sample AutoForm). For example:<br>$routes->add('employees' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');<br>$routes->add('employees' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');<br>$routes->add('employees' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');
3) Create a new table in your database (default: mysql/ignitionbase.sql).  The table structure would include the fields required for your new RAD app module.  In the employee example you may wish to create an employees table and include fields such as employee_firstname, employee_lastname, employee_city, employee_state, employee_zip.  In summary you may use the mariadb-dump command on the command line: <em>mariadb-dump ignitionbase > ignitionbase-modify.sql</em>, and edit in the new table using your text editor (edit: ignitionbase-modify.sql).  Then read the table back into the running database using the mariadb command, also on the command line: <em>mariadb ignitionbase < ignitionbase-modify.sql</em>. 
4) Build the one file to contain the 3 required elemental areas for an AutoForm.  Open the file named Site/Channels/Sample/MC/Sample.autoform.php and use save as to save it to Site/Channels/Employees/Employees.autoform.php. Next, change these specific file areas as required: 1) AutoForm variables, 2) Index array entry <em>$iIndex = [...]</em> and 3) Form aray entry <em>$iForm = [...]</em>.  Study the forenamed example file for a better understanding of these required areas.
5) Add your new AutoForm module to the RBAC list contained in the SiteConfig.php file. If you do not, the application will return an error, access not authorized. Open web/appCODEUSET/Site/SiteConfig.php. Scroll down to the array titled rbacLoginAdmin. Find the end of the array and add in your new AutoForm, in the example this would be called employees and should match the name set in your Routes. You can usually just copy and paste from another entry, for example the system entry. However, it is very important from a security standpoint to understand the settings in the RBAC entries. See below RBAC Application Security for more information about this key security system.

Now, you may access your new, fully functioning AutoForm by pointing your browser to the name you gave and set in Routes. For example:<br>
http://myignition.com/employees-[KEYCODE]/<br>
KEYCODE is your session key which is generated automatically after you login. In the example given you must login in order to access this new site module system you just added. For example if you just logged in, you will se the following URL:<br>
http://myignition.com/controlpanel-[KEYCODE]/

Simply replace the word controlpanel in the url with the word employees. If you followed these instructions and were able to get this running in just a few hours, you will save yourself many hours in the future by applying this same system when building new modules for your applications.

Additionally, what makes AutoForm a lasting and extremely useful addition to your development toolbox is the fact that it is built on top of the IForm system. The IForm is an object class for making form systems that maybe directly employed as yet another RAD tool. Examples of the implementation of IForm is in the following Ignition modules: Assets; Author; Blog; Login; Page; and User. Study each of these modules located in the Ignition directory and look for the IForm implementation, usually located in the CI view file which you may find located in the Views subdirectory for each of the named program modules.

IForm will likely become a welcome addition to your application development projects due to its time saving, built-in functions. Furthermore, because AutoForm is built ON TOP of IForm, you may remove all or just portions of your AutoForm system for each module that you create with it. In this way AutoForm is open ended and never a dead end if further customization, expansion or modification is required.

<b>Theme Setup in Ignition</b>

Themes are easily applied to your Ignition application. There are two categories of themes in Ignition, main and app (also called admin). The default main theme is the file web/Site/MainLayout.php file. The app theme file is set by default to Ignition/Base/app-layout.php file. The main theme file name may be changed at any time by changing the value stored in $mainLayoutFile in the Ignition/Base/BaseController.php file. This can be changed dynamicaly during run time. This would usually be done in the constructor of the controller that instantiates the BaseController. This allows one to build their app with more than one theme file. Study the built-in modules such as User (located in the Ignition directory) for an implementation example of how to change the theme in the constructor during execution. If only one theme is required this is not necessary.

The main theme is called by default. This is done automatically when the CodeIgniter view is invoked in the controller. The standard CI view is called from the Ignition wrapper function RenderTheme() which incorporates the various theme attributes and security features of Ignition. RenderTheme() is the central calling function to activate and call web pages and applications throughout Ignition. If the controller is to call the app (vs main) function then the variable AppLayout should be set to TRUE. Example from the Ignition Blog controller:

 return $this->RenderTheme('form', [<br>  'model' => $model,<br>  'formType' => 'new',<br>  'AppLayout' => true,<br>  'errors' => $model->validationErrors<br> ]);

<b>RBAC Application Security</b>

<em>RBAC Application Security System Greatly Hardens Your Web App Against Malicious Probes</em>

The RBAC system consists of a variable array set in SiteConfig.php that limits controller access to only those controller names listed in the array. Furthermore, the RBAC system incorporates a global variable named KEYCODE, which is a 25 character random string. This value must be passed in the URL and must match the value stored in the server session values in order to grant access to the called controller. This greatly limits access to only controllers where the KEYCODE is known and supplied by the site visitor. Vulnerable application URLs cannot be called without this code. This greatly limits the attacker's options to only public site URLs and not application URLs.

This entire system is implemented automatically with the exception of having to maintain the arrays in SiteConfig.php that are authorized routes for the application.

<b>Multi-Language Support</b>

Ignition offers two options for providing visitor site support for multiple languages. The two options are as follows: 1) language selection based on locale specific URLs. For example: en.williamsonsoftware.com and es.williamsonsoftware.com. 2) language selection based on SiteConfig->userLocale which can be set based on user preferences (in the user record) or the default site setting in SiteConfig which can be dynamically adjusted based on the visitor's IP address.

If multi language support is not required, simply set the $multiLanguage variable in SiteConfig to FALSE. To set your application to URL based language support set the AUTOLANG constant in SiteConstants to TRUE.

<em>Setting Up Multi-Languages</em>

The two variables to set in SetContants are as follows:<br>  define("AUTOLANG", TRUE);		   // if true, will determine language based on URL otherwise SiteConfig->userLocale<br>  define("DEFAULTLOCALE", "en");       // default locale, localized language, for site

The two variables to set in SiteConfig are as follows:<br>  public $languages = ['en' => "English", 'es' => "Spanish", 'zh' => "Chinese"];     // this tells ignition which languages to support and provides options on the language selection menu<br>  public $multiLanguage = true; // general setting to turn multi-languages support on and off

<b>HREFLANG Link Support</b>

If multi-language support is activated and AUTOLANG is set to TRUE, then the Ignition layout rendering engine will automatically generate SEO friendly hreflang meta data in the header. This tells the search engines how to locate the various languages for your site based on the locale for each language. This only works when the site is set to option 1, language selection based on locale specific URLs (i.e. AUTOLANG is set to TRUE).

<b>Create a New Ignition Application</b>

In order to create a new Ignition application, you must copy the entire application top level directory structure into a new application directory (see Ignition File Structure above). After you copy all files into a new directory you will need to modify several CodeIgniter configuration files. These are located in the web/appCODEUSET/Config directory, as follows:<br><table><tr><td style="width: 30px;"></td><td style="width: 200px; vertical-align: top">Database.php</td><td style="width: 500">❮ Set new database name</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">Session.php</td><td>❮ You may wish to modify your session type (file or database) and timeout length as well as site cookie name</td></tr><tr><td></td><td style="width: 200px; vertical-align: top">Routes.php</td><td>❮ If the Site/Routes/SiteRoutes.php is constraining, add new routes</td></tr></table>

Additionaly, you will need to set your database values in the web/.env file. This is where you will set your databases names, user names and passwords.

Consult the CodeIgniter documentation for more information. You must also set the database name, DBGROUP, and IGNITIONCD in index.php and the basic configuration in SiteConfig.php and SiteConstants.php. See Configure Ignition and Theme Setup in Ignition above.

<b>Install PHP for Ignition</b>

<em>If you are running the wrong version of PHP you may switch between versions using the following instructions to change your distribution to run PHP 8.3, for example:</em>

Modify debian 13 to use php 8.3
> apt-get install -y apt-transport-https lsb-release ca-certificates wget

> wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

> echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list

> apt-get update<br>apt-get install -y php8.3

<em>You must install several PHP extensions in order to run Ignition</em>

PHP and web server related
> apt-get install php8.3-xml php8.3-intl php8.3-curl php8.3-gmp

> apt-get install php8.3-mysql libaprutil1-dbd-mysql php8.3-http php-memcache php-memcached php8.3-gd php8.3-fpm

> apt-get install php8.3-mcrypt php-apcu php8.3-cli php-pear php8.3-mbstring # not available php-fdomdocument php-dompdf

install certbot in order to add SSL to your web server
> apt-get install python3-certbot

> apt-get install python3-certbot-nginx

<b>Tips and Tricks</b>

Among the most valuable tools that I use for application development are the command line tools <em>find</em> and <em>egrep</em>. These are valuable to me because I often need to search for bits of code, names of functions, files and the like within the hundreds of application files that I must manage for any given project. For example, I may remember that I have a program, a function, that searches for an item in a string list and that has items that are delimited by a | (bar), but I may be unable to remember the name of the function that does this. The following command can then be used to search, traversing all sub directories:<br>egrep -R delimit | grep '|'

This returns the following:

Library/IHelper.php:// ----- check and see if one value exists within a list of values delimit by |
Library/IHelper.php:    function array2string($array, $delimiter = '|', $showKeys = FALSE)

After a quick search in the IHelper.php file, I find my tool is named 'InList()'.

Egrep is particularly useful when trying to work with a new library that was written by someone else where I may know little to nothing about how it is designed or how it functions. By judiciously employing the egrep command I can locate text fragments or clues within perhaps thousands of program files to track down the ability to perform some bit of magic or function within the greater application.

The find command is useful for locating files and directories with a specific name. For example, I would like to find a file AdminMenu.php, but cannot remember where it was located. This is quickly accomplished with the following command:
> find . -name "AdminMenu.php"

<b>Write out your code in plain words first</b>

One of the greatest coding tips that I ever received was also one of the simplest: "Write out your program in plain words before writing the code". This simply means before beginning to input code, write out the basic objective of the program and what are the steps it will take in a simple, clear way. For example: "a program that allows visitors to fill out a form and click on a button to buy something". Then you can break down the project into the following steps: 
1) Present a form that has purchase options and includes a Confirm Order button.
2) Give them a pull down list of product items to select from and a field to input the quantity.
3) They must input their name, email and address.
4) When they click the Confirm Order button it will total the order and give them a new screen with various payment options.

Without wasting time trying to hold all of these various functions in one's mind and trying to keep them in order, we have a clear building plan that can be further broken down into more steps if necessary. Now, one only must think about writing code for each step, knowing that the complete objective is being built one piece at a time.

<b>Use the nmap command frequently to determine what listeners you may have running on your localhost</b>

Nmap is not just for penetration testing. It can also be used to check to see if your web server, for example nginx, is alive and responsive as well as your database server. For example the command may be entered
> nmap localhost

Which may return the following output:

Starting Nmap 7.95 ( https://nmap.org ) at 2026-03-28 12:36 MST<br>Nmap scan report for localhost (127.0.0.1)<br>Host is up (0.00024s latency).<br>Other addresses for localhost (not scanned): ::1<br>Not shown: 996 closed tcp ports (conn-refused)<br>PORT     STATE    SERVICE<br>80/tcp   open     http<br>443/tcp  filtered https<br>3306/tcp open     mysql

Nmap done: 1 IP address (1 host up) scanned in 1.38 seconds

This shows us that we have 3 ports that have responded with one port filtered, 443, possibly by a firewall. Port 80 is open and likely a web server and port 3306 is open and likely an sql server. If this was run locally on my computer it would confirm to me that my webserver and sql servers are alive and listening on their respective ports.

<b>Use the ps command to get insights into processes running on your system</b>

The ps command can be very useful in Linux when one may be looking to determine if a process is running, what its process number is and who the owner is of the process. You may ignore the final entry in each as these are just the ps command returning the process name entry within the actual grep command. Here are just a few examples.

> ps aux | grep mariadb

mysql       1672  0.0  1.5 694768 92648 ?        Ssl  03:38   0:09 /usr/bin/mariadbd<br>mark       18048  0.0  0.0   6528  3784 pts/3    S+   12:43   0:00 grep mariadb
> ps aux | grep nginx

root        1640  0.0  0.0  18180  1868 ?        Ss   03:38   0:00 nginx: master process /usr/bin/nginx<br>http        1641  0.0  0.0  18184  5296 ?        S    03:38   0:00 nginx: worker process<br>mark       18062  0.0  0.0   6528  3908 pts/3    S+   12:43   0:00 grep nginx
> ps aux | grep php

root        1692  0.0  0.2  84776 13492 ?        Ss   03:38   0:01 php-fpm: master process (/etc/php/php-fpm.conf)<br>http       17077  0.3  0.3  91528 19636 ?        S    12:15   0:05 php-fpm: pool www<br>http       17080  0.3  0.2  89464 17316 ?        S    12:15   0:05 php-fpm: pool www<br>http       17088  0.2  0.3  91724 19624 ?        S    12:15   0:04 php-fpm: pool www<br>mark       18076  0.0  0.0   6528  3920 pts/3    S+   12:43   0:00 grep php

In the final entries for nginx and php, the users listed are root and a regular user named http. This is because these applications run initially as root but then spawn under the name of a regular user as a security mechanism. The http user has fewer privileges then root and a compromise of either service resulting in the running a system shell or other potentially dangerous application would likely be less harmful.

Knowing the process number you can also kill it if it goes rogue or you are unable to stop it using your system control mechanism. For example the following command would stop the web server /usr/bin/nginx:
> kill 1640

If this did not work because a process has gone zombie the following command usually works:
> kill -s 9 1640

The -s is part of the kill command and calls it with signal value of 9 which usually brings about a less graceful but more absolute forced halt to the process.

If you do not know the process number or are too lazy to look for it, the killall command actually finds the process for you searching by name and then kills it, or all of the processes by that name. For example the above nginx server could also be stopped with the following command:
> killall nginx

