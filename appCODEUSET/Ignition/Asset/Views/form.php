<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        form.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create form object
$form = new IForm($model, $data);

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

// ----- create row and column
$form->IColumn(lang('base.' . $formType) . ' ' . lang('base.asset'));
$form->IText('id', ['enabled' => false]);
$form->IText('asset_user_cno', ['enabled' => false]);
$form->IText('asset_file_name', ['enabled' => false]);
$form->IText('asset_key', ['enabled' => false]);
$form->IText('asset_category', ['enabled' => false]);

// ----- create row and column
$form->IColumn(lang('base.asset'));
if ($formType === 'new')
    echo '<img src="/images/profile-icon.png" width="400">';
else
    echo '<img src="/assets/' . $data['asset_category'] . '/' . $data['asset_category'] . '-' . $data['asset_key'] . '-' . $data['asset_file_name'] . '" width="400">';

$form->IColumn(lang('base.details'), true);
$form->ITextArea('asset_notes');
$form->ICheckBox('active');
$form->IFormSubmit(lang($submitText));

// ----- this automatically closes the row and column 
$form->IFormClose();


?>