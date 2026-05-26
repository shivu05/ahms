<?php

/**
 * Description of Bed_details
 *
 * @author shiva
 */
class Bed_details extends SHV_Model {

    private $_department_short_codes = array(
        'KAYACHIKITSA' => 'KC',
        'BALAROGA' => 'BL',
        'PANCHAKARMA' => 'PM',
        'SHALAKYA_TANTRA' => 'SK',
        'SHALYA_TANTRA' => 'SY',
        'PRASOOTI_&_STRIROGA' => 'PS',
        'SWASTHAVRITTA' => 'SW',
        'AATYAYIKACHIKITSA' => 'AA',
        'AGADATANTRA' => 'AG',
        'SPECIALIZED' => 'SP'
    );

    public function get_beds_with_patient_details()
    {
        $columns = array(
            'b.*',
            'i.IpNo AS current_ipd_no',
            'i.OpdNo AS current_opd_no',
            'COALESCE(NULLIF(TRIM(CONCAT_WS(" ", p.FirstName, p.MidName, p.LastName)), ""), i.FName) AS patient_name',
            'COALESCE(p.Age, i.Age) AS patient_age',
            'COALESCE(p.gender, i.Gender) AS patient_gender',
            'i.status AS ipd_status'
        );

        $this->db->select(join(',', $columns), false);
        $this->db->from('bed_details b');
        $this->db->join(
            'inpatientdetails i',
            "i.status = 'stillin' AND (
                i.treatId = b.treatId
                OR (b.IpNo IS NOT NULL AND b.IpNo != '' AND i.IpNo = b.IpNo)
                OR (b.OpdNo IS NOT NULL AND b.OpdNo != '' AND i.OpdNo = b.OpdNo AND i.BedNo = b.bedno)
            )",
            'left',
            false
        );
        $this->db->join('patientdata p', 'p.OpdNo = i.OpdNo', 'left');
        $this->db->where('b.bedno !=', 0);
        $this->db->order_by('CAST(b.bedno AS UNSIGNED)', 'ASC', false);
        $this->db->order_by('b.id', 'ASC');

        $beds = $this->db->get()->result_array();
        foreach ($beds as &$bed) {
            $bed['bed_short_code'] = $this->build_bed_short_code($bed['department'], $bed['bedno']);
        }

        return $beds;
    }

    private function build_bed_short_code($department, $bed_no)
    {
        $department = strtoupper(trim($department));
        $short_code = isset($this->_department_short_codes[$department]) ? $this->_department_short_codes[$department] : $department;

        if ($bed_no === null || $bed_no === '') {
            return $short_code;
        }

        return $short_code . '-' . str_pad($bed_no, 2, '0', STR_PAD_LEFT);
    }
}
