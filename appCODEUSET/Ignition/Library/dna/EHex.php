<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        EHex.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class EHex extends EBaseNum
{
    // ----- constructor for configuration, must be even number columns, default 00
    public function __construct($setVal = FALSE)
    {
		// ----- init
		$this->sybolSet = "HEX";
		$this->caseSensative = FALSE;
		$this->wordSize = 4;
		$this->base = 16;

		// ----- construct without initialization
		$this->value = strtoupper($setVal);

	}

	public function set($setVal)
	{
		// ----- init 
		$success = TRUE;
		$setVal = strtoupper($setVal);

		// ----- check for proper col count 
		if (strlen($setVal) % 2 > 0) {
			$this->message = "Invalid hexidecimal number.  String length of " . strlen($setVal) . " is invalid.  Field entry must consist of length divisible by two, an even number.  Invalid HEX store value: " . $setVal;
			return FALSE;
		}

		$retVal = $this->validateVal($setVal, "HEX");

		// ----- check for fail test 
		if ($retVal > 0) {
			$this->message = "Invalid Hexadecimal number, at column: " . $retVal . ".  Check Hexidecimal numeric specification.  Invalid HEX store value: " . $setVal;
			$success = FALSE;
		} else 
			$this->value = $setVal;

		return $success;

	}

}


?>