<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Login.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\User\Forms;

// quirky bug: if omit this statement and add it to extends statement
// get error message, "class LoginForm not found" in call from controller 
// even though this appears to work in UserModel, ref BaseModel
use \Ignition\User\MC\Model;

// ----- user login form
class Login extends Model
{

	protected $allowedFields = [
        'user_username',
        'user_password',
        'autoLogout'
	];

	public $fieldLabels = [];

    // ----- constructor for configuration
    public function __construct()
    {
        PARENT::__construct();

        $this->fieldLabels = [
        'user_username' => strtoupper(lang('base.email') . lang('base.or') . lang('base.username')),
        'user_password' => strtoupper(lang('base.password')),
        'autoLogout' => lang('base.autoLogout'),
		];

    }

}