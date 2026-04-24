<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        EFan.php
    VERSION:     See BaseController.php
    DESCRIPTION: EFan: e Full asset number, asset type class
    COPYRIGHT:   2024
    FIRST REV:   27 Oct 2024
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class EFan extends EBaseNum
{
    // ----- constructor for configuration, must be proper fan: full asset number
    public function __construct($setVal = FALSE)
    {
		// ----- init
		$this->sybolSet = "FAN";
		$this->caseSensative = FALSE;
		$this->wordSize = 9;
		$this->base = 10;

		// ----- construct without initialization
		$this->value = strtoupper($setVal);

	}

	public function set($setVal)
	{
		// ----- init 
		$success = TRUE;
		$setVal = strtoupper($setVal);

        // ----- check to see if number in size range
        if (strlen($setVal) > 21 || strlen($setVal) < 18) {
			$this->message = "Invalid full asset number.  String length of " . strlen($setVal) . " is invalid.  Field entry must consist of length 18 - 21 characters.  Invalid FAN store value: " . $setVal;
			return FALSE;
		}

		$retVal = $this->validateVal($setVal, "FAN");

		// ----- check for fail test 
		if ($retVal > 0) {
			$this->message = "Invalid Full Asset Number (FAN), at column: " . $retVal . ".  Check FAN numeric specification.  Invalid FAN store value: " . $setVal;
			$success = FALSE;
		} else {
			$this->value = BinifyAsset($setVal);
            if (!$this->value) {
                $this->message = 'Invalid FAN value';
                SetFlash('Invalid FAN value in form. ' . GetFlash()); 
                return FALSE;
            }
            $this->displayValue = $setVal;
        }

		return $success;

	}

}


?>