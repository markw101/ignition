<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        ResetPassword.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

// ----- reset password
class ResetPassword extends Model
{

    protected $returnType = 'array';

    protected $validationRules = [
        'password' => [
            'rules' => 'required|' . Model::PASSWORD_RULES,
            'label' => 'Password'
        ]
    ];

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword($user, $data, &$error)
    {
        Model::setUserPassword($user, $data['password']);

        Model::setUserField($user, 'password_reset_token', null);

        return Model::saveEntity($user, false, $error);
    }

}