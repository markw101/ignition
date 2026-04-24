<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        ERex8.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Library\dna;

class ERex8 extends EBaseNum
{
    // ----- constructor for configuration
    public function __construct($v = '0')
    {
		$this->value = strtoupper($v);
		$this->symbolSet = 'REX8';
		$this->caseSensative = FALSE;
		$this->wordSize = 3;

	}



}


?>