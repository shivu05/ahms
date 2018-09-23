<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of treatment_model
 *
 * @author Shivaraj
 */
class treatment_model extends CI_Model {

    function get_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            't.ID', 'p.OpdNo', 'p.FirstName', 'p.LastName', 'p.Age', 'p.gender', 'p.city', 'p.occupation', 'p.address', 't.PatType', 'd.department',
            't.complaints', 't.Trtment', 't.diagnosis', 't.CameOn', 't.PatType'
        );
        $where_cond = " WHERE attndedon is NULL and InOrOutPat is NULL ";
        $limit = '';
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
                        $where_cond .= " AND t.OpdNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND CONCAT(p.FirstName,' ',p.LastName) LIKE '%$val%'";
                        break;
                    case 'date':
                        $where_cond .= " AND t.CameOn='$val'";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT " . join(',', $columns) . " FROM treatmentdata t "
                . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.dept_unique_code $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM treatmentdata t "
                        . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.dept_unique_code ")->num_rows();
        return $return;
    }

    function get_patient_basic_details($opd) {
        return $this->db->get_where('patientdata', array('OpdNo' => $opd))->row_array();
    }

    function get_ipd_patient_basic_details($opd, $ipd) {
        $this->db->from('patientdata p');
        $this->db->join('inpatientdetails i', 'p.OpdNo=i.OpdNo');
        $this->db->where('p.OpdNo', $opd);
        $this->db->where('i.IpNo', $ipd);
        return $this->db->get()->row_array();
    }

    function get_treatment_history($opd) {
        $where = array(
            'OpdNo' => $opd,
            'InOrOutPat !=' => NULL,
            'attndedon !=' => NULL,
        );
        return $this->db->get_where('treatmentdata', $where)->result_array();
    }

    function get_current_treatment_info($opd, $treat_id) {
        $where = array(
            'OpdNo' => $opd,
            'InOrOutPat' => NULL,
            'attndedon' => NULL,
        );
        $return['treatment_data'] = $this->db->get_where('treatmentdata', $where)->row_array();
        $query = "SELECT d.id,doctorname FROM doctors d JOIN treatmentdata t ON t.department=d.doctortype 
            WHERE t.ID=$treat_id GROUP BY doctorname";
        $return['doctors'] = $this->db->query($query)->result_array();
        return $return;
    }

    public function getBedno() {
        $query = "SELECT id,department,wardno,group_concat(bedno) as beds 
            FROM bed_details b where b.bedstatus='Available' 
            group by department,wardno order by wardno";
        return $this->db->query($query)->result_array();
    }

    function store_treatment($post_values, $treat_id) {
        return $this->db->update('treatmentdata', $post_values, array('ID' => $treat_id));
    }

    public function add_ecg_info($ecgdata) {
        $this->db->insert('ecgregistery', $ecgdata);
    }

    public function add_usg_info($usgdata) {
        $this->db->insert('usgregistery', $usgdata);
    }

    public function add_xray_info($xraydata) {
        $this->db->insert('xrayregistery', $xraydata);
    }

    public function add_kshara_info($ksharadata) {
        $this->db->insert('ksharsutraregistery', $ksharadata);
    }

    public function add_surgery_info($surgerydata) {
        $this->db->insert('surgeryregistery', $surgerydata);
    }

    public function add_lab_info($labdata) {
        $this->db->insert('labregistery', $labdata);
    }

    public function update_bed_info($beddata, $bed_no) {
        $this->db->where('bedno', $bed_no);
        return $this->db->update('bed_details', $beddata);
    }

    public function add_patient_to_ipd($inpatientdata) {
        $this->db->query("INSERT INTO inpatientdetails (OpdNo, deptOpdNo, FName, Age, Gender, department, BedNo, diagnosis, DoAdmission, Doctor,treatId) "
                . " SELECT OpdNo,deptOpdNo,CONCAT(FirstName,' ',MidName,' ',LastName),Age,Gender,'" . $inpatientdata['department'] . "','" . $inpatientdata['BedNo'] . "','" . $inpatientdata['diagnosis'] . "','" . $inpatientdata['DoAdmission'] . "','" . $inpatientdata['Doctor'] . "','" . $inpatientdata['treatId'] . "' FROM patientdata WHERE OpdNo='" . $inpatientdata['OpdNo'] . "'");
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function add_ipd_treatment_data($post_data) {
        return $this->db->insert('ipdtreatment', $post_data);
    }

    public function get_opd_by_ipd($ipd_no) {
        $this->db->select('OpdNo');
        return $this->db->get_where('inpatientdetails', array('IpNo' => $ipd_no))->row_array();
    }

    function get_ipd_treatment_history($ipd) {
        $where = array(
            'ipdno' => $ipd
        );
        return $this->db->get_where('ipdtreatment', $where)->result_array();
    }

    function store_ipd_treatment($post_values) {
        $this->db->insert('ipdtreatment', $post_values);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function store_birth_info($post_values) {
        if ($post_values) {
            return $this->db->insert('birthregistery', $post_values);
        } else {
            return false;
        }
    }

    public function update_bed_details($opd_id) {
        $this->db->where('OpdNo', $opd_id);
        return $this->db->update('bed_details', array('bedstatus' => 'Available'));
    }

    public function discharge_patient($ipd, $discharge) {
        $this->db->where('IpNo', $ipd);
        return $this->db->update('inpatientdetails', $discharge);
    }

}
