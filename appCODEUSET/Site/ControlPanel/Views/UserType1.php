<style>

	div.buttonItems {
		display: flex;
		justify-content: space-around;
		align-items: center;
		font-family: Chandas;
		font-weight: 400;
		font-size: 25px;
		width: 90%;
	}
</style>

<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        UserType1.php
    VERSION:     See BaseController.php
    DESCRIPTION: Hight level user, super admin
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/
// ----- create form object
$form = new IForm();

// ----- render any messages
$form->IFormSuccess();

// ----- create row and column
$form->IColumn('Options', true);
echo '<div class=buttonItems>';
echo '<table width="80%"><tr><td>';
sp(3);$form->IButton('Open login port', BaseURL("/tazscript" . $GLOBALS['KEYCODE'] . '/script/requestlogin'));br();
echo '</td></tr></table>';
$form->IRowClose();
echo '</div>';

?>

