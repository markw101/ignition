<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        BaseController.php
    VERSION:     .9 December 2021, project start
                 1.0 June 2022, first operational version
                 1.1 May 2023, improved blog added mulit language support
				 1.2 July 2023, added secure urls to all admin functions and rbac list
                 1.3 Dec 2023, addeded function for DNA extensions
                 1.4 Oct 2025, numerous refinements DNA launch upgrades
                 1.5 Dec 2025, DNA/Ignition security upgrades
    DESCRIPTION: General website dev tool building atop CodeIgniter
    COPYRIGHT:   2021, 2022, 2023, 2024, 2025, 2026
    FIRST REV:   December 2021
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Base;

class BaseController extends \CodeIgniter\Controller
{

    protected $allowedUsers = '';       // set in SiteConfig.  User list permitted to access controller.
    protected $allowedUsersEdit = '';   // set in SiteConfig.  User list permitted to edit records of controller.
    protected $layoutConfig = "";       // set final render parameters in channel constructors such as NOHEADER|NOMENU|NOBREAD
    protected $menuSelected = "";       // used to highlight this menu choice as page renders, see menu_item->
    protected $moduleName = "";         // used in adminlayout to indicate module/controller
    protected $moduleMethod = "";       // used in adminlayout to indicate method called
    protected $moduleID = 0;            // used in adminlayout to indicate record ID
    public $IConfig;					// object containing instantiation of Site/SiteConfig				
    public $browserTitle = "";			// setable web browser title
    public $baseIChannels = [			// base autoform for Ignition
        'asset' => 'Ignition/Asset/Asset.autoform.php',
        'category' => 'Ignition/Blog/Category.autoform.php',
        'usertype' => 'Ignition/UserType/UserType.autoform.php',
        'logs' => 'Ignition/System/MC/Logging.autoform.php',
		'authoradmin' => 'Ignition/Author/Author.autoform.php',
        'logins' => 'Ignition/System/MC/Logins.autoform.php',
		'tazuserlogin'    => 'Ignition/Taz/Views/TazLoginRequests.autoform.php'
    ];
    public $siteIChannels = [];			// iChannels specific to app.  Set this array in Site/SiteConstants
    public $customLanguage = FALSE;     // Changes default language, usually set dynamically in controller, used in autocrumble
    public $appLayoutFile = '';         // This sets the layout for admin
    public $mainLayoutFile = 'MainLayout.php';  // Set the default main layout.  copy this file and update for your layout
    public $IgnitionVersion = "1.5.0";  // Version number for this release
    protected $subModule = FALSE;       // for more complex modules with sub parts (used also autoform)
    protected $uri;
    protected $securityCode = FALSE;

