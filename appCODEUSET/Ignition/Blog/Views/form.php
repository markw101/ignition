<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        form.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create form object
$form = new IForm($model);

// ----- render any errors
$form->IFormErrors($errors);

// ----- determine submit text 
$form->IFormOpen();
// ----- determine submit text
if ($formType === 'new')
    $submitText = 'base.save';
elseif ($formType === 'delete')
    $submitText = lang('base.confirm') . ' ' . lang('base.delete');
else
    $submitText = 'base.update';

// ----- build meta info box 
$form->IFormSubmit(lang($submitText));
$form->IColumn(lang('base.' . $formType) . " Blog");
$form->IText('id', ['enabled' => false]);
$form->IText('blog_name');
$form->ICheckBox('blog_featured');
$form->ICheckBox('blog_justify');
$form->ICheckBox('active');
//$form->ICheckBox('blog_pinned');
$form->IColumn('*');
$form->IText('created_at');
$form->IText('blog_comments');
$form->IText('blog_languages');
echo "For remote access of blog articles from this site use the following code: " . REMOTEPIN;
$form->IColumnClose();
$form->IRowClose();

$form->IColumn(lang('base.notes'), true);
$form->ITextArea('blog_notes', 5);

// ----- build a list of children, storing id to array
$listChildrenID = [];
foreach(array_keys($form->data) as $fieldName) {

	if (strpos($fieldName, '_>bloglangs<_') === 0){
		$childID = substr($fieldName, strrpos($fieldName, "_>>") + 3, strlen($fieldName) - strrpos($fieldName, "_>>") + 3);
		if (!isset($listChildrenID[$childID]))
			$listChildrenID[$childID] = $childID;
	}
}

// ----- set up category database
//$db      = \Config\Database::connect();
//$builder = $db->table('category');

// ----- set up loop to display each language input area
foreach ($listChildrenID as $field => $languageID) {

	// ----- get the language from child record
	$lang = $form->data['_>bloglangs<_bloglang_lang_>>'  . $languageID];
	if ($lang == '')
		continue;

	// ----- check for external domain
	if ($form->data['_>bloglangs<_bloglang_external_domain_>>'  . $languageID] != '')
		$extern_disabled = ['enabled' => false];
	else
		$extern_disabled = [];

	echo '<hr>';

	// ----- create single language input area
	$form->IColumn(strtoupper(lang('languages.' . $lang)) . " " . lang('base.details'));
	$form->ICheckBoxChild('active', $languageID);
	$form->ITextChild('bloglang_title', $languageID);
	$form->ITextChild('bloglang_author', $languageID);
	$form->ITextChild('bloglang_authorid', $languageID);
	$form->ITextChild('bloglang_refreshdate', $languageID);
	$form->ITextChild('bloglang_slug', $languageID);
//	$form->ITextChild('bloglang_views', $languageID);

	$form->IColumn('*');
	$form->ITextChild('bloglang_external_domain', $languageID);
	$form->ITextChild('bloglang_external_code', $languageID);
	$form->ITextChild('bloglang_category', $languageID);
	$form->ITextChild('bloglang_tags', $languageID);

	$form->IColumn(ucfirst(strtoupper(lang('languages.' . $lang)) . " " . ucfirst(lang('text'))), true);
	$form->ITextChild('bloglang_description', $languageID);
	$form->ITextAreaChild('bloglang_text', 30, $languageID, $extern_disabled);

	$form->IHiddenChild('bloglang_lang', $languageID);    // must include as hidden, otherwise browser will not return and won't have to save
	$form->IHiddenChild('id', $languageID);
	$form->IHiddenChild('id_parent', $languageID);
	$form->IColumnClose();
	$form->IRowClose();

}

// ----- create Column
$form->IColumn(lang('base.add_photos'), true);

echo '<center>Add "main.jpg/png" image to have automatically be treated as anchor image</center>';

// ----- make file dropzone here
require APPPATH . '/Ignition/Asset/Scripts/make_dropzone.php';

$form->IFormSubmit(lang($submitText));

// ----- this automatically closes the row and column 
$form->IFormClose();

?>