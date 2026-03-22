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
$indexPage = new IIndex(TRUE);
$indexPage->start();

// ------ display success and/or error messages
$indexPage->iIndexMessages($errors);

// ----- store header field labels with IIndex column type labels
if (ValSet($auxButtons, ":edit")) {
	$tableHeader[0] = ['type' => 'buttonEdit', 'headerCell' => lang('base.edit')];
	$count = 1;
} elseif (ValSet($auxButtons, ":view")) {
	$tableHeader[0] = ['type' => 'buttonView', 'headerCell' => lang('base.view')];
	$count = 1;
} else {
	$tableHeader = [];
	$count = 0;
}

// ----- for dna assets, calling from dna autoform
$chainParams = (isset($chainParams) ? $chainParams : FALSE);

// ----- defaults for options
$subModule = (isset($subModule) ? $subModule : FALSE);
$filter = (isset($filter) ? $filter : FALSE);
$securityCode = (isset($securityCode) ? $securityCode : FALSE);

foreach ($model->fieldLabels as $fieldLabel) {
	if(is_array($fieldLabel)) {

		// ----- verify field type set
		if (!isset($fieldLabel['fieldType']) || empty($fieldLabel['fieldType'])) {
			echo _br() . 'You must set a fieldType in IIndex field defintions if building into subarray: ' . _br();
			halt($fieldLabel);
		}

		$fieldType = $fieldLabel['fieldType'];
	} else 
		$fieldType = 'textLabel';

    $tableHeader[$count] = ['type' => $fieldType, 'headerCell' => $fieldLabel];
    $count += 1;
}

if (ValSet($auxButtons, ":delete"))
	$tableHeader[$count] =  ['type' => 'buttonDelete', 'headerCell' => lang('base.delete')];

// ----- link to generator passable variable
$fieldLabelsIndex = $model->fieldLabels;

// ----- check for special text 
if ($indexText != '')
	echo $indexText;

$tableName = $model->table;

