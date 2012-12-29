<?php
define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', FALSE);

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

class Auth
{

    private $ci;
    private $db;
    private $error = array();
    private $autologin_expire = 8640000;
    private $max_login_attemps = 10;

    public function __construct()
    {
        $this->ci = get_instance();
        $this->ci->load->library('session');
        $this->ci->load->helper('cookie');
        $this->ci->load->helper('language');
        $this->ci->load->helper('user/user');
        $this->ci->load->language('user/auth');
        $this->ci->load->config('user/auth');
        $this->db = $this->ci->mongo_db;
        if (!$this->is_user())
        {
            $this->_autologin();
        }
    }

    public function create($email, $password, $data=array(), $activate=FALSE)
    {
        /*
         * @todo add indexes
         */
        if ($this->email_check($email) === FALSE)
        {
            include_once 'PasswordHash.php';
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $hashed_password = $hasher->HashPassword($password);
            $data = array_merge($data, array(
                'email' => $email,
                'password' => $hashed_password,
                'created_at' => new MongoDate()
                    ));
            if ($activate === FALSE)
            {
                $data['active'] = 0;
                $data['activation'] = array(
                    'key' => substr(md5(uniqid(rand())), 0, 16),
                    'expire_date' => new MongoDate(time() + 864000)
                );
            }
            else
            {
                $data['active'] = 1;
            }
            if ($this->db->user->insert($data))
            {
                $data['site_name'] = 'localhost';
                if ($activate === FALSE)
                {
                    $this->_send_email('activate', $data);
                }
                return TRUE;
            }
        }
        else
        {
            $this->error = array('email' => 'auth_email_in_use');
        }
        return FALSE;
    }

    public function update($user_id, $data=array())
    {
        if (!count($data))
        {
            return FALSE;
        }
        $data['updated_at'] = new MongoDate();

        if (isset($data['password']) && $data['password'] != '')
        {
            include_once 'PasswordHash.php';
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $data['password'] = $hasher->HashPassword($data['password']);
        }
        else
        {
            unset($data['password']);
        }
        return $this->db->user->update(array(
                    '_id' => new MongoId($user_id)), array(
                    '$set' => $data));
    }

    public function delete($user_id)
    {
        return $this->db->user->remove(array('_id' => new MongoId($user_id)));
    }

    public function activate($id, $key)
    {
        if ($this->_can_activate($id, $key))
        {
            if ($this->db->user->update(array(
                        '_id' => new MongoId($id)), array(
                        '$unset' => array('activation' => 1),
                        '$set' => array('active' => 1)
                    )))
            {
                return $this->ci->lang->line('auth_message_activation_completed');
            }
            else
            {
                return $this->ci->lang->line('auth_message_activation_failed');
            }
        }
    }

    private function _can_activate($id, $key)
    {
        $result = $this->db->user->findOne(array(
            '_id' => new MongoId($id),
            'activation.expire_date' => array('$gt' => new MongoDate()),
            'activation.key' => $key
                ));
        return ($result === NULL) ? FALSE : TRUE;
    }

    public function login($email, $password, $remember=FALSE)
    {

        include_once 'PasswordHash.php';
        $user = $this->db->user->findOne(array('email' => $email));
        if (!is_null($user))
        {
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            if ($hasher->CheckPassword($password, $user['password']))
            {
                $this->_set_user_login((string) $user['_id']);
                if ($remember)
                {
                    $this->_create_autologin((string) $user['_id']);
                }
                $this->_purge_login_attemps();
                return TRUE;
            }
            else
            {
                $this->error = array('password' => 'auth_incorrect_password');
                $this->_increase_login_attemp();
            }
        }
        else
        {
            $this->error = array('email' => 'auth_incorrect_email');
            $this->_increase_login_attemp();
        }

        return FALSE;
    }

    private function _set_user_login($user_id)
    {
        $this->db->user->update(array('_id' => new MongoID($user_id)), array('$set' => array('last_login' => new MongoDate(), 'last_ip' => $this->ci->input->ip_address())));
        $this->ci->session->set_userdata('loggen_in', TRUE);
        $this->ci->session->set_userdata('user_id', (string) $user_id);
    }

