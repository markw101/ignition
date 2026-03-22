<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        IForm.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- Used to build input form in Ignition Index/Form paradigm.  See 
//       examples under /app/Site/Channels for the application of CI 
//       MVC and its implementation within Ignition
class IForm
{
    protected $colOpen = false;
    protected $rowOpen = false;
    protected $fieldList;
    public $model;
    public $data;
    public $classOverride = FALSE;
    public $classOverrideText = '';
    public $returnValue;

    // ----- constructor
    public function __construct($model = '', $data = [])
    {
		/// this is provisional until elim data (already stored in model)
		if ($data == [] && $model != '')
			$this->data = $model->data;
		else
			$this->data = $data;

      $this->model = $model;

      // ----- load ci form helper
      helper('form');

    }

    // ----- create open tag
    public function IFormOpen($action = '')
    {

        if ($action === '')
            $action = fixed_url();

      // ex: <form action="http://en.ecoin.news/access" method="post" accept-charset="utf-8">
      echo form_open($action);

    }

    // ----- create open tag
    public function IFormClose()
    {
        echo '</form>';

        // ----- check for open state of col
        if ($this->colOpen)
            $this->IColumnClose();

        // ----- check for open state of row 
        if ($this->rowOpen)
            echo '</div>';
    }

    // ----- manually close a column (normally is done automatically in form close)
    public function IColumnClose()
    {
        if ($this->colOpen) {
            echo '</div></div></div>';
            $this->colOpen = false;
        }
    }

    // ----- manually close a row (normally is done automatically in form close)
    public function IRowClose()
    {
        if ($this->colOpen) {
            echo '</div></div></div>';
            $this->colOpen = false;
        }

        if ($this->rowOpen) {
            echo '</div>';
            $this->rowOpen = false;
        }
    }

    // ----- create new IForm column
    public function IColumn($title = "", $wide = false, $attributes = FALSE)
    {

        // ----- if already col open, then close it 
        if ($this->colOpen)
            echo '</div></div></div>';

        // ----- check for row open status
        if (!$this->rowOpen) {
            echo '<div class="row">';
            $this->rowOpen = true;
        }

        // ----- create column
        if ($title != "") {
            // ----- outer div container wide, or narrow
            if ($wide)
                echo '<div class="table-responsive" style="padding-top: 0px; padding-left: 15px; padding-right: 15px; padding-bottom: 0px">';
            else
                echo '<div class="col-xs-12 col-sm-6">';

            echo '<div class="panel panel-default" style="width: 100%"><div class="panel-heading form-inline clearfix" style="width: 100%">';
            echo $title . '</div><div class="panel-body">';
        } else {
            // ----- create basic column

            // ----- outer div container wide, or narrow
            if ($wide)
                echo '<div class="table-responsive" style="padding-top: 0px; padding-left: 15px; padding-right: 15px; padding-bottom: 0px">';
            else
                echo '<div class="col-xs-12 col-sm-6">';

            echo '<div class="panel panel-default"><div class="panel-body">';
        }

        $this->colOpen = true;
    }

    // ----- create submit button 
    public function IFormSubmit($name = '', $class = 'class="au-btn&#x20;au-btn--blue"')
    {
        if ($name === '')
            $name = lang('base.submit');

       // ex: '<input type="submit" value="' . $name . '" class=' . $class . '>';
       echo form_submit('submit', $name, $class);
    }

