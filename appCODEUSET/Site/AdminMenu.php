<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        AdminMenu.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

$menus = array(
    ['name' => lang('base.site'),
    'url' => BaseURL('/'),
    'icon' => 'fa-bars',
    'users' => '|' . SAN . '|2|3|4|',],
    ['name' => 'Control Panel',
    'url' => BaseURL('/controlpanel' . $GLOBALS['KEYCODE']),
    'icon' => 'fa-tachometer',
    'users' => '|' . SAN . '|2|3|4|',],
    ['name' => lang('base.tools'),
    'url' => '',
    'icon' => 'fa-wrench',
    'users' => '|' . SAN . '|2|',
    'subMenu' => [
        ['name' => lang('base.site') . ' ' . lang('base.page') . 's',
        'url' => BaseURL('/page' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|2|'],
        ['name' => lang('base.asset') . 's',
        'url' => BaseURL('/asset' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|2|'],
        ['name' => "Blog Categories",
        'url' => BaseURL('/category' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|2|'],
        ['name' => lang('base.usertype'),
        'url' => BaseURL('/usertype' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|'],
        ['name' => lang('base.logs'),
        'url' => BaseURL('/logs' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|2|'],
        ['name' => lang('blog.author'),
        'url' => BaseURL('/authoradmin' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|2|'],
        ['name' => lang('base.user') . ' ' . lang('base.login_title'),
        'url' => BaseURL('/logins' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|'],
        ['name' => lang('base.server') . ' ' . lang('base.login_title'),
        'url' => BaseURL('/tazuserlogin' . $GLOBALS['KEYCODE']),
        'icon' => '',
        'users' => '|' . SAN . '|']
        ]],
    ['name' => lang('base.user') . 's',
    'url' => BaseURL('/user' . $GLOBALS['KEYCODE'] . '/index'),
    'icon' => 'fa-users',
    'users' => '|' . SAN . '|2|'],
    ['name' => 'Blog',
    'url' => BaseURL('/blog' . $GLOBALS['KEYCODE'] . '/index'),
    'icon' => 'fa-coffee',
    'users' => '|' . SAN . '|2|']
    );
?>