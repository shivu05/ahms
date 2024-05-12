<?php

/**
 * Description of Fumigation_register
 *
 * @author shiva
 */
class Fumigation_register extends SHV_Model {

    public function __construct($table = '') {
        parent::__construct($table);
    }

    function get_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('fumigation_mothod', 'chemical_used', 'start_time', 'end_time', 'ot_number', 'neutralization', 'superviser_name',
            'remarks', 'f_date', 'Remarks');

        $where_cond = " WHERE f_date >='" . $conditions['start_date'] . "' AND f_date <='" . $conditions['end_date'] . "'";

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
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM fumigation_register a,
        (SELECT @a:= 0) AS a $where_cond ORDER BY a.id ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM fumigation_register')->num_rows();
        return $return;
    }
}
