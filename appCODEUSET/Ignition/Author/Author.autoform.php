<?php

$dataTable = 'authors';
$baseDirectory = 'author';            // this is an autoform flag indicating that a file dropzone is requested, must correspond to a directory in uploads dir
$customLangauge = "blog";

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
        'fieldType' => 'text'],
    'author_pub_date' => [
        'label' => lang('base.date'),
        'fieldType' => 'text'],
    'author_featured' => [
        'label' => lang('blog.featured'),
        'fieldType' => 'checkBox'],
    'author_comments' => [
        'label' => lang('blog.allow_comments'),
        'fieldType' => 'checkBox'],
    'author_languages' => [
        'label' => lang('base.language'),
        'fieldType' => 'text'],
    'author_about' => [
        'label' => lang('base.about'),
        'fieldType' => 'textArea'],
    'active' => [
        'label' => 'Enabled',
        'fieldType' => 'checkBox',
        'validation' => 'in_list[0,1]']
];

?>
