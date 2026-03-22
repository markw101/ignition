<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Author.autoform.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- AutoForm variables
$dataTable = 'authors';
$baseDirectory = 'author';            // this is an autoform flag indicating that a file dropzone is requested, must correspond to a directory in uploads dir
$customLangauge = "ignitionbase";
$editButton = TRUE;
$deleteButton = TRUE;
$allowedFields = 'ALL';

$iIndex = [
    'fieldLabels' => [
        'author_name' => lang('base.name'),
        'id' => 'ID',
        'author_featured' => lang('blog.featured'),
        'author_languages' => lang('base.language')
    ]
];
$iForm = [
    'id' => [
        'label' => lang('base.asset') . ' ID',
        'viewOnly' => true,
        'fieldType' => 'text'],
    'author_name' => [
        'label' => lang('base.name'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'author_pub_date' => [
        'label' => lang('base.date'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'author_featured' => [
        'label' => lang('blog.featured'),
		'enabled' => TRUE,
        'fieldType' => 'checkBox'],
    'author_comments' => [
        'label' => lang('blog.allow_comments'),
		'enabled' => TRUE,
        'fieldType' => 'checkBox'],
    'author_languages' => [
        'label' => lang('base.language'),
		'enabled' => TRUE,
        'fieldType' => 'text'],
    'author_about' => [
        'label' => lang('base.about'),
		'enabled' => TRUE,
        'fieldType' => 'textArea'],
    'active' => [
        'label' => 'Enabled',
        'fieldType' => 'checkBox',
		'enabled' => TRUE,
        'validation' => 'in_list[0,1]']
];

?>
