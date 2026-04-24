<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Edna40.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class Edna40 extends EBaseNum
{
    // ----- constructor for configuration, must be even number columns, default 00
    public function __construct($setVal = FALSE)
    {
		// ----- init
		$this->sybolSet = "DNA40";
		$this->caseSensative = FALSE;
		$this->wordSize = 8;
		$this->base = 256;

		// ----- construct without initialization
		$this->value = strtoupper($setVal);

	}

    // ----- set number as one or more Rextet pairs (similar to hex)
	public function set($setVal)
	{
		// ----- init 
		$success = TRUE;
		$setVal = strtoupper($setVal);
		$rex8Symbols = $GLOBALS['SYMBOL_SETS']['REX8'];
		$rex32Symbols = $GLOBALS['SYMBOL_SETS']['REX32'];

		// ----- exception to set len error of 1 column
		if (strlen($setVal) == 1)
			$this->value += '0';

		// ----- check for valid len, must be col pairs
		if ((strlen($setVal) % 2) != 0) {
			$this->message = "String length invalid in DNA40 set value: " . $setVal . '.  Must be even number of columns, alternating Rex32 with Rex8.  See DNA40 number standard at ' . EARTHICA . '.  Invalid DNA40 store value: ' . $setVal;
			return FALSE;
		}
		// ----- check for proper col count 
		if (strlen($setVal) % 2 > 0) {
			$this->message = "Invalid hexidecimal number.  String length of " . strlen($setVal) . " is invalid.  Field entry must consist of length divisible by two, an even number.  Invalid HEX store value: " . $setVal;
			return FALSE;
		}
		// ----- set up loop to check values
		for ($count = 1; $count < strlen($setVal) + 1; $count ++) {
			$col = substr($setVal, $count - 1, 1);

			if ( ($count % 2) == 0) {
		        if(str_contains($rex8Symbols, $col) === FALSE)
					$success = FALSE;
			} else {
		        if(str_contains($rex32Symbols, $col) === FALSE)
					$success = FALSE;
			}

			// ----- check for fail test 
			if (!$success) {
				$this->message = "Invalid DNA40 number, at column: " . $count . '.  Check DNA40 numeric specification ' . ' [' . $GLOBALS['SYMBOL_SETS']['REX32'] . '] + [' . $GLOBALS['SYMBOL_SETS']['REX8'] . ']' . '.  Invalid DNA40 store value: ' . $setVal;
				break;
			}

		}

		// ----- made it thru checks
		if ($success)
			$this->value = $setVal;

		return $success;

	}

}


?>