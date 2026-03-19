<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Home.php
    VERSION:     See BaseController.php
    DESCRIPTION: Home page
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Site\Channels\Home;

class Home extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Site\Channels\Home\Views';

	// ----- show custom home page
    public function view() {


//        $model = new \Ignition\Asset\MC\Model;
        $model = new \Ignition\Blog\MC\Model;
        $this->menuSelected = "site";            // set manually, override constructor (post uri)

        return $this->RenderTheme('view', [
            'model' => $model
        ]);

    }

}
