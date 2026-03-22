<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Logins.autoform.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- autform variables
$databaseName = DBPREFIX . 'taz';
$dataTable = 'userlogins';
$editButton = TRUE;
$deleteButton = TRUE;
$allowedFields = 'ALL';

$iIndex = [
    'fieldLabels' => [
        'id' => lang('base.id'),
        'created_at' => lang('base.date'),
        'logins_username' => lang('base.user') . ' ' . lang('base.id'),
        'logins_ip' => lang('admin.ip')
    ]
];

$iForm = [
    'id' => [
        'label' => lang('base.record') . ' ID',
        'viewOnly' => true,
        'fieldType' => 'text'],
    'created_at' => [
        'label' => lang('base.date'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logins_username' => [
        'label' => lang('base.user') . ' ' . lang('base.id'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logins_ip' => [
        'label' => lang('admin.ip'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logins_host' => [
        'label' => lang('base.hostname'),
        'viewOnly' => false,
        'fieldType' => 'text']
];

?>
