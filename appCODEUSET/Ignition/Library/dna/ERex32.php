<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        ERex32.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class ERex32 extends EBaseNum
{
    // ----- constructor for configuration
    public function __construct($v = '0')
    {
		$this->value = strtoupper($v);
		$this->symbolSet = 'REX32';
		$this->caseSensative = FALSE;
		$this->wordSize = 6;

	}

}


?>