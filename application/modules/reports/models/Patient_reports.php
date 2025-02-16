<?php

/**
 * Description of Patient_reports
 *
 * @author shivarajkumar.badige
 */
class Patient_reports extends CI_Model {

    private $_labregister;

    public function __construct() {
        parent::__construct();
        $this->_labregister = 'labregistery';
    }

    function fetch_patient_lab_details($treat_id = null) {
        if ($treat_id) {
            $columns = array('l.treatID', 'l.OpdNo', 'ip.IpNo ipdno', 'CONCAT(p.FirstName," ", p.LastName) as name', 'p.Age', 'p.gender', 't.deptOpdNo',
                't.diagnosis as labdisease', 't.department', 'l.refDocName', 'l.testDate',
                ' GROUP_CONCAT(testrange SEPARATOR "#") testrange', 'GROUP_CONCAT(testvalue SEPARATOR "#") testvalue', 'GROUP_CONCAT(lt.lab_test_name) lab_test_type',
                'GROUP_CONCAT(lc.lab_cat_name) lab_test_cat', 'GROUP_CONCAT(li.lab_inv_name) testName', 'l.testDate', 'l.refDocName', 'l.tested_date');
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

    function get_swarnaprashana_report($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('k.opd_no', 't.PatType', 't.deptOpdNo', 'CONCAT(FirstName," ",LastName) as name', 'FirstName', 'LastName', 'p.Age',
            'p.gender', 't.AddedBy', 'p.city', 'Trtment', 't.diagnosis', 'CameOn', 'attndedby',
            '(REPLACE((t.department),"_"," ")) department', 'sub_department sub_dept', 'date_month', 'dose_time', 'consultant');

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

    function get_agnikarma_report($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('a.id', 'a.opd_no', 'a.ipd_no', 'p.FirstName','p.Age','p.gender','t.diagnosis','a.treat_id', 'a.ref_date', 'a.doctor_name', 'a.treatment_notes', 'a.last_updates');

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
}
