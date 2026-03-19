<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        SiteConstants.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

    // ----- determine if authenticated user or not, set keycode from url if presents
    $session = service('session');
    $editMode = FALSE;
	define("SERVERBASENAME", "ignitionbase.williamsonsoftware.com");
	define("HTTPPROT", $_SERVER['REQUEST_SCHEME']);
	define("LOGINURL", "login");

	// ----- extract first segment of url after domain (controller)
    $urlParams = $_SERVER['REQUEST_URI'];
	if ($secondSlash = strpos($urlParams, "/", 1))
		$urlParams = substr($urlParams, 0, $secondSlash);

    if ($session->has('session_data')) {

		// ----- set session globals for user
		$sessionData = $_SESSION['session_data'];

		define("USERNAME", $sessionData['user_name']);
		define("UT", $sessionData['user_type']);

		// ------ assuming any controller with name len greater than 25 & code begins with a dash, it includes keycode
		if (strlen($urlParams) > 25 && substr(substr($urlParams, strlen($urlParams) - 25, 25), 0, 1) == '-') {
			$GLOBALS['KEYCODE'] = substr($urlParams, strlen($urlParams) - 25, 25);
            $editMode = TRUE;
		} else {
			$GLOBALS['KEYCODE'] = "__INVALID__";  // rbac: better not flag match in siteconfig
		}

	} else {

        // ----- check for expired session
		if (strlen($urlParams) > 25 && substr(substr($urlParams, strlen($urlParams) - 25, 25), 0, 1) == '-') {
            header('Location: ' . HTTPPROT . '://' . SERVERBASENAME . '/' . LOGINURL);
            exit();
        }

		// ------ set values for non authenticated user
		define("USERNAME", FALSE);
		$GLOBALS['KEYCODE'] = "__INVALID__";
        define('UT', 0);
        define('UR', 0);
	}

	$GLOBALS['LANGCODE'] = "";
	define("AUTOLANG", TRUE);		     // if true, will determine language based on URL otherwise SiteConfig->userLocale 
	define("DEFAULTLOCALE", "en");       // default locale, localized language, for site
	define("NOTSET", -1);
	define("MENUON", TRUE);			     // determines if a menu is being used for main site 
	define("REMOTEPIN", "12345");         // 4 digit, required for remote blog article access
	define("DNABASEENABLE", TRUE);       // enable basic dna40 number processing and specialized amendable table type
	define("DNASERVERENABLE", FALSE);     // if this deployent requires advanced DNA server functions
    $GLOBALS['DNAFORM'] = FALSE;         // used to tweak basemodel to exclude specific Ignition centric features such as the 5 standard table fields (created, updated, deleted, id and active) 

	define("DBPREFIX", "ignition_");        // dna db prefix 
    define('IGDBPREFIX', "ignition_");       // ignition db prefix
    define("CODEPOSTFIX", 'QRA373');       // used to identify the specific site codes in directories and sessions table
	define("DEFAULTDBGROUP", "ignitionbasic");   // default db table group, STATIC
	$GLOBALS['DBGROUP'] = 'ignitionbasic';       // current db table group, DYNAMIC
	define("USERVIEWPATH", 'Ignition\User\Views');    // user views location.  default: Ignition\User\Views
	define("USERFORMSPATH", 'Ignition\User\Forms');   // user forms location  default: Ignition\User\Forms
	define("APPHOME", "");	 // application home screen, breadcrumbs
	define("CUSTOMLANG", 'ignition');
    define("SPARKS", FALSE);             // include sparks in blog
    define("SUCCESS", TRUE);             // what we are playing for!
    define('AUTOTRIM0', TRUE);           // iform, autotrim zeros from demimal value
    define('BLOGSITE', TRUE);           // if wish to include blog
    define('LOGACCESS', TRUE);           // if enabled, will store a record of each login
    define('DNA_SALT_BYTES', 24);
    define('HOMEURL', '' . $GLOBALS['KEYCODE']);    // base route for app home screen
    define('APPURL', '' . $GLOBALS['KEYCODE']);         // main controller route common app

    // ----- These are the User Types and are meant to restrict/allow
    //       access to files, features and data within the app
    //       These are established at login and pulled from user table 
    //       and stored in the session variables throughout visit
    define('SAN', 8583234332);   // for security set this to a random large number.  this could be stored on disk and chaged every 24 hours
    define("VISITOR", 0);        // zero privilege, anyone who comes upon this website
    define("SUPERADMIN", SAN);   // 1 IN DB, BUT CHANGED AT LOGIN. highest privilege, may change any aspect of the site 
    define("SYSADMIN", 2);       // adminstrator of users and access 

	// ----- set menu
	switch (TRUE) {
		case (UT == 0) :
			$GLOBALS["APPMENU"] = "MainMenu";	     // main public site menu
			break;
		case (UT >= 10 && UT < 100) :
			$GLOBALS["APPMENU"] = "AdminMenu";	 // specialized app menu
			break;
		case (UT < 10 || UT == SAN) :
			$GLOBALS["APPMENU"] = "AdminMenu";	     // admin menu
			break;
		default :
			halt("Error in SiteConstants. UT does not match an Ignition standardized user type.");
			break;
	}


    // ----- set js, css, siteicon and colors according to page requirements

    // ----- these constants provide some basic color theming for
    //       your website
    define("THEMECOLOR", "#ec521f");     // theme base color for website
    define("ACCENTCOLOR", "#344765");    // ff0000ff theme accent color for website
    define("MENUBG", "#3a93dd");         // theme menu background color for website
    define("MENUFONT", "#ffffff");       // theme menu font color for website
    define("MENUSELECT", "#0099ffff");   // theme menu font color for website
    define("BLOGFONTCOLOR", "#000000");  // theme blog font color for website
    define("SHADOWCOLOR", "#000000");	 // theme blog vista main site menu 
    $GLOBALS['ADMINMENUCOLOR'] = '344765';
 
    define('ADMINMODE', (UT == SUPERADMIN) && $editMode);

    // ----- these are primarily for value by offset purposes
    $GLOBALS['SYMBOL_SETS'] = [
   		'REX8'   => "12345678",
   		'REX32'  => "12345679ABCDEFGHIJKLMNPQRTUVWXYZ",
   		'BASE64' => "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_",
   		'HEX'    => "0123456789ABCDEF",
   		'BASE10' => "0123456789",
        'FAN'    => "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ-@."
   	];
    $GLOBALS['DECSEP'] = '.';
    $GLOBALS['NUMSEP'] = ',';
    define('UNIX2DNA', 1355295600);     // convert unix epoch time to dna epoch

    // ----- running in debug
    if (getenv('CI_ENVIRONMENT') == 'development')
        $GLOBALS['DEBUGSTOP'] = FALSE;

?>
