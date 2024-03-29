<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_roles() {
        $this->db->where('role_code <>', 'ADMIN');
        return $this->db->get('role_master')->result_array();
    }

    function check_for_dup_email($email, $id = '') {
        $this->db->where('user_email', $email);
        if ($id) {
            $this->db->where('ID != ' . $id . '');
        }
        return $this->db->get('users')->num_rows();
    }

    function save_user_details($post_values) {

        $user_dept = (isset($post_values['user_dept'])) ? $post_values['user_dept'] : '';
        $is_inserted = $this->simpleloginsecure->create_user(trim($post_values['email']), $post_values['user_pass'], trim($post_values['name']), trim($post_values['mobile']), $post_values['user_role'], $user_dept);

        if ($is_inserted) {
            return true;
        } else {
            return FALSE;
        }
    }

    function get_users_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('u.ID', 'u.user_name', 'u.user_email', 'u.user_country', 'u.user_state', 'u.user_mobile',
            'u.user_type', '(REPLACE((u.user_department), "_", " ")) as user_department','u.user_department as department', 'u.user_date', 'u.user_modified', 'u.active', 'rm.role_name'
        );

        $where_cond = " WHERE u.ID != 1 ";

        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ', ' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'role':
                        $where_cond .= " AND ur.role_id='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND user_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    case 'keyword':
                        $val = strtoupper(str_replace(' ', '_', $val));
                        $where_cond .= " AND ( u.user_department like '%$val%' OR user_name like '%$val%' OR user_email like '%$val%') ";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(', ', $columns) . " FROM users u JOIN i_user_roles ur ON u.ID=ur.user_id JOIN role_master rm ON rm.role_id=ur.role_id,
        (SELECT @a:= 0) AS a  $where_cond ORDER BY serial_number ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM users u JOIN i_user_roles ur ON u.ID = ur.user_id JOIN role_master rm ON rm.role_id = ur.role_id WHERE u.ID != 1')->num_rows();

        return $return;
    }

    function update($update, $where) {
        $this->db->where($where);
        return $this->db->update('users', $update);
        //echo $this->db->last_query();
    }

}
