<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usg_model
 *
 * @author hp
 */
class Usg_model extends CI_Model {

    function dashboard_stats() {
        $query = "SELECT count(*) as TOTAL,
            SUM(case when usgDate is NULL then 1 else 0 end) PENDING,
            SUM(case when usgDate is NOT NULL AND refDate is NOT NULL then 1 else 0 end) COMPLETED
            FROM usgregistery";
        return $this->db->query($query)->row_array();
    }

    function get_pending_usgs($conditions, $export_flag = FALSE) {
        $cur_date = date('Y-m-d');
        $return = array();
        $columns = array('u.ID', 'u.OpdNo', 'u.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.Age',
            'p.gender', 'p.address', 'p.deptOpdNo', 'u.usgDate', 'refDate', '(REPLACE((t.department),"_"," ")) as department');

        $where_cond = " WHERE u.OpdNo = p.OpdNo AND u.treatId=t.ID AND (u.usgDate IS NULL OR u.usgDate='$cur_date') ";

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
                    case 'OpdNo':
                        $where_cond .= " AND OpdNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND CONCAT(FirstName,' ',LastName) LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM usgregistery u,
        (SELECT @a:= 0) AS a JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY u.usgDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM usgregistery u JOIN treatmentdata t WHERE u.treatId=t.ID')->num_rows();
        return $return;
    }

    function update($update_data, $id) {
        $this->db->where('ID', $id);
        return $this->db->update('usgregistery', $update_data);
    }

}