    // ----- constructor for configuration
    public function __construct()
    {
		$myLocale = '';

        // ----- load configurations
        $this->IConfig = new \Site\SiteConfig;

        // ----- request session and set user access.  keycode == NOTLOGGEDIN boolean for user has no session
		if (USERNAME) {
			// ----- autolang means determine language by url, otherwise user config 
			if (!AUTOLANG) {
				include APPPATH . "Ignition/Library/EarthicaLanguages.php";
				$myLocale = (isset($systemLanguages[$sessionData['user_language']]) ? $sessionData['user_language'] : DEFAULTLOCALE);
			}

			// ----- SECURITY: set internal rbac list controllers with keycodes
			if (UT >= 10 && UT < 100)
				$this->IConfig->rbacLoginCustomer();
			else
				$this->IConfig->rbacLoginAdmin();

            // ----- verify logged in user has correct ip
            if ($_SESSION['session_data']['ip'] != $_SERVER['REMOTE_ADDR'])
                halt('Current IP does not match IP used for login.');

        }

        // ----- diferentiate between casual visitors and admins
        //       if any type of administrator.  If going thru this constructor a second time
        //       supress the redefinition 
        if (!defined('SITEADMIN')) {
            if (UT <= 2 || UT == SAN)
                define("SITEADMIN", true);
            else
                define("SITEADMIN", false);
        }

		// ----- any site accessor with a login is considered an user
        if (!defined('USER')) {
    		if (UT != 0)
    		    define("USER", true);
    		else
    		    define("USER", false);
        }

        // ----- check for auto set locale based on url 
		if (AUTOLANG) {

            // ---- check for special case www, ommit and will default to 
            if (substr(strtolower($_SERVER['HTTP_HOST']), 0, 4) == strtolower('www.'))
                $http_domain = substr($_SERVER['HTTP_HOST'], 4);
            else
                $http_domain = $_SERVER['HTTP_HOST'];
 
            // ----- check for found locale 
    		$this->IConfig->userLocale = GetLocalURL($http_domain);
			if ($this->IConfig->userLocale === false) {
				if(getenv('CI_ENVIRONMENT') == 'development') {
					echo "<br>Error locating language code from URL<br>Url analyzed for langauge code in php session: " . $_SERVER['SERVER_NAME'] . "<br>This may be caused by omission of the language code within Ignition/Library/EarthicaLanguages.php<br>Or, perhaps you forgot to set the SERVERBASENAME in SiteConstants.";
					halt();
				} else 
					echo "<br>Error locating language code from URL<br>Url analyzed for langauge code was: " . $_SERVER['SERVER_NAME'];
			}

		} else {
			// ---- check to see if locale was set by user's record
			if ($myLocale != '')
				$this->IConfig->userLocale = $myLocale;
			else 
				$this->IConfig->userLocale = DEFAULTLOCALE;
		}

		// ----- must set locale based on above choice: if AUTOLANG, use the suburl; OTHERWISE, use configuration (if not set, resorts to DEFAULTLOCALE)
		$language = \Config\Services::language();
		$language->setLocale($this->IConfig->userLocale);
		$GLOBALS['LANGCODE'] = $this->IConfig->userLocale;
		$this->browserTitle = " - " . lang('base.welcome');

		// ----- set other dynamics
		$this->IConfig->SSL = $_SERVER['REQUEST_SCHEME'];
		$this->IConfig->URLPages = $_SERVER['REQUEST_URI'];

        // ----- set controler specific variables 
        $this->uri = new \CodeIgniter\HTTP\URI(fixed_url());

		$totSeg = $this->uri->getTotalSegments();
		if ($totSeg > 0) {
		    $this->moduleName = $this->uri->getSegment(1) ?? '';
		    $this->menuSelected = $this->uri->getSegment(1) ?? '';
		}
		if ($totSeg > 1)
		    $this->moduleMethod = $this->uri->getSegment(2) ?? '';
		if ($totSeg > 2)
		    $this->moduleID =  $this->uri->getSegment(3) ?? '';

		// ----- if running DNA, must select correct database
		if (DNASERVERENABLE)
			$GLOBALS['DBGROUP'] = SelectDNAdb($this->uri);

        // ----- check for failed to connect
        if (!$GLOBALS['DBGROUP'])
            halt('Database not found');

		// ----- check for any controller (anything other than index/home page)
		if ($totSeg > 0) {

			// ----- check to see if controller is on rbac list
			if (!isset($this->IConfig->rbacList[$this->moduleName])) {

				if(getenv('CI_ENVIRONMENT') == 'development')
					halt('Module "' . $this->moduleName . '" not found in array rbacList list in SiteConfig.php');

				// ----- give detailed description.  otherwise, block and log
				LogEvent('1', 'Call to unauthorized module: ' . $this->moduleName . '.  Not found in rbacList, set in Site/SiteConfig.php');
				$this->Go404();
			}

			// ----- vett any enforced controllers for user type, edit
			//       THE KEYCODE CONTAINED IN URL HAS NOT YET BEEN VERIFIED
			//       THIS ACTS AS A HONEYPOT FOR USERS TESTING URLS THAT  MATCH 
			//       LEN OF ACCESS CODE URLS, BUT DO NOT CONTAIN A REAL ACCESS CODE
			if ($this->IConfig->rbacList[$this->moduleName]['baseEnforce']) {

				// ----- non auth users not allowed beyond this point 
				if (!USERNAME) {

					if(getenv('CI_ENVIRONMENT') == 'development')
						halt('Non authenticated user attempting access to enforced module: "' . $this->moduleName . '"');

					LogEvent('106', "Non authenticated user attempting to access enforced controller.");
					$this->Go404();
				}

				// ----- must verify the presumed keycode is real (keycode was arbitrarily
				//       extracted from the url in SiteConstants)
				if (!password_verify($GLOBALS['KEYCODE'] . $_SESSION['session_data']['user_sess_salt'], $_SESSION['session_data']['user_sess_hash'])) {

					if(getenv('CI_ENVIRONMENT') == 'development')
						halt('Invalid KEYCODE passed for enforced module: "' . $this->moduleName . '"');

					// ----- this means that an arbitrary url was passed from a validated user
					//       THIS IS VERY SUSPICIOUS
					LogEvent('102', 'Suspicious url from logged in user.  Possible cookie theft, but with apparent arbitrary and erroneous keycode: ' . $this->moduleName);
					$this->Go404();

				}

				// ----- obtain security constraints for controller
				$this->allowedUsers  = $this->IConfig->rbacList[$this->moduleName]['allowUser'];
				$this->allowUsersEdit = $this->IConfig->rbacList[$this->moduleName]['allowUserEdit'];
				$allowVisitorMethods = $this->IConfig->rbacList[$this->moduleName]['allowVisitor'];
				$limitedToID = $this->IConfig->rbacList[$this->moduleName]['limitedToID'];

/// ADD limitedToID FILTER HERE

				// ----- if logged-in user, accessing CRUD module and not permitted edit priv, block and log
				if (ValSet("|edit|new|delete|index|", "|" . $this->moduleMethod . "|") && (!USER || !GrantAccess($this->allowUsersEdit))) {
					if(getenv('CI_ENVIRONMENT') == 'development')
						halt('CRUD permission requested for insufficient privilege user.  Module: "' . $this->moduleName . '"');

					// ----- log based on logged in or not
					if (USER)
						LogEvent('103', "Blocked, possible escellation of privilege attack.  CRUD permission requested from non admin user: " . USERNAME . ".  Requested edit access to module: " . $this->moduleName . ".");
					else 
						LogEvent('103', "Blocked, non logged in user requested CRUD access to module: " . $this->moduleName . ".");

					$this->Go404();
				}

				// ----- verify site visitor called only authorized module
				if (!USER && !ValSet($allowVisitorMethods, "|" . $this->moduleMethod . "|")) {
					if(getenv('CI_ENVIRONMENT') == 'development')
						halt('Non authenticated user called non public module: "' . $this->moduleName . '"');

					LogEvent('104', "Blocked, non authenticated user called non public module: " . $this->moduleName);
					$this->Go404();

				}

				// ----- verify logged in user is permitted to access this module
				if (USER && !GrantAccess($this->allowedUsers)) {
					if(getenv('CI_ENVIRONMENT') == 'development')
						halt('Authenticated user, but insufficient privilege to access this module: "' . $this->moduleName . '"');

					LogEvent('105', "Blocked, authenticated user attempting to access non allowedUsers module (rbac list) for this user.  Possible escellation of privilege attack.  Module: " . $this->moduleName . ".  User name: " . USERNAME . ".");
					$this->Go404();

				}

			}

		} // END construct

    }

