<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        index.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/


// ----- create ignition index object
$indexPage = new IIndex();
$indexPage->start();

// ------ display success and/or error messages
$indexPage->iIndexMessages($errors);

// ----- define and render index page table with header and rows
$indexPage->render([
    'tableHeader' => [
        ['type' => 'buttonEdit', 'headerCell' => lang('base.edit')],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['id']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['user_peernum']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['user_type']],
		['type' => 'textLabel', 'headerCell' => $model->fieldLabels['user_email']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['user_fname']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['user_lname']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['active']],
        ['type' => 'buttonDelete', 'headerCell' => lang('base.delete')]
    ],
    'tableRows' => function() use ($elements) {

		foreach($elements as $data)
		{
            // ----- check for visitor already left
            if ($data['deleted_at'])
                continue;

            // ------ check for super user yet, not logged in as one
            if ($data['user_type'] == SUPERADMIN && UT != SUPERADMIN)
                continue;

		    yield [
		        ['url' => BaseURL('/user' . $GLOBALS['KEYCODE'] . '/edit/' . $data['id'])],
		        $data['id'],
		        bin2dna40($data['user_peernum']),
		        $data['user_type'],
		        $data['user_email'],
		        $data['user_fname'],
		        $data['user_lname'],
		        $data['active'],
		        ['url' => BaseURL('/user' . $GLOBALS['KEYCODE'] . '/delete/' . $data['id'])]
		    ];
		}
	}
]);

$indexPage->end();
