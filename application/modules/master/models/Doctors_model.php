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
            'u.user_email', 'u.user_department as user_dept', '(REPLACE(u.user_department,"_"," ")) as user_department',
            'added_date'
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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(u.user_department,' ','_')) = '$val'" : '';
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
        if ($export_flag) {
            
        }
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM doctorsduty d JOIN users u on d.doc_id=u.id JOIN i_user_roles ur ON u.id=ur.user_id JOIN week_days w ON w.week_id=d.day WHERE u.ID !=1 AND u.active=1')->num_rows();

        return $return;
    }

    function save_doctors_duty($post_values) {
        extract($post_values);
        if (!empty($week_day)) {
            foreach ($week_day as $day) {
                $where = array(
                    'doc_id' => $doctor_name,
                    'day' => $day
                );
                $result = $this->db->get_where('doctorsduty', $where);
                if ($result->num_rows() == 0) {
                    $this->db->insert('doctorsduty', $where);
                }
            }
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
        return $this->db->update('doctorsduty', array('day' => $post_values['week_day'], 'doc_id' => $post_values['doctor_name']));

        //$this->db->trans_complete();
    }

    function fetch_roles() {
        return $this->db->where("role_code != 'ADMIN'")->get("role_master")->result_array();
    }

    function fetch_doctors_duty($conditions) {
        $department_cond = ($conditions['department'] == 1) ? '' : ' AND d.dept_unique_code="' . $conditions['department'] . '"';
        $query = "SELECT user_name,user_email,trim(dept_unique_code) user_department,day,week_day 
            FROM doctorsduty dt 
            JOIN users u ON dt.doc_id=u.id 
            JOIN week_days w ON dt.day=w.week_id 
            JOIN deptper d ON u.user_department=d.dept_unique_code
            WHERE u.ID != 1 AND u.active=1 $department_cond
            order by user_department,week_id asc";
//       // echo $query;exit;
//        $query = "SELECT d.id,
//            user_name,user_department 
//            FROM users u 
//            JOIN doctorsduty d ON u.ID=d.doc_id 
//            WHERE u.user_type=4 and user_department='BALAROGA'";
        return $result = $this->db->query($query)->result_array();
    }

}
