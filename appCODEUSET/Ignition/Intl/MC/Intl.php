<?php 

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Intl.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Intl\MC;

// ----- introduce additional maint functionality
class Intl extends \Ignition\AutoForm\MC\Controller
{

    // ----- show the status of payments for the community
    public function intlLanding()
    {

        $this->viewPath = 'Ignition\Intl\Views';
        $model = new \Ignition\Page\MC\Model;

        // ----- find page and get content 
        $pageDatum = $model->getPage('mantenimiento');

		// ----- get input fields from url object
        $data = $this->request->getPost();

        // ----- call data validation
    	if ($data)
        {
            // ----- connect to database and the records for section
            $db      = \Config\Database::connect();
            $builder = $db->table('maint')->getWhere(['maint_year' => $data['maint_year'], 'maint_month' => $data['maint_month']]);
        	$sectionResults = $builder->getResult('array');

        } else {

            // ----- init data
            $data['maint_month'] = "Mayo";
            $data['maint_year'] = "2022";

            // ----- show last calendar month 
            //

            // ----- connect to database and the records for section
            $db      = \Config\Database::connect();
            $builder = $db->table('maint')->getWhere(['maint_year' => '2022', 'maint_month' => 'Mayo']);
        	$sectionResults = $builder->getResult('array');

        }

        return $this->RenderTheme('view', [
            'narrowBanner' => true,
            'page_narrow_text' => "Pagos por Seccion",
            'pageDatum' => $pageDatum['pagelang_text'],
            'sectionResults' => $sectionResults[0],
            'data' => $data
        ]);
    }

}
?>