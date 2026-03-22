<?php
$editButton = TRUE;
$deleteButton = TRUE;

$iIndex = [
    'fieldLabels' => [
        'fileName' => lang('base.original_file'),
        'fileType' => lang('base.file_type'),
        'fileSize' => lang('base.size'),
        'created' => lang('base.update') . ' ' . lang('base.date')
    ]
];
$iForm = [
    'id' => [
        'label' => lang('base.asset') . ' ID',
        'viewOnly' => true,
        'fieldType' => 'text'],
    'asset_user_cno' => [
        'label' => lang('dna.dnapno'),
        'viewOnly' => true,
        'fieldType' => 'text'],
    'asset_file_name' => [
        'label' => lang('base.file') . ' '. lang('base.name'),
        'viewOnly' => true,
        'fieldType' => 'text'],
    'asset_key' => [
        'label' => lang('base.asset') . ' ' . lang('base.key'),
        'viewOnly' => true,
        'fieldType' => 'text'],
    'asset_category' => [
        'label' => lang('base.category'),
        'viewOnly' => true,
        'fieldType' => 'text'],
    'asset_notes' => [
        'label' => lang('base.notes'),
        'fieldType' => 'textArea'],
    'active' => [
        'label' => 'Enabled',
        'fieldType' => 'checkBox',
        'validation' => 'in_list[0,1]']
];

?>