    private function _autologin()
    {
        if (!$this->is_user())
        {
            $cookie = get_cookie('autologin', TRUE);
            if ($cookie)
            {
                $data = unserialize($cookie);
                if (isset($data['key']) AND isset($data['user']))
                {
                    $user = $this->db->user_autologin->findOne(array(
                        'user' => $data['user'],
                        'key' => md5($data['key']),
                        'ip' => $this->ci->input->ip_address(),
                        'user_agent' => $this->ci->input->user_agent(),
                        'expire_date' => array('$gt' => new MongoDate())
                            ));
                    if (!is_null($user))
                    {
                        $this->_set_user_login((string) $user['user']);
                        set_cookie(array('name' => 'autologin', 'value' => $cookie, 'expire' => $this->autologin_expire));
                        $this->db->user_autologin->update(array(
                            'user' => $data['user']), array(
                            '$set' => array('expire_date' => new MongoDate(time() + $this->autologin_expire))
                        ));
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    private function _create_autologin($user_id)
    {
        /*
         * @todo add indexes
         */
        $key = substr(md5(uniqid(rand() . get_cookie('autologin'))), 0, 16);
        $this->db->user_autologin->remove(array('user' => $user_id));

        if ($this->db->user_autologin->insert(array(
                    'user' => $user_id,
                    'key' => md5($key),
                    'ip' => $this->ci->input->ip_address(),
                    'user_agent' => $this->ci->input->user_agent(),
                    'expire_date' => new MongoDate(time() + $this->autologin_expire)
                )))
        {
            set_cookie(array(
                'name' => 'autologin',
                'value' => serialize(array('user' => $user_id, 'key' => $key)),
                'expire' => $this->autologin_expire,
            ));
            return TRUE;
        }
        return FALSE;
    }

    private function _delete_autologin()
    {
        $cookie = get_cookie('autologin', TRUE);
        if ($cookie)
        {
            $data = unserialize($cookie);
            $this->db->user_autologin->remove(array(
                '$or' => array(
                    array(
                        'user' => $data['user']),
                    array(
                        'expire_date' => array(
                            '$lt' => new MongoDate(time() - $this->autologin_expire)
                    ))
                    )));
            delete_cookie('autologin');
        }
    }

    public function logout()
    {
        $this->_delete_autologin();
        $this->ci->session->sess_destroy();
    }

    private function _increase_login_attemp()
    {
        /*
         * @todo add expire_date and ip index
         */

        $this->db->user_login_attemps->update(array(
            'ip' => $this->ci->input->ip_address()
                ), array(
            '$inc' => array('count' => 1),
            '$set' => array('expire_date' => new MongoDate(time() + 86400))
                ), array('upsert' => TRUE));
    }

    public function is_max_login_attempts_exceeded()
    {
        $result = $this->db->user_login_attemps->findOne(array(
            'ip' => $this->ci->input->ip_address(),
            'expire_date' => array('$gt' => new MongoDate())
                ), array('count'));
        if ($result['count'] > $this->max_login_attemps)
        {
            return TRUE;
        }
        return FALSE;
    }

    private function _purge_login_attemps()
    {
        return $this->db->user_login_attemps->remove(array(
                    '$or' => array(
                        array('ip' => $this->ci->input->ip_address()),
                        array('expire_date' => array(
                                '$lt' => new MongoDate()
                        )))));
    }

    public function forgot_password($email)
    {
        $user = $this->db->user->findOne(array('email' => $email));
        if (!is_null($user))
        {
            $key = substr(md5(uniqid($sessid, TRUE)), 0, 24);
            $this->db->user->update(array(
                'email' => $email), array(
                '$set' => array('forgot_password' => array(
                        'expire_date' => new MongoDate(time() + 86400),
                        'key' => $key
                ))
            ));
            $data = array(
                'key' => $key,
                'site_name' => 'localhost',
                'email' => $email,
                '_id' => $user['_id']
            );
            return $this->_send_email('forgot_password', $data);
        }
        else
        {
            $this->error = array('email' => 'auth_incorrect_email');
        }
        return FALSE;
    }

    public function can_reset_password($id, $key)
    {
        $result = $this->db->user->findOne(array(
            '_id' => new MongoId($id),
            'forgot_password.expire_date' => array('$gt' => new MongoDate()),
            'forgot_password.key' => $key
                ));
        return ($result === NULL) ? FALSE : TRUE;
    }

    public function reset_password($password, $id, $key)
    {
        if ($this->can_reset_password($id, $key))
        {
            include_once 'PasswordHash.php';
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            return $this->db->user->update(array(
                        '_id' => new MongoId($id)), array(
                        '$unset' => array('forgot_password' => 1),
                        '$set' => array('password' => $hasher->HashPassword($password))
                    ));
        }
    }

    /*
     * 
     */

    public function is_user()
    {
        return (bool) $this->ci->session->userdata('loggen_in');
    }

    public function is_admin()
    {
        if(!$this->is_user())
        {
            return FALSE;
        }
        return isset($this->ci->userdata['permissions'][':all:']) ? TRUE : FALSE;
    }

    public function email_check($email)
    {
        return ($this->db->user->findOne(array('email' => $email)) === NULL) ? FALSE : TRUE;
    }

    private function _send_email($type, $data)
    {
        $this->ci->load->library('email');
        return $this->ci->email->set_newline("\r\n")
                        ->from('navruz@navruz.net', $data['site_name'])
                        ->to($data['email'])
                        ->subject(sprintf($this->ci->lang->line('auth_subject_' . $type), $data['site_name']))
                        ->message($this->ci->load->view('email/' . $type, $data, TRUE))
                        ->send();
    }

    public function get_error_messages($array=FALSE)
    {
        $errors = array();
        foreach ($this->error as $error => $message)
        {
            $errors[$error] = $this->ci->lang->line($message);
        }
        return ($array) ? $errors : implode('<br/>', $errors);
    }

    public function get_errors()
    {
        return $this->error;
    }

    public function get_user($id)
    {
        return $this->db->user->findOne(array('_id' => new MongoID($id)));
    }

    

}