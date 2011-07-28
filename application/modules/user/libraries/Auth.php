<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once('PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', FALSE);

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		Auth
 * @author		Mustafa Navruz
 * @version		2.0
 * @based on            Tank_auth by Ilya Konyukhov (http://konyukhov.com/soft/)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Auth
{

    private $error = array();

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->model(array('user/users', 'permission/permissions_model'));
        $this->ci->load->config('user/auth');
        // Try to autologin
        $this->autologin();
        if ($this->is_logged_in(TRUE))
        {
            $this->ci->users->update_last_online();
        }
    }

    /**
     * Login user on the site. Return TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param	string	(username or email or both depending on settings in config file)
     * @param	string
     * @param	bool
     * @return	bool
     */
    public function login($login, $password, $remember, $login_by_username, $login_by_email)
    {
        if ((strlen($login) > 0) AND (strlen($password) > 0))
        {
            // Which function to use to login (based on config)
            if ($login_by_username AND $login_by_email)
            {
                $get_user_func = 'get_user_by_login';
            }
            else if ($login_by_username)
            {
                $get_user_func = 'get_user_by_username';
            }
            else
            {
                $get_user_func = 'get_user_by_email';
            }

            if (!is_null($user = $this->ci->users->$get_user_func($login)))
            { // login ok
                // Does password match hash in database?
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                if ($hasher->CheckPassword($password, $user->password))
                {  // password ok
                    if ($user->banned == 1)
                    {         // fail - banned
                        $this->error = array('banned' => $user->ban_reason);
                    }
                    else
                    {
                        if (trim(trim($user->name)) == '')
                        {
                            $user->name = $user->username;
                        }
                        $this->ci->session->set_userdata(array(
                            'user_id' => $user->id,
                            'username' => $user->username,
                            'name' => $user->name,
                            'user_email' => $user->email,
                            'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
                            'user_group' => $user->user_group,
                            'permissions' => $this->ci->permissions_model->get($user->id),
                        ));

                        if ($user->activated == 0)
                        {       // fail - not activated
                            $this->error = array('not_activated' => '');
                        }
                        else
                        {            // success
                            if ($remember)
                            {
                                $this->create_autologin($user->id);
                            }

                            $this->clear_login_attempts($login);

                            $this->ci->users->update_login_info(
                                    $user->id,
                                    $this->ci->config->item('login_record_ip'),
                                    $this->ci->config->item('login_record_time'));
                            return TRUE;
                        }
                    }
                }
                else
                {              // fail - wrong password
                    $this->increase_login_attempt($login);
                    $this->error = array('password' => 'auth_incorrect_password');
                }
            }
            else
            {               // fail - wrong login
                $this->increase_login_attempt($login);
                $this->error = array('login' => 'auth_incorrect_login');
            }
        }
        return FALSE;
    }

    /**
     * Logout user from the site
     *
     * @return	void
     */
    public function logout()
    {
        $this->delete_autologin();
        $this->ci->session->sess_destroy();
    }

    /**
     * Check if user logged in. Also test if user is activated or not.
     *
     * @param    bool
     * @return    bool
     */
    public function is_logged_in($activated = TRUE)
    {
        return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
    }

    /**
     * Check if user is admin.
     *
     * @param	bool
     * @return	bool
     */
    public function is_admin()
    {
        return $this->ci->session->userdata('user_group') == $this->ci->config->item('admin_group') ? TRUE : FALSE;
    }

    /**
     * Get user_id
     *
     * @return	string
     */
    public function get_user_id()
    {
        return $this->ci->session->userdata('user_id');
    }

    /**
     * Get username
     *
     * @return	string
     */
    public function get_username()
    {
        return $this->ci->session->userdata('username');
    }

    /**
     * Get user e-mail
     *
     * @return	string
     */
    public function get_user_email()
    {
        return $this->ci->session->userdata('user_email');
    }

    /**
     * Create new user on the site and return some data about it:
     * user_id, username, password, email, new_email_key (if any).
     *
     * @param	string
     * @param	string
     * @param	string
     * @param	bool
     * @return	array
     */
    public function create_user($username, $email, $password, $email_activation, $profile_data = NULL, $user_group = NULL)
    {
        if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username))
        {
            $this->error = array('username' => 'auth_username_in_use');
        }
        elseif (!$this->ci->users->is_email_available($email))
        {
            $this->error = array('email' => 'auth_email_in_use');
        }
        else
        {
            // Hash password using phpass
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $hashed_password = $hasher->HashPassword($password);

            $data = array(
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email,
                'last_ip' => $this->ci->input->ip_address(),
                'user_group' => ($user_group === NULL) ? $this->ci->config->item('default_user_group') : $user_group,
            );

            if ($email_activation)
            {
                $data['new_email_key'] = md5(rand() . microtime());
            }
            if (!is_null($res = $this->ci->users->create_user($data, !$email_activation)))
            {
                $data['user_id'] = $res['user_id'];
                $data['password'] = $password;
                unset($data['last_ip']);

                if ($profile_data !== NULL)
                {
                    $profile_data['user_id'] = $data['user_id'];
                    $this->ci->users->create_profile($profile_data);
                }
                return $data;
            }
        }
        return NULL;
    }

    public function update_user($user_id, $email, $password = NULL, $user_group = NULL)
    {
        $data = array(
            'email' => $email,
            'user_group' => $user_group,
        );
        if ($password !== NULL)
        {
            // Hash password using phpass
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $hashed_password = $hasher->HashPassword($password);
            $data['password'] = $hashed_password;
        }
        if (!is_null($this->ci->users->update_user($user_id, $data)))
        {
            /* if ($profile_data !== NULL)
              {
              $profile_data['user_id'] = $data['user_id'];
              $this->ci->users->create_profile($profile_data);
              } */
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Check if username available for registering.
     * Can be called for instant form validation.
     *
     * @param	string
     * @return	bool
     */
    public function is_username_available($username)
    {
        return ((strlen($username) > 0) AND $this->ci->users->is_username_available($username));
    }

    /**
     * Check if email available for registering.
     * Can be called for instant form validation.
     *
     * @param	string
     * @return	bool
     */
    public function is_email_available($email)
    {
        return ((strlen($email) > 0) AND $this->ci->users->is_email_available($email));
    }

    /**
     * Change email for activation and return some data about user:
     * user_id, username, email, new_email_key.
     * Can be called for not activated users only.
     *
     * @param	string
     * @return	array
     */
    public function change_email($email)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->users->get_user_by_id($user_id, FALSE)))
        {
            $data = array(
                'user_id' => $user_id,
                'username' => $user->username,
                'email' => $email,
            );
            if ($user->email == $email)
            {  // leave activation key as is
                $data['new_email_key'] = $user->new_email_key;
                return $data;
            }
            elseif ($this->ci->users->is_email_available($email))
            {
                $data['new_email_key'] = md5(rand() . microtime());
                $this->ci->users->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
                return $data;
            }
            else
            {
                $this->error = array('email' => 'auth_email_in_use');
            }
        }
        return NULL;
    }

    /**
     * Activate user using given key
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function activate_user($user_id, $new_email_key)
    {
        $this->ci->users->purge_na($this->ci->config->item('email_activation_expire'));

        if ((strlen($user_id) > 0) AND (strlen($new_email_key) > 0))
        {
            return $this->ci->users->activate_user($user_id, $new_email_key);
        }
        return FALSE;
    }

    /**
     * Set new password key for user and return some data about user:
     * user_id, username, email, new_pass_key.
     * The password key can be used to verify user when resetting his/her password.
     *
     * @param	string
     * @return	array
     */
    public function forgot_password($login)
    {
        if (strlen($login) > 0)
        {
            if (!is_null($user = $this->ci->users->get_user_by_login($login, TRUE)))
            {
                $data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'new_pass_key' => md5(rand() . microtime()),
                );

                $this->ci->users->set_password_key($user->id, $data['new_pass_key']);
                return $data;
            }
            else
            {
                $this->error = array('login' => 'auth_incorrect_email_or_username');
            }
        }
        return NULL;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function can_reset_password($user_id, $new_pass_key)
    {
        if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0))
        {
            return $this->ci->users->can_reset_password(
                    $user_id,
                    $new_pass_key,
                    $this->ci->config->item('forgot_password_expire')
            );
        }
        return FALSE;
    }

    /**
     * Replace user password (forgotten) with a new one (set by user)
     * and return some data about it: user_id, username, new_password, email.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function reset_password($user_id, $new_pass_key, $new_password)
    {
        if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0) AND (strlen($new_password) > 0))
        {

            if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE)))
            {
                // Hash password using phpass
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $hashed_password = $hasher->HashPassword($new_password);

                if ($this->ci->users->reset_password(
                                $user_id,
                                $hashed_password,
                                $new_pass_key,
                                $this->ci->config->item('forgot_password_expire')))
                { // success
                    // Clear all user's autologins
                    $this->ci->load->model('user/user_autologin');
                    $this->ci->user_autologin->clear($user->id);

                    return array(
                        'user_id' => $user_id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'new_password' => $new_password,
                    );
                }
            }
        }
        return NULL;
    }

    /**
     * Change user password (only when user is logged in)
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function change_password($old_pass, $new_pass)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE)))
        {
            // Check if old password correct
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            if ($hasher->CheckPassword($old_pass, $user->password))
            {   // success
                // Hash new password using phpass
                $hashed_password = $hasher->HashPassword($new_pass);

                // Replace old password with new one
                $this->ci->users->change_password($user_id, $hashed_password);
                return TRUE;
            }
            else
            {               // fail
                $this->error = array('old_password' => 'auth_incorrect_password');
            }
        }
        return FALSE;
    }

    /**
     * Change user profile
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function change_profile($pdata)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE)))
        {
            return $this->ci->users->update_profile($user_id, $pdata);
        }
        return FALSE;
    }

    /**
     * Change user email (only when user is logged in) and return some data about user:
     * user_id, username, new_email, new_email_key.
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param	string
     * @param	string
     * @return	array
     */
    public function set_new_email($new_email, $password)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE)))
        {
            // Check if password correct
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            if ($hasher->CheckPassword($password, $user->password))
            {   // success
                $data = array(
                    'user_id' => $user_id,
                    'username' => $user->username,
                    'new_email' => $new_email,
                );

                if ($user->email == $new_email)
                {
                    $this->error = array('email' => 'auth_current_email');
                }
                elseif ($user->new_email == $new_email)
                {  // leave email key as is
                    $data['new_email_key'] = $user->new_email_key;
                    return $data;
                }
                elseif ($this->ci->users->is_email_available($new_email))
                {
                    $data['new_email_key'] = md5(rand() . microtime());
                    $this->ci->users->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
                    return $data;
                }
                else
                {
                    $this->error = array('email' => 'auth_email_in_use');
                }
            }
            else
            {               // fail
                $this->error = array('password' => 'auth_incorrect_password');
            }
        }
        return NULL;
    }

    /**
     * Activate new email, if email activation key is valid.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function activate_new_email($user_id, $new_email_key)
    {
        if ((strlen($user_id) > 0) AND (strlen($new_email_key) > 0))
        {
            return $this->ci->users->activate_new_email(
                    $user_id,
                    $new_email_key);
        }
        return FALSE;
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @param	string
     * @return	bool
     */
    public function delete_user($password)
    {
        $user_id = $this->ci->session->userdata('user_id');

        if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE)))
        {
            // Check if password correct
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            if ($hasher->CheckPassword($password, $user->password))
            {   // success
                $this->ci->users->delete_user($user_id);
                $this->logout();
                return TRUE;
            }
            else
            {               // fail
                $this->error = array('password' => 'auth_incorrect_password');
            }
        }
        return FALSE;
    }

    /**
     * Get error message.
     * Can be invoked after any failed operation such as login or register.
     *
     * @return	string
     */
    public function get_error_message()
    {
        return $this->error;
    }

    /**
     * Save data for user's autologin
     *
     * @param	int
     * @return	bool
     */
    private function create_autologin($user_id)
    {
        $this->ci->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

        $this->ci->load->model('user/user_autologin');
        $this->ci->user_autologin->purge($user_id);

        if ($this->ci->user_autologin->set($user_id, md5($key)))
        {
            set_cookie(array(
                'name' => $this->ci->config->item('autologin_cookie_name'),
                'value' => serialize(array('user_id' => $user_id, 'key' => $key)),
                'expire' => $this->ci->config->item('autologin_cookie_life'),
            ));
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Clear user's autologin data
     *
     * @return	void
     */
    private function delete_autologin()
    {
        $this->ci->load->helper('cookie');
        if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name'), TRUE))
        {
            $data = unserialize($cookie);

            $this->ci->load->model('user/user_autologin');
            $this->ci->user_autologin->delete($data['user_id'], md5($data['key']));

            delete_cookie($this->ci->config->item('autologin_cookie_name'));
        }
    }

    /**
     * Login user automatically if he/she provides correct autologin verification
     *
     * @return	void
     */
    private function autologin()
    {
        if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE))
        {   // not logged in (as any user)
            $this->ci->load->helper('cookie');
            if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name'), TRUE))
            {
                $data = unserialize($cookie);

                if (isset($data['key']) AND isset($data['user_id']))
                {
                    $this->ci->load->model('user/user_autologin');
                    if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key']))))
                    {
                        if (trim(trim($user->name)) == '')
                        {
                            $user->name = $user->username;
                        }
                        // Login user
                        $this->ci->session->set_userdata(array(
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'username' => $user->username,
                            'user_email' => $user->email,
                            'status' => STATUS_ACTIVATED,
                            'user_group' => $user->user_group,
                            'permissions' => $this->ci->permissions_model->get($user->id),
                        ));
                        // Renew users cookie to prevent it from expiring
                        set_cookie(array(
                            'name' => $this->ci->config->item('autologin_cookie_name'),
                            'value' => $cookie,
                            'expire' => $this->ci->config->item('autologin_cookie_life'),
                        ));

                        $this->ci->users->update_login_info(
                                $user->id,
                                $this->ci->config->item('login_record_ip'),
                                $this->ci->config->item('login_record_time'));
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    /**
     * Check if login attempts exceeded max login attempts (specified in config)
     *
     * @param	string
     * @return	bool
     */
    public function is_max_login_attempts_exceeded($login)
    {
        if ($this->ci->config->item('login_count_attempts'))
        {
            $this->ci->load->model('user/user_login_attempts');
            return $this->ci->user_login_attempts->get_attempts_num($this->ci->input->ip_address(), $login)
            >= $this->ci->config->item('login_max_attempts');
        }
        return FALSE;
    }

    /**
     * Increase number of attempts for given IP-address and login
     * (if attempts to login is being counted)
     *
     * @param	string
     * @return	void
     */
    private function increase_login_attempt($login)
    {
        if ($this->ci->config->item('login_count_attempts'))
        {
            if (!$this->is_max_login_attempts_exceeded($login))
            {
                $this->ci->load->model('user/user_login_attempts');
                $this->ci->user_login_attempts->increase_attempt($this->ci->input->ip_address(), $login);
            }
        }
    }

    /**
     * Clear all attempt records for given IP-address and login
     * (if attempts to login is being counted)
     *
     * @param	string
     * @return	void
     */
    private function clear_login_attempts($login)
    {
        if ($this->ci->config->item('login_count_attempts'))
        {
            $this->ci->load->model('user/user_login_attempts');
            $this->ci->user_login_attempts->clear_attempts(
                    $this->ci->input->ip_address(),
                    $login,
                    $this->ci->config->item('login_attempt_expire'));
        }
    }

    /**
     * Login user on the site with Facebook. Return TRUE if email exists, otherwise FALSE.
     *
     * @param	string	(email)
     * @return	bool
     */
    public function login_facebook($email)
    {

        $user = $this->ci->users->get_user_by_email($email);
        if ($user === NULL)
            return FALSE;
        if (trim(trim($user->name)) == '')
        {
            $user->name = $user->username;
        }
        $this->ci->session->set_userdata(array(
            'user_id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'user_email' => $user->email,
            'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
            'user_group' => $user->user_group,
            'permissions' => $this->ci->permissions_model->get($user->id),
        ));
        return TRUE;
    }

}

/* End of file Auth.php */
/* Location: ./application/modules/user/libraries/Auth.php */