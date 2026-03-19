<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        UserType.autoform.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

$dataTable = 'user_types';
$formText = "Text added to notes will appear within the user record type selection.  Access Users from main administrative menu.  Remember to also add any new UserTypes to SiteConstants.php and SiteConfig.php located in app/Site directory.";
$indexText = "Any user type added here must also be added to rbacList in Site/SiteConfig.php and an entry for the new user type made in Site/SiteConstants.php.  User types develop a system of security and management within the Ignition framework.  An user type creates an user that may be given special access to existing or new Ignition MVC channels.  Any new UserType added here must also be added to SiteConstants.php and SiteConfig.php.  See these respective files for further instructions.  The accessControl array (SiteConfig.php) contains access settings for all system and site channels.";
$editButton = TRUE;
$deleteButton = TRUE;
$allowedFields = 'ALL';

$iIndex = [
    'fieldLabels' => [
        'type_num' => lang('base.type') . ' ' . lang('base.number'),
        'type_name' => lang('base.name'),
        'max_session' => 'Max Session'
    ]
];
$iForm = [
    'type_num' => [
        'label' => lang('base.type') . ' ' . lang('base.number'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'type_name' => [
        'label' => lang('base.type') . ' ' . lang('base.name'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'max_session' => [
        'label' => lang('base.session') . ' '. lang('base.maximum'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'redirectLogin' => [
        'label' => lang('base.redirect') . ' ' . lang('base.after') . ' ' . lang('base.login_title'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'notes' => [
        'label' => lang('base.user') . ' ' . lang('base.roles'),
		'enabled' => TRUE,
        'fieldType' => 'textArea']
];

?>
