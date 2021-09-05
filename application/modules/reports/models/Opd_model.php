<?php

/**
 * Description of opd_model
 *
 * @author Shivaraj
 */
class Opd_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            'OpdNo', 'FirstName', 'LastName', 'Age', 'gender', 'city', 'occupation', 'address'
        );
        $where_cond = " WHERE 1=1 ";
        $limit = ' LIMIT 100';
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

        $query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('patientdata');
        return $return;
    }

    function get_patient_by_opd($opd) {
        $patient_data = $this->db->get_where('patientdata', array('OpdNo' => $opd));
        return $patient_data->row_array();
    }

    public function store_treatment($action, $form_data, $opdNo = NULL) {
        if ($action == "add") {
            $status = $this->db->insert('treatmentdata', $form_data);
        } else if ($action == "edit") {
            $where = array('OpdNo' => $opdNo);
            $status = $this->db->update('treatmentdata', $form_data, $where);
        }
        return $status;
    }

    function get_opd_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('t.OpdNo', 't.PatType'
            , 't.deptOpdNo', 'CONCAT(FirstName," ",LastName) as name', 'Age', 'gender', 't.AddedBy', 'city', 'address',
            't.Trtment', 't.diagnosis', 'CameOn', 'attndedby', '(REPLACE((t.department),"_"," ")) department', 't.monthly_sid as msd', 't.ID',
            't.department as ref_dept');

        $where_cond = " WHERE t.OpdNo=p.OpdNo AND CameOn >='" . $conditions['start_date'] . "' AND CameOn <='" . $conditions['end_date'] . "' ";
        //$where_cond = " WHERE 1=1 ";

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
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        //$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";

        $query = "SELECT @a:=@a+1 AS serial_number," . implode(',', $columns) . " FROM treatmentdata t, (SELECT @a:= 0) AS a JOIN patientdata p
             $where_cond ORDER BY t.ID ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

}
