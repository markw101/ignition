<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');


// Create a new instance of our RouteCollection class.
//$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
//if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
//{
//	require SYSTEMPATH . 'Config/Routes.php';
//}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
$routes->setDefaultNamespace('App' . IGNITIONCD . '\Ignition');
$routes->setDefaultController('BaseController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);

// ----- turn off 404 handler when in debug
if(getenv('CI_ENVIRONMENT') == 'producton')
	$routes->set404Override(function(){IRedirect('system/error404', true, true);});

$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// THIS IS THE DEFAULT ROUTE FOR THE WEBSITE (HOME PAGE)
$routes->add('/', '\Site\Channels\Home\Home::view');

// ----- route to render site managed "pages" within Ignition
$routes->add('content/name/(:any)', '\Ignition\Page\MC\Content::byName/$1');
$routes->add('content/(:any)', '\Ignition\Page\MC\Content::view/$1');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

// ----- set debug route
if(getenv('CI_ENVIRONMENT') == 'development') {
    $routes->add('debug', '\Ignition\Base\BaseController::debug');
    $routes->add('debug/(:any)', '\Ignition\Base\BaseController::debug/$1');
}

// ----- ignition base system
require APPPATH . 'Ignition/Routes/Blog.php';
require APPPATH . 'Ignition/Routes/User.php';
require APPPATH . 'Ignition/Routes/IRoutes.php';

// ----- extensible user routes
//require APPPATH . 'Site/Routes/WilliamsonSoftware.php';

