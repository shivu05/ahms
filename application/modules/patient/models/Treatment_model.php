<?php

/**
 * Description of treatment_model
 *
 * @author Shivaraj
 */
class treatment_model extends CI_Model
{
    public function has_patient_identity_columns()
    {
        return $this->db->field_exists('aadhaar_number', 'patientdata')
            && $this->db->field_exists('abha_id', 'patientdata')
            && $this->db->field_exists('aadhaar_masked', 'patientdata');
    }

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

        if ($this->has_patient_identity_columns()) {
            $form_data['aadhaar_number'] = !empty($post_values['aadhaar_number']) ? $post_values['aadhaar_number'] : null;
            $form_data['abha_id'] = !empty($post_values['abha_id']) ? $post_values['abha_id'] : null;
            $form_data['aadhaar_masked'] = !empty($post_values['aadhaar_masked']) ? $post_values['aadhaar_masked'] : null;
        }

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
            return array('status' => false, 'message' => 'Failed to register patient.');
        } else {
            return array('status' => true, 'opd_no' => $last_id);
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

        if ($this->has_patient_identity_columns()) {
            $columns[] = "CASE WHEN COALESCE(NULLIF(TRIM(p.aadhaar_masked), ''), NULLIF(TRIM(p.abha_id), '')) IS NULL THEN 0 ELSE 1 END AS has_identity_data";
        } else {
            $columns[] = "0 AS has_identity_data";
        }

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

    function get_treatment_history($opd, $limit = NULL)
    {
        $where = array(
            'OpdNo' => $opd,
            'InOrOutPat !=' => NULL,
            'attndedon !=' => NULL,
        );
        $this->db->where($where);
        if ($limit !== NULL) {
            $this->db->order_by('attndedon', 'DESC');
            $this->db->order_by('ID', 'DESC');
            $this->db->limit((int) $limit);
        }
        return $this->db->get('treatmentdata')->result_array();
    }

    function get_visit_summary($opd)
    {
        $this->db->select('COUNT(*) AS total_visits, MAX(attndedon) AS last_visit_date');
        $this->db->where('OpdNo', $opd);
        $this->db->where('InOrOutPat !=', NULL);
        $this->db->where('attndedon !=', NULL);
        $summary = $this->db->get('treatmentdata')->row_array();

        return array(
            'total_visits' => isset($summary['total_visits']) ? (int) $summary['total_visits'] : 0,
            'last_visit_date' => isset($summary['last_visit_date']) ? $summary['last_visit_date'] : NULL
        );
    }

    public function get_patient_vitals($opd_no, $visit_date = NULL)
    {
        if (!$this->db->table_exists('patient_vitals')) {
            return array();
        }
        $this->db->where('opd_no', $opd_no);
        if ($visit_date) {
            $this->db->where('date', $visit_date);
        }
        $this->db->order_by('date', 'DESC');
        $this->db->order_by('id', 'DESC');
        return $this->db->get('patient_vitals')->row_array();
    }

    public function store_patient_vitals($opd_no, $visit_date, $vitals)
    {
        if (!$this->db->table_exists('patient_vitals') || !$opd_no || !$visit_date || empty($vitals)) {
            return false;
        }

        $allowed_fields = array('blood_pressure', 'pulse_rate', 'body_temperature', 'spo2', 'weight', 'height', 'bmi');
        $data = array();
        foreach ($allowed_fields as $field) {
            if ($this->db->field_exists($field, 'patient_vitals') && array_key_exists($field, $vitals)) {
                $data[$field] = ($vitals[$field] === '') ? NULL : $vitals[$field];
            }
        }
        if (empty($data)) {
            return false;
        }

        $existing = $this->db->select('id')
            ->where('opd_no', $opd_no)
            ->where('date', $visit_date)
            ->order_by('id', 'DESC')
            ->get('patient_vitals')
            ->row_array();

        if (!empty($existing)) {
            return $this->db->where('id', $existing['id'])->update('patient_vitals', $data);
        }

        $data['opd_no'] = $opd_no;
        $data['date'] = $visit_date;
        return $this->db->insert('patient_vitals', $data);
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
        $query = "SELECT id,department,wardno,group_concat(bedno ORDER BY id) as beds,
            group_concat(lower(concat_ws('#', bedno, bedstatus, ifnull(bed_category, ''))) order by id) bedstatus
            FROM bed_details b where bedno !='0' $where
            group by department order by bedno";
        return $this->db->query($query)->result_array();
    }

    function store_treatment($post_values, $treat_id)
    {
        return $this->db->update('treatmentdata', $post_values, array('ID' => $treat_id));
    }

    public function search_diagnosis($term = '', $limit = 20)
    {
        $term = trim((string) $term);
        $this->db->select('id, diagnosis_name');
        $this->db->from('diagnosis');
        if ($this->db->field_exists('InActive', 'diagnosis')) {
            $this->db->where('(InActive = 0 OR InActive IS NULL)', NULL, FALSE);
        }
        if ($term !== '') {
            $this->db->like('diagnosis_name', $term);
        }
        $this->db->order_by('diagnosis_name', 'ASC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result_array();
    }

    public function get_active_treatment_templates($department = NULL, $diagnosis_id = NULL)
    {
        if (!$this->db->table_exists('treatment_templates')) {
            return array();
        }

        $this->db->select('id, name, department_id, diagnosis_id, treatment_text');
        $this->db->from('treatment_templates');
        $this->db->where('status', 'ACTIVE');
        if ($department !== NULL && $department !== '') {
            $this->db->group_start();
            $this->db->where('department_id', NULL);
            $this->db->or_where('department_id', $department);
            $this->db->group_end();
        }
        if ($diagnosis_id !== NULL && $diagnosis_id !== '') {
            $this->db->group_start();
            $this->db->where('diagnosis_id', NULL);
            $this->db->or_where('diagnosis_id', $diagnosis_id);
            $this->db->group_end();
        }
        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_patient_procedure_timeline($patient_id, $opd_id = NULL, $visit_id = NULL, $limit = 20)
    {
        $opd_no = $opd_id ? $opd_id : $patient_id;
        if (!$opd_no) {
            return array();
        }

        $timeline = array();
        $timeline = array_merge($timeline, $this->_fetch_ecg_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_xray_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_usg_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_lab_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_surgery_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_ksharasutra_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_birth_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_panchakarma_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_kriyakalpa_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_physiotherapy_timeline($opd_no, $visit_id));
        $timeline = array_merge($timeline, $this->_fetch_other_procedure_timeline($opd_no, $visit_id));

        usort($timeline, array($this, '_sort_procedure_timeline'));
        return array_slice($timeline, 0, (int) $limit);
    }

    public function get_patient_clinical_timeline($patient_id, $opd_id = NULL, $visit_id = NULL, $limit = 30)
    {
        $opd_no = $opd_id ? $opd_id : $patient_id;
        if (!$opd_no) {
            return array();
        }

        $timeline = array();
        $this->db->select('ID, CameOn, attndedon, diagnosis, Trtment, complaints, notes, AddedBy, attndedby, department, InOrOutPat');
        $this->db->from('treatmentdata');
        $this->db->where('OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('ID', $visit_id);
        }
        $this->db->order_by('ID', 'DESC');
        $this->db->limit(20);
        $visits = $this->db->get()->result_array();

        foreach ($visits as $visit) {
            $event_date = $visit['attndedon'] ?: $visit['CameOn'];
            $doctor = $visit['attndedby'] ?: $visit['AddedBy'];
            $timeline[] = $this->_clinical_timeline_item('Visit', 'OPD Visit', $event_date, 'Recorded', 'Visit', $visit['complaints'], $doctor, $visit['department'], 'visits', $visit['ID']);
            if (trim((string) $visit['diagnosis']) !== '') {
                $timeline[] = $this->_clinical_timeline_item('Diagnosis', 'Diagnosis Added', $event_date, 'Recorded', 'Diagnosis', $visit['diagnosis'], $doctor, $visit['department'], 'visits', $visit['ID']);
            }
            if (trim((string) $visit['Trtment']) !== '') {
                $description = trim($visit['Trtment'] . ($visit['notes'] ? ' - ' . $visit['notes'] : ''));
                $timeline[] = $this->_clinical_timeline_item('Prescription', 'Treatment / Prescription Added', $event_date, 'Recorded', 'Prescription', $description, $doctor, $visit['department'], 'prescriptions', $visit['ID']);
            }
        }

        if ($this->db->table_exists('patient_vitals')) {
            $this->db->where('opd_no', $opd_no);
            $this->db->order_by('date', 'DESC');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(20);
            $vitals_rows = $this->db->get('patient_vitals')->result_array();
            foreach ($vitals_rows as $vitals) {
                $parts = array();
                $vital_labels = array(
                    'blood_pressure' => 'BP',
                    'pulse_rate' => 'Pulse',
                    'weight' => 'Weight',
                    'height' => 'Height',
                    'bmi' => 'BMI',
                    'body_temperature' => 'Temperature',
                    'spo2' => 'SpO2'
                );
                foreach ($vital_labels as $field => $label) {
                    if (isset($vitals[$field]) && $vitals[$field] !== NULL && $vitals[$field] !== '') {
                        $parts[] = $label . ' ' . $vitals[$field];
                    }
                }
                $timeline[] = $this->_clinical_timeline_item('Vitals', 'Vitals Recorded', $vitals['date'] ?: $vitals['created_at'], 'Completed', 'Vitals', implode(', ', $parts), '', '', 'vitals', $vitals['id']);
            }
        }

        $procedures = $this->get_patient_procedure_timeline($opd_no, NULL, $visit_id, 30);
        foreach ($procedures as $procedure) {
            $description = $procedure['procedure_name'];
            if ($procedure['remarks'] !== '') {
                $description .= ' - ' . $procedure['remarks'];
            }
            $timeline[] = $this->_clinical_timeline_item(
                $procedure['procedure_type'],
                $procedure['procedure_type'] . ': ' . $procedure['procedure_name'],
                $procedure['completed_date'] ?: $procedure['referred_date'],
                $procedure['status'],
                $procedure['procedure_type'],
                $description,
                $procedure['doctor_name'],
                $procedure['department_name'],
                'procedures',
                $procedure['source_id']
            );
        }

        if ($this->db->table_exists('inpatientdetails')) {
            $this->db->where('OpdNo', $opd_no);
            $this->db->order_by('IpNo', 'DESC');
            $this->db->limit(10);
            $admissions = $this->db->get('inpatientdetails')->result_array();
            foreach ($admissions as $admission) {
                $timeline[] = $this->_clinical_timeline_item('IPD', 'Patient Admitted', $admission['DoAdmission'], 'Recorded', 'IPD', 'Bed ' . $admission['BedNo'], $admission['Doctor'], $admission['department'], 'ipd', $admission['IpNo']);
                if (!empty($admission['DoDischarge']) && $admission['DoDischarge'] !== '--') {
                    $timeline[] = $this->_clinical_timeline_item('IPD', 'Patient Discharged', $admission['DoDischarge'], 'Completed', 'IPD', $admission['DischargeNotes'], $admission['DischBy'], $admission['department'], 'ipd', $admission['IpNo']);
                }
            }
        }

        usort($timeline, array($this, '_sort_clinical_timeline'));
        return array_slice($timeline, 0, (int) $limit);
    }

    private function _clinical_timeline_item($type, $title, $date, $status, $source, $description, $doctor, $department, $category, $source_id)
    {
        $date = $this->_clean_timeline_value($date);
        return array(
            'event_type' => $this->_clean_timeline_value($type),
            'event_title' => $this->_clean_timeline_value($title),
            'event_date' => $date,
            'status' => $this->_clean_timeline_value($status) ?: 'Recorded',
            'source_module' => $this->_clean_timeline_value($source),
            'description' => $this->_clean_timeline_value($description),
            'doctor_name' => $this->_clean_timeline_value($doctor),
            'department_name' => $this->_clean_timeline_value($department),
            'category' => $category,
            'source_id' => (int) $source_id,
            'sort_ts' => $this->_timeline_sort_value($date, $source_id)
        );
    }

    private function _sort_clinical_timeline($a, $b)
    {
        if ($a['sort_ts'] == $b['sort_ts']) {
            return 0;
        }
        return ($a['sort_ts'] < $b['sort_ts']) ? 1 : -1;
    }

    private function _fetch_ecg_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('ecgregistery')) {
            return array();
        }
        $this->db->select('e.ID AS source_id, e.refDocName, e.refDate, e.ecgDate, t.department');
        $this->db->from('ecgregistery e');
        $this->db->join('treatmentdata t', 't.ID=e.treatId', 'left');
        $this->db->where('e.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('e.treatId', $visit_id);
        }
        $this->db->order_by('e.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $items[] = $this->_procedure_timeline_item('ECG', 'ECG', $row['refDate'], $row['ecgDate'], '', $row['refDocName'], $row['department'], '', $row['source_id'], 'investigation');
        }
        return $items;
    }

    private function _fetch_xray_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('xrayregistery')) {
            return array();
        }
        $this->db->select('x.ID AS source_id, x.partOfXray, x.filmSize, x.xrayNo, x.refDocName, x.refDate, x.xrayDate, t.department');
        $this->db->from('xrayregistery x');
        $this->db->join('treatmentdata t', 't.ID=x.treatID', 'left');
        $this->db->where('x.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('x.treatID', $visit_id);
        }
        $this->db->order_by('x.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $remarks = trim('No: ' . $row['xrayNo'] . ' Film: ' . $row['filmSize']);
            $items[] = $this->_procedure_timeline_item('X-Ray', $row['partOfXray'] ?: 'X-Ray', $row['refDate'], $row['xrayDate'], '', $row['refDocName'], $row['department'], $remarks, $row['source_id'], 'investigation');
        }
        return $items;
    }

    private function _fetch_usg_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('usgregistery')) {
            return array();
        }
        $this->db->select('u.ID AS source_id, u.refDocName, u.refDate, u.usgDate, t.department');
        $this->db->from('usgregistery u');
        $this->db->join('treatmentdata t', 't.ID=u.treatId', 'left');
        $this->db->where('u.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('u.treatId', $visit_id);
        }
        $this->db->order_by('u.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $items[] = $this->_procedure_timeline_item('USG', 'USG', $row['refDate'], $row['usgDate'], '', $row['refDocName'], $row['department'], '', $row['source_id'], 'investigation');
        }
        return $items;
    }

    private function _fetch_lab_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('labregistery')) {
            return array();
        }
        $this->db->select('l.ID AS source_id, l.refDocName, l.testDate, l.tested_date, l.testName, l.testrange, l.testvalue, l.labdisease, li.lab_inv_name, t.department');
        $this->db->from('labregistery l');
        $this->db->join('lab_investigations li', 'li.lab_inv_id=l.testName', 'left');
        $this->db->join('treatmentdata t', 't.ID=l.treatID', 'left');
        $this->db->where('l.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('l.treatID', $visit_id);
        }
        $this->db->order_by('l.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $name = $row['lab_inv_name'] ?: ($row['labdisease'] ?: 'Lab Investigation');
            $remarks = trim('Range: ' . $row['testrange'] . ' Value: ' . $row['testvalue']);
            $items[] = $this->_procedure_timeline_item('Lab', $name, $row['testDate'], $row['tested_date'], '', $row['refDocName'], $row['department'], $remarks, $row['source_id'], 'lab');
        }
        return $items;
    }

    private function _fetch_surgery_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('surgeryregistery')) {
            return array();
        }
        $this->db->select('s.ID AS source_id, s.surgType, s.surgName, s.surgDate, s.anaesthetic, s.asssurgeon, s.surgeryname, t.department');
        $this->db->from('surgeryregistery s');
        $this->db->join('treatmentdata t', 't.ID=s.treatId', 'left');
        $this->db->where('s.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('s.treatId', $visit_id);
        }
        $this->db->order_by('s.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $name = $row['surgeryname'] ?: ($row['surgType'] ?: 'Surgery');
            $remarks = trim('Anaesthetic: ' . $row['anaesthetic'] . ' Assistant: ' . $row['asssurgeon']);
            $items[] = $this->_procedure_timeline_item('Surgery', $name, $row['surgDate'], '', '', $row['surgName'], $row['department'], $remarks, $row['source_id'], 'procedure');
        }
        return $items;
    }

    private function _fetch_ksharasutra_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('ksharsutraregistery')) {
            return array();
        }
        $this->db->select('k.ID AS source_id, k.ksharsType, k.ksharsDate, k.ksharaname, k.surgeon, k.asssurgeon, k.anaesthetic, t.department');
        $this->db->from('ksharsutraregistery k');
        $this->db->join('treatmentdata t', 't.ID=k.treatId', 'left');
        $this->db->where('k.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('k.treatId', $visit_id);
        }
        $this->db->order_by('k.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $name = $row['ksharaname'] ?: ($row['ksharsType'] ?: 'Ksharasutra');
            $remarks = trim('Anaesthetic: ' . $row['anaesthetic'] . ' Assistant: ' . $row['asssurgeon']);
            $items[] = $this->_procedure_timeline_item('Ksharasutra', $name, $row['ksharsDate'], '', '', $row['surgeon'], $row['department'], $remarks, $row['source_id'], 'procedure');
        }
        return $items;
    }

    private function _fetch_birth_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('birthregistery')) {
            return array();
        }
        $this->db->select('b.ID AS source_id, b.deliveryDetail, b.babyBirthDate, b.babyWeight, b.treatby, b.babygender, b.deliverytype, t.department');
        $this->db->from('birthregistery b');
        $this->db->join('treatmentdata t', 't.ID=b.treatId', 'left');
        $this->db->where('b.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('b.treatId', $visit_id);
        }
        $this->db->order_by('b.ID', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $name = $row['deliverytype'] ?: ($row['deliveryDetail'] ?: 'Birth');
            $remarks = trim('Baby: ' . $row['babygender'] . ' Weight: ' . $row['babyWeight']);
            $items[] = $this->_procedure_timeline_item('Birth', $name, $row['babyBirthDate'], $row['babyBirthDate'], '', $row['treatby'], $row['department'], $remarks, $row['source_id'], 'procedure');
        }
        return $items;
    }

    private function _fetch_panchakarma_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('panchaprocedure')) {
            return array();
        }
        $this->db->select('p.id AS source_id, p.disease, p.treatment, p.procedure, p.date, p.proc_end_date, p.docname, t.department');
        $this->db->from('panchaprocedure p');
        $this->db->join('treatmentdata t', 't.ID=p.treatid', 'left');
        $this->db->where('p.opdno', $opd_no);
        if ($visit_id) {
            $this->db->where('p.treatid', $visit_id);
        }
        $this->db->order_by('p.id', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $name = $row['procedure'] ?: ($row['treatment'] ?: 'Panchakarma');
            $items[] = $this->_procedure_timeline_item('Panchakarma', $name, $row['date'], $row['proc_end_date'], '', $row['docname'], $row['department'], $row['disease'], $row['source_id'], 'panchakarma');
        }
        return $items;
    }

    private function _fetch_kriyakalpa_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('kriyakalpa')) {
            return array();
        }
        $this->db->select('k.id AS source_id, k.kriya_procedures, k.kriya_date, t.AddedBy, t.department');
        $this->db->from('kriyakalpa k');
        $this->db->join('treatmentdata t', 't.ID=k.treat_id', 'left');
        $this->db->where('k.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('k.treat_id', $visit_id);
        }
        $this->db->order_by('k.id', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $items[] = $this->_procedure_timeline_item('Kriyakalpa', $row['kriya_procedures'] ?: 'Kriyakalpa', $row['kriya_date'], '', '', $row['AddedBy'], $row['department'], '', $row['source_id'], 'procedure');
        }
        return $items;
    }

    private function _fetch_physiotherapy_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('physiotherapy_treatments')) {
            return array();
        }
        $this->db->select('p.id AS source_id, p.therapy_name, p.physician, p.start_date, p.end_date, t.department');
        $this->db->from('physiotherapy_treatments p');
        $this->db->join('treatmentdata t', 't.ID=p.treat_id', 'left');
        $this->db->where('p.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('p.treat_id', $visit_id);
        }
        $this->db->order_by('p.id', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $items[] = $this->_procedure_timeline_item('Physiotherapy', $row['therapy_name'] ?: 'Physiotherapy', $row['start_date'], $row['end_date'], '', $row['physician'], $row['department'], '', $row['source_id'], 'procedure');
        }
        return $items;
    }

    private function _fetch_other_procedure_timeline($opd_no, $visit_id)
    {
        if (!$this->db->table_exists('other_procedures_treatments')) {
            return array();
        }
        $this->db->select('o.id AS source_id, o.therapy_name, o.physician, o.start_date, o.end_date, t.department');
        $this->db->from('other_procedures_treatments o');
        $this->db->join('treatmentdata t', 't.ID=o.treat_id', 'left');
        $this->db->where('o.OpdNo', $opd_no);
        if ($visit_id) {
            $this->db->where('o.treat_id', $visit_id);
        }
        $this->db->order_by('o.id', 'DESC');
        $this->db->limit(20);
        $rows = $this->db->get()->result_array();

        $items = array();
        foreach ($rows as $row) {
            $items[] = $this->_procedure_timeline_item('Other Procedure', $row['therapy_name'] ?: 'Other Procedure', $row['start_date'], $row['end_date'], '', $row['physician'], $row['department'], '', $row['source_id'], 'procedure');
        }
        return $items;
    }

    private function _procedure_timeline_item($type, $name, $referred_date, $completed_date, $status, $doctor, $department, $remarks, $source_id, $category)
    {
        $referred_date = $this->_clean_timeline_value($referred_date);
        $completed_date = $this->_clean_timeline_value($completed_date);
        $status = $this->_resolve_procedure_status($status, $completed_date, $referred_date);
        $sort_date = $completed_date ? $completed_date : $referred_date;

        return array(
            'procedure_type' => $this->_clean_timeline_value($type),
            'procedure_name' => $this->_clean_timeline_value($name) ?: $this->_clean_timeline_value($type),
            'referred_date' => $referred_date,
            'completed_date' => $completed_date,
            'status' => $status,
            'status_group' => ($status === 'Completed') ? 'completed' : (($status === 'Cancelled') ? 'cancelled' : 'pending'),
            'doctor_name' => $this->_clean_timeline_value($doctor),
            'department_name' => $this->_clean_timeline_value($department),
            'remarks' => $this->_clean_timeline_value($remarks),
            'category' => $category,
            'source_id' => (int) $source_id,
            'sort_ts' => $this->_timeline_sort_value($sort_date, $source_id)
        );
    }

    private function _clean_timeline_value($value)
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return '';
        }
        return $value;
    }

    private function _resolve_procedure_status($status, $completed_date, $referred_date)
    {
        $status = strtolower(trim((string) $status));
        if ($status !== '') {
            if (strpos($status, 'cancel') !== FALSE || strpos($status, 'reject') !== FALSE) {
                return 'Cancelled';
            }
            if (strpos($status, 'complete') !== FALSE || strpos($status, 'done') !== FALSE || strpos($status, 'report') !== FALSE) {
                return 'Completed';
            }
            if (strpos($status, 'pending') !== FALSE || strpos($status, 'refer') !== FALSE) {
                return 'Referred';
            }
        }
        if ($completed_date !== '') {
            return 'Completed';
        }
        return $referred_date !== '' ? 'Referred' : 'Referred';
    }

    private function _timeline_sort_value($date, $source_id)
    {
        $timestamp = strtotime($date);
        if (!$timestamp) {
            $timestamp = 0;
        }
        return $timestamp . '.' . str_pad((int) $source_id, 8, '0', STR_PAD_LEFT);
    }

    private function _sort_procedure_timeline($a, $b)
    {
        if ($a['sort_ts'] == $b['sort_ts']) {
            return 0;
        }
        return ($a['sort_ts'] < $b['sort_ts']) ? 1 : -1;
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
        $this->db->select('inpatientdetails.*, patientdata.UHID');
        $this->db->join('patientdata', 'patientdata.OpdNo = inpatientdetails.OpdNo');
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
            $existing_treatment = $this->db->get_where('treatmentdata', array(
                'OpdNo' => $post_values['opd'],
                'ID' => $post_values['treat_id']
            ))->row_array();

            if (empty($existing_treatment)) {
                return false;
            }

            $update_array = array(
                'Trtment' => $post_values['pat_treatment'],
                'diagnosis' => $post_values['pat_diagnosis'],
                'procedures' => $post_values['pat_procedure'],
                'AddedBy' => $post_values['AddedBy'],
                'CameOn' => $post_values['consultation_date'],
            );

            if (!empty($existing_treatment['attndedon'])) {
                $update_array['attndedon'] = $post_values['consultation_date'];
            }

            $personal_details = array(
                'FirstName' => $post_values['pat_name'],
                'Age' => $post_values['pat_age'],
                'gender' => $post_values['pat_gender']
            );

            if ($this->has_patient_identity_columns()) {
                $personal_details['aadhaar_number'] = isset($post_values['aadhaar_number']) && $post_values['aadhaar_number'] !== '' ? $post_values['aadhaar_number'] : null;
                $personal_details['abha_id'] = isset($post_values['abha_id']) && $post_values['abha_id'] !== '' ? $post_values['abha_id'] : null;
                $personal_details['aadhaar_masked'] = isset($post_values['aadhaar_masked']) && $post_values['aadhaar_masked'] !== '' ? $post_values['aadhaar_masked'] : null;
            }

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
