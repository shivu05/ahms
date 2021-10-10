<?php

/**
 * Description of Physiotherapy_treatments
 *
 * @author Abhilasha
 */
class Physiotherapy_treatments extends SHV_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_physiotherapy($conditions, $export_flag = false) {
        $start_date = $conditions['start_date'];
        $end_date = $conditions['end_date'];
        $department_cond = ($conditions['department'] == 1) ? '' : ' AND t.department="' . $conditions['department'] . '"';
        $query = "select pt.OpdNo,CONCAT(coalesce(p.FirstName,''),' ',coalesce(p.LastName)) as name,p.Age,p.gender,t.diagnosis, 
            pt.IpNo,pt.therapy_name,pt.physician,pt.referred_date,ucfirst(REPLACE((t.department),'_',' ')) department,t.deptOpdNo 
            from physiotherapy_treatments pt 
            JOIN treatmentdata t on pt.treat_id=t.ID 
            JOIN patientdata p on t.OpdNo=p.OpdNo
            WHERE pt.referred_date >= '" . $start_date . "' AND pt.referred_date <= '" . $end_date . "' $department_cond";
        return $result = $this->db->query($query)->result_array();
    }

}
