<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Controller.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/


namespace Ignition\User\MC;

class Controller extends \Ignition\Base\BaseController
{

    // ----- constructor for configuration
    public function __construct()
    {
		$this->viewPath = USERVIEWPATH;

		// ----- check for custom app layout 
		if (USERVIEWPATH != 'Ignition\User\Views')
			$this->appLayoutFile = APPPATH . str_ireplace('\\', '/', USERVIEWPATH) . '/app-layout.php';

        PARENT::__construct();
    }

    // ----- admin, list all users
    public function index()
    {
		$fp = USERFORMSPATH . '\\Index';
        $model = new $fp;
        $this->moduleMethod = 'index';             // sometimes called without index in uri

        return $this->RenderTheme('index', [
			'pager' => TRUE,
            'model' => $model,
            'AppLayout' => TRUE,
            'errors' => $model->errors()
        ]);

    }

    // ----- wrapper for edit method
    public function new () {

        $this->edit(1);
    }

    // ----- wrapper for edit method
    public function delete ($id = 0) {

        $this->edit($id);
    }

    // ----- admin, edit one user record
    public function edit($id = 0)
    {

		$fp = USERFORMSPATH . '\\Form';
        $model = new $fp;

		// ----- get input fields from url object
        $model->data = $this->request->getPost();

        // ----- call data validation
    	if ($model->data)
        {
            // ----- place check to verify ID
            if (GetEditID() != $this->moduleID && $model->data['user_type'] != SUPERADMIN)
                halt("Invalid user record");

            // ----- check for user trying to set their type wrongly
            if ($model->data['user_type'] == SUPERADMIN && (UT != SUPERADMIN)) {
                echo '<script language="javascript">alert("' . lang('base.user') . ' ' . lang('base.type') . ' ' . lang('base.invalid') . '");</script>';
                die();
            }

            if ($this->moduleMethod === 'delete') {

                $model->delete($this->moduleID);

                // ----- create session update message
                $this->SetFlashMessage(lang('base.record_successfully_deleted'));
                $this->redirect('user' . $GLOBALS['KEYCODE'] . '/index');
                return;
            }

            // ----- edit or insert record
            if ($model->saveProfile($model->data, $this->moduleID, ($this->moduleMethod === 'new')))
            {

                // ----- set success message
                $this->SetFlashMessage(lang('base.record_successfully_updated'));
                $this->redirect('user' . $GLOBALS['KEYCODE'] . '/index');
                return;
            }

            // ----- otherwise, go back to edit record
        	$model->data = $model->find($this->moduleID);
            if (!$model->data)
                 $this->redirect('/user' . $GLOBALS['KEYCODE']);
        	include APPPATH . "Ignition/Library/EarthicaLanguages.php";
			$model->data['user_language'] = (isset($systemLanguages[$model->data['user_language']]) ? $systemLanguages[$model->data['user_language']] : "");

        } else {

            // ----- check for new 
            if ($this->moduleMethod == 'new') {
	            $model->data = $model->AllowedBlank();
				$model->data['user_language'] = $GLOBALS['LANGCODE'];

            } else {

        		$model->data = $model->find($this->moduleID);

                // ----- if not found
                if (!$model->data)
                    $this->redirect('/user' . $GLOBALS['KEYCODE']);

				// ----- convert language code into text 
				include APPPATH . "Ignition/Library/EarthicaLanguages.php";
				$model->data['user_language'] = (isset($systemLanguages[$model->data['user_language']]) ? $systemLanguages[$model->data['user_language']] : "");

            }

        }

        // ----- set provisional password to hash when submit
        $model->data['user_password'] = '';
        $model->data['user_password2'] = '';

		// ----- set the edit session id 
		SetEditID($model->data['id']);

        return $this->RenderTheme('form', [
            'model' => $model,
            'AppLayout' => TRUE,
			'config' => $this->IConfig,
            'errors' => $model->errors()
        ]);

    }

    // ----- automated user signup
    public function signup()
    {
halt(lang('base.feature_unavailable'));

        $model = new \Ignition\User\Forms\Signup;

        $model->data = $this->request->getPost();

        $errors = [];

        if ($model->data && $model->validate($model->data))
        {
            if (($user = $model->signup($model->data, $error)) && $model->sendEmail($user, $error))
            {
                $session = service('session');

                $session->SetFlashdata(
                    'success', 
                    'Thank you for registration. Please check your inbox for verification email.'
                );
            
                return $this->GoHome();
            }
            else
            {
                $errors[] = $error;
            }
        }

        $model->data['password'] = '';

        return $this->RenderTheme('signup', [
            'model' => $model,
            'data' => $model->data,
            'errors' => array_merge((array) $model->errors(), $errors)
        ]);
    }
    
