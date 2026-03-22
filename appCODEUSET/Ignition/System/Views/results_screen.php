<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        results_screen.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create form object
$form = new IForm();

if (is_array($body)) {
    $title = $body['title'];
    $button = $body['button'];
    $action = $body['action'];
    $body = $body['body'];
} else {
    $title = "Unknown error";
    $button = lang('base.error');
    $action = '';
    $body = 'Unknown error';
}

$form->IColumn($title, TRUE);

// ----- present body text
br();
echo $body;
br(2);
echo $form->IButton($button, $action);

br(2);

?>
