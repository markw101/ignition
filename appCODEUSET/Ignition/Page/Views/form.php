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

// ----- create general input area
$form->IFormSubmit(lang($submitText));
$form->iHidden('id');
$form->IColumn(lang('base.site') . " " . lang('base.page') . " " . lang('base.' . $formType));
$form->IText('id', ['enabled' => false]);
$form->IText('page_name');
$form->ICheckBox('page_justify');
$form->ICheckBox('page_narrow_banner');
$form->ICheckBox('page_no_carriage_returns');
$form->IColumn('*');
$form->IText('page_views');
$form->IText('page_languages');
$form->IText('updated_at');
$form->IColumnClose();
$form->IRowClose();

// ----- build a list of children, storing id to array
$listChildrenID = [];
foreach(array_keys($model->data) as $fieldName) {

	// ------ search for children records in data
	if (strpos($fieldName, '_>pagelangs<_') === 0){
		$startpos = strrpos($fieldName, "_>>") + 3;
		$childID = substr($fieldName, $startpos, strlen($fieldName) - $startpos);

		// ----- if child record not already saved, add to array 
		if (!isset($listChildrenID[$childID]))
			$listChildrenID[$childID] = $childID;
	}
}

// ----- set up loop to display each language input area
foreach ($listChildrenID as $field => $languageID) {

	// ----- get the language from 
	$lang = $model->data['_>pagelangs<_pagelang_lang_>>'  . $languageID];
	if ($lang == '')
		continue;

	echo '<hr>';

	// ----- create single language input area
	$form->IColumn(strtoupper(lang('languages.' . $lang)) . " " . lang('base.details'));
	$form->ICheckBoxChild('active', $languageID);
	$form->ITextChild('pagelang_title', $languageID);
	$form->ITextChild('pagelang_slug', $languageID);
	$form->IColumn('*');
	$form->ITextChild('pagelang_tags', $languageID);
	$form->ITextChild('pagelang_narrow_text', $languageID);

	$form->IColumn(strtoupper(lang('languages.' . $lang)) . " " . ucfirst(lang('text')), true);
	$form->ITextAreaChild('pagelang_text', 30, $languageID);
	$form->IHiddenChild('pagelang_lang', $languageID);    // must include as hidden, otherwise browser will not return and won't have to save
	$form->IHiddenChild('id', $languageID);
	$form->IHiddenChild('id_parent', $languageID);
	$form->IColumnClose();
	$form->IRowClose();
}

// ----- update data stored in form 
$form->data = $model->data;

$form->IColumn(lang('base.add_photos'), true);

echo '<center>Add "main.jpg/png" image to have automatically be treated as anchor image</center>';

// ----- make file dropzone here
require APPPATH . '/Ignition/Asset/Scripts/make_dropzone.php';

$form->IFormSubmit(lang($submitText));

// ----- this automatically closes the row and column 
$form->IFormClose();

?>