    // ----- verify email
    public function verifyEmail($id, $token)
    {
halt(lang('base.feature_unavailable'));

        $model = new \Ignition\User\MC\Model;
        $user = $model->getUserFromID($id);
        $errors = [];

        if (!$user || !Model::setUserVerification($user, $token, $errors))
            return $this->GoHome();

        $session = service('session');

        $session->SetFlashdata('success', lang('email_confirmed'));

        return $this->redirect(BaseURL('/' . LOGINURL));
    }

    // ----- resent verification email
    public function sendVerificationEmail()
    {
		$this->appLayoutFile = APPPATH . 'Ignition/Login/Views/app-layout.php';
		$this->layoutConfig = "NORESPONSIVE";

        $model = new \Ignition\User\Forms\VerificationEmail;
        
        $errors = [];

        $model->data = $this->request->getPost();

        if ($model->data && $model->validate($model->data))
        {
halt(lang('base.feature_unavailable'));

            if ($model->sendEmail($error))
            {
                $session = service('session');

                $session->SetFlashdata('success', 'Check your email for further instructions.');
            
                return $this->GoHome();
            }
            else
            {
                $errors[] = $error;
            }
        } else 
            $model->data = ['user_email' => ''];

        $breadCrumbs = $this->MakeCrumbs("nocp|",
	        ['name' => lang('base.resend_verification'),
	        'url' => BaseURL('/user/sendverificationemail')]
         );

        return $this->RenderTheme('verificationEmail', [
            'model' => $model,
            'data' => $model->data,
            'breadCrumbs' => $breadCrumbs,
            'AppLayout' => TRUE,
            'errors' => array_merge((array) $model->errors(), $errors)
        ]);
    }

    // ----- request password reset
    public function requestPasswordReset()
    {
		$this->appLayoutFile = APPPATH . 'Ignition/Login/Views/app-layout.php';
		$this->layoutConfig = "NORESPONSIVE";

        $model = new \Ignition\User\Forms\RequestPasswordReset;

        $model->data = $this->request->getPost();

        $errors = [];
        
        if ($model->data && $model->validate($model->data))
        {
halt(lang('base.feature_unavailable'));

            if ($model->sendEmail($error))
            {
                $session = service('session');

                $session->SetFlashdata('success', 'Check your email for further instructions.');

                return $this->GoHome();
            }
            else
            {
                //'Sorry, we are unable to reset password for the provided email address.'

                $errors[] = $error; 
            }
        } else 
            $model->data = ['user_email' => ''];

        $breadCrumbs = $this->MakeCrumbs("nocp|",
	        ['name' => lang('base.reset_password'),
	        'url' => BaseURL('/user/requestpasswordreset')]
         );

        return $this->RenderTheme('requestPasswordReset', [
            'model' => $model,
            'data' => $model->data,
            'breadCrumbs' => $breadCrumbs,
            'AppLayout' => TRUE,
            'errors' => array_merge((array) $model->errors(), $errors)
        ]);
    }

    // ----- reset password
    public function resetPassword($id, $token)
    {
halt(lang('base.feature_unavailable'));

        $user = \Ignition\Blog\MC\Model::findByPk($id);

        if (!$user)
        {
            throw new PageNotFoundException;
        }

        if (UserModel::getUserField($user, 'password_reset_token') != $token)
        {
            throw new Exception('Wrong password reset token.');
        }

        $errors = [];

        $model = new \Ignition\User\Forms\PasswordReset;

        $model->data = $this->request->getPost();

        if ($model->data && $model->validate($model->data))
        {
            if ($model->resetPassword($user, $model->data, $error))
            {
                $session = service('session');

                $session->SetFlashdata('success', 'New password saved.');

                return $this->redirect(BaseURL('/' . LOGINURL));
            }
            else
            {
                $errors[] = $error;
            }
        }

        return $this->RenderTheme('resetPassword', [
            'model' => $model,
            'data' => $model->data,
            'errors' => array_merge((array) $model->errors(), $errors),
            'id' => $id,
            'token' => $token
        ]);
    }

