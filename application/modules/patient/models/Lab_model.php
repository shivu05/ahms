<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lab_model
 *
 * @author hp
 */
class Lab_model extends CI_Model {

    function dashboard_stats() {
        $query = "SELECT count(*) as TOTAL, 
            SUM(case when tested_date is NULL AND testvalue is NULL then 1 else 0 end) PENDING, 
            SUM(case when tested_date is NOT NULL AND testvalue is NOT NULL then 1 else 0 end) COMPLETED 
            FROM labregistery";
        return $this->db->query($query)->row_array();
    }

    function get_pending_labs($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            'l.ID', 'l.OpdNo', 'refDocName', 'testName', 'testDate', 'treatID', 'testrange', 'testvalue', 'tested_date', 'CONCAT(FirstName," ",LastName) as name', 't.department'
        );

        $cur_date = date('Y-m-d');
        $search_data = $this->input->post('search');
        $search = (isset($search_data)) ? 'AND l.OpdNo like "%' . $search_data['value'] . '%"' : '';
        $where_cond = " WHERE (tested_date is NULL) OR ( testrange is NULL AND testvalue IS NULL) OR (testDate='$cur_date') $search"; //xrayDate is NULL AND filmSize is NULL
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
                    case 'name':
                        $where_cond .= " AND CONCAT(FirstName,' ',LastName) LIKE '%$val%'";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . " FROM labregistery l JOIN patientdata p ON p.OpdNo=l.OpdNo 
           JOIN treatmentdata t ON l.treatID=t.ID ,(SELECT @a:= 0) AS a $where_cond order by l.ID DESC ";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('labregistery');
        return $return;
    }

    function update($update_data, $id) {
        $this->db->where('ID', $id);
        return $this->db->update('labregistery', $update_data);
    }

}
