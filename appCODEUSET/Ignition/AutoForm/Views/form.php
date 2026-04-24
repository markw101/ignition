<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        form.php
    VERSION:     See BaseController.php
    DESCRIPTION: Autoform automated field display
    COPYRIGHT:   2022
    FIRST REV:   15 Aug 2022
    LICENSE:     GPL Check

*********************************************************************/

// ----- create form object
$form = new IForm($model);

// ----- render any errors
$form->IFormErrors($errors);

// ----- determine submit text 
$form->IFormOpen();
// ----- determine submit text
if ($formType === 'new')
    $submitText = 'base.append_record';
elseif ($formType === 'delete')
    $submitText = lang('base.delete_record');
else
    $submitText = 'base.update_record';

// ----- top of form submit button
if (InList('edit|delete|new|', $method))
    $form->IFormSubmit(lang($submitText));

// ----- required for the file dropzone
if ($model->subDirectory)
    $form->iHidden('id');

// ----- if not user defined, create row and column
if ($model->formFields[0]['fieldType'] != "wideBox" && $model->formFields[0]['fieldType'] != "narrowBox")
	$form->IColumn(lang('base.' . $formType) . ' ' . lang('base.record'), true);
else {
	if ($model->formFields[0]['title'] == '')
		$model->formFields[0]['title'] = lang('base.' . $formType) . ' ' . lang('base.record');
}

if ($formText != '')
	echo $formText;

// ----- if running DNA, must unify binary data and convert to display text
//       setup loop to convert all binary data types
//       this must be done before show because fields may be out of order 
if (DNABASEENABLE) {
	foreach ($model->formFields as $field) {

		// ----- look for next binary type
	    if ($field['fieldType'] == "text" && $field['dataType'] != "text" && empty($field['externalTable']) && !ValSet($field['name'], "__VIRTUAL__")) {
			$form->data[$field['name']] = dnaStore2View($form->data[$field['name']], $field['fieldType'], $field['dataType']);
		}

	}

}

// ----- safety for overrun child records count
if ($model->dbType != 'STD' && ($model->currentChildRecord >= count($model->rawRecords)))
    $model->currentChildRecord = 0;

// ----- if amd style database, then store the current record id in a hidden field
if ($model->dbType == 'AMD' || $model->dbType2 == 'AMD')
    echo '<input type="hidden" name="__SESSIONID2428934__" value="' .  ($formType === 'new' ? 'new' : $model->rawRecords[$model->currentChildRecord]['id']) . '">';

// ----- create special field objects for processing all external & virtual fields
$extern = new \Ignition\Library\IXtern($model->data);
$virt = new \Ignition\Library\IVirtual($model->data);

