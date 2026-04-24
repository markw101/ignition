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
$form = new iForm();

// ----- render any messages
$form->iFormSuccess();

// ----- create row and column
$form->iColumn(lang('ecoin.my_stripe'), true);
echo '<div class=buttonItems>';
echo '<table width="80%"><tr><td>';
echo "<tr><td>Portfolio:<br>";
sp(3);$form->iButton(lang('dna.ebond') . ' ' . lang('dna.account'), baseURL("/ebond_accounts" . $GLOBALS['KEYCODE']));br();
sp(3);$form->iButton(lang('dna.ecoin') . ' ' . lang('dna.account'), baseURL("/ecoin_accounts" . $GLOBALS['KEYCODE'] . '/index'));br();
sp(3);$form->iButton(lang('dna.ecompany_charter'), baseURL("/ecompany_charter" . $GLOBALS['KEYCODE']));br();
sp(3);$form->iButton(lang('dna.ecomplaint') . ' ' . lang('dna.record'), baseURL("/ecomplaint_accounts" . $GLOBALS['KEYCODE']));br();
sp(3);$form->iButton(lang('dna.edocument_folio'), baseURL("/edocument_folio" . $GLOBALS['KEYCODE']));br();
sp(3);$form->iButton(lang('dna.etitle') . ' ' . lang('dna.record'), baseURL("/etitle_record" . $GLOBALS['KEYCODE']));br();
sp(3);$form->iButton(lang('dna.estock') . ' ' . lang('dna.certificate'), baseURL("/myworld" . $GLOBALS['KEYCODE']) . '/estock/listing');br();
sp(3);$form->iButton(lang('dna.evote') . ' ' . lang('dna.record'), baseURL("/evote_record" . $GLOBALS['KEYCODE']));br();
sp(3);$form->iButton(lang('dna.ereward') . ' ' . lang('dna.record'), baseURL("/ereward_account" . $GLOBALS['KEYCODE']));br();
echo '</td></tr></table>';
$form->iRowClose();
echo '</div>';

?>

