<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        EBase64.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class EBase64 extends EBaseNum
{
    // ----- constructor for configuration, must be even number columns, default 00
    public function __construct($setVal = FALSE)
    {
		// ----- init
		$this->sybolSet = "BASE64";
		$this->caseSensative = TRUE;
		$this->wordSize = 6;
		$this->base = 64;

	}

	public function set($setVal)
	{
		// ----- init 
		$success = TRUE;

		// ----- check for proper col count 
/// MUST ADD PADDING FOR TEST
//		if ((strlen($setVal) * 6) % 8 > 0) {
//			$this->message = "Invalid Base64 number.  String length of " . strlen($setVal) . " is invalid.  Field entry must consist of characters equaling a length of length X 6 mod 8 = 0.  Total bits, at 6 bits per column, must be divisible by 8.";
//			return FALSE;
//		}

		$retVal = $this->validateVal($setVal, "BASE64");

		// ----- check for fail test 
		if ($retVal > 0) {
			$this->message = "Invalid Base64 number, at column: " . $retVal . ".  Check Base64 numeric specification.  Invalid Base64 store value: " . $setVal;
			$success = FALSE;
		} else 
			$this->value = $setVal;

		return $success;

	}


}


?>