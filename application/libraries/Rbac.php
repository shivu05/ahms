<?php

/*
 * @author: Himansu 
 * @Updated by: Shivaraj B
 */

class Rbac {

    private $_session;
    private $_ci;

    public function __construct() {
        $this->_ci = & get_instance();
        $this->_session = $this->_ci->session->userdata();
        //pma($this->_session['user_data'], 1);
    }

    public function is_login() {
        if (isset($this->_session['user_data']['ID'])) {
            return $this->_session['user_data']['ID'];
        }
        return 0;
    }

    public function get_name() {
        if (isset($this->_session['user_data']['user_name'])) {
            return $this->_session['user_data']['user_name'];
        }
        return 0;
    }

    public function get_email() {
        if (isset($this->_session['user_data']['user_email'])) {
            return $this->_session['user_data']['user_email'];
        }
        return 0;
    }

    public function get_uid() {
        if (isset($this->_session['user_data']['id'])) {
            return $this->_session['user_data']['id'];
        }
        return 0;
    }

    public function get_user_department() {
        if (isset($this->_session['user_data']['user_department'])) {
            return $this->_session['user_data']['user_department'];
        }
        return 0;
    }

    public function is_admin() {
        if ($this->is_login()) {
            //if (in_array('ADMIN', $this->_session['user_data']['role_code'])) {
            if ('ADMIN' == $this->_session['user_data']['role_code']) {
                return 1;
            }
        }
        return 0;
    }

    public function is_sadmin() {
        if ($this->is_login()) {
            //if (in_array('ADMIN', $this->_session['user_data']['role_code'])) {
            if ('SADMIN' == $this->_session['user_data']['role_code']) {
                return 1;
            }
        }
        return 0;
    }

    public function is_doctor() {
        if ($this->is_login()) {
            //if (in_array('ADMIN', $this->_session['user_data']['role_code'])) {
            if ('DOCTOR' == $this->_session['user_data']['role_code']) {
                return 1;
            }
        }
        return 0;
    }

    public function get_logged_in_user_name() {
        if (isset($this->_session['user_data']['ID'])) {
            return $this->_session['user_data']['user_name'];
        }
        return '';
    }

    public function has_role($role_code) {

        if (isset($this->_session['user_data']['role_code']) && $this->_session['user_data']['role_code'] == $role_code) {
            return 1;
        }
        return 0;
    }

    public function get_role_ids() {
        if ($this->is_login()) {
            $role_ids = array_column($this->_session['user_data']['role_code'], 'role_id');
            return $role_ids;
        }
        return 0;
    }

    function in_array_r($needle, $haystack) {
        $found = false;
        foreach ($haystack as $item) {
            if ($item === $needle) {
                $found = true;
                break;
            } elseif (is_array($item)) {
                $found = in_array_r($needle, $item);
                if ($found) {
                    break;
                }
            }
        }
        return $found;
    }

