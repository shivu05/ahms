<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Patient_model extends CI_Model
{
    const PATIENT_TABLE = 'patientdata';
    const AADHAAR_COLUMN = 'aadhaar_number';
    const ABHA_COLUMN = 'abha_id';
    const AADHAAR_MASKED_COLUMN = 'aadhaar_masked';

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
        $this->db->insert(self::PATIENT_TABLE, $data);
        return $this->db->insert_id();
    }

    public function get_patient_table_columns()
    {
        return $this->db->list_fields(self::PATIENT_TABLE);
    }

    public function get_missing_identity_columns()
    {
        $required_columns = array(
            self::AADHAAR_COLUMN,
            self::ABHA_COLUMN,
            self::AADHAAR_MASKED_COLUMN
        );

        return array_values(array_diff($required_columns, $this->get_patient_table_columns()));
    }

    public function has_identity_columns()
    {
        return empty($this->get_missing_identity_columns());
    }

    public function has_aadhaar_column()
    {
        return $this->db->field_exists(self::AADHAAR_COLUMN, self::PATIENT_TABLE);
    }

    public function has_aadhaar_access_log_table()
    {
        return $this->db->table_exists('aadhaar_access_log');
    }

    public function check_aadhaar_exists($aadhaar_number)
    {
        if (!$this->has_aadhaar_column()) {
            return false;
        }

        return $this->db->where(self::AADHAAR_COLUMN, $aadhaar_number)
            ->count_all_results(self::PATIENT_TABLE) > 0;
    }

    public function check_aadhaar_exists_for_other_patient($aadhaar_number, $opd_no)
    {
        if (!$this->has_aadhaar_column()) {
            return false;
        }

        return $this->db
            ->where(self::AADHAAR_COLUMN, $aadhaar_number)
            ->where('OpdNo !=', $opd_no)
            ->count_all_results(self::PATIENT_TABLE) > 0;
    }

    public function get_patient_identity_summary($opd_no)
    {
        if (!$this->has_identity_columns()) {
            return false;
        }

        return $this->db
            ->select('OpdNo, FirstName, LastName, aadhaar_masked, abha_id')
            ->where('OpdNo', $opd_no)
            ->get(self::PATIENT_TABLE)
            ->row_array();
    }

    public function get_aadhaar_for_authorized_user($opd_no, $user_id)
    {
        if (!$this->has_identity_columns()) {
            return false;
        }

        if ($this->has_aadhaar_access_log_table()) {
            $this->db->insert('aadhaar_access_log', array(
                'opd_no' => $opd_no,
                'accessed_by' => $user_id,
                'action' => 'view'
            ));
        }

        $row = $this->db
            ->select(self::AADHAAR_COLUMN)
            ->where('OpdNo', $opd_no)
            ->get(self::PATIENT_TABLE)
            ->row_array();

        return !empty($row[self::AADHAAR_COLUMN]) ? $row[self::AADHAAR_COLUMN] : false;
    }
}
