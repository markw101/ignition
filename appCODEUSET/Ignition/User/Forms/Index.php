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

namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

// ----- list index
//
class Index extends Model
{
    protected $fieldLabels = [];

    // ----- constructor for configuration
    public function __construct()
    {
        PARENT::__construct();

        $this->fieldLabels = [
            'id' => lang('base.id'),
            'user_peernum' => lang('dna.dnapno'),
            'user_type' => lang('base.type') . ' ' . lang('base.user'),
            'user_email' => lang('base.email'),
            'user_fname' => lang('base.first_name'),
            'user_lname' => lang('base.last_name'),
            'user_peernum' => lang('base.epn'),
            'user_accountnum' => lang('base.account') . '#',
            'active' => lang('base.active')
        ];

    }

}