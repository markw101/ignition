<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        SiteConfig.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Site;

class SiteConfig extends \Config\App
{

    // ----- ESSENTIAL SITE CONFIGURATION - others set in app/Site/SiteConstants.php
    //       and in CI standard file config in app/Config/App.php 
	public $siteLogo = '/images/ignition-logo.png';
	public $adminLogo = '/images/ignition-logo.png';
    public $title = '';
    public $siteDescription = '';
    public $mastheadImage = '/images/ignition-banner.jpg';
    public $mastheadTitle = '';
	public $mastheadSubTitle = '';
    public $narrowBanner = '';
    public $favIcon = 'img/fav.png';
    public $author = '';
    public $authorImg = '/images/profile-icon.png';
    public $authorTitle = '';
    public $personalTagPhrase = '';
    public $keyWords;
    public $frontendTheme = 'blogvista';
    public $backendTheme = 'admindark';
    public $perPage = 1500;        // set paginate value
	public $blogPerPage = 100;	 // blog index page pagination value
    public $fileQuotaSize = 100; // size on megabytes total that the user may store per directory
	public $noCR = true;		 // when render blog and pages, do not insert carriage returns into content
	public $dbMenu = false;      // set to true if using a database based menu 
	public $languages = ['en' => "English", 'es' => "Spanish", 'zh' => "Chinese"];
	public $multiLanguage = true;
	public $urlLocale = '';		  // set dynamically get locale based on the url
	public $SSL;				  // set dynamically does url support https tls encryption
	public $URLPages;			  // set dynamically pages selected
	public $passwordMagic = '*IAMMAGIC*';  // if user password hash is this value, then no password required
	public $blogComments = FALSE; // allow visitors to comment on blog articles
    public $usernameLogins = 3;   // 	Login type allowed. 1: ONLY EMAIL 2: ONLY USERNAME 3: USERNAME OR EMAIL
    public $signupURL = '';

    public function __construct()
    {
       $this->signupURL = baseURL('/signup/new');

    }

    // ----- must add any required autoform channels here
    //       IF NOT USING AutoForm - ERGO NOT SET IN ROUTES - THEN DO NOT ADD TO THIS ARRAY 
    public $siteIChannels = [
		'tazuserlogin'    => 'Ignition/Taz/Views/TazLoginRequests.ignition.php',
	];

	// ----- standard public access controllers. non protected (keycoded) MVC controllers
	public $rbacList = [
		'post' => [
			'baseEnforce' => FALSE,
			'allowUser' => "ALL",
			'allowUserEdit' => "",
			'allowVisitor' => ""
		],
		'content' => [
			'baseEnforce' => FALSE,
			'allowUser' => "ALL",
			'allowUserEdit' => "",
			'allowVisitor' => ""
		],
		'access' => [
			'baseEnforce' => FALSE,
			'allowUser' => "ALL",		// all logged in may be given access to upload
			'allowUserEdit' => "",
			'allowVisitor' => "",
			'limitedToID' => FALSE
		],
		LOGINURL => [
			'baseEnforce' => FALSE,
			'allowUser' => "ALL",
			'allowUserEdit' => "",
			'allowVisitor' => "login"
		],
		'system' => [
			'baseEnforce' => FALSE,
			'allowUser' => "ALL",
			'allowUserEdit' => "",
			'allowVisitor' => "error404"
		],
		'user' => [
			'baseEnforce' => FALSE,
			'allowUser' => "ALL",
			'allowUserEdit' => "",
			'allowVisitor' => ""
		]
	];

	// ----- specialized admin and internal controllers. these are applied KEYCODE which obfuscates
	//       all of these controllers from public and between sessions. Use limitedToID to build 
	//       limited access controllers where only one user's information may be accessible
	public function rbacLoginAdmin()
	{

	    // ----- must add any required autoform channels here
		$this->rbacList = array_merge($this->rbacList, [
			'blog' . $GLOBALS['KEYCODE'] => [	// KEYCODE only applies to logged in users
				'baseEnforce' => TRUE,	    // TRUE= BaseController, in the constructor, allows/obstructs access based on allowUser and allowUserEdit user role list
											// FALSE=Access control to allowUser/allowUserEdit must be implemented in the respective controller using BaseController->allowedUsers and BaseController->allowedUsersEdit
				'allowUser' => SAN,       // list of users who may access, ALL=all visitors
				'allowUserEdit' => SAN,	// users who may edit/delete/create records
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE      // users may only access their own ID record(s). good for customer access controllers. STOPS MANY API HACKS
			],
			'user' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'usertype' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'page' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => "ALL",
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'asset' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,		// all logged in may be given access to upload
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'category' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,		// all logged in may be given access to upload
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'controlpanel' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => "ALL",
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'logs' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'logins' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN . "|2|",
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'authoradmin' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			],
			'userlogout' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => "10",
				'allowVisitor' => "NONE",
				'limitedToID' => TRUE
			],
			'userprofile' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => "10",
				'allowVisitor' => "NONE",
				'limitedToID' => TRUE
			],
    		'tazuserlogin' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
    		],
    		'tazscript' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
    		],
			'system' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => SAN,
				'allowUserEdit' => SAN,
				'allowVisitor' => "NONE",
				'limitedToID' => FALSE
			]
			]);
	}

	// ----- specialized admin and internal controllers. these are applied KEYCODE which obfuscates
	//       all of these controllers from public and between sessions. Use limitedToID to build 
	//       limited access controllers where only one user's information may be accessible
	public function rbacLoginCustomer()
	{

	    // ----- must add any required autoform channels here
		$this->rbacList = array_merge($this->rbacList, [
			'userprofile' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => "|10|",
				'allowUserEdit' => "|10|",
				'allowVisitor' => "NONE",
				'limitedToID' => TRUE
			],
			'userlogout' . $GLOBALS['KEYCODE'] => [
				'baseEnforce' => TRUE,
				'allowUser' => "|10|",
				'allowUserEdit' => "|10|",
				'allowVisitor' => "NONE",
				'limitedToID' => TRUE
			]
            ]);
	}

}
?>