// ----- call generator render. define and render index page table with header and rows
$indexPage->render([
    'tableHeader' => $tableHeader,
    'tableRows' => function() use ($elements, $controller, $fieldLabelsIndex, $tableName, $auxButtons, $chainParams, $model, $filter, $securityCode, $subModule) {

		// ----- pull keys
		$labelKeys = array_keys($fieldLabelsIndex);

		// ----- if dna, reverse array order
		if ($chainParams)
			array_multisort($elements, SORT_DESC);

		// ----- create virtual field object 
		$extern = new \Ignition\Library\IXtern();
		$virt = new \Ignition\Library\IVirtual('', $tableName);

        // ----- set up loop for every row
		foreach($elements as $data)
		{
            // ----- check top level admin to see deleted records
			// incompatible with DNA and as of Jul 2024, not used anywhere
            //if (UT != SUPERADMIN && $data['deleted_at'] != '')
            //    continue;

            // ----- check for filter
            if ($filter && $data[$filter['filterField']] != $filter['filterValue'])
                continue; 

            // ----- check for amend and complex database types
            if (DNABASEENABLE && strpos($model->table2, '_amend') > 0) {
                $merg = CurrentAmend($model->table, $data['id']);
                unset($merg['id']);
                $data = array_merge($data, $merg);
            }

            // ----- set data values
			if (ValSet($auxButtons, ":edit")) {
				$tableRow['AUTOFORM'] = ['url' => BaseURL('/' . $controller . ($subModule ? '/' . $subModule : '') . '/edit/' . $data['id'] . ($securityCode ? '/' . $securityCode : ''))];
				$count = 1;
			} elseif (ValSet($auxButtons, ":view")) {
				$tableRow['AUTOFORM'] = ['url' => BaseURL('/' . $controller . ($subModule ? '/' . $subModule : '') . '/view/' . $data['id'] . ($securityCode ? '/' . $securityCode : ''))];
				$count = 1;
			} else {
				$tableRow = [];
				$count = 0;
			}

			// ----- establish the data in extern/virtual objects
			$extern->set($data);
			$virt->set($data);

            // ----- set up loop for every column
            foreach ($labelKeys as $labelKey) {

				// ----- set field
				$field = $fieldLabelsIndex[$labelKey];

				// ----- check for virtual fields
				if (ValSet($labelKey, "__VIRTUAL__"))
					$data[$labelKey] = $virt->process($field, $labelKey);

			    // ----- check for field from external table and fetch
				if (isset($field['externalTable']) && !empty($field['externalTable'])) {

					// ----- call class and fetch data
					$field['name'] = $labelKey;
					$data[$labelKey] = $extern->FetchExternalData($field);

				}

                // ----- process any requested options for column item
                if (isset($field['options'])) {

                    // ----- check for style to place within the table column
                    if (isset($field['options']['style']))
                        $dataValue['style'] = $field['options']['style'];

                }

				// ------ check for binary storage fields
				if (is_array($fieldLabelsIndex[$labelKey])) {
					
					$fieldLabelsIndex[$labelKey]['dataType'] = (isset($fieldLabelsIndex[$labelKey]['dataType']) ? $fieldLabelsIndex[$labelKey]['dataType'] : '');

				    switch ($fieldLabelsIndex[$labelKey]['dataType']) {
				        case "dna40" :
				            $dataVal = bin2dna40($data[$labelKey]);
				            break;
        		        case "binified" :
        		            $dataVal = UnbinifyAsset($data[$labelKey]);
        		            break;
				        case "hex" :
				            $dataVal = sodium_bin2hex($data[$labelKey]);
				            break;
				        case "base64" :
				            $dataVal = sodium_bin2base64($data[$labelKey]);
				            break;
				        case "base10" :
				            $dataVal = bin2base10($data[$labelKey]);
				            break;
        				case "efloat" :
                            $dataVal = UnbinifyEFloat($data[$labelKey]);
//        		            $form->IFloat($field['name'], $field['options']);
        	    	        break;
        				case "dna_date" :
                            $dataVal = \dna\Library\CryptoUtil::dnadate2rfc($data[$labelKey]);
//        		            $form->IDNADate($field['name'], $field['options']);
        	    	        break;
				        default :
				            $dataVal = $data[$labelKey];
				            break;
					}
				} else 
					$dataVal = $data[$labelKey];

				// ----- check for link requested
				if (isset($field['link']) && !empty($field['link'])) {

					// ----- check for self link to record within this table
					if ($tableName == $field['link']) {

						// ----- check for DNA asset ID 
						if (isset($field['displayKey']) && !empty($field['displayKey'])) {
							if ($field['displayKey'] == '__DNA_ASSET_ID__') {
                                if ($chainParams['assetCategory'] == 'transaction')
                                    $dataValue['displayValue'] = height2asset($GLOBALS['ASSETPREFIX'], $chainParams['assetParentID']) . '-' . $dataVal; 
                                else {
                                    if ($GLOBALS['ENTITYTYPE'])
                                        $dataValue['displayValue'] = height2asset($GLOBALS['ASSETPREFIX'], $dataVal);
                                    else
                        				$dataValue['displayValue'] = height2asset($GLOBALS['ASSETPREFIX'], $dataVal);
                                }
                            }

						} else
							$dataValue['displayValue'] = $dataVal;

						// ----- if dna asset
						if ($chainParams) {

                            // ----- if transactional use the chain params to set the additional parameter
                            if ($chainParams['assetCategory'] == 'transaction')
    							$dataValue['url'] = BaseURL('/ezone' . $GLOBALS['KEYCODE'] . '/' . $chainParams['assetTypeName'] . '/' . $chainParams['assetCategory'] . '/'. $chainParams['ezone'] . '/edit/' . $chainParams['assetNumber'] . '/' . $data['id']);
                            else {
                                if ($GLOBALS['ENTITYTYPE'] > 3)
						            $dataValue['url'] = BaseURL('/root' . $GLOBALS['KEYCODE'] . '/' . $chainParams['assetTypeName'] . '/' . $chainParams['assetCategory'] . '/'. $chainParams['ezone'] . '/edit/' . $data['id']);
                                else
                                    $dataValue['url'] = BaseURL('/ezone' . $GLOBALS['KEYCODE'] . '/' . $chainParams['assetTypeName'] . '/' . $chainParams['assetCategory'] . '/'. $chainParams['ezone'] . '/edit/' . $data['id']);
                            }

						} else
							$dataValue['url'] = BaseURL('/' . $controller . '/edit/' . $data['id']);

						$dataVal = $dataValue;

					} else 
						halt("Link field not supported in IIndex");
				}

				// ----- check for image type
				if (isset($field['fieldType']) && $field['fieldType'] == 'image') {

					$dataValue['imageURL'] = $dataVal;
					$dataVal = $dataValue;
				}

				// ----- check for image type
				if (isset($field['fieldType']) && $field['fieldType'] == 'radio') {

                    // ----- check array size against offset
                    if (count($field['radioOptions']) < $dataVal || $dataVal == 0)
                        $dataValue['radioVal'] = '[' . lang('base.not_set') . ']';
                    else
    					$dataValue['radioVal'] = $field['radioOptions'][$dataVal - 1];

					$dataVal = $dataValue;
				}

                $tableRow[$count] = $dataVal;
                $count += 1;
            }

			if (ValSet($auxButtons, ":delete")) {
				// ----- check for custom del link
				if (isset($chainParams['customDelete']))
					$urlStr = $chainParams['customDelete'] . $data['id'];
				else 
					$urlStr = BaseURL('/' . $controller . '/delete/' . $data['id']);

	            $tableRow[$count] =  ['url' => $urlStr];

			}

            // ------ call yield, triggering creation of generator function and call
			//        this is called by the $tableRows generator function within IIndex 
		    yield [$tableRow];

		}
	}
]);

$indexPage->end();
