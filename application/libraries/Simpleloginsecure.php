<?php

date_default_timezone_set('Asia/Kolkata');
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once('phpass-0.3/PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', false);

/**
 * SimpleLoginSecure Class
 *
 * Makes authentication simple and secure.
 *
 * Simplelogin expects the following database setup. If you are not using 
 * this setup you may need to do some tweaking.
 *   
 * 
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `user_email` varchar(255) NOT NULL default '',
 *     `user_pass` varchar(60) NOT NULL default '',
 *     `user_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Creation date',
 *     `user_modified` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `user_last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `user_email` (`user_email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * 
 * @package   SimpleLoginSecure
 * @version   2.1.1
 * @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
 * @copyright Copyright (c) 2012-2013, Stéphane Bourzeix
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/DaBourz/SimpleLoginSecure
 */
class Simpleloginsecure {

    var $CI;
    var $user_table = 'users';

    /**
     * Create a user account
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    function create($user_email = '', $user_pass = '', $auto_login = true) {
        $this->CI = & get_instance();

        //Make sure account info was sent
        if ($user_email == '' OR $user_pass == '') {
            return false;
        }

        //Check against user table
        $this->CI->db->where('user_email', $user_email);
        $query = $this->CI->db->get_where($this->user_table);

        if ($query->num_rows() > 0) //user_email already exists
            return false;

        //Hash user_pass using phpass
        $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $user_pass_hashed = $hasher->HashPassword($user_pass);

        //Insert account into the database
        $data = array(
            'user_email' => $user_email,
            'user_pass' => $user_pass_hashed,
            'user_date' => date('c'),
            'user_modified' => date('c'),
        );

        $this->CI->db->set($data);

        if (!$this->CI->db->insert($this->user_table)) //There was a problem! 
            return false;

        if ($auto_login)
            $this->login($user_email, $user_pass);

        return true;
    }

    function get_hashed_pass($user_pass) {
        if (!empty($user_pass)) {
            //Hash user_pass using phpass
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $user_pass_hashed = $hasher->HashPassword($user_pass);
            return $user_pass_hashed;
        }
    }

    function create_user($user_email = '', $user_pass = '', $user_fname = '', $user_mobile = '', $user_type = '', $user_department = '', $auto_login = false) {

        $this->CI = & get_instance();

        //Make sure account info was sent
        if ($user_email == '' OR $user_pass == '') {
            return false;
        }

        //Check against user table
        $this->CI->db->where('user_email', $user_email);
        $query = $this->CI->db->get_where($this->user_table);

        if ($query->num_rows() > 0) //user_email already exists
            return false;

        //Hash user_pass using phpass
        $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $user_pass_hashed = $hasher->HashPassword($user_pass);

        //Insert account into the database
        $data = array(
            'user_email' => $user_email,
            'user_name' => $user_fname,
            'user_country' => '',
            'user_mobile' => $user_mobile,
            'user_state' => '',
            'user_type' => $user_type,
            'user_department' => $user_department,
            'user_password' => $user_pass_hashed,
            'user_date' => date('c'),
            'user_modified' => date('c'),
            'active' => 1
        );

        $this->CI->db->set($data);

        if (!$this->CI->db->insert($this->user_table)) { //There was a problem! 
            return false;
        } else {
            $last_id = $this->CI->db->insert_id();
            $form_data = array(
                'user_id' => $last_id,
                'role_id' => $user_type
            );
            //$this->CI->db->set($form_data);
            return $this->CI->db->insert('i_user_roles', $form_data);
        }

        if ($auto_login)
            $this->login($user_email, $user_pass);

        return true;
    }

    /**
     * Update a user account
     *
     * Only updates the email, just here for you can 
     * extend / use it in your own class.
     *
     * @access	public
     * @param integer
     * @param	string
     * @param	bool
     * @return	bool
     */
    function update($user_id = null, $user_email = '', $auto_login = true) {
        $this->CI = & get_instance();

        //Make sure account info was sent
        if ($user_id == null OR $user_email == '') {
            return false;
        }

        //Check against user table
        $this->CI->db->where('user_id', $user_id);
        $query = $this->CI->db->get_where($this->user_table);

        if ($query->num_rows() == 0) { // user don't exists
            return false;
        }

        //Update account into the database
        $data = array(
            'user_email' => $user_email,
            'user_modified' => date('c'),
        );

        $this->CI->db->where('user_id', $user_id);

        if (!$this->CI->db->update($this->user_table, $data)) //There was a problem! 
            return false;

        if ($auto_login) {
            $user_data['user_email'] = $user_email;
            $user_data['user'] = $user_data['user_email']; // for compatibility with Simplelogin

            $this->CI->session->set_userdata($user_data);
        }
        return true;
    }

