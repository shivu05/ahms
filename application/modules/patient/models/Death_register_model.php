<?php

class Death_register_model extends CI_Model
{
    private $table = 'death_register';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_ipd_episode($ipd_no)
    {
        return $this->db
            ->select('ip.*, p.UHID, p.FirstName, p.LastName, p.address, p.city, p.mob')
            ->from('inpatientdetails ip')
            ->join('patientdata p', 'p.OpdNo = ip.OpdNo', 'left')
            ->where('ip.IpNo', $ipd_no)
            ->get()
            ->row_array();
    }

    public function exists_for_ipd($ipd_no)
    {
        return $this->db
            ->where('ipd_no', $ipd_no)
            ->count_all_results($this->table) > 0;
    }

    public function create_from_ipd($ipd_no, array $post_data, $entered_by = null)
    {
        $episode = $this->get_ipd_episode($ipd_no);
        if (empty($episode)) {
            return array('status' => false, 'message' => 'IPD record not found.');
        }

        $status = strtolower(trim(isset($episode['status']) ? (string) $episode['status'] : ''));
        if ($status !== 'stillin') {
            return array('status' => false, 'message' => 'Death register can be created only for admitted IPD patients.');
        }

        if ($this->exists_for_ipd($ipd_no)) {
            return array('status' => false, 'message' => 'Death register already exists for this IPD.');
        }

        $death_date = $this->normalize_date($this->array_value($post_data, 'death_date'));
        if ($death_date === false) {
            return array('status' => false, 'message' => 'Invalid death date.');
        }

        $death_time = trim((string) $this->array_value($post_data, 'death_time'));
        if ($death_time === '') {
            return array('status' => false, 'message' => 'Death time is required.');
        }
        if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $death_time)) {
            return array('status' => false, 'message' => 'Death time must be in HH:MM format.');
        }

        $certifying_doctor = trim((string) $this->array_value($post_data, 'certifying_doctor'));
        $immediate_cause = trim((string) $this->array_value($post_data, 'immediate_cause'));
        if ($certifying_doctor === '' || $immediate_cause === '') {
            return array('status' => false, 'message' => 'Certifying doctor and immediate cause are required.');
        }

        $admission_date = $this->normalize_date($episode['DoAdmission'], true);
        $address = trim(trim((string) $episode['address']) . ' ' . trim((string) $episode['city']));
        $now = date('Y-m-d H:i:s');
        $register_no = $this->generate_register_no($death_date);

        $death_data = array(
            'death_register_no' => $register_no,
            'ipd_no' => (int) $ipd_no,
            'opd_no' => !empty($episode['OpdNo']) ? (int) $episode['OpdNo'] : null,
            'uhid' => isset($episode['UHID']) ? $episode['UHID'] : null,
            'patient_name' => !empty($episode['FName']) ? $episode['FName'] : trim($episode['FirstName'] . ' ' . $episode['LastName']),
            'age' => isset($episode['Age']) ? $episode['Age'] : null,
            'gender' => isset($episode['Gender']) ? $episode['Gender'] : null,
            'address' => $address,
            'mobile' => isset($episode['mob']) ? $episode['mob'] : null,
            'department' => isset($episode['department']) ? $episode['department'] : null,
            'ward_no' => isset($episode['WardNo']) ? $episode['WardNo'] : null,
            'bed_no' => isset($episode['BedNo']) ? $episode['BedNo'] : null,
            'admission_date' => $admission_date,
            'admission_time' => isset($episode['admit_time']) ? $episode['admit_time'] : null,
            'death_date' => $death_date,
            'death_time' => $death_time,
            'treating_doctor' => isset($episode['Doctor']) ? $episode['Doctor'] : null,
            'certifying_doctor' => $certifying_doctor,
            'diagnosis_at_admission' => isset($episode['diagnosis']) ? $episode['diagnosis'] : null,
            'final_diagnosis' => trim((string) $this->array_value($post_data, 'final_diagnosis')),
            'immediate_cause' => $immediate_cause,
            'antecedent_cause' => trim((string) $this->array_value($post_data, 'antecedent_cause')),
            'underlying_cause' => trim((string) $this->array_value($post_data, 'underlying_cause')),
            'other_significant_conditions' => trim((string) $this->array_value($post_data, 'other_significant_conditions')),
            'mccd_form4_issued' => !empty($post_data['mccd_form4_issued']) ? 1 : 0,
            'mlc_case' => !empty($post_data['mlc_case']) ? 1 : 0,
            'police_informed' => !empty($post_data['police_informed']) ? 1 : 0,
            'crs_registration_no' => trim((string) $this->array_value($post_data, 'crs_registration_no')),
            'body_handed_over_to' => trim((string) $this->array_value($post_data, 'body_handed_over_to')),
            'informant_name' => trim((string) $this->array_value($post_data, 'informant_name')),
            'informant_relation' => trim((string) $this->array_value($post_data, 'informant_relation')),
            'informant_mobile' => trim((string) $this->array_value($post_data, 'informant_mobile')),
            'remarks' => trim((string) $this->array_value($post_data, 'remarks')),
            'entered_by' => $entered_by,
            'verified_by' => trim((string) $this->array_value($post_data, 'verified_by')),
            'created_at' => $now
        );

        $no_of_days = $this->calculate_days($episode['DoAdmission'], $death_date);
        $ipd_update = array(
            'DoDischarge' => $death_date,
            'discharge_time' => $death_time,
            'DischargeNotes' => 'Death: ' . $immediate_cause,
            'NofDays' => $no_of_days,
            'DischBy' => $certifying_doctor,
            'status' => 'death'
        );

        $this->db->trans_start();
        $this->db->insert($this->table, $death_data);
        $this->db->where('IpNo', $ipd_no)->update('inpatientdetails', $ipd_update);
        if (!empty($episode['BedNo'])) {
            $this->db->where('bedno', $episode['BedNo'])->update('bed_details', array(
                'OpdNo' => null,
                'bedstatus' => 'Available',
                'treatId' => null,
                'IpNo' => null
            ));
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return array('status' => false, 'message' => 'Failed to save death register.');
        }

        return array('status' => true, 'message' => 'Death register saved.', 'death_register_no' => $register_no);
    }

    public function get_records(array $conditions, $export_flag = false)
    {
        $this->db->start_cache();
        $this->db->from($this->table . ' dr');
        $this->db->where('dr.death_date >=', $conditions['start_date']);
        $this->db->where('dr.death_date <=', $conditions['end_date']);

        if (!empty($conditions['department']) && $conditions['department'] !== '1') {
            $this->db->where('dr.department', $conditions['department']);
        }
        if (!empty($conditions['IpNo'])) {
            $this->db->where('dr.ipd_no', $conditions['IpNo']);
        }
        if (!empty($conditions['OpdNo'])) {
            $this->db->where('dr.opd_no', $conditions['OpdNo']);
        }
        if (!empty($conditions['name'])) {
            $this->db->like('dr.patient_name', $conditions['name']);
        }
        $this->db->stop_cache();

        $found_rows = $this->db->count_all_results();
        $this->db->select('dr.*');
        $this->db->order_by('dr.death_date', 'ASC');
        $this->db->order_by('dr.id', 'ASC');

        if (!$export_flag) {
            $start = isset($conditions['start']) ? (int) $conditions['start'] : 0;
            $length = isset($conditions['length']) ? (int) $conditions['length'] : 25;
            $this->db->limit($length, $start);
        }

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        return array(
            'data' => $records,
            'found_rows' => $found_rows,
            'total_rows' => $this->db->count_all($this->table)
        );
    }

    public function get_statistics(array $conditions)
    {
        $this->db->select('department, COUNT(*) Total, SUM(CASE WHEN gender = "Male" THEN 1 ELSE 0 END) Male, SUM(CASE WHEN gender = "Female" THEN 1 ELSE 0 END) Female');
        $this->db->from($this->table);
        $this->db->where('death_date >=', $conditions['start_date']);
        $this->db->where('death_date <=', $conditions['end_date']);
        if (!empty($conditions['department']) && $conditions['department'] !== '1') {
            $this->db->where('department', $conditions['department']);
        }
        $this->db->group_by('department');
        return $this->db->get()->result_array();
    }

    private function generate_register_no($death_date)
    {
        $prefix = 'DR-' . date('Ymd', strtotime($death_date)) . '-';
        $count = $this->db
            ->like('death_register_no', $prefix, 'after')
            ->count_all_results($this->table);

        return $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    private function normalize_date($date, $allow_empty = false)
    {
        $date = trim((string) $date);
        if ($date === '' || $date === '--' || $date === '0000-00-00') {
            return $allow_empty ? null : false;
        }
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return false;
        }
        return date('Y-m-d', $timestamp);
    }

    private function calculate_days($admission_date, $death_date)
    {
        $admission = $this->normalize_date($admission_date, true);
        if (empty($admission)) {
            return null;
        }

        $start = new DateTime($admission);
        $end = new DateTime($death_date);
        $end->modify('+1 day');
        return $start->diff($end)->days;
    }

    private function array_value(array $data, $key, $default = '')
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }
}