// ----- setup loop to output all fields
foreach ($model->formFields as $field) {

    // ----- check for field from external table
	if (!empty($field['externalTable'])) {

        // ----- check for virtual
        $tempField = $field;
        if (ValSet($field['name'], '__VIRTUAL__'))
           $tempField['name'] = substr($field['name'], 0, strlen($field['name']) - 11);

		// ----- check for new record
		if ($formType === 'new')
			$form->data[$field['name']] = '';
		else 
			$form->data[$field['name']] = $extern->FetchExternalData($tempField);

       // ----- check for error, if external, field should not be enabled
       if ($field['options']['enabled'])
            halt("External reference field accidentally enabled: " . $field['name'] . '.  Please disable.');

	} else {

    	// ----- check for virtual fields (ie: ecompany, esector, ttm)
    	if (ValSet($field['name'], "__VIRTUAL__"))
    		$form->data[$field['name']] = $virt->process($field, $field['name']);

    }

    // ----- for special child list records, must set type
    if (strpos($field['fieldType'], ':') == FALSE) {
        $childListType = NOTSET;
        $switchType = $field['fieldType'];
    } else {
        $childListType = substr(strstr($field['fieldType'], ':'), 1);
        $switchType = substr($field['fieldType'], 0, strpos($field['fieldType'], ':'));
    }

    switch ($switchType) {
        case "text" :

		    switch ($field['dataType']) {
		        case "dna40" :
		            $form->IDNA40($field['name'], $field['options']);
		            break;
		        case "binified" :
		            $form->IFan($field['name'], $field['options']);
		            break;
		        case "base64" :
		            $form->IBase64($field['name'], $field['options']);
		            break;
		        case "hex" :
		            $form->IHex($field['name'], $field['options']);
		            break;
				case "text" :
		            $form->IText($field['name'], $field['options']);
	    	        break;
				case "base10" :
		            $form->IBase10($field['name'], $field['options']);
	    	        break;
				case "efloat" :
		            $form->IFloat($field['name'], $field['options']);
	    	        break;
				case "dna_date" :
		            $form->IDNADate($field['name'], $field['options']);
	    	        break;
				default :
	            	echo "Form Error: IForm incorrect dataType attribute in field defintion in AutoForm for field: " . $field['name'] . ".  Must be set to either text, dna40, base64, base10, hex, efan or efloat.<br>";
					break;
			}
			break;
        case "textArea" :
            $form->ITextArea($field['name'], 0, $field['options']);
            break;
        case "checkBox" :
            $form->ICheckBox($field['name'], $field['options']);
            break;
        case "hidden" :
            $form->iHidden($field['name'], $field['options']);
            break;
        case "password" :
            $form->IPassword($field['name'], $field['options']);
            break;
        case "select" :
			$field['dataType'] = (isset($field['dataType']) && $field['dataType'] != '' ? $field['dataType'] : "text");
			$field['options'] = array_merge($field['options'], ['dataType' => $field['dataType']]);
            $form->ISelect($field['name'], $field['selectOptions'], $model->fieldLabels[$field['name']], $field['options']);
            break;
		case "wideBox" :
			$form->IColumn($field['title'], TRUE);
			break;
		case "narrowBox" :
			$form->IColumn($field['title']);
			break;
		case "break" :
            if (isset($field['options']['count']))
                $count = $field['options']['count'];
            else 
                $count = 1;
			br($count);
			break;
		case "radio" :
			$form->IRadio($field['name'], $field['radioOptions'], $model->fieldLabels[$field['name']], $field['options']);
			break;
		case "image" :
			$form->IImage($field['name'], $field['options']);
			break;
		case "button" :
			$form->IButton($field['label'], $field['link']);
			break;
		case "label" :
            $form->ILabel($field['label'], $field['options']);
			break;
		case "link" :
			// ----- check for dna fungible assset
			if ($field['displayKey'] == '__DNA_FUNG_ID__') {

                // ----- switch according to type
                switch (TRUE) {

                    case ($chainParams['assetCategory'] == 'transaction' ) :
                        $eRobotRecord = FetchWorldwideRecord($form->data['erobot']);
                        if ($eRobotRecord) {
                            //$displayValue = $eRobotRecord['erobot_symbol'] . '-' . $chainParams['assetParentID'] . '-' . $chainParams['transNumber']; 
                            $linkText = $eRobotRecord['erobot_symbol'] . '-' . $chainParams['assetParentID']; 
                        } else 
                            $linkText = lang('base.error');
                        break;

                    case ($GLOBALS['ASSETPREFIX'] == "M" || $GLOBALS['ASSETPREFIX'] == "B" || $GLOBALS['ASSETPREFIX'] == "T" || $GLOBALS['ASSETPREFIX'] == "A") :
                        $eRobotRecord = FetchWorldwideRecord($form->data['erobot']);
                        if ($eRobotRecord)
                            $linkText = $eRobotRecord['erobot_symbol'] . '-' . bin2dna40($form->data['dna_account']);
                        else 
                            $linkText = lang('base.error');
                        break;

                    default :
                        $linkText = bin2dna40($form->data['dna_account']);
                        break;
                }

				$linkText = $linkText . '@' . $chainParams['ezone'];
                $link = $linkText;

			// ----- check for dna regular assset or entity
            } elseif ($field['displayKey'] == '__DNA_ASSET_ID__') {

                // ----- switch according to type
                switch (TRUE) {


// ????!!!!
                    case ($chainParams['assetCategory'] == 'transaction' && ($chainParams['assetFunction'] == 'view')) :
                        $linkText = height2asset($GLOBALS['ASSETPREFIX'], $chainParams['assetParentID']) . '-' . $chainParams['transNumber']; 
                        break;

                    default :
        				$linkText = height2asset($GLOBALS['ASSETPREFIX'], $form->data['id']);
                        break;
                }

				$linkText = $linkText . '@' . $chainParams['ezone'];
                $link = $linkText;

			} elseif($field['displayKey'] == '__DNA_COLLECT_ID__') {

				$linkText = bin2dna40($form->data['dna_account']) . '@' . $chainParams['ezone'];

            } else {
				$linkText = $form->data[$field['name']];
			}

            $link = (isset($link) ? $link : $linkText);

			$form->ILink($linkText, $link, $field['options'], 'assetID', $field['label']);
			break;

        case "child_list" :
            $height = (isset($field['options']['height']) ? $field['options']['height'] : 100);
            $width =  (isset($field['options']['width']) ? $field['options']['width'] : 125);
            echo "<style>table.childList{display:block;border:1px solid #" . $GLOBALS['ADMINMENUCOLOR'] . ";margin-top:10px;}tbody.childList{display:block;overflow-y:scroll;height:" . $height . "px;}th.childList{width:" . $width . "px;text-align: center;}td.childList{width:" . $width. "px;padding-left:3px}</style>";

            if (!isset($field['childColumns']))
                 halt('childCoumns array not found in call to "child_list". Must set a childColumns in field: ' . $field['name']);

            // ----- label
            if ($field['label'] != '' && $field['label'] != [])
                echo $field['label'] . '<br>';
            echo '<table class="childList"><thead><tr class="childList">';

            // ----- set up loop to print every column in the record
            foreach ($field['childColumns'] as $childCol) {
                echo '<th class="childList">';
                echo $childCol['label'];
                echo '</th>';
            }

            echo '</tr></thead><tbody class="childList">';

            // ----- if user defined order reverse, set
            if (ValSetArray($field['recordOrder'], 'reverse')) {
                $reverse = TRUE;
                $rowKey = ($model->totalChildren - 1);
            } else { 
                $rowKey = 0;
                $reverse = FALSE;
            }

            // ----- set up loop to iterate through child records in recordOrder
            do {

                // ----- check for new type
                if ($formType === 'new')
                    break;

                $childRec = $model->rawRecords[$rowKey];

                // ----- check for AMD and entity number
                if (($form->model->dbType == 'AMD' || $form->model->dbType == 'TRN') && isset($form->model->data['entity_number'])) {
                    $childRec = array_merge($childRec, ['entity_number' => $form->model->data['entity_number']]);
                }

                // ----- check for COM, need to get child of child and merg with parent
                if ($form->model->dbType == 'COM') {
                    $merg = CurrentAmend($model->table2, $rowKey + 1, $GLOBALS['ECOUNTRY'], $GLOBALS['PREFIX'], $model->dataBase2);
                    unset($merg['id']);

                    // ----- set up loop to add to data
                    foreach ($merg as $key => $value) {
                        // ----- add to allowed2, retrieve external value and set value in data
                        $form->model->allowedFields2[] = $key;
                        $childFieldName =  '_>' . $form->model->table2 . '<_' . $key . '_>>'  . $childRec['id'];
                        $form->data[$childFieldName] = $value;
                    }

                    $childRec = array_merge($childRec, $merg);
                }

                echo '<tr class="childList">';

                // ----- set up loop to print every column in the record
                foreach ($field['childColumns'] as $childCol) {

                    // ----- set defaults
                    if (isset($childCol['options']))
                        $childCol['options'] = array_merge($childCol['options'], ['noLabel','noEcho', 'enabled' => FALSE]);
                    else {
                        if (isset($field['options']))
                            $childCol['options'] = array_merge($field['options'], ['noLabel','noEcho', 'enabled' => FALSE]);
                        else
                            $childCol['options'] = ['noLabel','noEcho', 'enabled' => FALSE];
                    }

                    if (!isset($childCol['fieldType']))
                        $childCol['fieldType'] = 'text';

                    if (!isset($childCol['dataType']))
                        $childCol['dataType'] = 'text';

                    if (!isset($childCol['linkType']))
                        $childCol['linkType'] = 'copytext';

                    // ----- check for field from external table
                	if (isset($childCol['externalTable'])) {
                        // ----- add to allowed2, retrieve external value and set value in data
                        $form->model->allowedFields2[] = $childCol['name'];
                        $childFieldName =  '_>' . $form->model->table2 . '<_' . $childCol['name'] . '_>>'  . $childRec['id'];

                        // ----- in case of complex, must modify the link field 0 column to include the full child field name
                        if ($form->model->dbType == 'COM') {
                            $childCol['externalTable']['link1Fields'][0] = '_>' . $form->model->table2 . '<_' . $childCol['externalTable']['link1Fields'][0] . '_>>'  . $childRec['id'];

                            // ----- check for amendment record requested (this does not work, need to get all amendables)
                            if (isset($childCol['externalTable']['link1AMDField']))
                                $childCol['externalTable']['link1AMDField'] = '_>' . $form->model->table2 . '<_' . $childCol['externalTable']['link1AMDField'] . '_>>'  . $childRec['id'];
                        }
//halt($childRec);
                        $externChild = new \Ignition\Library\IXtern($childRec);
                        $form->data[$childFieldName] = $externChild->FetchExternalData($childCol);
                	}

                    switch ($childCol['fieldType']) {
                        case "text" :
                		    switch ($childCol['dataType']) {
                		        case "dna40" :
                		            $form->IDNA40Child($childCol['name'], $childRec['id'], $childCol['options']);
                		            break;
                		        case "binified" :
                		            $form->IFanChild($childCol['name'], $childRec['id'], $childCol['options']);
                		            break;
                				case "text" :
                		            $form->ITextChild($childCol['name'], $childRec['id'], $childCol['options']);
                	    	        break;
                				case "efloat" :
                		            $form->IFloatChild($childCol['name'], $childRec['id'], $childCol['options']);
                	    	        break;
                				case "dna_date" :
                		            $form->IDNADateChild($childCol['name'], $childRec['id'], $childCol['options']);
                	    	        break;
                				default :
                	            	echo "Form Error: IForm incorrect dataType attribute in field defintion in AutoForm for field: " . $field['name'] . ". Must be set to either text, dna40, base64, hex, efloat<br>";
                					break;
                			}
                			break;
                        case "textArea" :
                            $form->ITextAreaChild($childCol['name'], 10, $childRec['id'], $childCol['options']);
                            break;
                        case "checkBox" :
                            $childCol['options']['style'] = (isset($childCol['style']) ? $childCol['style'] : '');
                            $form->ICheckBoxChild($childCol['name'], $childRec['id'], $childCol['options']);
                            break;
                        case "hidden" :
                            $form->IHiddenChild($childCol['name'], $childRec['id'], $childCol['options']);
                            break;
                        case "radio" :
                            $enabled = $childCol['options']['enabled'];

                            // ----- check array size against offset
                            if (count($childCol['radioOptions']) < $childRec[$childCol['name']] || $childRec[$childCol['name']] == 0)
                                $radioOptions = '[' . lang('base.not_set') . ']';
                            else
                                $radioOptions = $childCol['radioOptions'][$childRec[$childCol['name']] - 1];

                            $form->returnValue = '<input type="text" name="' . $childCol['name'] . '" value="' . $radioOptions . '"  class="form-control"' . (!$enabled ? ' disabled="1"' : "") . (isset($childCol['style']) ? $childCol['style'] : '') . '>';
        	    	        break;

                		case "link" :
                            // ----- move optional types, styles and options into linkOptions
                			$childCol['linkOptions']['options'] = $childCol['options'];
                            $childCol['linkOptions']['linkType'] = $childCol['linkType'];

                            $rowHighlight = (isset($childCol['options']['highlight']) ? $childCol['options']['highlight'] : FALSE);

                            // ----- if dna and this is an amend type record system, must highlight for of current table record
                            if (DNABASEENABLE) {
                                if ($rowHighlight && ( ValSet($chainParams['classInfo']['flags'], 'AMD|') || ValSet($chainParams['classInfo']['flags'], 'TRN|') || ValSet($chainParams['classInfo']['flags'], 'COM|') ) && $model->currentChildRecord == $rowKey) { 
                                   $childCol['style'] = 'text-align:center;background:coral;' . (isset($childCol['style']) ? $childCol['style'] : '');
                                }
                            }

                            $childCol['linkOptions']['style'] = (isset($childCol['style']) ? $childCol['style'] : '');

                            // ----- branch according to link type
                            switch ($childCol['linkType']) {

                                case ('copytext') :
                        			// ----- check for dna assset
                        			if ($childCol['displayKey'] == '__DNA_ASSET_ID__') {
                        				$displayValue = height2asset($GLOBALS['ASSETPREFIX'], $model->data[$childCol['name']]);
                        				$displayValue = $displayValue . '@' . $GLOBALS['ESECTOR'] . '.' . $GLOBALS['ECOUNTRY'];
                        				$childCol['linkOptions']['assetID'] = TRUE;
                        			} else {
                        				$displayValue = $form->data[$childCol['name']];
                        			}
                                    $link = $childCol['dataType'];
                                    break;

                                case ('button') :

                                    // ----- check for paramaterized button, or standard
                                    if (isset($childCol['values'])) {

                                        // ----- set
                                        $displayValue = $childCol['displayValue'];
                                        $link = BaseURL();

                                        // ----- set up loop to build 
                                        foreach ($childCol['values'] as $urlSet) {

                                            // ----- check for array (requires special processing)
                                            if (is_array($urlSet)) {

                                                switch ($urlSet[0]) {
                                                    case ('collection_id') :
                                                        $urlSet = '/' . $model->table2;
                                                        break;
                                                    case ('id') :
                                                        $urlSet = '/' . $model->data['id'];
                                                        break;
                                                    case ('rowCount') :
                                                        $urlSet = '/' . $rowKey + 1;
                                                        break;
                                                    default :
                                                        halt('Call to child col button with invalid values parameter in form');
                                                }
                                            }

                                            $link .= $urlSet;

                                        }

                                    } else {
    
                                        // ----- check for amd/com, dna accout within a collection
                                        if (ValSet($chainParams['classInfo']['flags'], 'AMD|') && ValSet($chainParams['classInfo']['flags'], 'COM|'))
        							        $link = BaseURL() . '/ezone' . $GLOBALS['KEYCODE'] . '/' . $chainParams['assetTypeName'] . '/' . $chainParams['assetCategory'] . '/'. $chainParams['ezone'] . '/edit/' . $chainParams['assetParentID'] . '/' . $chainParams['transNumber'] . '/' . $rowKey + 1;
                                        else 
            							    $link = BaseURL() . ($GLOBALS['ENTITYTYPE'] > 3 ? '/root' : '/ezone') . $GLOBALS['KEYCODE'] . '/' . $chainParams['assetTypeName'] . '/' . $chainParams['assetCategory'] . '/'. $chainParams['ezone'] . '/edit/' . $model->data['id'] . '/' . $rowKey + 1;
    
                                        if ($childCol['name'] == 'id') {
                                            if ($form->model->dbType == 'TRN')
                                                    $displayValue = $childRec['id'];
                                                else
                                                    $displayValue = $rowKey + 1;
                                        } else {
                                            $childFieldName =  '_>' . $form->model->table2 . '<_' . $childCol['name'] . '_>>'  . $childRec['id'];
                                            $displayValue = $form->data[$childFieldName];
                                        }

                                    }

                                    break;

                            } // END switch

                            // ----- produce link based on link type and parameters
                			$form->ILink($displayValue, $link, $childCol['linkOptions'], $childCol['linkType'] );
                			break;

                    }

                    echo '<td class="childList">' . $form->returnValue . '</td>';
                }

                echo '</tr>';

                // ----- checkfor system max records
                if ($rowKey >= MAXTRANSACTIONS)
                    break;

                // ----- increment or decrement pointer depending on direction
                if ($reverse) {
                    $rowKey --;
                    if ($rowKey < 0)
                        break;
                } else {
                    $rowKey ++;
                    if ($rowKey > ($model->totalChildren - 1))
                        break;
                }

            } while (TRUE);

            echo '</tbody></table>';
			break;
        default :
            echo "Field name: " . $field['name'] . ".  Form Error: IForm incorrect fieldType definition.  Must be text, textArea, checkBox, hidden, password, select<br>";
            break;
    }

}

if ($model->subDirectory)
    require APPPATH . '/Ignition/Asset/Scripts/make_dropzone.php';

if (InList('edit|delete|new|', $method))
    $form->IFormSubmit(lang($submitText));

// ----- this automatically closes the row and column 
$form->IFormClose();

?>