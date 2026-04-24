<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        RequestPasswordReset.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

// ----- user reset
class RequestPasswordReset extends Model
{
    protected $allowedFields = [
        'user_email',
    ];

    protected $returnType = 'array';

    protected static $_user;

    protected $validationRules = [
        'email' => [
            'rules' => 'required|' . Model::EMAIL_RULES . '|' . __CLASS__ . '::validateEmail|' .  __CLASS__ .'::validateVerification',
            'label' => 'Email'
        ]
    ];

    protected $validationMessages = [
        'email' => [
            __CLASS__ . '::validateEmail' => 'There is no user with this email address.',
            __CLASS__ . '::validateVerification' => 'Unable to reset password for not verified email address.'
        ]
    ];

    // ----- constructor for configuration
    public function __construct()
    {
        PARENT::__construct();
        $this->fieldLabels = [
            'user_email' => lang('base.email')
        ];
    }

    public static function validateEmail($email)
    {
        static::$_user = Model::findByEmail($email);

        return static::$_user ? true : false;
    }

    public static function validateVerification($email)
    {
        if (static::$_user)
        {
            if (!Model::getUserField(static::$_user, 'verified_at'))
            {
                static::$_user = null;

                return false;
            }
        }

        return true;
    }    

    public function getUserRec()
    {
        return static::$_user;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail(&$error = null)
    {    
        $user = $this->getUserRec();

        if (!UserModel::isTokenValid(Model::getUserField($user, 'password_reset_token')))
        {
            Model::setUserField($user, 'password_reset_token', Model::generateToken());

            if (!Model::saveEntity($user, false, $error))
            {
                throw new Exception($error);
            }
        }

        $params = [
            '{resetLink}' => Model::getUserResetPasswordUrl($user)
        ];

        return MessageModel::getMessage('reset-password', true, [
            'message_subject' => 'Password reset for {BaseURL}',
            'message_body' => '{resetLink}'
        ])->sendToUser($user, $params, $error);
    }

}