    // ----- extended ci render to include theme
    protected function RenderTheme(string $view, array $params = [])
    {
  
        $viewPath = $this->viewPath ?? FALSE;

        if ($viewPath)
            $viewPath .= '/';

        // ----- save view path to parameters
        $params['viewPath'] = $viewPath;

        // ----- assemble complete view file name with path
        $viewFile = $viewPath . $view;

        // ----- check for pager, and paginate
        if (($params['pager'] ?? false) && !isset($params['elements']))
            $params['elements'] = $params['model']->paginate($this->IConfig->perPage);

        // ----- tansitional code, 2024
        //       WARNING: all calls to RenderTheme should not use adminLayout, use AppLayout
        if (isset($params['adminLayout']))
        	$params['AppLayout'] = $params['adminLayout'];

        // ----- if app layout will also need to set formType (used in mose Views/form.php)
        if (($params['AppLayout'] ?? false) && !isset($params['formType']))
             $params['formType'] = $this->moduleMethod;

        $params['iconfig'] = $this->IConfig;

		// ----- convert langauges to their own language
		foreach($this->IConfig->languages as $langKey => $langText) {
			$langs[$langKey] = lang('languages.' . $langKey);
		}

		// ----- these are the set specific configuration settings, set here for language
		$this->IConfig->languages = $langs;
		$this->IConfig->title = lang('config.title');
		$this->IConfig->siteDescription = lang('config.siteDescription');
		$this->IConfig->mastheadTitle = lang('config.mastheadTitle');
		$this->IConfig->mastheadSubTitle = lang('config.mastheadSubTitle');
		$this->IConfig->authorTitle = lang('config.authorTitle');;
		$this->IConfig->personalTagPhrase = lang('config.personalTagPhrase');
		$this->IConfig->keyWords = lang('config.keyWords');

        // ----- utilize ci view to generate the content segment of page, unless view file set to none
        if (ValSet($viewFile, "__NONE__"))
            $content = "";
        else
            $content = view($viewFile, $params, ['saveData' => true]);

		// ----- specialized call where peer requires specific content, not themed site, only main form
		//       can call the dna form remote using curl with this option by setting
        //       the url $assetFunction segment to formonly (vs view or listing) 
        //       user must authenticate by signing with their private key.  Requires
        //       requester authenticate as DNA peer to this specific DM
        //       Requires corrleated code in dna/autoform/mc/controller.php:View
		if (isset($params['formOnly']) && $params['formOnly']) {

            // ----- prep json
            if ($_SESSION['asset_data']['passed']) {
        		$jsonCoded = json_encode([
                    'resultCode' => TRUE,
        			'asset_data' => (isset($_SESSION['asset_data']) ? $_SESSION['asset_data'] : lang('base.no') . ' ' . lang('base.return')),
                    'miner_banner' => MINERBANNER
        		]);
            } else {
        		$jsonCoded = json_encode([
                    'resultCode' => FALSE,
        			'errorMessage' => "Request returned no record. " . $_SESSION['asset_data']['message'] . ' ' . GetFlash(),
        			'asset_data' => '',
                    'miner_banner' => MINERBANNER
        		]);
            }

            header("HTTP/1.1 200 OK");
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=ISO-8859-1");
            echo $jsonCoded . '__ENDDATA__';
            if ($_SESSION['asset_data']['passed'])
    			echo $content;
			exit();

		}   // formonly

        // ----- set all params
        $paramsAll = [
            'breadCrumbs' => $params['breadCrumbs'] ?? false,
            'content' => $content,
			'model' => $params['model'] ?? false,
			'pager' => $params['pager'] ?? false,
            'narrowBanner' => $params['narrowBanner'] ?? false,
            'page_narrow_text' => $params['page_narrow_text'] ?? false,
            'altLayout' => $params['altLayout'] ?? false,
			'params' => $params
        ];

        if ($params['AppLayout'] ?? false) {
			// ----- check default application layout
			if ($this->appLayoutFile == '')
				$this->appLayoutFile = APPPATH . 'Ignition/Base/app-layout.php';

            $this->AppLayout($paramsAll);
        } else
	    	$this->MainLayout($paramsAll);
    }