    public function has_permission($perm_code, $access = null) {
        if ($this->is_login()) {
            $perm_code = strtoupper($perm_code);
            $permissions = $this->get_user_permission();
            if ($this->in_array_r($perm_code, $permissions)) {
                if ($access == 'w') {
                    return $this->has_write_access($perm_code);
                } else {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function has_write_access($perm_code) {
        if ($this->is_login()) {
            $perm_code = strtoupper($perm_code);
            $roles = $this->get_role_ids();
            $query = "SELECT access_perm FROM role_perm irp 
                JOIN perm_master ipm ON irp.perm_id=ipm.perm_id 
                WHERE LOWER(ipm.perm_code)=LOWER('$perm_code') AND role_id IN (" . implode(',', $roles) . ");";
            $access = $this->_ci->db->query($query)->row_array();
            if ($access['access_perm'] == 2) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return false;
    }

    /**
     * @method: get_user_permission() 
     * @param: int $status int $user_id
     * @return:  boolean as per result
     * @desc: Function to enable/disable the user
     */
    public function get_user_permission() {
        if ($this->is_login()) {
            $permissions = '';
            $id = $this->get_uid();
            /* $query = "SELECT * FROM i_user_roles iur LEFT JOIN i_m_role imr ON iur.role_id=imr.idvs_role_id 
              LEFT JOIN idvs_role_perm irm ON imr.idvs_role_id=irm.idvs_role_id
              LEFT JOIN idvs_perm_master ipm ON ipm.idvs_perm_id=irm.idvs_perm_id
              WHERE iur.user_id='" . $id . "' AND LOWER(ipm.idvs_perm_status)=LOWER('Active') AND LOWER(irm.idvs_status)=LOWER('Active') order by idvs_perm_order"; */
            $query = "SELECT * FROM i_user_roles iur 
                LEFT JOIN role_master imr ON iur.role_id=imr.role_id 
                LEFT JOIN role_perm irm ON imr.role_id=irm.role_id 
                LEFT JOIN perm_master ipm ON ipm.perm_id=irm.perm_id 
                WHERE iur.user_id='" . $id . "' AND LOWER(ipm.perm_status)=LOWER('Active') 
                    AND LOWER(irm.status)=LOWER('Active') order by perm_order";
            $result = $this->_ci->db->query($query);
            //mysqli_next_result($this->_ci->db->conn_id); //imp
            if ($result->num_rows() > 0) {
                $role_permission_detail = $result->result_array();
                $all_permissions = $this->_merge_duplicate($role_permission_detail);
                $permissions = $this->_tree_view($all_permissions, 0);
            }
            if ($permissions == '') {
                $this->_ci->session->set_flashdata('error', 'No permission assigned you to access the Application, Please contact site Admin.');
                redirect('login');
            }
            return $permissions;
        }
        return 0;
    }

    /**
     * @param  : NA
     * @desc   : generate top menu based on users permission
     * @return : string menu 
     * @author : Shiv
     */
    public function show_user_menu_top($arr_flag = null) {
        if ($this->is_login()) {
            $tree = $this->get_user_permission();
            if ($arr_flag) {
                return $tree;
            }
            $params = array('tree' => $tree, 'rbac_session' => $this->_session);
            $this->_ci->load->library('rbac_menu', $params);
            return $this->_ci->rbac_menu->show_user_menu_top();
        } else {
            redirect('Login');
        }
    }

    function _tree_view($results, $parent_id) {
        $tree = array();
        $counter = sizeof($results);
        $results = array_values($results);
        for ($i = 0; $i < $counter; $i++) {
            if ($results[$i]['perm_parent'] == $parent_id) {
                //echo $results[$i]['idvs_perm_code'];
                if ($this->_has_child($results, $results[$i]['perm_id'])) {
                    $sub_menu = $this->_tree_view($results, $results[$i]['perm_id']);
                    $index = strtoupper($results[$i]['perm_code'] . '_' . $results[$i]['perm_id']);
                    $tree[$index] = $sub_menu;
                    $tree[$index][$index] = $results[$i];
                } elseif ($this->_is_self_parent($results[$i])) {
                    $index = strtoupper($results[$i]['perm_code'] . '_' . $results[$i]['perm_id']);
                    $tree[$index] = array($index => $results[$i]);
                } else {
                    $index = strtoupper($results[$i]['perm_code'] . '_' . $results[$i]['perm_id']);
                    if (count($results[$i]) > 1) {
                        if ($results[$i]['perm_parent'] == 0) {
                            $tree[$index] = $results[$i];
                        } else {
                            $tree[$i] = $results[$i];
                        }
                    } else {
                        $tree[$index] = $results[$i];
                    }
                }
            }
        }
        return $tree;
    }

    private function _has_child($results, $menu_id) {
        $counter = sizeof($results);
        for ($i = 0; $i < $counter; $i++) {
            if ($results[$i]['perm_parent'] == $menu_id) {
                return true;
            }
        }
        return false;
    }

    private function _is_self_parent($results) {
        if ($results['perm_parent'] == 0) {
            return true;
        }
        return false;
    }

    private function _merge_duplicate($data) {
        $result = array();
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $result[$val['perm_code']] = $val;
            }
        }
        return $result;
    }

}
