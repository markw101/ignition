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

namespace Ignition\Author\MC;

// ----- introduce asset functionality
class Controller extends \Ignition\AutoForm\MC\Controller
{
    protected $viewPath = 'Ignition\Author\Views';

    // ----- constructor for configuration
    public function __construct()
    {
        $this->customLangauge = "blog";

        PARENT::__construct();
    }

	// ----- present author profile
	public function profile($author_id = 0)
	{

		// ----- check for non given
		if ($author_id == 0)
			$this->redirect('/', TRUE);

		$model = new \Ignition\Author\MC\Model;

		$model->data = $model->find(1);

        if ($model->data == '') {
            $this->redirect('/', TRUE);
            return;
        }

        return $this->RenderTheme('profile', [
            'model' => $model,
        ]);

	}
}