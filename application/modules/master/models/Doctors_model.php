<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Doctors_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_week_days() {
        return $this->db->get('week_days')->result_array();
    }

    function get_doctors_by_department($dept = '') {
        $this->db->where('user_department', $dept);
        return $this->db->get('users')->result_array();
    }

    function get_doctos_duty_list($conditions, $export_flag = false) {
        $return = array();
        $columns = array(
            'd.id', 'd.doc_id', 'week_id', 'week_day', 'u.ID as user_id', 'u.user_name',
            'u.user_email', '(REPLACE(ucfirst(u.user_department),"_"," ")) as user_department', 'added_date'
        );

        $where_cond = " WHERE u.ID != 1 AND u.active=1";

        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
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
                    case 'day':
                        $where_cond .= " AND UPPER(w.week_day)=UPPER('$val')";
                        break;
                    case 'name':
                        $where_cond .= " AND user_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM doctorsduty d 
            JOIN users u on d.doc_id=u.id 
            JOIN i_user_roles ur ON u.id=ur.user_id 
            JOIN week_days w ON w.week_id=d.day,
        (SELECT @a:= 0) AS a  $where_cond ORDER BY user_department,week_id";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM doctorsduty d JOIN users u on d.doc_id=u.id JOIN i_user_roles ur ON u.id=ur.user_id JOIN week_days w ON w.week_id=d.day WHERE u.ID !=1 AND u.active=1')->num_rows();

        return $return;
    }

    function save_doctors_duty($post_values) {
        extract($post_values);
        $where = array(
            'doc_id' => $doctor_name,
            'day' => $week_day
        );
        $result = $this->db->get_where('doctorsduty', $where);
        if ($result->num_rows() == 0) {
            return $this->db->insert('doctorsduty', $where);
        } else {
            return 'exists';
        }
    }

    function delete_doctors_duty($id = NULL) {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->delete('doctorsduty');
        }
        return FALSE;
    }

    function edit_doctors_duty($post_values) {
        //$this->db->trans_start();
        $this->db->where('id', $post_values['edit_duty_id']);
        return $this->db->update('doctorsduty', array('day' => $post_values['week_day']));
        //$this->db->trans_complete();
    }

}
