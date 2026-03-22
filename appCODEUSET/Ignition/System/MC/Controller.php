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

namespace Ignition\System\MC;

class Controller extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\System\Views';

	// ----- shows all records of visitors who are on property
    public function result()
    {
        // ----- prep variables
        if (isset($_GET['body']))
            $body = json_decode($_GET['body'], TRUE);
        else 
            $body = '';
    
		// ----- set breadcrumbs for view form 
        $breadCrumbs = [
	        ['name' => lang('base.site'),
	        'url' => BaseURL('/')],
	        ['name' => lang('ecoin.my_world'),
	        'url' => BaseURL('/system')]
		];

        return $this->RenderTheme('results_screen', [
            'body' => $body,
			'breadCrumbs' => $breadCrumbs,
            'AppLayout' => FALSE
        ]);

    }

	// ----- shows all records of visitors who are on property
    public function error404($calledPage = '')
    {
        // ----- get 404 custom page
        $pageModel = new \Ignition\Page\MC\Model;
        $page404 = $pageModel->getPage('Error_404', TRUE);

		// ------ warn not set up 
		if ($page404 == [])
			halt('Error page not set for langauge code: ' . $GLOBALS['LANGCODE']);

        // ----- check for requested narrow banner
        if ($page404['page_narrow_banner'])
            $page_narrow_text = $page404['page_narrow_text'] ?? "Error 404";
        else
            $page_narrow_text = '';

        return $this->RenderTheme('error404', [
            'page_narrow_text' => $page_narrow_text,
            'calledPage' => $calledPage,
            'page404' => $page404,
            'narrowBanner' => true
        ]);

    }

/*
	// ----- run a script
    public function script($script_name = '')
    {
        if (UT != 1)
            halt("ACCESS DENIED!");

        include_once APPPATH . "Site/util/" . $script_name . '.php';
		br(2);
		halt("Script ended: " . $script_name . '.php');
	}
*/
}
