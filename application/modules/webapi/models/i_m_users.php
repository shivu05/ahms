<?php

/*
 * @author: Shivaraj B 
 * @Date: 07-May-2018
 */

class I_m_users extends CI_Model {

    private $_userTable = 'i_m_users';

    public function __construct() {
        parent::__construct();
    }

    function validate_credentials($creds) {
        $result = $this->db->get_where($this->_userTable, $creds);
        if ($result->num_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

}
