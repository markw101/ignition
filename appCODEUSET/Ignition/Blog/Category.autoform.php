<?php

$databaseName = $GLOBALS['DBGROUP'];
$dataTable = 'category';
$baseDirectory = 'cat';            // this is an autoform flag indicating that a file dropzone is requested, must correspond to a directory in uploads dir
$editButton = TRUE;
$deleteButton = TRUE;
$allowedFields = 'ALL';

$iIndex = [
    'fieldLabels' => [
        'category_name' => lang('base.name'),
        'category_code' => lang('base.code'),
        'category_name' => lang('base.category'),
        'category_featured' => lang('base.featured'),
		'category_lang' => lang('base.language'),
        'id' => 'ID'
    ]
];
$iForm = [
    'id' => [
        'label' => 'ID',
        'fieldType' => 'text',
		'enabled' => FALSE],
    'category_lang' => [
        'label' => lang('base.language'),
        'fieldType' => 'text',
        'validation' => 'required',
		'enabled' => TRUE],
    'category_name' => [
        'label' => lang('base.category') . ' ' . lang('base.name'),
        'fieldType' => 'text',
        'validation' => 'required',
		'enabled' => TRUE],
    'category_code' => [
        'label' => lang('base.code') . lang('blog.how_cat_ref'),
        'fieldType' => 'text',
		'enabled' => TRUE],
    'category_description' => [
        'label' => lang('base.description'),
        'fieldType' => 'text',
		'enabled' => TRUE],
    'category_notes' => [
        'label' => lang('base.notes'),
        'fieldType' => 'textArea',
		'enabled' => TRUE],
    'category_url' => [
        'label' => lang('base.link_url'),
        'fieldType' => 'text',
		'enabled' => TRUE],
    'category_featured' => [
        'label' => lang('base.featured'),
        'fieldType' => 'checkBox',
        'validation' => 'in_list[0,1]',
		'enabled' => TRUE],
    'active' => [
        'label' => 'Active',
        'fieldType' => 'checkBox',
        'validation' => 'in_list[0,1]',
		'enabled' => TRUE]
];


?>