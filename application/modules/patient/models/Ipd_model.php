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
            'ip.IpNo', 'ip.OpdNo', 'FName', 'Age', 'Gender', '(REPLACE((ip.department),"_"," ")) department', 'WardNo', 'BedNo',
            'DoAdmission', 'DoDischarge', 'Doctor', 't.diagnosis', 't.procedures', 'ip.status', 'NofDays',
            ' COALESCE(admit_time,"00:00") admit_time', 'COALESCE(discharge_time,"00:00") discharge_time','treatId'
        );
        $user_dept_cond = '';
        if ($this->rbac->is_doctor()) {
            $user_dept_cond = " AND LOWER(t.department) = LOWER('" . $this->rbac->get_user_department() . "')";
        }
        $where_cond = " WHERE 1=1 $user_dept_cond ";
        //$where_cond = " WHERE ip.status='stillin' $user_dept_cond ";
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
                    case 'keyword':
                        $val = strtoupper(str_replace(' ', '_', $val));
                        $where_cond .= " AND ( ip.department like '%$val%' OR t.diagnosis like '%$val%' OR FName LIKE '%$val%' OR 
                            OpdNo like '%$val%' OR IpNo like '%$val%') ";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT " . join(',', $columns) . " FROM inpatientdetails ip 
            JOIN ipdtreatment t ON t.ipdno = ip.IpNo  $where_cond order by ip.IpNo desc";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("select * from inpatientdetails ip 
            JOIN ipdtreatment t ON t.ipdno = ip.IpNo where IpNo is not null $user_dept_cond")->num_rows();
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
                JOIN ipdtreatment t JOIN patientdata p $where_cond group by IpNo order by IpNo desc";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('inpatientdetails');
        return $return;
    }
}