    // ----- checkbox wrapper
    public function ICheckBox($fieldName, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, 0, "checkbox");
    }

    // ----- checkbox wrapper
    public function ICheckBoxChild($fieldName, $childID, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, 0, "checkbox", $childID);
    }

    // ----- single line text input wrapper
    public function IFan($fieldName, $attributes = [], $autoConvert = FALSE)
    {

		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = UnbinifyAsset($this->data[$fieldName]);

		// ----- convert binary field data into dna40 for user input 
        $this->InputCombined($fieldName, $attributes, 0, "binified");
    }

    // ----- single line text input wrapper
    public function IFanChild($fieldName, $childID, $attributes = [])
    {

		// ----- convert binary field data into dna40 for user input 
        $attributes = array_merge($attributes, ['autoConvert' => 'binified']);
        $this->InputCombined($fieldName, $attributes, 0, "binified", $childID);

    }

    // ----- single line text input wrapper
    public function IBase10($fieldName, $attributes = [], $autoConvert = FALSE)
    {

		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = bin2base10($this->data[$fieldName]);

		// ----- convert binary field data into dna40 for user input 
        $this->InputCombined($fieldName, $attributes, 0, "base10");
    }

    // ----- single line text input wrapper
    public function IDNA40($fieldName, $attributes = [], $autoConvert = FALSE)
    {
		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = bin2dna40($this->data[$fieldName]);

        $this->InputCombined($fieldName, $attributes, 0, "dna40");
    }

    // ----- single line text input wrapper
    public function IFloat($fieldName, $attributes = [], $autoConvert = FALSE)
    {
		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = UnbinifyEFloat($this->data[$fieldName]);

        $this->InputCombined($fieldName, $attributes, 0, "efloat");
    }

    // ----- single line text input wrapper
    public function IDNADate($fieldName, $attributes = [], $autoConvert = FALSE)
    {
		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = \dna\Library\CryptoUtil::dnadate2rfc($this->data[$fieldName]);

        $this->InputCombined($fieldName, $attributes, 0, "dna_date");
    }

    // ----- single line text input wrapper
    public function IDNADateChild($fieldName, $childID, $attributes = [])
    {
		// ----- set data conversion flag 
        $attributes = array_merge($attributes, ['autoConvert' => 'dna_date']);

        $this->InputCombined($fieldName, $attributes, 0, "dna_date", $childID);
    }

    // ----- single line text input wrapper
    public function IDNA40Child($fieldName, $childID, $attributes = [])
    {
		// ----- set data conversion flag 
        $attributes = array_merge($attributes, ['autoConvert' => 'dna40']);

        $this->InputCombined($fieldName, $attributes, 0, "dna40", $childID);
    }

    // ----- single line text input wrapper
    public function IFloatChild($fieldName, $childID, $attributes = [])
    {
		// ----- set data conversion flag 
        $attributes = array_merge($attributes, ['autoConvert' => 'efloat']);

        $this->InputCombined($fieldName, $attributes, 0, "efloat", $childID);
    }

    // ----- single line text input wrapper
    public function IBase64($fieldName, $attributes = [], $autoConvert = FALSE)
    {
		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = sodium_bin2base64($this->data[$fieldName], SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);

        // ----- convert binary field data into dna40 for user input 
        $this->InputCombined($fieldName, $attributes, 0, "base64");
    }

    // ----- single line text input wrapper
    public function IHex($fieldName, $attributes = [], $autoConvert = FALSE)
    {
		// ----- if autoconvert, then convert data from binary 
		if ($autoConvert || isset($attributes['autoConvert']))
			$this->data[$fieldName] = strtoupper(bin2hex($this->data[$fieldName]));

		// ----- convert binary field data into dna40 for user input 
        $this->InputCombined($fieldName, $attributes, 0, "hex");
    }

    // ----- single line text input wrapper
    public function IText($fieldName, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, 0, "input");
    }

    // ----- single line text input wrapper
    public function ITextChild($fieldName, $childID, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, 0, "input", $childID);
    }

    // ----- input text area (multi line) wrapper
    public function ITextArea($fieldName, $rows = 0, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, ($rows == 0 ? (isset($attributes['areaRows']) ? $attributes['areaRows'] : 10) : $rows), "textarea");

    }

    // ----- input text area (multi line) wrapper
    public function ITextAreaChild($fieldName, $rows = 0, $childID = FALSE, $attributes = [])
    {

        $this->InputCombined($fieldName, $attributes, ($rows == 0 ? (isset($attributes['areaRows']) ? $attributes['areaRows'] : 10) : $rows), "textarea", $childID);
    }

    // ----- input password wrapper
    public function IPassword($fieldName, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, 0, "password");
    }

    public function iHidden($fieldName, $attributes = [], $autoConvert = FALSE)
    {

        switch($autoConvert || isset($attributes['autoConvert'])) {
            case 'dna40' :  
                $this->data[$fieldName] = bin2dna40($this->data[$fieldName]);
                break;
            case 'base64' :
                $this->data[$fieldName] = sodium_bin2base64($this->data[$fieldName], SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
                break;
            case 'hex' :
                $this->data[$fieldName] = strtoupper(bin2hex($this->data[$fieldName]));
                break;
            case 'binified' :
                $this->data[$fieldName] = strtoupper(UnbinifyAsset($this->data[$fieldName]));
                break;
            }

        $this->InputCombined($fieldName, $attributes, 0, "hidden");
    }

    public function IHiddenChild($fieldName, $childID, $attributes = [])
    {
        $this->InputCombined($fieldName, $attributes, 0, "hidden", $childID);
    }

    public function ISelectChild($fieldName, $options = [], $label = '', $attributes = [])
    {
		halt("I am ISelectChild.  Write me!");

	}

    public function ISelect($fieldName, $options = [], $label = '', $attributes = [], $idName = '', $autoConvert = FALSE)
    {

        // ----- init
        $noEcho = ValSetArray($attributes, 'noEcho');
        $returnVal = '';

		// ----- check for attributes of dataType set 
		if (isset($attributes['dataType']) && $autoConvert) {

			switch ($attributes['dataType']) {
			    case "dna40" :
			        $this->data[$fieldName] = strtoupper(bin2dna40($this->data[$fieldName]));
					$returnVal .= '<input type="hidden" name="__DNA40__' . $fieldName . '">';
			        break;
			    case "base64" :
			        $this->data[$fieldName] = sodium_bin2base64($this->data[$fieldName], SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
					$returnVal .= '<input type="hidden" name="__BASE64__' . $fieldName . '">';
 			        break;
			    case "hex" :
			        $this->data[$fieldName] = strtoupper(bin2hex($this->data[$fieldName]));
 					$returnVal .= '<input type="hidden" name="__HEX__' . $fieldName . '">';
			        break;
			    case "efloat" :
			        $this->data[$fieldName] = strtoupper(UnbinifyEFloat($this->data[$fieldName]));
 					$returnVal .= '<input type="hidden" name="__EFLOAT__' . $fieldName . '">';
			        break;
				case "text" :
				    break;
				default :
			    	$returnVal .= "Form Error: IForm incorrect dataType attribute in type select field defintion in IForm for field: " . $fieldName . ".  Must be set to either text, dna40, base64, hex, efloat or left unset<br>";
					break;
			}
		}

        // ----- init
        $disabled = isset($attributes['enabled']) ? !$attributes['enabled'] : false;
	    $style = isset($attributes['style']) ? $attributes['style'] : false;

        // ----- locate the value, if not found, echo not found
        if (!isset($this->data[$fieldName])) {
            $returnVal .= "<em>Field name: " . $fieldName . ", not found in form field post data, usually the model->data array.  Also, must set in allowedFields.  Form error.</em>";
            echo $returnVal;
            return;
        }

        // ----- check for the requisite allowed fields
        if (!isset($this->model->allowedFields)) {
            $returnVal .= "<em>allowedFields not set in model.  Required.</em>";
            echo $returnVal;
            return;
        }

        // ----- must create a random name for the label to link it to the select control
		$idName = ($idName != '' ? $idName : RandomName(4));

		// ----- options and label may be passed using iselect tag, 
        //       or pre set in the model (utilized in autoform/iform file)
		if ($options != [])
		{

			// ----- check for label 
			if ($label != "")
		        $returnVal .= '<label for="' . $idName . '">' . $label . '</label>&nbsp;';

   	    	$returnVal .= '<select name="' . $fieldName . '" id="'.  $idName . '"' . ($disabled ? 'disabled="disabled"' : '') . ($style ? ' style=' . $style : '') . '>';

	        // ----- set up loop to output options 
	        foreach ($options as $FieldKey => $FieldValue) {
	            $returnVal .= '<font color=red><option value="' . $FieldKey . '"' . ($this->data[$fieldName] == $FieldKey ? ' selected="selected">' : '>') . $FieldValue . '</option></font>';
	        }

		} else {
	        $returnVal .= '<label for="' . $idName . '">' . $this->model->iForm[$fieldName]['label'] . '</label>&nbsp;';
	        $returnVal .= '<select name="' . $fieldName . '" id="'.  $idName . '"' . ($disabled ? 'disabled="disabled"' : '') . ($style ? ' style=' . $style : '') . '>';


	        // ----- set up loop to print the options 
	        foreach ($this->model->iForm[$fieldName]['fieldValues'] as $value) {
	            $returnVal .= '<option value="' . $value . '"' . ($this->data[$fieldName] == $value ? ' selected="selected">' : '>') . $value . '</option>';
	        }
		}

        $returnVal .= '</select><br>';

        // ----- render/return control
        if ($noEcho) {
            $this->returnValue = $returnVal;
            return;
        } else
            echo $returnVal;

    }

    public function IRadio($fieldName, $radioOptions = [], $fieldLabel = '', $attributes = '')
    {

        // ----- init
        $disabled = isset($attributes['enabled']) ? !$attributes['enabled'] : false;
		$style = isset($attributes['style']) ? $attributes['style'] : false;
        $decoration = isset($attributes['decoration']['position']) ?? FALSE;
        $noEcho = ValSetArray($attributes, 'noEcho');
        $returnVal = '';

        // ----- locate the value, if not found, echo not found
        if (!isset($this->data[$fieldName])) {
            echo "<em>Field name: " . $fieldName . ", not found in form field post data, usually the model->data array.  Also, must set in allowedFields.  Form error.</em>";
            echo $returnVal;
            return;
        }

        // ----- check for the requisite allowed fields
        if (!isset($this->model->allowedFields)) {
            echo "<em>allowedFields not set in model.  Required.</em>";
            echo $returnVal;
            return;
        }

        // ----- if a BEFORE decoration, output now
        if ($decoration && $attributes['decoration']['position'] == 'before')
            $returnVal .= $attributes['decoration']['html'];

		// ----- output the label 
		$returnVal .= '<label class="form-control-label" for="' . $fieldName . '">' . $fieldLabel . '</label>';

        // ----- if a POSTLABEL decoration, set now
        if ($decoration && $attributes['decoration']['position'] == 'postlabel')
            $returnVal .= $attributes['decoration']['html'];

        $returnVal .= '<br>';

		// ----- setup loop to print every option
		for ($count = 0; $count < count($radioOptions); $count ++) {
			$returnVal .= '<input type="radio" name="' . $fieldName . '"' . 'value="' . $count + 1 . '"' . ($this->data[$fieldName] == ($count + 1) ? ' checked="checked">' : '>') . _sp() . $radioOptions[$count] . (isset($options['horizontal']) ? " " : '<br>');
		}

        // ----- if an after decoration, output now
        if ($decoration && $attributes['decoration']['position'] == 'after')
            $returnVal .= $attributes['decoration']['html'];

		$returnVal .= _br();

        // ----- render/return control
        if ($noEcho) {
            $this->returnValue = $returnVal;
            return;
        } else
            echo $returnVal;
    }

    // ----- display an image
    public function IImage($fieldName, $attributes = [])
    {
        // ----- check for auto set proto
        if (substr($this->data[$fieldName], 0, 4) != 'http')
            $this->data[$fieldName] = HTTPPROT . "://" . $this->data[$fieldName];

		// ----- build image tag
        if (isset($attributes['style']))
    		$returnVal = '<center><img src="' . $this->data[$fieldName] . '" style="' . $attributes['style'] . '"></center>';
        else
    		$returnVal = '<center><img src="' . $this->data[$fieldName] . '"></center>';

        if (ValSetArray($attributes, 'noEcho')) {
            $this->returnValue = $returnVal;
            return;
        } else
            echo $returnVal;

    }

    // ----- single line display label wrapper
    public function ILabel($fieldLabel, $attributes)
    {

        $this->InputCombined($fieldLabel, array_merge($attributes, ['labelOnly']), 0, 0);
    }

    // ----- create input differing groups with text labels and input fields
    public function InputCombined($fieldName, $attributes, $rows, $type, $childID = FALSE)
    {

        // ----- init
        $disabled = isset($attributes['enabled']) ? !$attributes['enabled'] : false;
		$returnVal = '';
        $displayLabel = !ValSetArray($attributes, 'noLabel');
        $noEcho = ValSetArray($attributes, 'noEcho');
        $labelOnly = ValSetArray($attributes, 'labelOnly');
        $styleCSS = (isset($attributes['style']) ? $attributes['style'] : FALSE);
        $decoration = isset($attributes['decoration']['position']) ?? FALSE;
		$autoFocus = $attributes['autofocus'] ?? FALSE;
		$autoFocus = ($autoFocus ? 'autofocus' : '');
        $dnaViewOnly = $attributes['dnaViewOnly'] ?? FALSE;  // these forms never return a value

        // ----- if a before decoration, output now
        if ($decoration && $attributes['decoration']['position'] == 'before')
            $returnVal .= $attributes['decoration']['html'];

        // ----- check for requesting only the field label
        if ($labelOnly)  {
            $returnVal .= '<label class="' . ($this->classOverride ? $this->classOverrideText : 'form-control-label') . '">' . $fieldName . '</label>';
            echo $returnVal;
            return;
        }

        // ----- if this is a child record field
		if ($childID) {
			$childFieldName =  '_>' . $this->model->table2 . '<_' . $fieldName . '_>>'  . $childID;

			// ----- fix null set 
			if (!isset($this->data[$childFieldName]) || $this->data[$childFieldName] === null) {
				$this->data[$childFieldName] = '';
            }

	        if (!isset($this->data[$childFieldName])) {
	            $returnVal .= "<em>Field name in child table: " . $fieldName . ", not found in form field post data, usually the model->data array.  Also, must set in allowedFields2.  Data may also be NULL value.  Form error.</em>";
                echo $returnVal;
	            return;
	        }
			$value = $this->data[$childFieldName];

    		// ----- if autoconvert, then convert data from binary forms
    		if (isset($attributes['autoConvert'])) {
    
                switch($attributes['autoConvert']) {
                    case 'dna40' : 
            			$value = bin2dna40($value);
                        break;
                    case 'binified' :
                        $value = UnbinifyAsset($value);
                        break;
                    case 'efloat' :
                        $value = UnbinifyEFloat($value);
                        break;
                    case 'dna_date' :
                        $value = \dna\Library\CryptoUtil::dnadate2rfc($value);
                        break;
                    default: 
                        halt("Autoconvert type not established in IForm call for child record");
    
                }

            }

		} else {

			// ----- fix null set 
			if ($this->data[$fieldName] === null)
				$this->data[$fieldName] = '';

	        // ----- locate the value, if not found, echo not found
	        if (!isset($this->data[$fieldName])) {
	            $returnVal .= "<em>Field name: " . $fieldName . ", not found in form field post data, usually the data array.  Data may also be NULL value.  Form error11.</em>";
                echo $returnVal;
	            return;
	        }

	        $value = $this->data[$fieldName];
		}

        // ----- check for the requisite allowed fields
        if (!isset($this->model->allowedFields)) {
            $returnVal .= "<em>allowedFields not set in model.  Required.</em>";
            echo $returnVal;
            return;
        }

        // ----- set allowed fields
		if ($childID && isset($this->model->allowedFields2)) {
			$allowedFields = $this->model->allowedFields2;
		} elseif (!$childID && isset($this->model->allowedFields)) {
			$allowedFields = $this->model->allowedFields;
		} else
			$allowedFields = [];

        // ----- check for not hidden or disabled, must confirm included in $allowedFields/showOnly list
        if ( !($type == "hidden" || $disabled) )
        {
            // ----- setup loop to locate field in allowed
            if ($childID)
                $formFields = array_merge($allowedFields, $this->model->showOnly2);
            else
                $formFields = array_merge($allowedFields, $this->model->showOnly);

			$fieldCount = count($formFields);
            for ($count = 0; $count < $fieldCount; $count ++) {

                if ($formFields[$count] == $fieldName)
                    break;
            }

            // ----- check to see if field found, if not return not allowed
            if ($count >= $fieldCount) {
                $returnVal .= "<em>Field name: " . $fieldName . ", not found in " . ($childID ? 'allowedFields2/showOnly2 (using relational child table)' : 'allowedFields/showOnly') . ".  Data may also be NULL value.  Form error.</em>";
                echo $returnVal;
                return;
            }

        }

        // ----- set field labels
		if ($childID && isset($this->model->fieldLabels2)) {
			$fieldLabels = $this->model->fieldLabels2;
		} elseif (!$childID && isset($this->model->fieldLabels)) {
			$fieldLabels = $this->model->fieldLabels;
		} else
			$fieldLabels = [];

        if (isset($fieldLabels[$fieldName]))
            $fieldLabel = $fieldLabels[$fieldName];
        else
            $fieldLabel = $fieldName;

        // ----- check for required
        if (isset($this->model->validationRules[$fieldName])) {
            // ----- check for array
            if (is_array($this->model->validationRules[$fieldName])) {
                if (ValSet($this->model->validationRules[$fieldName]['rules'], "required"))
                    $fieldLabel = '<font color="red">*&nbsp;</font>' . $fieldLabel;
            } else {
            if ((ValSet($this->model->validationRules[$fieldName], "required")))
                $fieldLabel = '<font color="red">*&nbsp;</font>' . $fieldLabel;
            }
        }

        // ----- IF CHILD check for required
        if ($childID && isset($this->model->validationRules2[$fieldName])) {
            // ----- check for array
            if (is_array($this->model->validationRules2[$fieldName])) {
                if (ValSet($this->model->validationRules2[$fieldName]['rules'], "required"))
                    $fieldLabel = '<font color="red">*&nbsp;</font>' . $fieldLabel;
            } else {
            	if ((ValSet($this->model->validationRules2[$fieldName], "required")))
                	$fieldLabel = '<font color="red">*&nbsp;</font>' . $fieldLabel;
            }
        }

        // ----- if a postlable decoration, set now
        if ($decoration && $attributes['decoration']['position'] == 'postlabel')
            $decoreHTML = $attributes['decoration']['html'];
        else 
            $decoreHTML = '';

        // ----- output the label, br
        if ($type != "hidden" && $type != "checkbox" && $displayLabel) {
            $returnVal = $returnVal . '<div class="form-group">';
            $returnVal = $returnVal . '<label class="' . ($this->classOverride ? $this->classOverrideText : 'form-control-label') . '">' . $fieldLabel . '</label>' . $decoreHTML;
        }

        // ----- check for number format, base10 only
        if (isset($attributes['numberFormat']))
            $value = NumberFormat(($value == '' ? 0 : $value), $attributes['numberFormat']);

        // ----- if disabled, make a hidden field of the same name
        //       this is necessary because otherwise this field will disappear from form data
        if ($disabled && !$dnaViewOnly)
            $returnVal .= '<input type="hidden" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '">';

        // ----- if a indiv decoration, set now
        if ($decoration && $attributes['decoration']['position'] == 'indiv')
            $decoreHTML = $attributes['decoration']['html'];
        else 
            $decoreHTML = '';

        // ----- set switch to output different types
        switch ($type) {
            case "input" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "textarea" :
                $returnVal .= '<textarea name="' . ($childID ? $childFieldName : $fieldName) . '" rows="' . $rows . '" class="' . ($this->classOverride ? $this->classOverride : 'form-control&#x20;editor') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>' . $value . "</textarea>";
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "checkbox" :
				$returnVal .= ($displayLabel ? '<div class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '">' : '');
				$returnVal .= '<input type="checkbox" id="' . ($childID ? $childFieldName : ($childID ? $childFieldName : $fieldName)) . '" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . ($value == 1 ? '1' : '0') . '"' . ($value == 1 ? ' checked="checked"' : "") . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>&nbsp;';
				$returnVal .= ($displayLabel ? '<label class="form-control-label" for="' . ($childID ? $childFieldName : $fieldName) . '">' . $fieldLabel . '</label>' . $decoreHTML . '<br>' : '');
				$returnVal .= '<input type="hidden" name="__CHECKBOX__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "password" :
                $returnVal .= '<input type="password" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "hidden" :
                $returnVal .= '<input type="hidden" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '">';
                break;
            case "binified" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__FAN__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "dna40" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__DNA40__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "base10" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__BASE10__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "efloat" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__EFLOAT__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "dna_date" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__DNADATE__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "base64" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__BASE64__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
            case "hex" :
                $returnVal .= '<input type="text" name="' . ($childID ? $childFieldName : $fieldName) . '" value="' . $value . '"  class="' . ($this->classOverride ? $this->classOverride : 'form-control') . '"' . ($disabled ? ' disabled="1"' : "") . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . $autoFocus . '>';
				$returnVal .= '<input type="hidden" name="__HEX__' . ($childID ? $childFieldName : $fieldName) . '">';
                $returnVal .= $decoreHTML;
                $returnVal .= ($displayLabel ? '</div>' : '');
                break;
        }

        // ----- if an after decoration, output now
        if ($decoration && $attributes['decoration']['position'] == 'after')
            $returnVal .= $attributes['decoration']['html'];

        // ----- render/return control
        if ($noEcho) {
            $this->returnValue = $returnVal;
            return;
        } else
            echo $returnVal;
    }

    // ----- output error messages
    public function IFormErrors($errors = [])
    {
        $session = service('session');

        // ----- check for form errors
        if (!empty($errors)) {
            echo '<div class="alert alert-danger">';

            if (!is_array($errors))
                $errors = [0 => $errors];

            // ----- setup loop to display all errors
            foreach ($errors as $field => $error)
                echo $error;

            echo '</div>';
        } elseif (!empty($this->model->validationErrors)) {
            echo '<div class="alert alert-danger">';

            // ----- setup loop to display all errors
            foreach ($this->model->validationErrors as $field => $error)
                echo $error;

            echo '</div>';

		} elseif ($session->has('success')) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

    }

    // ----- output error messages
    public function IFormMessage($message = '')
    {
		if ($message != '')
			echo '<div class="alert alert-success">' . $message . '</div>';

		$this->IFormSuccess();

    }

    // ----- output messages
    public function IFormSuccess()
    {

        $session = service('session');
        if ($session->has('success'))
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';

    }

    public function IFill($total = 1)
    {
        for ($count = 1; $count <= $total; $count ++) {
            echo "<br>";
        }
    }

    public function IButton($label, $link, $attributes = [], $noEcho = FALSE)
    {

        // ----- check for attributes style
        $styleCSS = (isset($attributes['style']) ? $attributes['style'] : FALSE);
        $newTab = (isset($attributes['options']['target']) && $attributes['options']['target'] == 'blank');

        // ----- check for disabled, gray font and blank the url
        if (isset($attributes['enabled']) && !$attributes['enabled'])
            $retVal = '<a href="' . $link . '"' . ' class="btn&#x20;btn-primary"' . (isset($attributes['target']) ? ' target="' . $attributes['target'] . '"' : '') . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . '><font color=#484848>' . $label . '</font></a>';
        else
            $retVal = '<a href="' . $link . '"' . ' class="btn&#x20;btn-primary"' . (isset($attributes['target']) ? ' target="' . $attributes['target'] . '"' : '') . ($styleCSS ? ' style="' . $styleCSS . '"' : '') . ($newTab ? ' target="_blank" >' : '>') . $label . '</a>';

        if ($noEcho)
            return $retVal;
        else 
            echo $retVal;
    }

    public function ILink($linkText, $link, $options = [], $linkType = 'button', $label = FALSE)
    {

        $noBreak = isset($options['noBreak']) || isset($options['nobreak']);

        // ----- check for clipboard copy, create script
        $clipBoard = isset($options['copyClip']) && $options['copyClip'];
        if ($clipBoard) {
            echo "<script>const copyToClipboard = str => {const el = document.createElement('textarea');el.value = str;document.body.appendChild(el);el.select();document.execCommand('copy');document.body.removeChild(el);};function copyClip(textToCopy) {copyToClipboard( textToCopy );}</script>";

        }

        // ----- branch according to link type
        switch ($linkType) {

            case "button" :
                $this->returnValue = $this->IButton($linkText, $link, $options, TRUE);
                return;

            case "assetID" :
        
                echo '<table><tr><td>' . $label;

                if (isset($options['iconText'])) {
                    echo '<a href="' . $options['iconLink'] . '"' . (isset($options['target']) ? ' target=' . $options['target'] : '') . '><span title="' . $options['iconText'] . '" style="margin-left:0;cursor:pointer;margin-top:20">';
                    echo '<img src="/dnapublic/images/Information_icon.svg.png" height="25" width="27"></span></a>&nbsp;';
                }

                if (!$noBreak)
                    echo '<br>';

        		echo '<b>' . $linkText . '</b></td>';
        
        		if ($clipBoard) {
                    echo '<td>&nbsp;';
                    echo '<button onclick=\'copyClip("' . $link . '")\' style="border: 0px"><img src=\'/dnapublic/images/mini-copy-mon.jpg\'></button>';
                    echo '</td>';
        		}

		        echo '</tr></table>';
                break;

        }   // END switch

    }

    public function ITypeAheadInit($fieldList = [], $model = FALSE, $scriptDir = FALSE, $template = FALSE)
    {
		$this->fieldList = $fieldList;

		if (!$model)
			$model = $this->model;

		if (!$scriptDir)
			$scriptDir = 'themes';

		// ----- check to see if need to freshen json querry for typeahead field
		$typeAheadFile = date("Y.m.d.H") . ".json";
		if (!file_exists(FCPATH . "tmp/" . $typeAheadFile) || date("H", filectime(FCPATH . "tmp/" . $typeAheadFile)) != date("H"))
		{

	        // ----- querry 
	        $visitors = $model->findAll();
			$visitorList = '[';
			$isFirst = TRUE;

			// ----- set up loop to make the output file 
			foreach ($visitors as $visitor) {

				if (!$isFirst)
					$visitorList .= ',';

				// ----- get next visitor name
				$visitorList .= '{"value": "' . $visitor['visitante_name'] . '",';
				$visitorList .= '"telephone": "' . $visitor['visitante_telephone'] . '",';
				$visitorList .= '"resident": "' . $visitor['visitante_name_resident'] . '",';
				$visitorList .= '"license": "' . $visitor['visitante_vehicle_license'] . '",';
				$visitorList .= '"cartype": "' . $visitor['visitante_vehicle_type'] . '",';
				$visitorList .= '"carstate": "' . $visitor['visitante_vehicle_state'] . '"}';

				$isFirst = FALSE;
			}

			// ----- write file to disk
			$typeAheadFile = date("Y.m.d.H") . ".json";
			file_put_contents(FCPATH . "tmp/" . $typeAheadFile, $visitorList . ']');
		}

?>
<script src="/themes/jquery.typeahead.min.css"></script>
<script  src="/themes/typeahead.bundle.js"></script>
<script  src="/themes/handlebars.js"></script>
<script>
$(document).ready(function(){
    // make twitter typeahead
    var visitors = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: <?= "'/tmp/" . $typeAheadFile . "'" ?>
    });

	// ----- init buf
    $('#scrollable-dropdown-menu .typeahead').typeahead(null, {
        name: 'visitors',
		display: 'value',
        source: visitors,
        limit: 20, /* Specify maximum number of suggestions to be displayed */
		templates: {
		    pending: '<div>Loading...</div>',
			empty: [
			  '<div class="empty-message">',
			    'No se ha encontrado ningún visitante',
			  '</div>'
			].join('\n'),
			suggestion: Handlebars.compile('<div><strong>{{value}}</strong> - Mat:{{license}} Carro:{{cartype}} Res:{{resident}}</div>')
		}
    }).on('typeahead:selected', onSelected);

	// update fields
	function onSelected($e, datum) {
		$('#visitante_name').val(datum['value']);
		$('#visitante_telephone').val(datum['telephone']);
		$('#visitante_name_resident').val(datum['resident']);
		$('#visitante_vehicle_license').val(datum['license']);
		$('#visitante_vehicle_type').val(datum['cartype']);
		$('#visitante_vehicle_state').val(datum['carstate']);
	}
}); 

</script>

<style>
#scrollable-dropdown-menu .tt-menu {
	padding: 5px 10px;
	text-align: left;
	max-height: 250px;
	overflow-y: auto;
}
.bs-example {
	font-family: sans-serif;
	position: relative;
	margin: 100px;
}
.typeahead, .tt-query, .tt-hint {
	border: 2px solid #CCCCCC;
	border-radius: 2px;
	font-size: 18px; /* Set input font size */
	height: 35px;
	line-height: 35px;
	outline: medium none;
	padding: 8px 12px;
	width: 480px;
}
.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 2px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 8px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	margin-top: 12px;
	padding: 8px 0;
	width: 422px;
}
.tt-suggestion {
	font-size: 18px;  /* Set suggestion dropdown font size */
	padding: 3px 20px;
}
.tt-suggestion:hover {
	cursor: pointer;
	background-color: #0097CF;
	color: #FFFFFF;
}
.tt-suggestion p {
	margin: 0;
}
</style>

<?php

	}

	// ----- call autofill
	public function iTypeAhead($fillText = "Autofill") {

		echo ' autocomplete="off" spellcheck="false" placeholder="' . $fillText . '" class="typeahead tt-query form-control"';

	}
}


?>
