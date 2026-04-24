<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        UserType3.php
    VERSION:     See BaseController.php
    DESCRIPTION: Third tier administrator
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create form object
$form = new iForm();

// ----- render any messages
$form->iFormSuccess();

// ----- create row and column
$form->iColumn(lang('gravitron.accounts'), true);
echo '<table width="50%"><tr><td>';
echo '<center>';
$form->iButton(lang('gravitron.account'), baseURL("/account" . $GLOBALS['KEYCODE']));
echo '</center>';
echo '</td></tr></table>';
$form->iRowClose();

?>