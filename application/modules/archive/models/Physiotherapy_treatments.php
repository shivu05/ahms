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

    function get_physiotherapy($db, $conditions, $export_flag = false) {
        $query = "select pt.OpdNo,CONCAT(coalesce(p.FirstName,''),' ',coalesce(p.LastName)) as name,p.Age,p.gender,t.diagnosis, 
            pt.IpNo,pt.therapy_name,pt.physician,'" . $conditions['end_date'] . "' as referred_date,ucfirst(REPLACE((t.department),'_',' ')) department,t.deptOpdNo 
            from physiotherapy_treatments pt 
            JOIN treatmentdata t on pt.treat_id=t.ID 
            JOIN patientdata p on t.OpdNo=p.OpdNo
            WHERE (pt.start_date <= '" . $conditions['start_date'] . "' AND pt.end_date >= '" . $conditions['end_date'] . "')";
        return $result = $db->query($query)->result_array();
    }

}
