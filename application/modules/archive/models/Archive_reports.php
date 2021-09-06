<?php

class Archive_reports extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('custom_db');
    }

    public function fetch_opd_details($conditions) {
        $custom_db = $this->custom_db->getdatabase($conditions['db']);
        $return = array();
        $columns = array('t.OpdNo', 't.PatType'
            , 't.deptOpdNo', 'CONCAT(FirstName," ",LastName) as name', 'Age', 'gender', 't.AddedBy', 'city', 'address',
            't.Trtment', 't.diagnosis', 'CameOn', 'attndedby', '(REPLACE((t.department),"_"," ")) department', 't.monthly_sid as msd', 't.ID',
            'UPPER(REPLACE((t.department)," ","_")) as ref_dept');

        $where_cond = " WHERE t.OpdNo=p.OpdNo AND CameOn >='" . $conditions['start_date'] . "' AND CameOn <='" . $conditions['end_date'] . "' ";
        $limit = ' ';
        $dept = $conditions['department'];
        $where_cond .= ($dept != 1) ? " AND UPPER(REPLACE(t.department,' ','_')) = '$dept'" : '';
        $query = "SELECT @a:=@a+1 AS serial_number," . implode(',', $columns) . " FROM treatmentdata t, (SELECT @a:= 0) AS a JOIN patientdata p
             $where_cond ORDER BY t.ID ASC";
        $result = $custom_db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['opd_stats'] = $custom_db->query("call get_opd_patients_count('" . $conditions['department'] . "','" . $conditions['start_date'] . "','" . $conditions['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        return $return;
    }

}
