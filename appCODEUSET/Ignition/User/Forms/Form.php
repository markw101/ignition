<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Form.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

class Form extends Model
{
	protected $iForm = [];

    protected $allowedFields = [
        'user_email',
        'user_username',
        'user_fname',
        'user_mname',
        'user_lname',
        'user_oname',
        'active',
        'user_type',
        'user_roles',
        'user_password',
        'user_password2',
        'user_password_hash',
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
            'user_username' => lang('base.email'),
            'user_fname' => lang('base.first_name'),
            'user_mname' => lang('base.middle_name'),
            'user_lname' => lang('base.last_name'),
            'user_oname' => lang('base.other_last_name'),
            'user_password' => lang('base.password'),
            'user_password2' => lang('base.password') . lang('base.confirm'),
            'user_verification_token' =>  lang('base.verify_token'),
            'user_password_reset_token' => 'Password Reset Token',
    		'user_peernum' => lang('base.dna_peer_number'),
            'user_type' => lang('base.user') . ' ' . lang('base.type'),
            'user_roles' => lang('base.user') . ' ' . lang('base.roles'),
            'active' => lang('base.active'),
			'user_language' => lang('base.language'),
			'user_email' => lang('base.email')
        ];

        $this->validationRules = [
            'user_email' => [
                'rules' => 'max_length[255]|valid_email|min_length[4]|required',
                'label' => lang('base.email')],
            'user_fname' => [
                'rules' => 'max_length[255]|required',
                'label' => lang('base.name')],
            'active' => 'in_list[0,1]',
            'user_password' => 'max_length[72]|min_length[5]|permit_empty',
            'user_type' => [
                'rules' => 'in_list[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]|required',
                'label' => lang('base.usertype')],
            'user_language' => [
                'rules' => 'required',
                'label' => lang('base.language')]
        ];

    }
}