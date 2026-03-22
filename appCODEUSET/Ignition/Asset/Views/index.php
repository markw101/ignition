<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        index.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
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
        ['type' => 'buttonView', 'headerCell' => lang('base.open')],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['fileName']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['fileType']],
        ['type' => 'textLabel', 'headerCell' => $model->fieldLabels['fileSize']],
		['type' => 'textLabel', 'headerCell' => $model->fieldLabels['created']],
        ['type' => 'buttonDelete', 'headerCell' => lang('base.delete')]
    ],
    'tableRows' => function() use ($elements) {

		foreach($elements as $data)
		{

		    yield [
		        ['url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/' . ($data['isDir'] ? 'index' : 'edit') . '/' . $data['fileLink'])],
		        $data['fileName'],
                $data['fileType'],
		        $data['fileSize'],
		        $data['created'],
		        ['url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/delete/')]
		    ];
		}
	}
]);

// ----- make file dropzone here
require APPPATH . '/Ignition/Asset/Scripts/general_dropzone.php';

// ----- show stats 
$remaining = ($fileQuotaSize * 1000000) - (is_numeric($totalBytes) ? $totalBytes : 0);
echo '<br><div class="panel-body clearfix" style="border:2px solid #000000; padding: 10px;"><b>Directory Statistics</b><br>';
echo 'Bytes Total: ' . (strlen($totalBytes) ? number_format($totalBytes) : 0);
echo '<br>Directory Quota: ' . number_format($fileQuotaSize * 1000000);
echo '<br>Bytes Remaining: ' . number_format($remaining);
echo '<br></div>';

$indexPage->end();
