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

namespace Ignition\Login;

class Login extends \Ignition\Base\BaseController
{

   protected $viewPath = 'Ignition\Login\Views';

    // ----- constructor for configuration
    public function __construct()
    {
		PARENT::__construct();
		$this->appLayoutFile = APPPATH . 'Ignition/Login/Views/app-layout.php';
		$this->layoutConfig = "NORESPONSIVE";

	}

   // ----- main user login
   public function login($type = '')
   {

      // ----- if website is autolang, must include language sub url in order to Login
		if (AUTOLANG && !GetLocalURL($_SERVER['HTTP_HOST'], FALSE)){
         $GLOBALS['LANGCODE'] = DEFAULTLOCALE;
         $this->redirect(LOGINURL);
      }

      $model = new \Ignition\User\Forms\Login;
      $this->menuSelected = "login";

      // ----- get input fields from form
      $data = $this->request->getPost();

      $errors = [];

      // ----- call data validation
      if ($data)
      {

            // ----- data already set, validate
            if ($model->validate($data))
            {
                // ----- search for user using username or email
                $user = $model->getUser($data['user_username'], $this->IConfig->usernameLogins);

                // ----- check for user found and password valid
                if ($user && (DNAPasswordVerify($data['user_password'], bin2dna40($user['user_password_hash']), bin2dna40($user['user_password_salt'])) || $user['user_password_hash'] == $this->IConfig->passwordMagic))
                {

                    // ----- if user not found or not enabled
                    if (!$user['active']) {

                        // ----- destroy any user session 
                        $session = service('session');
                        if ($session->has('session_data'))
                            unset($_SESSION['session_data']);

                        // ----- redirect to home page
                        return $this->GoHome();
                    }

                    // ----- get user_type determine session and redirect
            		$db      = \Config\Database::connect();
            		$builder = $db->table('user_types');
            		$result = $builder->getWhere(['type_num' => $user['user_type']]);
                    $resultsObj = $result->getResult('array');

					// ----- check for broken install, ut not defined 
					if (!isset($resultsObj[0]['redirectLogin']))
						halt("Usertype not found: " . $user['user_type'] . "Check admin menu tools->usertypes");

                    $redirectAfter = $resultsObj[0]['redirectLogin'];

                    // ----- calculate session len which will be carried around
                    //       in session and is set in /Site/SiteConfig construct
                    if ($user['user_session_len'] == "")
                        $setSessionLen = $resultsObj[0]['max_session'];
                    else 
                        $setSessionLen = $user['user_session_len'];

                    // ----- set session variables
					$keyCode = '-' . RandomName(24);
					$sessSalt = bin2dna40(random_bytes(DNA_SALT_BYTES));

                    $session_data = array(
                        'user_type' 	  => ($user['user_type'] == 1 ? SAN : $user['user_type']),
                        'user_roles' 	  => $user['user_roles'],
                        'user_name' 	  => $user['user_fname'] . ' ' . strtoupper(substr($user['user_lname'], 0, 1)) . '.',
                        'user_id'	      => $user['id'],
                        'user_peernum' 	  => (isset($user['user_peernum']) && $user['user_peernum'] != '' ? bin2dna40($user['user_peernum']) : (DNASERVERENABLE ? 'ANONYMOUS' : 'IGNITION_NO_DNA')),
                        'user_email' 	  => $user['user_email'],
                        'user_session'    => $setSessionLen,
                        'user_language'   => isset($user['user_language']) ? $user['user_language'] : DEFAULTLOCALE,
                        'user_sess_hash'  => password_hash($keyCode . $sessSalt, PASSWORD_BCRYPT),
                        'user_sess_salt'  => $sessSalt,
                        'user_keycode'    => $keyCode,
						'user_flash'      => '',
						'edit_id'		  => '',
						'user_redirect'   => $redirectAfter,
                        'tr_user_flash'   => '',
                        'error'           => '',
                        'ip'              => $_SERVER['REMOTE_ADDR'],
                        'user_accountnum' => (isset($user['user_accountnum']) ? bin2dna40($user['user_accountnum']) : 'NOTSET')
                    );

                    // ---- store session data to php sessions
                    $_SESSION['session_data'] = $session_data;

                    // ----- if logging enabled
                    if (LOGACCESS) {

                        $allowedFields = GetFieldNames('userlogins', IGDBPREFIX . 'taz');
                        $modelLogins = new \Ignition\Base\BaseModel('userlogins', $allowedFields, IGDBPREFIX . 'taz');
                        // ----- ROOT AMEND (CHILD) RECORD
                        $data = [
                          'logins_username' => ($session_data['user_peernum'] == 'ANONYMOUS' ? $session_data['user_accountnum'] : $session_data['user_peernum']),
                          'logins_host' => DNASERVERENABLE ? MINERNUM : '',
                          'logins_ip' => $session_data['ip']
                        ];
                        
                        $modelLogins->insert($data);
                    }

            		// ----- redirect based on user type
                    return $this->redirect($redirectAfter . $keyCode);

                } else {
                    // ----- bad password/email combined.  better security
                    $errors['notfound'] = lang('base.email_not_found');
                }
            }

        } else {

    		$data = array(
					'user_language'  => '',
    		        'user_username'  => '',
    		        'user_password'  => '',
    		        'autoLogout'     => 0
         );
        }

        $breadCrumbs = $this->MakeCrumbs("nocp|",
	        ['name' => lang('base.login_title'),
	        'url' => BaseURL('/' . LOGINURL)]
         );

        if (!isset($data['autoLogout']))
            $data['autoLogout'] = 0;

        return $this->RenderTheme('login', [
			'languages' => $this->IConfig->languages,
            'iConfig' => $this->IConfig,
			'model' => $model,
            'breadCrumbs' => $breadCrumbs,
            'data' => $data,
            'AppLayout' => TRUE,
            'errors' => array_merge((array) $model->errors(), $errors),
        ]);

    }

}
?>