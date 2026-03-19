<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Profile.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

class Profile extends Model
{
    protected $allowedFields = [
        'user_fname',
        'user_mname',
        'user_lname',
        'user_oname',
        'user_password',
        'user_password2',
        'user_email',
        'user_password_hash',
        'user_password_salt',
		'user_language',
        'user_peernum'
    ];

    protected $validationRules = [];

    protected $fieldLabels = [];

    // ----- constructor for configuration
    public function __construct()
    {
        PARENT::__construct();

        $this->fieldLabels = [
            'user_email' => lang('base.email'),
            'user_fname' => lang('base.first_name'),
            'user_mname' => lang('base.middle_name'),
            'user_lname' => lang('base.last_name'),
            'user_oname' => lang('base.other_last_name'),
            'user_password' => lang('base.password'),
            'user_password2' => lang('base.password'),
    		'user_peernum' => lang('base.dna_peer_number')
        ];

        $this->validationRules = [
            'user_email' => [
                'rules' => 'max_length[255]|valid_email|min_length[4]|required',
                'label' => lang('base.email')],
            'user_name' => [
                'rules' => 'max_length[255]|required',
                'label' => lang('base.name')],
            'user_password' => 'max_length[72]|min_length[5]|permit_empty'
        ];

    }
}