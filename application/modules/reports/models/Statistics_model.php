<?php

class Statistics_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_other_procedure_statistics($start_date, $end_date, $dept = '') {
        $query = "select * from  
                (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from 
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v 
                where selected_date between '$start_date' and '$end_date'";
        $date_list = $this->db->query($query)->result_array();
        $list = array();
        if (!empty($date_list)) {
            $dept_cond = '';
            if ($dept != '1') {
                $dept_cond = ' AND t.department="' . $dept . '"';
            }
            foreach ($date_list as $row) {
                $phy_query = "select '" . $row["selected_date"] . "' as cur_date,count(*) as count_p from other_procedures_treatments pt 
                      JOIN treatmentdata t on pt.treat_id=t.ID 
                      JOIN patientdata p on t.OpdNo=p.OpdNo 
                      WHERE (pt.start_date <= '" . $row["selected_date"] . "' AND pt.end_date >= '" . $row["selected_date"] . "') $dept_cond";
                $treat_count = $this->db->query($phy_query)->row_array();
                array_push($list, $treat_count);
            }
        }
        return $list;
    }
    
    function get_physiotherapy_statistics($start_date, $end_date, $dept = '') {
        $query = "select * from  
                (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from 
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v 
                where selected_date between '$start_date' and '$end_date'";
        $date_list = $this->db->query($query)->result_array();
        $list = array();
        if (!empty($date_list)) {
            $dept_cond = '';
            if ($dept != '1') {
                $dept_cond = ' AND t.department="' . $dept . '"';
            }
            foreach ($date_list as $row) {
                $phy_query = "select '" . $row["selected_date"] . "' as cur_date,count(*) as count_p from physiotherapy_treatments pt 
                      JOIN treatmentdata t on pt.treat_id=t.ID 
                      JOIN patientdata p on t.OpdNo=p.OpdNo 
                      WHERE (pt.start_date <= '" . $row["selected_date"] . "' AND pt.end_date >= '" . $row["selected_date"] . "') $dept_cond";
                $treat_count = $this->db->query($phy_query)->row_array();
                array_push($list, $treat_count);
            }
        }
        return $list;
    }

}
