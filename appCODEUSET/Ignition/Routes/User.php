<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        User.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

$routes->add('user' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\User\MC\Controller::$1');
$routes->add('user' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\User\MC\Controller::$1/$2');
$routes->add('userprofile' . $GLOBALS['KEYCODE'], '\Ignition\User\MC\Controller::profile');
$routes->add('userlogout' . $GLOBALS['KEYCODE'], '\Ignition\User\MC\Controller::logout');

// ----- user management
$routes->add('user/requestpasswordreset', '\Ignition\User\MC\Controller::requestPasswordReset');
$routes->add('user/sendverificationemail', '\Ignition\User\MC\Controller::sendVerificationEmail');
$routes->add('user/resetpassword', '\Ignition\User\MC\Controller::resetPassword');
$routes->add('user/verifyemail', '\Ignition\User\MC\Controller::verifyEmail');
$routes->add('user/signup', '\Ignition\User\MC\Controller::signup');

?>
