<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        VerificationEmail.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/


namespace Ignition\User\Forms;

use Ignition\User\MC\Model;

class VerificationEmail extends Model
{
    protected $allowedFields = [
        'user_email',
    ];

    protected static $_user;

    protected $validationRules = [
        'email' => [
            'label' => 'Email',
            'rules' => 'required|' . Model::EMAIL_RULES . '|' . __CLASS__ . '::validateEmail|' . __CLASS__ . '::validateVerification'
        ]
    ];

    protected $validationMessages = [
        'email' => [
            __CLASS__ . '::validateEmail' => 'There is no user with this email address.',
            __CLASS__ . '::validateVerification' => 'Email is already verified.'
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
            if (Model::getUserField(static::$_user, 'verified_at'))
            {
                return false;
            }
        }

        return true;
    }

    public function getUserRec()
    {
        return static::$_user;
    }

    public function sendEmail(&$error)
    {
        $user = $this->getUserRec();

        if (!Model::isTokenValid(Model::getUserField($user, 'verification_token')))
        {
            Model::setUserField($user, 'verification_token', Model::generateToken());

            if (!Model::saveEntity($user, false, $error))
            {
                throw new Exception($error);
            }
        }

        $params = [
            '{verifyLink}' => Model::getUserVerificationUrl($user)
        ];

        return MessageModel::getMessage('email-verification', true, [
            'message_subject' => 'Account verification at {BaseURL}',
            'message_body' => '{verifyLink}'
        ])->sendToUser($user, $params, $error);
    }

}