    // ----- edit user profile
    public function profile()
    {

        // ----- check for user not logged in
        if (!USER)
            $this->redirect('/');

		$fp = USERFORMSPATH . '\Profile';
        $model = new $fp;
        $model->data = $this->request->getPost();
        $errors = [];

        // ----- check for submitted data from user profile form
        if ($model->data)
        {

            // ----- place check to verify ID
            if (GetEditID() != GetUserID())
                halt("Invalid user record");

            if ($model->saveProfile($model->data, GetUserID()))
            {
                $this->SetFlashMessage(lang('base.profile_update'));
                return $this->redirect($_SESSION['session_data']['user_redirect'] . $GLOBALS['KEYCODE']);
            }

            // ----- otherwise, go back to edit record
        	$model->data = $model->find(GetUserID());
            if (!$model->data)
                 $this->redirect('/');
        	include APPPATH . "Ignition/Library/EarthicaLanguages.php";
			$model->data['user_language'] = (isset($systemLanguages[$model->data['user_language']]) ? $systemLanguages[$model->data['user_language']] : "");

        } else {

            // ----- get user table data from session id
    		$model->data = $model->find(GetUserID());

            // ----- if not found
            if (!$model->data)
                $this->redirect('/');

			// ----- convert language code into text 
			include APPPATH . "Ignition/Library/EarthicaLanguages.php";
			$model->data['user_language'] = (isset($systemLanguages[$model->data['user_language']]) ? $systemLanguages[$model->data['user_language']] : "");

        }

		// ----- check for app mode or admin mode 
		if (UT >= 10 && UT < 100) {

	        $breadCrumbs = $this->MakeCrumbs('|nocp|',
		        ['name' => lang(CUSTOMLANG . '.' . APPHOME),
		        'url' => BaseURL('/' . APPHOME . $GLOBALS['KEYCODE'])],
		        ['name' => lang('base.my_profile'),
		        'url' => BaseURL('/userprofile' . $GLOBALS['KEYCODE'])]
			);

		} else {
	        $breadCrumbs = $this->MakeCrumbs('',
		        ['name' => lang('base.my_profile'),
		        'url' => BaseURL('/userprofile' . $GLOBALS['KEYCODE'])]
			);
		}

        // ----- set provisional password field, must be converted to hash before save
        $model->data['user_password'] = '';
		$model->data['user_password2'] = '';

		// ----- set the edit session id 
		SetEditID($model->data['id']);

        return $this->RenderTheme('profile', [
            'narrowBanner' => true,
            'page_narrow_text' => lang('base.my_profile'),
            'model' => $model,
			'config' => $this->IConfig,
            'AppLayout' => TRUE,
			'breadCrumbs' => $breadCrumbs,
            'errors' => array_merge((array) $model->errors(), $errors)
        ]);
    }

    // ----- logout by destroying current user session and deleting session data
    public function logout()
    {
        // ----- destroy user session 
        $session = service('session');
        if ($session->has('session_data'))
            unset($_SESSION['session_data']);

        $session->destroy();

        return $this->GoHome();
    }

/*
    // ----- allow user login simply with a token
    public function token($autToken = "INVALID")
    {
        // ----- check for no token
        if ($autToken == "INVALID")
            $this->Go404();

        // ----- check token for valid user 
        $model = new \Ignition\User\MC\Model;
        $user = $model->where(['user_login_token' => $autToken])->first();

        // ----- check for not found 
        if (!$user)
            $this->Go404();

        // ----- get user_type determine session and redirect
        $db      = \Config\Database::connect();
        $builder = $db->table('user_types');
        $result = $builder->getWhere(['id' => $user['user_type']]);
        $resultsObj = $result->getResult('array');

        $redirectAfter = $resultsObj[0]['redirectLogin'];

        // ----- calculate session len which will be carried around
        //       in session and is set in /Site/SiteConfig construct
        if ($user['user_session_len'] == "")
            $setSessionLen = $resultsObj[0]['max_session'];
        else 
            $setSessionLen = $user['user_session_len'];

        // ----- set session variables
        $session_data = array(
            'user_type' 	=> $user['user_type'],
            'user_roles' 	=> $user['user_roles'],
            'user_name' 	=> $user['user_name'],
            'user_id'	    => $user['id'],
            'user_peernum' 	=> $user['user_peernum'],
            'user_email' 	=> $user['user_email'],
            'user_session'  => $setSessionLen,
            'user_language' => isset($user['user_language']) ? $user['user_language'] : 'english'
        );

        // ---- store session data to php sessions
        $_SESSION['session_data'] = $session_data;

	    // ----- redirect based on user type
        return $this->redirect($redirectAfter);

    }
*/
}