    // ----- call main layout for themed web interface
    public function MainLayout(array $params = [])
    {

        // ----- init menues
		if ($this->IConfig->dbMenu) {
	        if (!ValSet($this->layoutConfig, "NOMENU"))
	            $mainMenu = $this->getMenu(1, MENUFONT);
	        if (!ValSet($this->layoutConfig, "NOSOCIAL"))
	            $socialMenu = $this->getMenu(2, MENUFONT);
		}

        // ----- check for alt layout
        if ($params['altLayout'])
            require APPPATH . 'Site/' . $params['altLayout'] . '.php';
        else
            require APPPATH . 'Site/' . $this->mainLayoutFile;

    }

    // ----- call application layout for themed web interface
    public function AppLayout(array $params = [])
    {

        // ----- check for no bread
        if (ValSet($this->layoutConfig, 'NOBREAD'))
            $breadCrumbs = false;
        else {
            if (!$params['breadCrumbs'])
                $breadCrumbs = $this->AutoCrumble();
            else
                $breadCrumbs = $params['breadCrumbs'];
        }

        if ($params['pager'])
            $pager = $params['model']->pager;

        // ----- configurable application layout, can set as needed
        require $this->appLayoutFile;

    }

    // ----- build and output menu 
    //       admin menu builds two menus for the browser: Desktop and Mobile.
    protected function RenderMenu($menus, $noProfile = FALSE, $logoText = '') {

		// ----- create bg style color 
		echo '<style>.colorbgadmin {background-color: #' . $GLOBALS['ADMINMENUCOLOR'] . ';}</style>';

        // ----- commence DESKTOP header and menu
        $anon = (isset($_SESSION['session_data']['user_peernum']) && $_SESSION['session_data']['user_peernum'] == 'ANONYMOUS');
 
        if (UT) {
            switch (TRUE) {
                case ($anon) :
                    $borderColor = 'e8ea1c';        // yellow
                    break;
                case (UT == SUPERADMIN) :
                    $borderColor = 'ffffff';        // white
                    break;
                case (UT == SYSADMIN) :
                    $borderColor = 'f66d00';        // orange
                    break;
                default :
                    $borderColor = '2e6991';        // blue
                    break;
            }
            echo '<header class="header-desktop3 d-none d-lg-block colorbgadmin" style="border:3px solid #' . $borderColor . '">';

        } else
            echo '<header class="header-desktop3 d-none d-lg-block colorbgadmin">';

        echo '<div class="section__content section__content--p35 colorbgadmin"><div class="header3-wrap colorbgadmin">';

        // ----- set logo, first of 3 groups
        echo '<div class="header__logo">';
        echo '<a href="' . BaseURL($_SERVER['REQUEST_URI']) . '">';
        echo '<img src="' . BaseURL('/' . $this->IConfig->adminLogo) . '" alt="' . $this->IConfig->title . '" style="max-height:55px">';

		// ----- check for additional text 
		if ($logoText != '')
			echo '<font style="font-size: 13px; color: #feffff">' . $logoText . '</font>';
        echo '</a></div>';

        // ----- begin main menu
        echo '<div class="header__navbar"><ul class="list-unstyled">';

        // ----- setup loop to render menus as part of main menu group, second of three groups
        foreach ($menus as $menuItems) {

            // ----- check security constraint
            if ($menuItems['users'] == 'ALL' || ValSet($menuItems['users'], '|' . UT . '|'))
            {
                // ----- check for submenu
                if(isset($menuItems['subMenu'])) {
                    echo '<li class="has-sub">';
                    echo '<a class="js-arrow" onclick="return false;" href="#">';
                    echo '<i class="fa ' . (isset($menuItems['icon']) ? $menuItems['icon'] : '') . '"></i>' . $menuItems['name'] . '</a>';
                    echo '<ul class="header3-sub-list list-unstyled">';

                    // ----- setup loop to render sub menus 
                    foreach ($menuItems['subMenu'] as $subMenu) {
                        if ($menuItems['users'] == 'ALL' || ValSet($subMenu['users'], '|' . UT . '|'))
                            echo '<li><a href="' . $subMenu['url'] . '"' . (isset($subMenu['options']) ? ' ' . $subMenu['options'] : '') . '>' . $subMenu['name'] . '</a></li>';
                    }

                    // ----- close out list
                    echo '</ul>';

                } else  {
                    if ($menuItems['users'] == 'ALL' || ValSet($menuItems['users'], '|' . UT . '|')) {
                        echo '<li><a href="' . (isset($menuItems['url']) ? $menuItems['url'] : '') . '" style:"font-color:red"' . (isset($menuItems['options']) ? ' ' . $menuItems['options'] : '') . '>';
                        echo '<i class="fa ' . (isset($menuItems['icon']) ? $menuItems['icon'] : '') . '"></i>' . (isset($menuItems['name']) ? $menuItems['name'] : '') . '</a></li>';
                    }
                }
            }
        }

        // ----- end main menu 
        echo '</ul></div>';

        //echo '<a href="/userlogout' . $GLOBALS['KEYCODE'] . '" class="btn&#x20;btn-primary"' . '>' . lang('base.logout') . '</a>';

        // ----- render account, third of 3 groups
		if (!$noProfile) {
	        echo '<div class="account-wrap"><div class="account-item account-item--style2 clearfix">';
	       	echo '<a href="' . BaseURL('/userprofile' . $GLOBALS['KEYCODE']) . '">';
	        echo '<div class="image">';
	        echo '<img src="' . BaseURL('/images/profile-icon.png') . '" alt="' . $this->IConfig->title . '"/>';
	        echo '</div><span style="color: white;">' . lang('base.my_profile') . '</span></a></div></div>';
		}

        // ----- close out desktop menu 
        echo '</div></div></header>';

        //
        // ----- END DESKTOP, BEGIN MOBILE MENU
        //

        if (UT)
            echo '<header class="header-mobile header-mobile-2 d-block d-lg-none colorbgadmin" style="border:3px solid #' . $borderColor . '">';
        else
            echo '<header class="header-mobile header-mobile-2 d-block d-lg-none colorbgadmin">';

        // ----- begin MOBILE menu 
        echo '<div class="header-mobile__bar colorbgadmin"><div class="container-fluid colorbgadmin"><div class="header-mobile-inner colorbgadmin">';
        echo '<a href="' . BaseURL($_SERVER['REQUEST_URI']) . '">';
        echo '<img src="' . BaseURL('/' . $this->IConfig->adminLogo) . '" alt="' . $this->IConfig->title . '" width="80">';
		// ----- check for additional text 
		if ($logoText != '')
			echo '<font style="font-size: 13px; color: #feffff">' . $logoText . '</font>';
        echo '</a><button class="hamburger hamburger--slider" type="button"><span class="hamburger-box"><span class="hamburger-inner"></span>';
        echo '</span></button></div></div></div>';
        echo '<nav class="navbar-mobile"><div class="container-fluid"><ul class="navbar-mobile__list list-unstyled">';

        // ----- setup loop to render menus as part of main menu group, second of three groups
        foreach ($menus as $menuItems) {

            // ----- check security constraint
            if ($menuItems['users'] == 'ALL' || ValSet($menuItems['users'], '|' . UT . '|'))
            {
                // ----- check for submenu
                if(isset($menuItems['subMenu'])) {
                    echo '<li class="has-sub">';
                    echo '<a class="js-arrow" href="#">';
                    echo '<i class="fa ' . (isset($menuItems['icon']) ? $menuItems['icon'] : '') . '"></i>' . $menuItems['name'] . '</a>';
                    echo '<ul class="navbar-mobile-sub__list list-unstyled js-sub-list">';

                    // ----- setup loop to render sub menus 
                    foreach ($menuItems['subMenu'] as $subMenu) {
                        if ($menuItems['users'] == 'ALL' || ValSet($subMenu['users'], '|' . UT . '|'))
                            echo '<li><a href="' . $subMenu['url'] . '"' . (isset($subMenu['options']) ? ' ' . $subMenu['options'] : '') . '>' . $subMenu['name'] . '</a></li>';
                    }

                    // ----- close out list
                    echo '</ul>';

                } else  {
                    if ($menuItems['users'] == 'ALL' || ValSet($menuItems['users'], '|' . UT . '|')) {
                        echo '<li><a href="' . (isset($menuItems['url']) ? $menuItems['url'] : '') . '"' . (isset($subMenu['options']) ? ' ' . $subMenu['options'] : '') . '>';
                        echo '<i class="fa ' . (isset($menuItems['icon']) ? $menuItems['icon'] : '') . '"></i>' . (isset($menuItems['name']) ? $menuItems['name'] : '') . '</a></li>';
                    }
                }
            }
        }

        // ----- render account, third of 3 groups
		if (!$noProfile) {
	        echo '<li><a href="' . BaseURL('/userprofile' . $GLOBALS['KEYCODE']) . '">';
    	    echo '<i class="fa fa-star"></i>' . lang('base.my_profile') . '</a></li>';
		}

        // ----- end mobile menu 
        echo '</ul></div></nav></header>';

    }

