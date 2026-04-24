<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Blog.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

if (!BLOGSITE)
    return;

$routes->add('blog' . $GLOBALS['KEYCODE'] . '/index', '\Ignition\Blog\MC\Blog::index');
$routes->add('blog' . $GLOBALS['KEYCODE'] . '/edit/(:any)', '\Ignition\Blog\MC\Blog::edit/$1');
$routes->add('blog' . $GLOBALS['KEYCODE'] . '/delete/(:any)', '\Ignition\Blog\MC\Blog::delete/$1');
$routes->add('blog' . $GLOBALS['KEYCODE'] . '/new', '\Ignition\Blog\MC\Blog::new');

$routes->add('post/category', '\Ignition\Blog\MC\Post::category');
$routes->add('post/category/(:any)', '\Ignition\Blog\MC\Post::category/$1');
$routes->add('post/tagcloud/(:any)', '\Ignition\Blog\MC\Post::tagcloud/$1');
$routes->add('post/extern/(:any)/(:any)', '\Ignition\Blog\MC\Post::externalarticle/$1/$2');
$routes->add('post/(:any)', '\Ignition\Blog\MC\Post::article/$1');
