<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ecg_model
 *
 * @author hp
 */
class Ecg_model extends CI_Model {

    function dashboard_stats() {
        $query = "SELECT count(*) as TOTAL,
            SUM(case when ecgDate is NULL then 1 else 0 end) PENDING,
            SUM(case when ecgDate is NOT NULL AND refDate is NOT NULL then 1 else 0 end) COMPLETED
            FROM ecgregistery";
        return $this->db->query($query)->row_array();
    }

    function get_pending_ecgs($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('e.ID', 'e.OpdNo', 'e.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.Age',
            'p.gender', 'p.address', 'p.deptOpdNo', 'refDate', 'e.ecgDate', '(t.department) as department');

        $where_cond = " WHERE e.OpdNo = p.OpdNo AND e.treatId=t.ID AND e.ecgDate IS NULL";

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

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM ecgregistery e,
        (SELECT @a:= 0) AS a JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY e.ecgDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM ecgregistery e JOIN treatmentdata t WHERE e.treatId=t.ID')->num_rows();
        return $return;
    }

    function update($update_data, $id) {
        $this->db->where('ID', $id);
        return $this->db->update('ecgregistery', $update_data);
    }

}