    protected function RenderCustomMenu($renderParameters, $renderFile) {

		include APPPATH . $renderFile;

    }

    // ----- redirect: set location and die
    //       AUTOMATICALLY INCLUDES BASE_URL IF NO SECOND PARAMETER
    protected function redirect(string $url, $includeBase = true)
    {
        if ($includeBase) {

			if ($url == '/')
				$url = '';

            $url = BaseURL('/' . $url);

		}
		else 
			$url = $_SERVER["REQUEST_SCHEME"] . "://" . $url;

		header('Location: ' . $url);
		halt();

    }

    protected function GoHome()
    {
        return $this->redirect("/");
    }

    protected function Go404()
    {

		header('Location: ' . BaseURL("/system/error404"));
		halt();
    }

    protected function SetFlashMessage(string $message, $name = 'success') {
        $session = service('session');
        $session->SetFlashdata($name, $message);
    }

    // ----- build breadcrumbs array for module controllers and channels in admin and app layouts
    protected function AutoCrumble()
    {

		// ----- look for admin functions with keycode 
		if ($keyPos = strpos($this->moduleName, $GLOBALS['KEYCODE']))
			$modName = substr($this->moduleName, 0, $keyPos);
		else 
			$modName = $this->moduleName;

        // ----- set name, switching to custom
        $textModuleName = lang(($this->customLanguage ? $this->customLanguage : 'base') . '.' . $modName);

		// ----- add only basic site + control panel
        $returnBread = $this->MakeCrumbs('', []);

        // ----- check for control/app panel only
        if (((UT >=10 && UT < 100) && $this->moduleName == APPHOME . $GLOBALS['KEYCODE']) || ((UT < 10 || UT == SAN) && $this->moduleName == 'controlpanel' . $GLOBALS['KEYCODE']))
            return $returnBread;

        $returnBread[2] = array(
            'name' => $textModuleName,
            'url' => BaseURL('/' . $this->moduleName) . ($this->subModule ? '/' . $this->subModule : '') . '/index' . ($this->securityCode ? '/' . $this->securityCode : ''));

        // ----- check for set aux (new, edit, delete)
        if ($this->moduleMethod != 'index') {

            // ----- add in id for non new 
            if ($this->moduleMethod == 'new')
                $url = BaseURL('/' . $this->moduleName . ($this->subModule ? '/' . $this->subModule : '') . '/' . $this->moduleMethod . ($this->securityCode ? '/' . $this->securityCode : ''));
            else 
                $url = BaseURL('/' . $this->moduleName . ($this->subModule ? '/' . $this->subModule : '') . '/' . $this->moduleMethod . '/' . $this->moduleID . ($this->securityCode ? '/' . $this->securityCode : ''));

            $returnBread[3] = array('name' => lang('base.' . $this->moduleMethod), 'url' => $url);
        }

        return $returnBread;

    }

