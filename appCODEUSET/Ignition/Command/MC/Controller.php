<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Command.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Command\MC;

class Controller extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\Command\Views';


	// ----- execute command on server
    public function command($command, $parameter_file = '')
    {




	}

	// ----- execute command on server
    public function ide()
    {

		
		echo view($this->viewPath . '/ide', [], ['saveData' => true]);
/*
        return $this->RenderTheme('ide', [
        ]);
*/

	}

	// ----- execute command on server
    public function sql()
    {

		
		echo view($this->viewPath . '/sql', [], ['saveData' => true]);
/*
        return $this->RenderTheme('ide', [
        ]);
*/

	}

}

?>