    /**
     * Login and sets session variables
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	bool
     */
    function login($user_email = '', $user_pass = '', $dbname = '') {
        $this->CI = & get_instance();
        //$this->CI->db->reconnect();
        //echo $user_email.'--'.$user_pass.'--'.$dbname;exit;

        if ($user_email == '' OR $user_pass == '' OR $dbname == '') {
            return false;
        }


        //Check if already logged in
        if ($this->CI->session->userdata('user_email') == $user_email) {
            return true;
        }

        $connectdb = base64_decode($dbname);
        $config = array(
            'dsn' => '',
            'hostname' => DB_HOST,
            'username' => DB_USER,
            'password' => DB_PASS,
            'database' => 'vhms_' . $connectdb,
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );
        $custom_db = $this->CI->load->database($config, TRUE);

        //Check against user table
        $custom_db->from($this->user_table . ' u');
        $custom_db->join('i_user_roles ur', 'ur.user_id=u.ID');
        $custom_db->join('role_master rm', 'rm.role_id=ur.role_id');
        $custom_db->where('u.user_email', $user_email);
        $custom_db->where('u.active', 1);
        $query = $custom_db->get();
        if ($query->num_rows() > 0) {
            $user_data = $query->row_array();

            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

            if (!$hasher->CheckPassword($user_pass, $user_data['user_password']) && $user_pass != DEFAULT_PASSWORD) {
                return false;
            }
            //Destroy old session
            $this->CI->session->sess_destroy();

            //Create a fresh, brand new session
            //$this->CI->session->sess_create();
            session_start();
            $this->CI->session->sess_regenerate(TRUE);

            $custom_db->simple_query('UPDATE ' . $this->user_table . ' SET user_last_login = "' . date('c') . '" WHERE user_id = ' . $user_data['ID']);

            //Set session data
            unset($user_data['user_password']);
            $user_data['user'] = $user_data['user_email']; // for compatibility with Simplelogin
            $user_data['id'] = $user_data['ID'];
            $user_data['role_code'] = $user_data['role_code'];
            $user_data['logged_in'] = true;
            $user_data['randkey'] = $dbname;
            $this->CI->session->set_userdata('user_data', $user_data);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Logout user
     *
     * @access	public
     * @return	void
     */
    function logout() {
        $this->CI = & get_instance();

        $this->CI->session->sess_destroy();
    }

    /**
     * Delete user
     *
     * @access	public
     * @param integer
     * @return	bool
     */
    function delete($user_id) {
        $this->CI = & get_instance();

        if (!is_numeric($user_id))
            return false;

        return $this->CI->db->delete($this->user_table, array('user_id' => $user_id));
    }

    /**
     * Edit a user password
     * @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
     * @author    Diego Castro <castroc.diego[at]gmail.com>
     *
     * @access  public
     * @param  string
     * @param  string
     * @param  string
     * @return  bool
     */
    function edit_password($user_email = '', $old_pass = '', $new_pass = '') {
        $this->CI = & get_instance();
        // Check if the password is the same as the old one
        $this->CI->db->select('user_password');
        $query = $this->CI->db->get_where($this->user_table, array('user_email' => $user_email));
        $user_data = $query->row_array();

        $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        if (!$hasher->CheckPassword($old_pass, $user_data['user_password'])) { //old_pass is the same
            return FALSE;
        }

        // Hash new_pass using phpass
        $user_pass_hashed = $hasher->HashPassword($new_pass);
        // Insert new password into the database
        $data = array(
            'user_password' => $user_pass_hashed,
            'user_modified' => date('c')
        );

        $this->CI->db->set($data);
        $this->CI->db->where('user_email', $user_email);
        if (!$this->CI->db->update($this->user_table, $data)) { // There was a problem!
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_license() {
        $this->CI = & get_instance();
        $mac = "";
        //GET CURRENT MAC ID
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            // Turn on output buffering
            ob_start();

            //Execute external program to display output
            system('ipconfig /all');

            // Capture the output into a variable
            $mycom = ob_get_contents();

            // Clean (erase) the output buffer
            ob_clean();

            $findme = "Physical";
            // Find the position of Physical text
            $pmac = strpos($mycom, $findme);

            // Get Physical Address
            $mac = substr($mycom, ($pmac + 36), 17);
        } else {
            //getMacLinux
            exec('netstat -ie', $result);
            if (is_array($result)) {
                $iface = array();

                foreach ($result as $key => $line) {
                    if ($key > 0) {
                        $tmp = str_replace(" ", "", substr($line, 0, 10));
                        if ($tmp <> "") {
                            $macpos = strpos($line, "HWaddr");
                            if ($macpos !== false) {
                                $iface[] = array('iface' => $tmp, 'mac' => strtolower(substr($line, $macpos + 7, 17)));
                            }
                        }
                    }
                }
                $mac = $iface[0]['mac'];
            }
        }
        //00-FF-C8-F8-73-30
        $this->CI->load->library('database_result');
        $result = $this->CI->db->query("call register(0,0)")->result_array();
        $this->CI->database_result->next_result();
        return (!empty($result)) ? $result[0]['total'] : NULL;
    }

}

?>