    // ----- build breadcrumbs array for module controllers for admin layout
    protected function MakeCrumbs($crumParams = '', $crumb1 = [], $crumb2 = [], $crumb3 = [], $crumb4 = [], $crumb5 = [], $crumb6 = [], $crumb7 = [])
    {

		if (is_array($crumParams))
			halt('Must set crumParams in call to MakeCrumbs.');

        $returnBread[][] = array();
		$crumbCount = 0;

		// ----- base site link
		$returnBread[$crumbCount] = ['name' => lang('base.site'), 'url' => BaseURL('/')];
		$crumbCount ++;

		// ----- if admin or logged-in customer, set a control panel link in breadcrumb (nocp=no controlpanel)
		if (UT > 0 && !ValSet($crumParams, "nocp|")) {

			if (UT >= 10 && UT < 100)
				$returnBread[$crumbCount] = ['name' => lang(CUSTOMLANG . '.' . APPHOME), 'url' => BaseURL('/' . APPHOME . $GLOBALS['KEYCODE'])];
			else 
				$returnBread[$crumbCount] = ['name' => lang('base.mydashboard'), 'url' => BaseURL('/controlpanel' . $GLOBALS['KEYCODE'])];

			$crumbCount ++; 
        }

        if ($crumb1) {
            $returnBread[$crumbCount] = $crumb1;
			$crumbCount ++; }

        if ($crumb2) {
            $returnBread[$crumbCount] = $crumb2;
			$crumbCount ++; }

        if ($crumb3) {
            $returnBread[$crumbCount] = $crumb3;
			$crumbCount ++; }

        if ($crumb4){
            $returnBread[$crumbCount] = $crumb4;
			$crumbCount ++; }

        if ($crumb5) {
            $returnBread[$crumbCount] = $crumb5;
			$crumbCount ++; }

        if ($crumb6) {
            $returnBread[$crumbCount] = $crumb6;
			$crumbCount ++; }

        if ($crumb7) {
            $returnBread[$crumbCount] = $crumb7;
			$crumbCount ++; }

        return $returnBread;

    }

    // ----- useful debugging feature
    public function Debug($unset = "_showvariables_")
    {

        if ($unset == "_showvariables_") {
            echo print_r($_SESSION) . _br();
        //die();
        } else {
            // ----- init
            unset($_SESSION[$unset]);
            echo "Unset: " . $unset;
            die();
        }
    }

}
