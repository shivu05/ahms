<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Patient_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save_patient($post_values, $id = null)
    {
        if ($id) {
            $ipd_data = array(
                'FName' => $post_values['FirstName']
            );
            $this->db->update('inpatientdetails', $ipd_data, array('OpdNo' => $id));
            return $this->db->update('patientdata', $post_values, array('OpdNo' => $id));
        }
        return false;
    }

    function get_patient_info($opd = NULL, $ipd = NULL)
    {
        $query = "SELECT OpdNo,UHID, CONCAT(COALESCE(FirstName, ''), ' ', COALESCE(LastName, '')) as name, Age, gender, city, dept, DATE_FORMAT((entrydate), '%d-%m-%Y' ) as entrydate,mob FROM patientdata WHERE OpdNo = '" . $opd . "';";
        $result["opd_data"] = $this->db->query($query)->result_array();
        $query_ipd = "SELECT IpNo,OpdNo,WardNo, BedNo, diagnosis, DATE_FORMAT((DoAdmission), '%d-%m-%Y' ) as DoAdmission, DATE_FORMAT((DoDischarge), '%d-%m-%Y' ) as DoDischarge, DischargeNotes, NofDays, Doctor FROM 
		inpatientdetails WHERE OpdNo='" . $opd . "';";
        $result['ipd_data'] = $this->db->query($query_ipd)->result_array();
        return $result;
    }

    public function insert_patient($data)
    {
        // Insert patient data
        $this->db->insert('patientdata', $data);
        return $this->db->insert_id(); // Return the inserted record ID
    }
}
