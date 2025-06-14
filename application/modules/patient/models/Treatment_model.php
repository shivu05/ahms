<?php

/**
 * Description of treatment_model
 *
 * @author Shivaraj
 */
class treatment_model extends CI_Model
{

    function add_patient_for_treatment($post_values)
    {
        $user_id = $this->rbac->get_uid();
        $uid = $this->uuid->v5('AnSh');
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
            'mob' => $post_values['mobile'],
            'sid' => $uid,
            'UHID' => $this->insert_patient_with_uhid($post_values['consultation_date'])
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
            'sub_department' => @$post_values['sub_department'],
            'AddedBy' => $post_values['doctor'],
            'CameOn' => $post_values['consultation_date']
        );
        $this->db->insert('treatmentdata', $treat_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    function insert_patient_with_uhid($date)
    {
        $config = $this->db->get('config')->row(); // assuming only one row exists
        $hospital_code = $config->college_id ?? 'VHMS';

        $today = $date;
        $today_short = date('ymd', strtotime($date)); //date('ymd');

        // Step 1: Get or insert sequence
        $query = $this->db->get_where('uhid_sequence', [
            'seq_date' => $today,
            'hospital_code' => $hospital_code
        ]);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $daily_seq = $row->daily_seq + 1;
            $this->db->update('uhid_sequence', ['daily_seq' => $daily_seq], ['id' => $row->id]);
        } else {
            $daily_seq = 1;
            $this->db->insert('uhid_sequence', [
                'seq_date' => $today,
                'hospital_code' => $hospital_code,
                'daily_seq' => $daily_seq
            ]);
        }

        // Step 2: Generate UHID
        $uhid = 'VH-' . $hospital_code . '-' . $today_short . '-' . str_pad($daily_seq, 4, '0', STR_PAD_LEFT);

        // Step 3: Add UHID to patient data
        //$patient_data['UHID'] = $uhid;
        //$this->db->insert('patientdata', $patient_data);

        return $uhid;
    }

