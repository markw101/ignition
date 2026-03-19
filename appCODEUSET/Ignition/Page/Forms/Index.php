<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Index.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Page\Forms;

use Ignition\Page\MC\Model;

// ----- list all visitors
//
class Index extends Model
{

    protected $fieldLabels = [];

    // ----- constructor for configuration
    public function __construct()
    {
        PARENT::__construct();

        $this->fieldLabels = [
    		'id' => 'ID',
    		'updated_at' => lang('base.last') . ' ' . lang('base.update'),
    		'page_name' => lang('base.name'),
    		'page_slug' => lang('base.site') . ' URL',
    		'active' => lang('base.active')
        ];

    }
}