<?php

/**
 * Description of Xray_model
 *
 * @author hp
 */
class Xray_model extends CI_Model {

    function dashboard_stats() {
        $query = "SELECT count(*) as TOTAL, 
            SUM(case when xrayDate is NULL AND filmSize is NULL then 1 else 0 end) PENDING, 
            SUM(case when xrayDate is NOT NULL AND filmSize is NOT NULL then 1 else 0 end) COMPLETED 
            FROM xrayregistery";
        return $this->db->query($query)->row_array();
    }

    function get_pending_xrays($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            'x.ID', 'x.OpdNo', 'xrayNo', 'partOfXray', 'filmSize', 'refDocName', 'xrayDate', 'treatID', 'refDate', 'CONCAT(FirstName," ",LastName) as name', 't.department'
        );

        $search_data = $this->input->post('search');
        $search = (isset($search_data)) ? 'AND x.OpdNo like "%' . $search_data['value'] . '%"' : '';
        $where_cond = " WHERE xrayDate is NULL AND filmSize is NULL $search"; //xrayDate is NULL AND filmSize is NULL
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

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . " FROM xrayregistery x JOIN patientdata p ON p.OpdNo=x.OpdNo 
           JOIN treatmentdata t ON x.treatID=t.ID ,(SELECT @a:= 0) AS a $where_cond order by x.ID DESC ";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('xrayregistery');
        return $return;
    }

    function update($update_data, $id) {
        $this->db->where('ID', $id);
        return $this->db->update('xrayregistery', $update_data);
    }

}
