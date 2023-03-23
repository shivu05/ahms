<?php

/**
 * Description of Other_procedures_treatments
 *
 * @author shivaraj.badiger
 */
class Other_procedures_treatments extends SHV_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_other_procedures($conditions, $export_flag = false) {

        $return = array();
        $columns = array('pt.id', 'pt.OpdNo', "CONCAT(coalesce(p.FirstName,''),' ',coalesce(p.LastName)) as name", 'p.Age', 'p.gender', 't.diagnosis',
            ' pt.IpNo', 'pt.therapy_name', 'pt.physician', '" ' . $conditions['end_date'] . '" as referred_date',
            "ucfirst(REPLACE((t.department), '_', ' ')) department", 't.deptOpdNo');

        $where_cond = " WHERE (pt.start_date <= '" . $conditions['start_date'] . "' AND pt.end_date >= '" . $conditions['end_date'] . "')";

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

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " 
            FROM other_procedures_treatments pt 
            JOIN treatmentdata t on pt.treat_id=t.ID 
            JOIN patientdata p on t.OpdNo=p.OpdNo , (SELECT @a:= 0) AS a $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT k.OpdNo FROM other_procedures_treatments k JOIN treatmentdata t WHERE k.treat_id=t.ID ')->num_rows();
        return $return;
    }

    function update_other_procedures($data, $id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
            return $this->db->update('other_procedures_treatments', array('physician' => $data['physician']));
        }
        return false;
    }

    function get_all_other_procedures() {

        $return = array();
        $columns = array('pt.id', 'pt.OpdNo', "CONCAT(coalesce(p.FirstName,''),' ',coalesce(p.LastName)) as name", 'p.Age', 'p.gender', 't.diagnosis',
            ' pt.IpNo', 'pt.therapy_name', 'pt.physician', 'start_date', 'end_date',
            "ucfirst(REPLACE((t.department), '_', ' ')) department", 't.deptOpdNo');

        $where_cond = "";
        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " 
            FROM other_procedures_treatments pt 
            JOIN treatmentdata t on pt.treat_id=t.ID 
            JOIN patientdata p on t.OpdNo=p.OpdNo , (SELECT @a:= 0) AS a $where_cond";
        $result = $this->db->query($query);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT k.OpdNo FROM other_procedures_treatments k JOIN treatmentdata t WHERE k.treat_id=t.ID ')->num_rows();
        return $return;
    }

}
