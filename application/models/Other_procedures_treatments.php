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
        $start_date = $conditions['start_date'];
        $end_date = $conditions['end_date'];
        $department_cond = ($conditions['department'] == 1) ? '' : ' AND t.department="' . $conditions['department'] . '"';
        $query = "select pt.id,pt.OpdNo,CONCAT(coalesce(p.FirstName,''),' ',coalesce(p.LastName)) as name,p.Age,p.gender,t.diagnosis, 
            pt.IpNo,pt.therapy_name,pt.physician,'" . $conditions['end_date'] . "' as referred_date,ucfirst(REPLACE((t.department),'_',' ')) department,t.deptOpdNo 
            from other_procedures_treatments pt 
            JOIN treatmentdata t on pt.treat_id=t.ID 
            JOIN patientdata p on t.OpdNo=p.OpdNo
            WHERE (pt.start_date <= '" . $conditions['start_date'] . "' AND pt.end_date >= '" . $conditions['end_date'] . "')";
        return $result = $this->db->query($query)->result_array();
    }

}
