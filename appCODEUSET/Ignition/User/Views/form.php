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

// ----- begin form
$form->IFormOpen();

// ----- determine submit text
if ($formType === 'new')
    $submitText = 'base.save';
elseif ($formType === 'delete')
    $submitText = lang('base.confirm') . ' ' . lang('base.delete');
else
    $submitText = 'base.update';

// ----- convert field for display
//$form->data['user_cnumber'] = dnaStore2View($form->data['user_cnumber'], 'text', 'dna40');

// ----- create row and columns
$form->iHidden('id');
$form->IColumn(lang('base.' . $formType) . ' ' . lang('base.user') . ' ' . lang('base.record'));
$form->IText('user_fname');
$form->IText('user_mname');
$form->IText('user_lname');
$form->IText('user_oname');
$form->IText('user_email');
$form->IDNA40('user_peernum', ['enabled' => true], TRUE);
$form->ICheckBox('active');
$form->ISelect('user_language', $config->languages, lang('base.language'));

$form->IColumn(lang('base.security'));
$form->IText('user_type');
$form->IText('user_roles');
$form->IPassword('user_password');
$form->IPassword('user_password2');
$form->IFormSubmit(lang($submitText));

// ----- this automatically closes the row and column 
$form->IFormClose();
