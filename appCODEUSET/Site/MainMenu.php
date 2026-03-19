<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        MainMenu.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

$menus = array(
    ['name' => lang('base.site'),
    'url' => baseURL(),
    'icon' => 'fa-bars',
	'item_uid' => 'site',
    'users' => 'ALL'],
    ['name' => 'Blog',
    'url' => '/post/category',
    'icon' => 'fa-info',
    'item_uid' => 'blog',
    'users' => 'ALL'],
    ['name' => 'Readme',
    'url' => '/content/' . urlize(lang('base.readme')),
    'icon' => 'fa-server',
	'item_uid' => 'access',
    'users' => 'ALL'],
    ['name' => lang('base.about'),
    'url' => '/content/' . urlize(lang('base.about')),
    'icon' => 'fa-star',
	'item_uid' => 'about',
    'users' => 'ALL']
    );
?>