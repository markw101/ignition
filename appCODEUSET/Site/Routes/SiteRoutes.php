<?php

$routes->add('sample' . $GLOBALS['KEYCODE'] . '/(:segment)', '\Ignition\AutoForm\MC\Controller::$1');
$routes->add('sample' . $GLOBALS['KEYCODE'] . '/(:segment)/(:any)', '\Ignition\AutoForm\MC\Controller::$1/$2');
$routes->add('sample' . $GLOBALS['KEYCODE'], '\Ignition\AutoForm\MC\Controller::index');