    function get_patients($conditions, $export_flag = FALSE)
    {
        $return = array();
        $display_all = FALSE;
        $columns = array(
            't.ID',
            'p.OpdNo',
            'p.FirstName',
            'p.LastName',
            'p.Age',
            'p.gender',
            'p.city',
            'p.occupation',
            'p.address',
            't.PatType',
            'd.department',
            't.complaints',
            't.Trtment',
            't.diagnosis',
            't.CameOn',
            't.PatType',
            'attndedon',
            'InOrOutPat'
        );

        $user_dept_cond = '';
        if ($this->rbac->is_doctor()) {
            $user_dept_cond = " AND LOWER(t.department) = LOWER('" . $this->rbac->get_user_department() . "')";
        }
        $cur_date = date('Y-m-d');
        if (isset($conditions['all_patients']) && $conditions['all_patients'] == '1') {
            $display_all = TRUE;
        }
        $cur_date_condition = ($display_all) ? '' : " AND (attndedon = '$cur_date' AND InOrOutPat ='FollowUp') OR (attndedon is null AND InOrOutPat is null) ";
        $where_cond = " WHERE 1=1 $cur_date_condition $user_dept_cond";

        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['all_patients']);

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
                    case 'department':
                        $where_cond .= " AND t.department='$val'";
                        break;
                    case 'keyword':
                        $val = strtoupper(str_replace(' ', '_', $val));
                        $where_cond .= " AND ( t.department like '%$val%' OR t.diagnosis like '%$val%' OR t.OpdNo='$val' OR CONCAT(p.FirstName,' ',p.LastName) LIKE '%$val%' OR p.occupation LIKE '%$val%') ";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT " . join(',', $columns) . " FROM treatmentdata t "
            . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.dept_unique_code $where_cond order by t.ID DESC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM treatmentdata t "
            . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.department WHERE 1=1 $user_dept_cond")->num_rows();
        return $return;
    }

    function get_patient_basic_details($opd)
    {
        return $this->db->get_where('patientdata', array('OpdNo' => $opd))->row_array();
    }

    function get_ipd_patient_basic_details($opd, $ipd)
    {
        $this->db->from('patientdata p');
        $this->db->join('inpatientdetails i', 'p.OpdNo=i.OpdNo');
        $this->db->where('p.OpdNo', $opd);
        $this->db->where('i.IpNo', $ipd);
        return $this->db->get()->row_array();
    }

    function get_treatment_history($opd)
    {
        $where = array(
            'OpdNo' => $opd,
            'InOrOutPat !=' => NULL,
            'attndedon !=' => NULL,
        );
        return $this->db->get_where('treatmentdata', $where)->result_array();
    }

    function get_current_treatment_info($opd, $treat_id)
    {
        $where = array(
            'OpdNo' => $opd,
            'InOrOutPat' => NULL,
            'attndedon' => NULL,
        );
        $return['treatment_data'] = $this->db->get_where('treatmentdata', $where)->row_array();
        $query = "SELECT d.id,d.user_name FROM users d JOIN treatmentdata t ON t.department=d.user_department 
            WHERE t.ID=$treat_id GROUP BY user_name";
        $return['doctors'] = $this->db->query($query)->result_array();
        return $return;
    }

    function get_patient_treatment($opd, $treat_id)
    {
        $where = array(
            'it.OpdNo' => $opd,
            'it.ID' => $treat_id
        );
        return $this->db->from('patientdata p')
            ->join('treatmentdata it', 'p.OpdNo=it.OpdNo')
            ->where($where)
            ->get()
            ->row_array();
    }

    public function getBedno($all_beds = false)
    {
        $where = " and b.bedstatus='Available'";
        if ($all_beds) {
            $where = '';
        }
        $query = "SELECT id,department,wardno,group_concat(bedno ORDER BY id) as beds,group_concat(lower(concat(bedno,'#',bedstatus)) order by id) bedstatus
            FROM bed_details b where bedno !='0' $where
            group by department order by bedno";
        return $this->db->query($query)->result_array();
    }

    function store_treatment($post_values, $treat_id)
    {
        return $this->db->update('treatmentdata', $post_values, array('ID' => $treat_id));
    }

    public function add_ecg_info($ecgdata)
    {
        $this->db->insert('ecgregistery', $ecgdata);
    }

    public function add_usg_info($usgdata)
    {
        $this->db->insert('usgregistery', $usgdata);
    }

    public function add_xray_info($xraydata)
    {
        $this->db->insert('xrayregistery', $xraydata);
    }

    public function add_kshara_info($ksharadata)
    {
        $this->db->insert('ksharsutraregistery', $ksharadata);
    }

    public function add_surgery_info($surgerydata)
    {
        $this->db->insert('surgeryregistery', $surgerydata);
    }

    public function add_lab_info($labdata)
    {
        $this->db->insert_batch('labregistery', $labdata);
    }

    public function update_bed_info($beddata, $bed_no)
    {
        $this->db->where('bedno', $bed_no);
        return $this->db->update('bed_details', $beddata);
    }

    public function add_patient_to_ipd($inpatientdata)
    {
        $this->db->query("INSERT INTO inpatientdetails (OpdNo, deptOpdNo, FName, Age, Gender, department, BedNo, diagnosis, DoAdmission, Doctor,treatId) "
            . " SELECT OpdNo,deptOpdNo,CONCAT(FirstName,' ',MidName,' ',LastName),Age,Gender,'" . $inpatientdata['department'] . "','" . $inpatientdata['BedNo'] . "',
                    '" . $inpatientdata['diagnosis'] . "','" . $inpatientdata['DoAdmission'] . "','" . $inpatientdata['Doctor'] . "','" . $inpatientdata['treatId'] . "' FROM patientdata WHERE OpdNo='" . $inpatientdata['OpdNo'] . "'");
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function admit_patient($inpatientdata)
    {
        if (!empty($inpatientdata)) {
            $query = "INSERT INTO inpatientdetails (OpdNo, deptOpdNo, FName, Age, Gender, department, BedNo, diagnosis, DoAdmission, Doctor,treatId,admit_time) 
                SELECT T.OpdNo,T.deptOpdNo,CONCAT(FirstName,' ',LastName) as name,Age,Gender,T.department,'" . $inpatientdata['BedNo'] . "',T.diagnosis,'" . $inpatientdata['DoAdmission'] . "',T.AddedBy,'" . $inpatientdata['treatId'] . "' 
                    ,'" . $inpatientdata['admit_time'] . "' FROM patientdata P JOIN treatmentdata T ON P.OpdNo=T.OpdNo WHERE P.OpdNo='" . $inpatientdata['OpdNo'] . "' and T.ID='" . $inpatientdata['treatId'] . "'";
            $this->db->query($query);
            $insert_id = $this->db->insert_id();
            if ($insert_id) {
                $tquery = "INSERT INTO ipdtreatment (ipdno, AddedBy, Trtment, diagnosis, complaints, department, procedures, notes, attndedon, status) 
                    SELECT $insert_id,T.AddedBy,T.Trtment,T.diagnosis,T.complaints,T.department,T.procedures,T.notes,'" . $inpatientdata['DoAdmission'] . "','nottreated' 
                        FROM patientdata P JOIN treatmentdata T ON P.OpdNo=T.OpdNo WHERE P.OpdNo='" . $inpatientdata['OpdNo'] . "' and T.ID='" . $inpatientdata['treatId'] . "'";
                $this->db->query($tquery);

                $diet_entry = "INSERT INTO `diet_register` (`ipd_no`,`opd_no`,`treat_id`,`morning`,`after_noon`,`evening`) 
                    SELECT IpNo,OpdNo,treatId,'Bread/Biscuit/Tea','Chapati rice','Chapati rice' FROM inpatientdetails  WHERE IpNo='$insert_id'";
                $this->db->query($diet_entry);
            }
            return $insert_id;
        }
        return false;
    }

    public function add_ipd_treatment_data($post_data)
    {
        return $this->db->insert('ipdtreatment', $post_data);
    }

    public function get_opd_by_ipd($ipd_no)
    {
        //$this->db->select('OpdNo');
        return $this->db->get_where('inpatientdetails', array('IpNo' => $ipd_no))->row_array();
    }

    function get_ipd_treatment_history($ipd)
    {
        $where = array(
            'ipdno' => $ipd
        );
        return $this->db->get_where('ipdtreatment', $where)->result_array();
    }

    function store_ipd_treatment($post_values)
    {
        $this->db->insert('ipdtreatment', $post_values);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function store_birth_info($post_values)
    {
        if ($post_values) {
            return $this->db->insert('birthregistery', $post_values);
        } else {
            return false;
        }
    }

    public function update_bed_details($bed_no)
    {
        $update_data = array(
            'OpdNo' => NULL,
            'bedstatus' => 'Available',
            'treatId' => NULL,
            'IpNo' => NULL
        );
        $this->db->where('bedno', $bed_no);
        return $this->db->update('bed_details', $update_data);
    }

    public function discharge_patient($ipd, $discharge)
    {
        $this->db->where('IpNo', $ipd);
        return $this->db->update('inpatientdetails', $discharge);
    }

    public function add_to_pharmacy($treat_id, $type)
    {
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
            } // end if
        } else if (strtolower($type) == 'ipd') {
            $treatment_data = $this->db->query("SELECT * FROM ipdtreatment i JOIN inpatientdetails ip On i.ipdno=ip.IpNo 
                WHERE ip.department !='Swasthavritta' AND ID='" . $treat_id . "'")->row_array();
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

    public function get_all_opd_patients($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array(
            't.ID',
            'p.OpdNo',
            'p.FirstName',
            'p.LastName',
            'p.Age',
            'p.gender',
            'p.city',
            'p.occupation',
            'p.address',
            't.PatType',
            'd.department',
            't.complaints',
            't.Trtment',
            't.diagnosis',
            't.CameOn',
            't.PatType'
        );

        $where_cond = "";
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
            . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.dept_unique_code $where_cond ORDER BY OpdNo DESC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM treatmentdata t "
            . " JOIN patientdata p ON t.OpdNo=p.OpdNo JOIN deptper d ON t.department=d.department ")->num_rows();
        return $return;
    }

    public function get_treatment_information($where = NULL)
    {
        $this->db->from('treatmentdata t');
        $this->db->join('patientdata p', 'p.OpdNo=t.OpdNo');
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    private function _delete_pharmcay_data($treat_id)
    {
        $this->db->where('treat_id', $treat_id);
        return $this->db->delete('sales_entry');
    }

    public function update_opd_treatment_data($post_values)
    {
        if ($post_values['treat_id']) {
            $update_array = array(
                'Trtment' => $post_values['pat_treatment'],
                'diagnosis' => $post_values['pat_diagnosis'],
                'procedures' => $post_values['pat_procedure'],
                'AddedBy' => $post_values['AddedBy'],
            );
            $personal_details = array(
                'FirstName' => $post_values['pat_name'],
                'Age' => $post_values['pat_age'],
                'gender' => $post_values['pat_gender']
            );
            $this->db->where('OpdNo', $post_values['opd']);
            $is_pupdated = $this->db->update('patientdata', $personal_details);

            $this->db->where('OpdNo', $post_values['opd']);
            $this->db->where('ID', $post_values['treat_id']);
            $is_updated = $this->db->update('treatmentdata', $update_array);
            if ($is_updated) {
                $this->_delete_pharmcay_data($post_values['treat_id']);
                $this->add_to_pharmacy($post_values['treat_id'], 'opd');
            }
            return $is_updated;
        } else {
            return false;
        }
    }

    public function insert_kriyakalpa($post_data)
    {
        return $this->db->insert('kriyakalpa', $post_data);
    }

    public function check_opdno_status($opd_no)
    {
        $this->db->select('IpNo');
        $this->db->from('inpatientdetails');
        $this->db->where('OpdNo', $opd_no);
        $this->db->where('status', 'stillin');
        $result = $this->db->get()->row_array();

        if (!empty($result)) {
            return $result['IpNo'];
        } else {
            return false;
        }
    }
}
