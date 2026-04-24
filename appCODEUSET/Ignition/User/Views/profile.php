<br>
<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        profile.php
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

echo '<div class="col-lg-8 col-md-10 mx-auto">';
echo '<font style="font-family:Sans Serif; font-size:18px; font-weight: 400; padding: 0px; color:black;">';

$form->IFormOpen();

// ----- create row and columns
$form->iHidden('id');
$form->IColumn(lang('base.edit') . ' ' . lang('base.my_profile'));
$form->IText('user_fname');
$form->IText('user_mname');
$form->IText('user_lname');
$form->IText('user_oname');
$form->IText('user_email');
$form->IDNA40('user_peernum', ['enabled' => FALSE], TRUE);
$form->IColumn(lang('base.security'));
$form->IPassword('user_password');
$form->IPassword('user_password2');
if (AUTOLANG) {
	echo '<font color="red">' . lang('admin.autolang') . '</font>';
	$form->iHidden('user_language');
} else
	$form->ISelect('user_language', $config->languages, lang('base.language'));

// ----- this automatically closes the row and column 
$form->IRowClose();
br(2);
$form->IFormSubmit(lang('base.save'));
$form->IFormClose();
br();
$form->IButton("Logout", BaseURL("/user" . $GLOBALS['KEYCODE'] . '/logout'));
echo '</font><br><br></div>';
