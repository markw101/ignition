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
    FILE:        UserType10.php
    VERSION:     See BaseController.php
    DESCRIPTION: Standard logged in user, customer
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create form object
$form = new IForm();

// ----- render any messages
$form->iFormSuccess();

// ----- create row and column
$form->iColumn(lang('base.mydashboard'), true);
echo '<div class=buttonItems>';
echo '<table width="80%"><tr><td>';
echo '<tr><td></td></tr></table>';
$form->iRowClose();
echo '</div>';

?>

