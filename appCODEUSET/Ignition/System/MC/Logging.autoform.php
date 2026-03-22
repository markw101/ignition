<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Logging.autoform.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- autform variables
$databaseName = $GLOBALS['DBGROUP'];
$dataTable = 'logs';
$editButton = TRUE;
$deleteButton = TRUE;
$allowedFields = 'ALL';

$iIndex = [
    'fieldLabels' => [
        'id' => lang('base.id'),
        'created_at' => lang('base.date'),
        'logs_code' => lang('base.code'),
        'logs_desc' => lang('base.description')
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
    'logs_code' => [
        'label' => lang('base.code'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logs_desc' => [
        'label' => lang('base.description'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logs_username' => [
        'label' => lang('base.user') . ' ' . lang('base.name'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logs_host' => [
        'label' => lang('base.hostname'),
        'viewOnly' => false,
        'fieldType' => 'text'],
    'logs_ip' => [
        'label' => lang('admin.ip'),
        'viewOnly' => false,
        'fieldType' => 'text']
];

?>
