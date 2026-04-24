<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        TazLoginRequests.ignition.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- autform variables
$databaseName = DBPREFIX . 'taz';
$dataTable = 'serverlogins';
$customLangauge = 'ecoin';
$editButton = FALSE;
$deleteButton = TRUE;
$allowedFields = 'ALL';

$iIndex = [
    'fieldLabels' => [
        'id' => [
			'label' => lang('base.id'),
			'fieldType' => 'link',
			'link' => 'serverlogins'],
    	'created_at' => [
    		'label' => lang('base.timestamp'),
			'fieldType' => 'text',
    		'dataType' => 'text'],
    	'request_number' => [
    		'label' => lang('base.request') . ' ' . lang('base.number'),
			'fieldType' => 'text',
    		'dataType' => 'dna40'],
    	'request_type' => [
    		'label' => lang('base.type'),
			'fieldType' => 'text'],
    	'request_user' => [
    		'label' => lang('base.user'),
			'fieldType' => 'text',
    		'dataType' => 'dna40'],
    	'request_ip' => [
    		'label' => lang('admin.ip'),
			'fieldType' => 'text'],

    ]
];

$iForm = [
	// ----- narrow box
	'FormElement1a' => [
		'fieldType' => 'narrowBox',
		'title' => "Admin Server SSH Login Requests"],
    'id' => [
		'label' => lang('base.id'),
		'fieldType' => 'text',
        'enabled' => FALSE],
	'created_at' => [
		'label' => lang('base.created'),
		'fieldType' => 'text',
		'dataType' => 'text',
        'enabled' => ADMINMODE],
	'updated_at' => [
		'label' => lang('base.last_update'),
		'fieldType' => 'text',
		'dataType' => 'text',
        'enabled' => FALSE],
	'request_number' => [
		'label' => lang('dna.request') . ' ' . lang('base.number'),
		'fieldType' => 'text',
		'dataType' => 'dna40',
        'enabled' => FALSE],
	'request_type' => [
		'label' => lang('base.type'),
		'fieldType' => 'text',
        'enabled' => ADMINMODE],

	// ----- narrow box
	'FormElement1b' => [
		'fieldType' => 'narrowBox',
		'title' => "*"],
	'request_user' => [
		'label' => lang('base.user'),
		'fieldType' => 'text',
		'dataType' => 'dna40',
        'enabled' => ADMINMODE],
	'request_use_full_name__VIRTUAL__' => [
		'label' => lang('base.name') . lang('base.of') . lang('dna.signatory'),
		'fieldType' => 'text',
		'dataType' => 'text',
        'virtualType' => 'value',
        'values' => '',
        'enabled' => FALSE],
	'request_sig' => [
		'label' => lang('dna.signature'),
		'fieldType' => 'text',
		'dataType' => 'dna40',
        'enabled' => FALSE],
	'request_ip' => [
		'label' => lang('admin.ip'),
		'fieldType' => 'text',
        'enabled' => FALSE],

];

// ----- call fix up, everything that requires doing after variables are processed and before form is rendered
function PostDataSet($model, $chainParams = '', $callType = '')
{

    // ----- get user record
    $userRecord = GetUserProfile(bin2dna40($model->data['request_user']), TRUE);
    $model->formFields[ShowFieldsOS("request_use_full_name__VIRTUAL__", $model->formFields)]['values'] = $userRecord['user_fname'] . ' ' . $userRecord['user_lname'];

    return $model;
}
?>

