<?php

/**
 * Description of Patient_reports
 *
 * @author shivarajkumar.badige
 */
class Patient_reports extends CI_Model
{

    private $_labregister;

    public function __construct()
    {
        parent::__construct();
        $this->_labregister = 'labregistery';
    }

    function fetch_patient_lab_details($treat_id = null)
    {
        if ($treat_id) {
            $columns = array(
                'l.treatID',
                'l.OpdNo',
                'ip.IpNo ipdno',
                'CONCAT(p.FirstName," ", p.LastName) as name',
                'p.Age',
                'p.gender',
                't.deptOpdNo',
                't.diagnosis as labdisease',
                't.department',
                'l.refDocName',
                'l.testDate',
                ' GROUP_CONCAT(testrange SEPARATOR "#") testrange',
                'GROUP_CONCAT(testvalue SEPARATOR "#") testvalue',
                'GROUP_CONCAT(lt.lab_test_name) lab_test_type',
                'GROUP_CONCAT(lc.lab_cat_name) lab_test_cat',
                'GROUP_CONCAT(li.lab_inv_name) testName',
                'l.testDate',
                'l.refDocName',
                'l.tested_date'
            );
            $query = "SELECT @a:=@a+1 serial_number, " . implode(',', $columns) . "
                FROM labregistery l
                JOIN patientdata p ON l.OpdNo = p.OpdNo 
                LEFT JOIN inpatientdetails ip ON l.treatID=ip.treatId
                JOIN treatmentdata t ON l.treatID = t.ID
                JOIN lab_investigations li ON li.lab_inv_id=l.testName
                JOIN lab_tests lt ON li.lab_test_id=lt.lab_test_id
                JOIN lab_categories lc ON lt.lab_cat_id = lc.lab_cat_id,
                (SELECT @a:= 0) AS a
                WHERE l.testName <>'' AND l.treatID ='" . $treat_id . "' group by l.treatID order by l.tested_date asc";
            $result = $this->db->query($query);
            return $result->row_array();
        }
        return false;
    }

