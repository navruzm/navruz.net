<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * Users
 *
 * This model represents user authorization data. It operates the following tables:
 * - TABLE -- user account data,
 * - TABLE_PROFILE -- user profiles
 * - TABLE_GROUP -- user groups
 * 
 * @package	Tank_auth
 * @author	Tank
 */
class Users extends CI_Model
{

    private $ci;
    const TABLE = 'users';   // user accounts
    const TABLE_PROFILE = 'user_profiles';
    const TABLE_GROUP = 'user_groups';

    // user profiles

    function __construct()
    {
        parent::__construct();
        $this->ci = get_instance();
    }

    /**
     * Get user profile
     *
     * @param	int
     */
    function get_user_profile($user_id)
    {
        $this->db->select('*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->where('user_id', $user_id);
        //if (!is_null($activated)) $this->db->where('activated', $activated ? 1 : 0);

        $query = $this->db->get(self::TABLE_PROFILE);
        if ($query->num_rows() == 1)
            return $query->row_array();
        return NULL;
    }

    /**
     * Get user record by Id
     *
     * @param	int
     * @param	bool
     * @return	object
     */
    function get_user_by_id($user_id, $activated = NULL)
    {
        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->where('u.id', $user_id);
        if (!is_null($activated))
            $this->db->where('u.activated', $activated ? 1 : 0);

        $query = $this->db->get();
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Get user record by login (username or email)
     *
     * @param	string
     * @param	bool
     * @return	object
     */
    function get_user_by_login($login, $activated = NULL)
    {

        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->where('LOWER(u.username)=', strtolower($login));
        $this->db->or_where('LOWER(u.email)=', strtolower($login));

        $query = $this->db->get();

        if ($query->num_rows() == 1)
        {
            $row = $query->row();
            if (is_null($activated))
            {
                return $row;
            }
            else
            {
                if ($activated)
                {
                    if ($row->activated == 1)
                        return $row;
                } else
                {
                    if ($row->activated == 0)
                        return $row;
                }
            }
        }
        return NULL;
    }

    /**
     * Get user record by username
     *
     * @param	string
     * @param	bool
     * @return	object
     */
    function get_user_by_username($username, $activated = NULL)
    {
        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->where('LOWER(u.username)=', strtolower($username));
        if (!is_null($activated))
            $this->db->where('u.activated', $activated ? 1 : 0);

        $query = $this->db->get();
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Get user record by email
     *
     * @param	string
     * @param	bool
     * @return	object
     */
    function get_user_by_email($email, $activated = NULL)
    {
        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->where('LOWER(u.email)=', strtolower($email));
        if (!is_null($activated))
            $this->db->where('u.activated', $activated ? 1 : 0);

        $query = $this->db->get();
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Check if username available for registering
     *
     * @param	string
     * @return	bool
     */
    function is_username_available($username)
    {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(username)=', strtolower($username));
        $query = $this->db->get(self::TABLE);
        return $query->num_rows() == 0;
    }

    /**
     * Check if email available for registering
     *
     * @param	string
     * @return	bool
     */
    function is_email_available($email)
    {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->or_where('LOWER(new_email)=', strtolower($email));
        $query = $this->db->get(self::TABLE);
        return $query->num_rows() == 0;
    }

    /**
     * Create new user record
     *
     * @param	array
     * @param	bool
     * @return	array
     */
    function create_user($data, $activated = TRUE)
    {
        $data['created'] = date('Y-m-d H:i:s');
        $data['activated'] = $activated ? 1 : 0;

        if ($this->db->insert(self::TABLE, $data))
        {
            $user_id = $this->db->insert_id();
            //if ($activated)	$this->create_profile($user_id);
            return array('user_id' => $user_id);
        }
        return NULL;
    }

    /**
     * Activate user if activation key is valid.
     * Can be called for not activated users only.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    function activate_user($user_id, $new_email_key)
    {
        $this->db->select('1', FALSE);
        $this->db->where('id', $user_id);
        $this->db->where('new_email_key', $new_email_key);
        $this->db->where('activated', 0);
        $query = $this->db->get(self::TABLE);

        if ($query->num_rows() == 1)
        {

            $this->db->set('activated', 1);
            $this->db->set('new_email_key', NULL);
            $this->db->where('id', $user_id);
            $this->db->update(self::TABLE);

            //$this->create_profile($user_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Purge table of non-activated users
     *
     * @param	int
     * @return	void
     */
    function purge_na($expire_period = 172800)
    {
        $this->db->where('activated', 0);
        $this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
        $this->db->delete(self::TABLE);
    }

    /**
     * Delete user record
     *
     * @param	int
     * @param	bool
     * @return	bool
     */
    function delete_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->delete(self::TABLE);
        if ($this->db->affected_rows() > 0)
        {
            $this->delete_profile($user_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Set new password key for user.
     * This key can be used for authentication when resetting user's password.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    function set_password_key($user_id, $new_pass_key)
    {
        $this->db->set('new_password_key', $new_pass_key);
        $this->db->set('new_password_requested', date('Y-m-d H:i:s'));
        $this->db->where('id', $user_id);
        $this->db->update(self::TABLE);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param	string
     * @param	string
     * @return	void
     */
    function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
    {
        $this->db->select('1', FALSE);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);
        $query = $this->db->get(self::TABLE);
        return $query->num_rows() == 1;
    }

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param	string
     * @param	string
     * @param	string
     * @param	int
     * @return	bool
     */
    function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
    {
        $this->db->set('password', $new_pass);
        $this->db->set('new_password_key', NULL);
        $this->db->set('new_password_requested', NULL);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);
        $this->db->update(self::TABLE);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Change user password
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    function change_password($user_id, $new_pass)
    {
        $this->db->set('password', $new_pass);
        $this->db->where('id', $user_id);
        $this->db->update(self::TABLE);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @return	bool
     */
    function set_new_email($user_id, $new_email, $new_email_key, $activated)
    {
        $this->db->set($activated ? 'new_email' : 'email', $new_email);
        $this->db->set('new_email_key', $new_email_key);
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);
        $this->db->update(self::TABLE);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    function activate_new_email($user_id, $new_email_key)
    {
        $this->db->set('email', 'new_email', FALSE);
        $this->db->set('new_email', NULL);
        $this->db->set('new_email_key', NULL);
        $this->db->where('id', $user_id);
        $this->db->where('new_email_key', $new_email_key);
        $this->db->update(self::TABLE);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param	int
     * @param	bool
     * @param	bool
     * @return	void
     */
    function update_login_info($user_id, $record_ip, $record_time)
    {
        $this->db->set('new_password_key', NULL);
        $this->db->set('new_password_requested', NULL);

        if ($record_ip)
            $this->db->set('last_ip', $this->input->ip_address());
        if ($record_time)
            $this->db->set('last_login', date('Y-m-d H:i:s'));

        $this->db->where('id', $user_id);
        $this->db->update(self::TABLE);
    }

    /**
     * Ban user
     *
     * @param	int
     * @param	string
     * @return	void
     */
    function ban_user($user_id, $reason = NULL)
    {
        $this->db->where('id', $user_id);
        $this->db->update(self::TABLE, array(
            'banned' => 1,
            'ban_reason' => $reason,
        ));
    }

    /**
     * Unban user
     *
     * @param	int
     * @return	void
     */
    function unban_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update(self::TABLE, array(
            'banned' => 0,
            'ban_reason' => NULL,
        ));
    }

    /**
     * Update user
     *
     * @param	int
     * @param	array
     * @return	bool
     */
    function update_user($user_id, $pdata)
    {
        $this->db->where('id', $user_id);
        $this->db->update(self::TABLE, $pdata);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Create an empty profile for a new user
     *
     * @param	int
     * @return	bool
     */
    function create_profile($pdata)
    {
        //$this->db->set('user_id', $user_id);
        $this->db->insert(self::TABLE_PROFILE, $pdata);
    }

    /**
     * Update profile
     *
     * @param	int
     * @param	array
     * @return	bool
     */
    function update_profile($user_id, $pdata)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update(self::TABLE_PROFILE, $pdata);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Delete user profile
     *
     * @param	int
     * @return	void
     */
    private function delete_profile($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete(self::TABLE_PROFILE);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * List user
     *
     * @param	int
     * @param	int
     * @return	array
     */
    function get_users($per_page, $offset)
    {
        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->where('u.activated', '1');
        $this->db->order_by('u.username', 'asc');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * List admin
     *
     * @param	int
     * @param	int
     * @return	array
     */
    function get_admins($per_page, $offset)
    {
        $this->db->where('user_group', $this->ci->config->item('admin_group'));
        $this->db->where('activated', '1');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get(self::TABLE, $per_page, $offset);
        return $query->result_array();
    }

    /**
     * Count admin
     *
     * @param	int
     * @param	int
     * @return	array
     */
    function count_admin()
    {
        $this->db->where('user_group', $this->ci->config->item('admin_group'));
        $this->db->where('activated', '1');
        $query = $this->db->get(self::TABLE);
        return $query->num_rows();
    }

    public function get_group($group_id)
    {
        $this->db->where('id', $group_id);
        $query = $this->db->get(self::TABLE_GROUP);
        return $query->row_array();
    }

    public function get_groups()
    {
        $query = $this->db->get(self::TABLE_GROUP);
        return $query->result_array();
    }

    public function add_group($sql_data)
    {
        $this->db->insert(self::TABLE_GROUP, $sql_data);
        return $this->db->insert_id();
    }

    public function update_group($group_id, $sql_data)
    {
        $this->db->where('id', $group_id);
        $this->db->update(self::TABLE_GROUP, $sql_data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function delete_group($group_id)
    {
        $this->db->where('id', $group_id);
        $this->db->delete(self::TABLE_GROUP);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function count_group_users($group_id)
    {
        $this->db->where('user_group', $group_id);
        $query = $this->db->get(self::TABLE);
        return $query->num_rows();
    }

    public function update_last_online()
    {
        $this->db->set('last_online', date('Y-m-d H:i:s'));
        $this->db->where('id', $this->ci->session->userdata('user_id'));
        $this->db->update(self::TABLE);
    }

    function is_user($username, $activated = TRUE)
    {
        $this->db->where('LOWER(username)=', strtolower($username));
        if (!is_null($activated))
            $this->db->where('activated', $activated ? 1 : 0);

        return (boolean) $this->db->count_all_results(self::TABLE);
    }

    function search($per_page, $offset, $keyword)
    {
        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->like('u.username', $keyword);
        $this->db->or_like('p.first_name', $keyword);
        $this->db->or_like('p.last_name', $keyword);
        $this->db->where('u.activated', '1');
        $this->db->order_by('u.id', 'desc');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    function search_count($keyword)
    {
        $this->db->select('u.*');
        $this->db->select('p.*');
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->like('u.username', $keyword);
        $this->db->or_like('p.first_name', $keyword);
        $this->db->or_like('p.last_name', $keyword);
        $this->db->where('u.activated', '1');
        $this->db->group_by('u.id');
        return $this->db->count_all_results();
    }

    /**
     * Last user
     *
     * @param	int
     * @param	int
     * @return	array
     */
    function get_last_users($limit = 10)
    {
        $this->db->select('u.*');
        $this->db->select('p.*, CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        $this->db->where('u.activated', '1');
        $this->db->order_by('u.created', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_last_seened_users()
    {
        $this->db->select('u.username,u.last_online');
        $this->db->select('CONCAT_WS(\' \', first_name, last_name) AS name',FALSE);
        $this->db->from(self::TABLE . ' as u');
        $this->db->join(self::TABLE_PROFILE . ' as p', 'u.id = p.user_id', 'left');
        //$this->db->where('last_online <', date('Y-m-d H:i:s', now() - 300));
        if (is_user ())
            $this->db->where_not_in('u.id', get_user_id());
        $this->db->where('u.last_online >', '0000-00-00 00:00:00');
        $this->db->where('u.activated', '1');
        $this->db->order_by('u.last_online', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }

    function is_email_exists($email)
    {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->or_where('LOWER(new_email)=', strtolower($email));
        $query = $this->db->get(self::TABLE);
        return $query->num_rows() > 0;
    }

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */