<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Signup.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/


namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

// ----- sign up form
class Signup extends Model
{

    protected $returnType = 'array';

    protected $validationRules = [
        'username' => [
            'rules' => 'required|max_length[255]|min_length[2]',
            'label' => 'Name',
        ],
        'email' => [
            'rules' => 'required|' . Model::EMAIL_RULES . '|is_unique[user.user_email,user_id,{user_id}]',
            'label' => 'Email',
        ],
        'password' => [
            'rules' => 'required|' . Model::PASSWORD_RULES,
            'label' => 'Password'
        ]
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address has already been taken.'
        ]
    ];

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup(array $data, &$error = null)
    {
        $model = new Model;
        
        return $model->createUser([
            'user_name' => $data['username'],
            'user_email' => $data['email'],
            'user_password' => $data['password']
        ], $error);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user data to with email should be send
     * @return bool whether the email was sent
     */
    public function sendEmail(User $user, &$error = null)
    {
        $params = [
            '{verifyLink}' => Model::getUserVerificationUrl($user)
        ];

        return MessageModel::getMessage('signup', true, [
            'message_subject' => 'Account registration at {BaseURL}',
            'message_body' => '{verifyLink}'
        ])->sendToUser($user, $params, $error);
    }

}