    function get_swarnaprashana_report($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array(
            'k.opd_no',
            't.PatType',
            't.deptOpdNo',
            'CONCAT(FirstName," ",LastName) as name',
            'FirstName',
            'LastName',
            'p.Age',
            'p.gender',
            't.AddedBy',
            'p.city',
            'Trtment',
            't.diagnosis',
            'CameOn',
            'attndedby',
            '(REPLACE((t.department),"_"," ")) department',
            'sub_department sub_dept',
            'date_month',
            'dose_time',
            'consultant'
        );

        $where_cond = " WHERE k.opd_no=t.OpdNo AND k.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND date_month >='" . $conditions['start_date'] . "'
                AND date_month <='" . $conditions['end_date'] . "'";
        //$where_cond = " WHERE 1=1 ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'OpdNo':
                        $where_cond .= " AND OpdNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND CONCAT(FirstName,' ',LastName) LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        //$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";
        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . "
            FROM swarnaprashana k
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            $where_cond ORDER BY date_month ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM swarnaprashana k
            JOIN treatmentdata t ON k.opd_no=t.OpdNo AND k.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function get_agnikarma_report($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array('a.id', 'a.opd_no', 'a.ipd_no', 'p.FirstName', 'p.Age', 'p.gender', 't.diagnosis', 'a.treat_id', 'a.ref_date', 'a.doctor_name', 'a.treatment_notes', 'a.last_updates');

        $where_cond = " WHERE a.opd_no=t.OpdNo AND a.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND a.ref_date >='" . $conditions['start_date'] . "'
            AND a.ref_date <='" . $conditions['end_date'] . "'";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'opd_no':
                        $where_cond .= " AND a.opd_no='$val'";
                        break;
                    case 'doctor_name':
                        $where_cond .= " AND a.doctor_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . "
            FROM agnikarma_opd_ipd_register a
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            $where_cond ORDER BY a.ref_date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM agnikarma_opd_ipd_register a
            JOIN treatmentdata t ON a.opd_no=t.OpdNo AND a.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function get_cupping_report($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array('c.id', 'c.opd_no', 'c.ipd_no', 'p.FirstName', 'p.Age', 'p.gender', 't.diagnosis', 'c.treat_id', 'c.ref_date', 'c.doctor_name', 'c.type_of_cupping', 'c.site_of_application', 'c.no_of_cups_used', 'c.treatment_notes', 'c.last_updates');

        $where_cond = " WHERE c.opd_no=t.OpdNo AND c.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND c.ref_date >='" . $conditions['start_date'] . "'
            AND c.ref_date <='" . $conditions['end_date'] . "'";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'opd_no':
                        $where_cond .= " AND c.opd_no='$val'";
                        break;
                    case 'doctor_name':
                        $where_cond .= " AND c.doctor_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . "
            FROM cupping_opd_ipd_register c
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            $where_cond ORDER BY c.ref_date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM cupping_opd_ipd_register c
            JOIN treatmentdata t ON c.opd_no=t.OpdNo AND c.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function get_jaloukavacharana_report($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array('j.id', 'j.opd_no', 'j.ipd_no', 'p.FirstName', 'p.Age', 'p.gender', 't.diagnosis', 'j.treat_id', 'j.ref_date', 'j.doctor_name', 'j.procedure_details', 'j.doctor_remarks', 'j.last_updated');

        $where_cond = " WHERE j.opd_no=t.OpdNo AND j.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND j.ref_date >='" . $conditions['start_date'] . "'
            AND j.ref_date <='" . $conditions['end_date'] . "'";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'opd_no':
                        $where_cond .= " AND j.opd_no='$val'";
                        break;
                    case 'doctor_name':
                        $where_cond .= " AND j.doctor_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . "
            FROM jaloukavacharana_opd_ipd_register j
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            $where_cond ORDER BY j.ref_date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM jaloukavacharana_opd_ipd_register j
            JOIN treatmentdata t ON j.opd_no=t.OpdNo AND j.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function get_siravyadana_report($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array('s.id', 's.opd_no', 's.ipd_no', 'p.FirstName', 'p.Age', 'p.gender', 't.diagnosis', 's.treat_id', 's.ref_date', 's.doctor_name', 's.procedure_details', 's.doctor_remarks', 's.last_updated');

        $where_cond = " WHERE s.opd_no=t.OpdNo AND s.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND s.ref_date >='" . $conditions['start_date'] . "'
            AND s.ref_date <='" . $conditions['end_date'] . "'";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'opd_no':
                        $where_cond .= " AND s.opd_no='$val'";
                        break;
                    case 'doctor_name':
                        $where_cond .= " AND s.doctor_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . "
            FROM siravyadana_opd_ipd_register s
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            $where_cond ORDER BY s.ref_date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM siravyadana_opd_ipd_register s
            JOIN treatmentdata t ON s.opd_no=t.OpdNo AND s.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function get_wound_dressing_report($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array('w.id', 'w.opd_no', 'w.ipd_no', 'p.FirstName', 'p.Age', 'p.gender', 't.diagnosis', 'w.treat_id', 'w.ref_date', 'w.wound_location', 'w.wound_type', 'w.dressing_material', 'w.doctor_name', 'w.next_dressing_date', 'w.doctor_remarks', 'w.last_updated');

        $where_cond = " WHERE w.opd_no=t.OpdNo AND w.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND w.ref_date >='" . $conditions['start_date'] . "'
            AND w.ref_date <='" . $conditions['end_date'] . "'";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'opd_no':
                        $where_cond .= " AND w.opd_no='$val'";
                        break;
                    case 'doctor_name':
                        $where_cond .= " AND w.doctor_name LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . "
            FROM wound_dressing_opd_ipd_register w
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            $where_cond ORDER BY w.ref_date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM wound_dressing_opd_ipd_register w
            JOIN treatmentdata t ON w.opd_no=t.OpdNo AND w.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }
}
