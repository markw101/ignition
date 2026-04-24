<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Model.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/


namespace Ignition\User\MC;

class Model extends \Ignition\Base\BaseModel
{

    const EMAIL_RULES = 'max_length[255]|valid_email|min_length[2]';
    const PASSWORD_RULES = 'max_length[72]|min_length[5]';

	protected $table = 'user';

    public static function getUser($loginHandle, $usernameLogins) {

        $class = get_called_class();
        $model = new $class;

        switch($usernameLogins) {
            case 1 :
                return $model->where(['user_email' => $loginHandle])->first();
            case 2 :
                return $model->where(['username' => $loginHandle])->first();
            case 3 :
                if (isEmail($loginHandle))
                    $loginField = 'user_email';
                else {
                    $loginField = 'user_accountnum';
                    $loginHandle = dna402bin($loginHandle);
                }
                return $model->where([$loginField => $loginHandle])->first();
        }

    }

    public static function getUserFromID($id) {

        $class = get_called_class();
        $model = new $class;
        return $model->where(['id' => $id])->first();

    }

    public static function getUserVerificationUrl($email)
    {
        $user = static::getUser($email);

        $token = $user['verification_token'];

        return site_url('users/verifyEmail/' . $user['id']  . '/'. $token);
    }

    // ----- save profile data taking into account password 
    public function saveProfile($data, $id, $create = false) {


/*
		// ----- must convert language select control from text to lang code
		include APPPATH . "Ignition/Library/EarthicaLanguages.php";
        $langKeys = array_keys($systemLanguages);

		foreach($langKeys as $key) {

			if ($data['user_language'] == $systemLanguages[$key]) {
				$data['user_language'] = $key;
				break;
			}
		}
*/

		// ----- validate double password 
		if ($data['user_password'] != '' || $data['user_password2'] != '') {

			// ----- check for equality
			if ($data['user_password'] != $data['user_password2']) {
				$this->validationErrors = ["password" => lang('base.password_no_match')];
				$this->data = $this->FixCheckBoxes($data);			// restore data
				$this->data = $this->ConvertBinaryFields($data, FALSE);	// restore data 
				return FALSE;
			}

            // ----- changing password
            if (!empty($data['user_password'])) {
                $data['user_password_salt'] = random_bytes(DNA_SALT_BYTES);
                $data['user_password_hash'] = dna402bin(DNAPasswordHash($data['user_password'], bin2dna40($data['user_password_salt'])));

            }

		}

		unset($data['user_password2']);
        unset($data['user_password']);

        // ----- validate and save
        if ($this->validate($data)) {
            if ($create)
                return $this->IInsert($data);
            else {
				$this->data['id'] = GetEditID(); // prevents session jumping

				// ----- check for modified
				if ($id != $this->data['id'])
					halt('Record ID mismatch in call to user edit');

                return $this->IUpdate($id,  $data);
			}
        } else 
            return false;

    }

	// ----- receive user verification link from email
    public static function setUserVerification($user, $token, &$error = null)
    {
/*
        if (static::getUserField($user, 'verified_at'))
        {
            $error = 'User already verified.';

            return false;
        }

        if (static::getUserField($user, 'verification_token') != $token)
        {
            $error = 'Unable to verify your account with provided token.';
        
            return false;
        }

        $model = new UserModel;

        $model->set('user_verified_at', 'NOW()', false);

        $model->set('user_verification_token', 'NULL', false);

        $model->protect(false);

        $id = $model::getUserField($user, 'id');

        $updated = $model->IUpdate($id);

        $model->protect(true);

        if (!$updated)
        {
            $error = $model->getFirstError();

            return false;
        }

        $user = UserModel::findByPk($id);
*/
        return true;
    }



/*
    public function beforeCreateUser($user, array $data)
    {
        $token = static::getUserField($user, 'verification_token');

        if (!$token)
        {
            static::setUserField($user, 'verification_token', static::generateToken());
        }
    }

    public static function generateToken()
    {
        return md5(time() . rand(0, PHP_INT_MAX)) . '_' . time();
    }

    public static function getUserVerificationUrl($user)
    {
        $id = static::getUserField($user, 'id');

        $token = static::getUserField($user, 'verification_token');

        return site_url('user/verifyEmail/' . $id  . '/'. $token);
    }

    public static function getUserResetPasswordUrl($user)
    {
        $id = static::getUserField($user, 'id');
        
        $token = static::getUserField($user, 'password_reset_token');

        return site_url('user/resetPassword/' . $id . '/' .  $token);
    }

	// ------ check is token valid
    public static function isTokenValid($token)
    {
        if (!$token)
        {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
     
        $expire = 600;
        
        return $timestamp + $expire >= time();
    }


*/

}
