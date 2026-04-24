<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        iform-set-fields.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

/// GENERIC IFORM SNIPPET FOR SETTING FIELDS IN AUTOFORM
for ($count = 0; $count < $keyCount; $count ++) {

    // ----- store sub array name (field name)
    $fieldName = $iFormKeys[$count];
    $fieldValues = $iForm[$fieldName];

    // ----- check for label and set
    if (isset($fieldValues['label']))
        $fieldLabels[$fieldName] = $fieldValues['label'];

    // ----- add to allowed
	if ( (isset($fieldValues['enabled']) && ($fieldValues['enabled'] == false && $fieldName != 'id')))
		$showOnly[] = $fieldName;

    // ----- combine core options
    $options = $fieldValues['options'] ?? [];
    $options = array_merge($options, ['enabled' => $fieldValues['enabled'] ?? FALSE]);

    // ----- combine optional options
    if (isset($fieldValues['style']))
        $options = array_merge($options, ['style' => $fieldValues['style']]);
    if (isset($fieldValues['decoration']))
        $options = array_merge($options, ['decoration' => $fieldValues['decoration']]);
    if (isset($viewOnlyDNA))
        $options = array_merge($options, ['dnaViewOnly' => TRUE]);

    //----- normalize fields to always include all elements
    $formFields[$count] =
        ['name' => $fieldName,
        'options' => $options,
        'fieldType' => (isset($fieldValues['fieldType']) ? $fieldValues['fieldType'] : ''),
        'title' => (isset($fieldValues['title']) ? $fieldValues['title'] : ''),
        'dataType' => (isset($fieldValues['dataType']) ? $fieldValues['dataType'] : 'text'),
		'selectOptions' => (isset($fieldValues['selectOptions']) ? $fieldValues['selectOptions'] : ''),
		'externalTable' => (isset($fieldValues['externalTable']) ? $fieldValues['externalTable'] : []),
		'values' => (isset($fieldValues['values']) ? $fieldValues['values'] : []),
		'radioOptions' => (isset($fieldValues['radioOptions']) ? $fieldValues['radioOptions'] : []),
		'link' => (isset($fieldValues['link']) ? $fieldValues['link'] : []),
		'label' => (isset($fieldValues['label']) ? $fieldValues['label'] : []),
		'virtualType' => (isset($fieldValues['virtualType']) ? $fieldValues['virtualType'] : ''),
		'displayKey' => (isset($fieldValues['displayKey']) ? $fieldValues['displayKey'] : []),
        'childColumns' => (isset($fieldValues['childColumns']) ? $fieldValues['childColumns'] : []),
        'linkType' =>  (isset($fieldValues['linkType']) ? $fieldValues['linkType'] : []),
        'recordOrder' =>  (isset($fieldValues['recordOrder']) ? $fieldValues['recordOrder'] : []),
        'dbType' =>  (isset($fieldValues['dbType']) ? $fieldValues['dbType'] : [])
		];

    // ----- check for validation and set
    if (isset($fieldValues['validation']))
        $validationRules[$fieldName] = $fieldValues['validation'];

}

// ----- assign values to model
$model->fieldLabels = $fieldLabels;
if (isset($validationRules))
    $model->validationRules = $validationRules;
$model->formFields = $formFields;
$model->showOnly = $showOnly;
$model->iForm = $iForm;

// ----- dropzone sets base directory
$model->subDirectory = $baseDirectory ?? false;

