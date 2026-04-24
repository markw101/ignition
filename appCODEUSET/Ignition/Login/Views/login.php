<br>
<style>
	div.buttonItems {
		display: flex;
		justify-content: space-around;
		align-items: center;
		font-family: Chandas;
		font-weight: 400;
		font-size: 25px;
		width: 90%;
		border-color:#131925;
	}
	input {
		margin: 5px;
	    font-family: sans-serif;
	    outline: 1px;
	    background: #344765;
	    color: #fff;
	    border: 1px solid #344765;
	    padding: 4px;
	    border-radius: 5px;
	}
	.inputClass {
	    display: block;
    	width: 75%;
		margin: 5px;
	    font-family: sans-serif;
	    outline: 1px;
	    background: #344765;
	    color: #fff;
	    border: 1px solid #344765;
	    padding: 4px;
	    border-radius: 5px;
	}
	.inputText {
	    display: block;
		margin: 5px;
		font-size: 12px;
	    font-family: sans-serif;
	    outline: 1px;
	    color: #fff;
	    padding: 4px;
	    border-radius: 5px;
	}
	.checkboxClass {
	    display: block;
		margin: 5px;
	    font-family: sans-serif;
	    color: #fff;
	    padding: 10px;
	    border-radius: 5px;
	}
	.buttonClass {
		margin: 5px;
	    font-family: sans-serif;
	    outline: 1px;
	    background: #26a9e1;
	    color: #fff;
	    border: 1px solid <?=ACCENTCOLOR?>;
	    padding-left: 15px;
	    padding-right: 15px;
	    border-radius: 5px;
	}
	select {
		margin: 5px;
	    height: 34px;
	    font-family: sans-serif;
	    outline: 1px;
	    background: #26a9e1;
	    color: #fff;
	    border: 1px solid <?=ACCENTCOLOR?>;
	    padding-left: 15px;
	    padding-right: 15px;
	    border-radius: 5px;
	}
</style>

<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        login.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create form object
$form = new IForm($model, $data);
$form->classOverride = 'inputClass';
$form->classOverrideText = 'inputText';
$form->IFormErrors($errors);
$form->IFormOpen();

// ----- create row and column
echo '<div class="row" style="border-color:#131925">';
echo '<div class="table-responsive" style="border-color:#131925; padding-top: 0px; padding-left: 15px; padding-right: 15px; padding-bottom: 0px">';
echo '<div class="panel panel-default" style="width: 100%; border-color:#222e3e">';
echo '<div class="panel-heading form-inline clearfix" style="width: 100%; background-color:#344765; border-color:#222e3e; color:#b9d2d7; font-size:20px">';
echo lang('base.login_title') . '</div>';

echo '<div class=panel-body style="width: 100%; background-color:#1b2433; border-color:#222e3e; color:#ffffffff; font-size:20px">';

// ----- display language picker if requested
if (AUTOLANG) {

    echo '<div class="dropdown">';
    echo '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 10px; margin-top: 10px; background: #' . $GLOBALS['ADMINMENUCOLOR'] . '">';
    echo '<font style="font-size: 10px; color: #ffffff">' . lang('base.language') . '</font>';
    echo '</button>';
    
    echo '<ul class="dropdown-menu" style="font-size: 10px; background: #26a9e1">';

	// ----- set up loop to make li for every lang
	foreach ($languages as $key => $label) {
    	$label = lang('languages.' . $key);
        echo '<li><a href="' . BaseURL("/", $key) . 'access" style="font-size: 10px; color: ' . MENUFONT . '; text-shadow: 1px 1px #000000">' . $label . '</a></li>';
	}

    echo '</ul></div>';

}
$form->model->fieldLabels['user_username'] = strtoupper(lang('base.email') . lang('base.or') . lang('base.account') . ' ' . lang('base.number'));

$form->IText('user_username', ['autofocus' => TRUE]);
$form->IPassword('user_password');
$form->classOverride = 'checkboxClass';
$form->ICheckBox('autoLogout');
$form->IFormSubmit(lang('base.login_title'), 'class=buttonClass');
$form->IFormClose();

// --- display instructions
echo '<br><br>';
echo lang('base.no_account') . '&nbsp;<a href="' . $iConfig->signupURL . '">[' . lang('base.sign_up') . ']</a><br>';
echo lang('base.if_forgot') . '&nbsp;<a href="/user/requestpasswordreset">[' . lang('base.password_reset') . ']</a><br>';
echo lang('base.need_verification_email') . '&nbsp;<a href="/user/sendverificationemail">[' . lang('base.resend') . ']</a>.<br><br>';
echo '</div>';

return;

?>

