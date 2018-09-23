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
        $where_cond = " WHERE status='stillin' ";
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
        $return['total_rows'] = $this->db->count_all('inpatientdetails');
        return $return;
    }

}
