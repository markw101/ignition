<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        IRoutes.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- login
$routes->add(LOGINURL, '\Ignition\Login\Login::login');

// ------ usertype
$routes->add('usertype' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('usertype' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('usertype' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');

// ----- ajax
$routes->add('ajax/(:any)', '\Ignition\Ajax\Ajax::ajax/$1');

// ----- logging routes
$routes->add('logs' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('logs' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('logs' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');

// ----- logins routes
$routes->add('logins' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('logins' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('logins' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');

// ----- page
$routes->add('page' . $GLOBALS['KEYCODE'] . '/(:any)', '\Ignition\Page\MC\Page::$1');
$routes->add('page' . $GLOBALS['KEYCODE'], '\Ignition\Page\MC\Page::index');

// ----- controlpanel
$routes->add('controlpanel' . $GLOBALS['KEYCODE'], '\Ignition\Panel\MC\Controller::panel');

// ----- system
$routes->add('system/result', '\Ignition\System\MC\Controller::result');
$routes->add('system' . $GLOBALS['KEYCODE'] . '/script/(:any)', '\Ignition\System\MC\Controller::script/$1');
$routes->add('system/(:any)', '\Ignition\System\MC\Controller::$1');

// ----- taz
$routes->add('taz' . $GLOBALS['KEYCODE'] . '/script/(:any)', '\Ignition\Taz\MC\Controller::script/$1');
$routes->add('taz' . $GLOBALS['KEYCODE'] . '/(:any)', '\Ignition\Taz\MC\Controller::$1');

// ------ asset
$routes->add('asset' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\Asset\MC\Controller::$1');
$routes->add('asset' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\Asset\MC\Controller::$1/$2');
$routes->add('asset' . $GLOBALS['KEYCODE'], '\Ignition\Asset\MC\Controller::index');

// ----- author
$routes->add('author/profile/(:any)', '\Ignition\Author\MC\Controller::profile/$1');
$routes->add('authoradmin' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('authoradmin' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('authoradmin' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');

// ----- category
$routes->add('category' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('category' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('category' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');

// ----- taz
$routes->add('tazuserlogin' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');
$routes->add('tazuserlogin' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('tazuserlogin' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('tazscript' . $GLOBALS['KEYCODE'] . '/script/(:any)', '\Ignition\Taz\MC\Controller::script/$1');
$routes->add('tazscript' . $GLOBALS['KEYCODE'] . '/script/(:any)/(:any)', '\Ignition\Taz\MC\Controller::script/$1/$2');

$routes->add('user' . $GLOBALS['KEYCODE'], '\Ignition\User\MC\Controller::index');
$routes->add('user' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\User\MC\Controller::$1');
$routes->add('user' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\User\MC\Controller::$1/$2');


?>