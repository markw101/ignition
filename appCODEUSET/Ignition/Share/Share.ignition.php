<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Share.ignition.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- autform variables
$this->allowedUsersEdit = "12";
$dataTable = 'share';

$iIndex = [
    'fieldLabels' => [
        'share_name' => 'Share Name',
        'share_table' => 'Table Name',
        'share_apikey' => 'API Key'
    ]
];
$iForm = [
	'id' => [
		'label' => "I.D.",
        'viewOnly' => true,
		'fieldType' => 'text'],
	'news_name' => [
		'label' => "Name",
		'fieldType' => 'text'],
    'news_date' => [
        'label' => "Date",
		'fieldType' => 'text'],
    'news_outlet' => [
        'label' => "News Outlet",
        'fieldType' => 'text'],
    'news_interviewer' => [
        'label' => "Interviewer",
        'fieldType' => 'text'],
    'news_recap' => [
        'label' => "News story recap",
        'fieldType' => 'textArea'],
    'created_at' => [
        'label' => lang('base.date') . ' ' . lang('base.created'),
        'viewOnly' => true,
        'fieldType' => 'text'],
    'active' => [
        'label' => lang('base.active'),
        'fieldType' => 'checkBox',
        'validation' => 'in_list[0,1]']
];

?>

