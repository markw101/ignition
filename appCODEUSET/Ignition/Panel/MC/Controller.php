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

namespace Ignition\Panel\MC;

class Controller extends \Ignition\Base\BaseController
{
    // ----- this is set to the directory for the individual site 
    protected $viewPath = 'Site\ControlPanel\Views';

	// ----- call admin controller panel based on usertype
    public function panel()
    {
        // ----- check for user not logged in
        if (!USER)
            $this->redirect('/');

        return $this->RenderTheme('UserType' . (UT != SAN ? UT : 1), [
            'AppLayout' => TRUE
        ]);

    }

}
