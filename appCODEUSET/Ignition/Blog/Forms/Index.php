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

namespace Ignition\Blog\Forms;

use Ignition\Blog\MC\Model;

// ----- list all visitors
//
class Index extends Model
{

    protected $fieldLabels = [];

    // ----- constructor for setting variables
    public function __construct()
    {
        PARENT::__construct();

        $this->fieldLabels = [
                'id' => 'ID',
                'updated_at' => lang('base.last') . ' ' . lang('base.update'),
                'blog_title' => lang('base.title'),
                'blog_slug' => 'Blog URL',
                'active' => lang('active')
        ];

    }
}

