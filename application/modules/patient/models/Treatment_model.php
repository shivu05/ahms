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

    function add_patient_for_treatment($post_values) {
        $user_id = $this->rbac->get_uid();
        $this->db->trans_start();
        $form_data = array(
            'FirstName' => $post_values['first_name'],
            'LastName' => $post_values['last_name'],
            'Age' => $post_values['age'],
            'gender' => $post_values['gender'],
            'occupation' => $post_values['occupation'],
            'address' => $post_values['address'],
            'city' => $post_values['place'],
            'AddedBy' => $user_id,
            'entrydate' => $post_values['consultation_date'],
            'mob' => $post_values['mobile']
        );
        $this->db->insert('patientdata', $form_data);
        $last_id = $this->db->insert_id();
        $dept_max_id = $this->db->select_max('deptOpdNo')->where('department', $post_values['department'])->get('treatmentdata')->row()->deptOpdNo;
        $dept_opd_count = ($dept_max_id == NULL) ? 1 : $dept_max_id + 1;
        $treat_data = array(
            'OpdNo' => $last_id,
            'deptOpdNo' => $dept_opd_count,
            'PatType' => NEW_PATIENT,
            'department' => $post_values['department'],
            'AddedBy' => $post_values['doctor'],
            'CameOn' => $post_values['consultation_date']
        );
        $this->db->insert('treatmentdata', $treat_data);
        $this->db->trans_complete();
    }

    function get_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array(
            't.ID', 'p.OpdNo', 'p.FirstName', 'p.LastName', 'p.Age', 'p.gender', 'p.city', 'p.occupation', 'p.address', 't.PatType', 'd.department',
            't.complaints', 't.Trtment', 't.diagnosis', 't.CameOn', 't.PatType'
        );

        $user_dept_cond = '';
        if ($this->rbac->is_doctor()) {
            $user_dept_cond = " AND LOWER(t.department) = LOWER('" . display_department($this->rbac->get_user_department()) . "')";
        }

        $where_cond = " WHERE attndedon is NULL AND InOrOutPat is NULL $user_dept_cond";
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
                . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.department $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM treatmentdata t "
                        . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.department WHERE 1=1 $user_dept_cond")->num_rows();
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

    public function add_to_pharmacy($treat_id, $type) {
        if (strtolower($type) == 'opd') {
            $this->db->where('ID', $treat_id);
            $this->db->where('department <>', 'Swasthavritta');
            $treatment_data = $this->db->get('treatmentdata')->row_array();
            if (!empty($treatment_data)) {
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                $products = explode(',', $treatment_data['Trtment']);
                $n = count($products);
                for ($i = 0; $i < $n; $i++) {
                    if (strlen(trim($products[$i])) > 0) {
                        $med_arr = array(
                            'opdno' => $treatment_data['OpdNo'],
                            'billno' => $four_digit_random_number,
                            'batch' => 947,
                            'date' => $treatment_data['CameOn'],
                            'qty' => 1,
                            'product' => trim($products[$i]),
                            'treat_id' => $treatment_data['ID']
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
            }// end if
        } else if (strtolower($type) == 'ipd') {
            $treatment_data = $this->db->query("SELECT * FROM ipdtreatment i JOIN inpatientdetails ip On i.ipdno=ip.IpNo 
                WHERE ip.department !='Swasthavritta' AND ID='" . $treat_id . "'")->result_array();
            $digits = 4;
            $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            $products = explode(',', $treatment_data['Trtment']);
            $n = count($products);
            for ($i = 0; $i < $n; $i++) {
                if (strlen(trim($products[$i])) > 0) {
                    $med_arr = array(
                        'opdno' => $treatment_data['OpdNo'],
                        'billno' => $four_digit_random_number,
                        'batch' => 947,
                        'date' => $treatment_data['attndedon'],
                        'qty' => 1,
                        'ipdno' => $treatment_data['IpNo'],
                        'product' => $products[$i],
                        'treat_id' => $treatment_data['ID']
                    );
                    $this->db->insert('sales_entry', $med_arr);
                }
            }
        }
    }

}