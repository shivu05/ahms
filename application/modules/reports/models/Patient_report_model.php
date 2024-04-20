<?php

/**
 * Description of Patient_report_model
 *
 * @author shiva
 */
class Patient_report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_report_stats($treat_id = null) {
        if ($treat_id) {
            $query = "select t.OpdNo,t.ID, ecg.ID as ecg_id,usg.ID as usg_id,xray.ID as xray_id,lab.ID as lab_id,IpNo as ipdno 
                from treatmentdata t  
                LEFT JOIN ecgregistery ecg  ON ecg.treatId=t.ID 
                LEFT JOIN usgregistery usg  ON t.ID=usg.treatId 
                LEFT JOIN xrayregistery xray ON t.ID=xray.treatID 
                LEFT JOIN labregistery lab ON t.ID=lab.treatID 
                LEFT JOIN inpatientdetails i ON t.ID=i.treatId 
                where t.ID='" . $treat_id . "'  
                group by lab.treatID";
            return $this->db->query($query)->row_array();
        }
        return false;
    }

    
}
