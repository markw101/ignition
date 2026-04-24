<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Controller.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Taz\MC;

class Controller extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\Taz\Views';

	// ----- execute a script in the taz views directory
    public function script($script_name = '', $script_param = FALSE)
    {
        if (UT != SAN)
            halt("ACCESS DENIED!");

        include_once APPPATH . "Ignition/Taz/Views/" . $script_name . '.php';

	}

}
