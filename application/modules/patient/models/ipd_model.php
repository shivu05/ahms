<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ipd_model
 *
 * @author Shivaraj
 */
class Ipd_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            'IpNo', 'OpdNo', 'FName', 'Age', 'Gender', 'department', 'WardNo', 'BedNo', 'DoAdmission', 'Doctor'
        );
        $user_dept_cond = '';
        if ($this->rbac->is_doctor()) {
            $user_dept_cond = " AND LOWER(department) = LOWER('" . display_department($this->rbac->get_user_department()) . "')";
        }
        $where_cond = " WHERE status='stillin' $user_dept_cond ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'OpdNo':
                        $where_cond .= " AND OpdNo='$val'";
                        break;
                    case 'IpNo':
                        $where_cond .= " AND IpNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND FName LIKE '%$val%'";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT " . join(',', $columns) . " FROM inpatientdetails $where_cond order by IpNo desc";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("select * from inpatientdetails where IpNo is not null $user_dept_cond")->num_rows();
        return $return;
    }

    public function get_indent_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            'IpNo', 'ip.OpdNo', 'FName', 'p.Age', 'p.Gender', 'ip.department', 't.diagnosis', 't.ID', 'ip.DoAdmission', 'ip.Doctor'
        );

        $where_cond = " WHERE t.ipdno = ip.IpNo AND ip.OpdNo = p.OpdNo AND t.status ='nottreated'";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'OpdNo':
                        $where_cond .= " AND ip.OpdNo='$val'";
                        break;
                    case 'IpNo':
                        $where_cond .= " AND IpNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND FName LIKE '%$val%'";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . " FROM inpatientdetails  ip 
                JOIN ipdtreatment t JOIN patientdata p $where_cond order by IpNo desc";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('inpatientdetails');
        return $return;
    }

}
