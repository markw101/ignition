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
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['updated_at']],
		['type' => 'textLabel', 'headerCell' => $model->fieldLabels['blog_title']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['blog_slug']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['active']],
        ['type' => 'buttonDelete', 'headerCell' => lang('base.delete')]
    ],
    'tableRows' => function() use ($elements) {

		foreach($elements as $data)
		{
            // ----- check for visitor already left
            if ($data['deleted_at'])
                continue;

		    yield [
		        ['url' => BaseURL('/blog' . $GLOBALS['KEYCODE'] . '/edit/' . $data['id'])],
		        $data['id'],
		        $data['updated_at'],
		        $data['blog_title'],
		        $data['blog_slug'],
		        $data['active'],
		        ['url' => BaseURL('/blog' . $GLOBALS['KEYCODE'] . '/delete/' . $data['id'])]
		    ];
		}
	}
]);

$indexPage->end();

