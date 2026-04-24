<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        EBase10.php
    VERSION:     See BaseController.php
    DESCRIPTION: Extension of CI Model.php
    COPYRIGHT:   2024
    FIRST REV:   15 Jan 2024
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class EBase10 extends EBaseNum
{
    // ----- constructor for configuration, must be even number columns, default 00
    public function __construct($setVal = FALSE)
    {
		// ----- init
		$this->sybolSet = "BASE10";
		$this->caseSensative = TRUE;
		$this->wordSize = 8;
		$this->base = 10;

		// ----- construct without initialization
		$this->value = strtoupper($setVal);

	}

	public function set($setVal)
	{
		$success = TRUE;
		$setVal = strtoupper($setVal);

		$retVal = $this->validateVal($setVal, $this->sybolSet);

		// ----- check for fail test 
		if ($retVal > 0) {
			$this->message = "Invalid base 10 number, at column: " . $retVal . ".  This must contain a valid numeric value.  Invalid number store value: " . $setVal;
			$success = FALSE;
		} else 
			$this->value = $setVal;

		return $success;

	}

}


?>