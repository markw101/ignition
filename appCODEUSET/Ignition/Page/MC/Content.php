<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Content.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Page\MC;

class Content extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\Page\Views';


	// ----- display site pages, open access
    public function view($pageURL = 'index')
    {

        // ----- sets menus selected.  if page not on menu, will not select a menu choice
        $this->menuSelected = $pageURL;

        // ----- find page and get content 
		$model = new \Ignition\Page\MC\Model;
        $model->data = $model->getPage($pageURL);   // default is select by child record

        // ----- check to see if page is published
        if (!isset($model->data['active']) || !$model->data['active'])
            $this->GoHome();

		// ----- set page title for browser
		$this->browserTitle = ' - ' . $model->data['page_title'];

        return $this->RenderTheme('view', [
            'narrowBanner' => ($model->data['page_narrow_banner'] == '1' ? true : false),
            'page_narrow_text' => $model->data['page_narrow_text'],
            'pageURL' => $model->data['page_url'],
            'model' => $model,
        ]);

    }

	// ----- display site pages, open access
    public function byName($pageURL = 'index')
    {

        // ----- sets menus selected.  if page not on menu, will not select a menu choice
        $this->menuSelected = $pageURL;

        // ----- find page and get content 
		$model = new \Ignition\Page\MC\Model;
        $model->data = $model->getPage($pageURL, true);   // default is select by child record

        // ----- check to see if page is published
        if (!isset($model->data['active']) || !$model->data['active'])
            $this->GoHome();

		// ----- set page title for browser
		$this->browserTitle = ' - ' . $model->data['page_title'];

        return $this->RenderTheme('view', [
			'noCR' => $model->data['page_no_carriage_returns'],
            'narrowBanner' => ($model->data['page_narrow_banner'] == '1' ? true : false),
            'page_narrow_text' => $model->data['page_narrow_text'],
            'data' => $model->data['page_text'],
            'pageURL' => $model->data['page_url'],
            'model' => $model,
        ]);

    }

}
