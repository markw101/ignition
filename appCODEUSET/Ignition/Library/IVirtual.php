<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        IVirtual.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library;

class IVirtual
{

	protected $data;
    protected $data2;
    protected $tableName;

    // ----- constructor for configuration
    public function __construct($data = '', $tableName = '')
    {
		$this->data = $data;
        $this->tableName = $tableName;

	}

    // ----- set data as needed
    public function set($data)
    {
		$this->data = $data;

	}

	// ----- used in autoform to fetch external table columns
	public function process($field, $labelKey = '')
	{

		// ----- setup case to handle virtuals
		switch ($field['virtualType']) {

			case 'time_calc' :

                // ----- check for amendable
				$seconds1 = ($this->data[$field['values']['field1']] === null ? '' : $this->data[$field['values']['field1']]);
				$seconds2 = ($this->data[$field['values']['field2']] === null ? '' : $this->data[$field['values']['field2']]);

				// ----- calculate data value 
				$retVal = (int) $seconds1 - (int) $seconds2;
				return $retVal . "S";

			case 'ecountry' :

				return $GLOBALS['ECOUNTRY'];

			case 'estripe' :

				return $GLOBALS['ESECTOR'];

			case 'language_code' :

                // ----- return language code
				return $this->data['entity_language_code'];

            // ----- this can be used in dna index of records as a quick method to get data in amend pair table
			case 'repeater' :
				return $this->data[substr($labelKey, 0, strlen($labelKey) - 11)];

            // ----- this can be used in the case that a field name has been used elsewhare and, for example, an external ref is required
			case 'set_blank' :

				return '';

            // ----- this can be used in dna index of records as a quick method to get data in amend pair table
			case 'langcode' :

				$langcode = $this->data[substr($labelKey, 0, strlen($labelKey) - 11)];

                if (strlen($langcode) == 5 || strlen($langcode) == 2)
                    $langcode = $langcode;
                else 
                    $langcode = lang('dna.empty');

				return $langcode;

            // ----- return the record height value
			case 'height' :

                return $this->data['id'];

            // ----- set entity prefix in values, return entity number with prefix based on id (height)
			case 'entity' :

                return FillAsset($field['values'] . dec2dna40($this->data['id']));

            // ----- span tag
            case 'span' :

                return '';

            // ----- this can be used in dna index of records as a quick method to get data in amend pair table
            case 'AMD' :
            
                // ----- retrieve fields for amend pair table record
                $fieldName = substr($labelKey, 0, strlen($labelKey) - 11);
                $amendData = CurrentAmend($this->tableName, $this->data['id']);
                
                if (empty($amendData))
                    halt('No matching AMD records were found for master record ID: ' . $this->data['id'] . '.  In the amend style data table paired system at least one amend record must exist for every master record.');
                
                // ----- return data for requested amend field
                return $amendData[$fieldName];

            // ----- for complex database, total transactions
            case 'trans_total_com' :

                // ------ these are required in BaseController for view only calls to chain wallet assets
                /// MAY CREATE A LEAK POTENTIAL FOR TOTAL IN SESSION VARS FOR NON FORM ONLY CALLS.  MAY WISH TO CHECK FORM ONLY
                if (DNASERVERENABLE)
                    $_SESSION['asset_data']['account_total'] = $field['values']; // may require for formonly inquiry

                return $field['values'];

            // ----- for non complex database, total transactions
            case 'trans_total' :

                // ------ extract values
                $os = strpos($field['values'], ':');
                $dataBase2 = substr($field['values'], 0, $os);
                $dataTable2 = substr($field['values'], $os + 1);

                // ----- pull the data total
                $db = db_connect($dataBase2);

                // ----- get all records and then add all value together
                $valueEFloat = new \dna\Library\EFloat('0', $GLOBALS['DECLIMIT']);
                $transRecords = $db->table($dataTable2)->get()->getResultArray();
                foreach ($transRecords as $transRecord) {
                        $valueEFloat->Add(UnbinifyEFloat($transRecord['dna_value']));
                }

                $totTrans = $valueEFloat->displayValue;

                // ------ these are required in BaseController for view only calls to chain wallet assets
                if (DNASERVERENABLE)
                    $_SESSION['asset_data']['account_total'] = $totTrans; // may require for formonly inquiry

                return $totTrans;

            // ----- special display of eCountry + eSector
            case 'ezone' :

                // ------ extract values
                $os = strpos($field['values'], ':');
                if (substr($field['values'], 0, 8) == 'FROMDATA') {
                    $ezone = substr($field['values'], 8, $os - 8);
                    $ezone = bin2dna40($this->data[$ezone]);
                    $ecountry = substr($field['values'], $os + 1);
                    $ecountry = $this->data[$ecountry];
                } else {
                    $ezone = substr($field['values'], 0, $os);
                    $ecountry = substr($field['values'], $os + 1);
                }

                // ----- return the data
                return $ezone . '.' . ucfirst(strtolower($GLOBALS['COUNTRY'][$ecountry]));

            // ----- value type, returns what is contained in value
            case 'value' :

                return $field['values'];

            // ----- erobot script execution
            case 'script' :

                $eRobotScript = bar2array($field['values']);

//              $eRobotAgent = new /dna/Erobot/eRobotAgent;
//              $Robotica = new /dna/Library/Robotica;
/* Consider create a Robotica directory under /app/dna to contain the following classes:
    Robotica.php
    eRobotAgent.php
    ?? some other file that generically takes a dna file, runs it and returns output
 
*/
//                return "Contacting eRobot: xxxx, Authenticating..., Retrieving Information..., Captive Asset Balance, ERobot name: " . $eRobotScript[0] . '. Script name: ' . $eRobotScript[1];
return '';
			default :

				if (getenv('CI_ENVIRONMENT') != 'producton') {
					br(2);
					echo _br() . print_r($field);
					br();
				}

				halt("Invalid virtual field type in call to eVirtual from a DNA form, field: " . $labelKey);

			}

	}


}


?>