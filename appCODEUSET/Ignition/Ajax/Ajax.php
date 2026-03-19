<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Ajax.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Ajax;

class Ajax // extends \Ignition\Base\BaseController
{
	// ----- show custom home page
    public function ajax($ajaxRun) {

        // ----- bring in ajax rbac
        include APPPATH . 'Site/AjaxControl.php'; 

        $ajaxConstraints = $ajaxControl[$ajaxRun];
        $runFile = $ajaxConstraints['fileName'];

        // ----- confirm user pass
        switch (TRUE) {
            case ($ajaxConstraints['allowVisitor'] && !$ajaxConstraints['baseEnforce']) :
                $pass = TRUE;
                break;
            case ($ajaxConstraints['allowUser'] == 'LOGGEDIN' && USERNAME) :
                $pass = TRUE;
                break;
            case (ValSet($ajaxConstraints['allowUser'], '|' . UT . '|')) :
                $pass = TRUE;
                break;
            default :
                $pass = FALSE;
        }

        // ----- if pass, run it
        if ($pass)
            include APPPATH . 'Ajax' . IGNITIONCD . '/' . $runFile;
        else 
            ActiveIntrusion('AJAXRUNATTEMPT', ['method' => 'ajax', 'parameter' => $ajaxRun]);

    }

}
