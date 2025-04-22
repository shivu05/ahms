<?php

/**
 * Description of opd_model
 *
 * @author Shivaraj
 */
class Opd_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_patients($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array(
            'OpdNo',
            'FirstName',
            'LastName',
            'Age',
            'gender',
            'city',
            'occupation',
            'address',
            '(CASE WHEN sid IS NULL THEN "##" ELSE sid END) sid'
        );
        $where_cond = " WHERE 1=1 ";
        $limit = ' LIMIT 100';
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
                        $where_cond .= " AND OpdNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND CONCAT(FirstName,' ',LastName) LIKE '%$val%'";
                        break;
                    case 'sid':
                        $where_cond .= " AND sid LIKE '%$val%'";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('patientdata');
        return $return;
    }

    public function get_patients_screening($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array(
            'OpdNo',
            'FirstName',
            'LastName',
            'Age',
            'gender',
            'city',
            'occupation',
            'address',
            '(CASE WHEN sid IS NULL THEN "##" ELSE sid END) sid'
        );
        $where_cond = " WHERE 1=1 ";
        $limit = ' LIMIT 100';
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
                        $where_cond .= " AND OpdNo='$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND CONCAT(FirstName,' ',LastName) LIKE '%$val%'";
                        break;
                    case 'sid':
                        $where_cond .= " AND sid LIKE '%$val%'";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond AND OpdNo NOT IN (SELECT OpdNo FROM treatmentdata)";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('patientdata');
        return $return;
    }

    function get_patient_by_opd($opd)
    {
        $patient_data = $this->db->get_where('patientdata', array('OpdNo' => $opd));
        return $patient_data->row_array();
    }

    public function store_treatment($action, $form_data, $opdNo = NULL)
    {
        if ($action == "add") {
            $status = $this->db->insert('treatmentdata', $form_data);
        } else if ($action == "edit") {
            $where = array('OpdNo' => $opdNo);
            $status = $this->db->update('treatmentdata', $form_data, $where);
        }
        return $status;
    }

    function get_opd_patients($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array(
            't.ID',
            't.monthly_sid as msd',
            't.OpdNo',
            't.deptOpdNo',
            't.PatType',
            'CONCAT(COALESCE(FirstName,"")," ",COALESCE(LastName,"")) as name',
            'Age',
            'gender',
            'address',
            'city',
            't.diagnosis',
            't.Trtment',
            't.AddedBy',
            '(REPLACE((t.department),"_"," ")) department',
            'CameOn',
            'd.ref_room ref_dept',
            't.sequence',
            'COALESCE(t.sub_department,"") sub_department'
        );

        $where_cond = " WHERE CameOn >='" . $conditions['start_date'] . "' AND CameOn <='" . $conditions['end_date'] . "' ";
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
                    case 'sub_department':
                        $where_cond .= ($val != 'both') ? " AND t.sub_department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        //$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";

        $query = "SELECT " . implode(',', $columns) . " 
            FROM treatmentdata t 
            JOIN patientdata p ON t.OpdNo=p.OpdNo 
            JOIN deptper d ON t.department=d.dept_unique_code $where_cond ORDER BY t.ID ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function get_opd_patients_excel($conditions, $export_flag = FALSE)
    {
        $return = array();
        $columns = array(
            't.sequence',
            't.monthly_sid as msd',
            't.OpdNo',
            't.deptOpdNo',
            'CASE WHEN (t.PatType="New Patient") THEN "N" ELSE "O" END as PatType',
            'CONCAT(COALESCE(FirstName,"")," ",COALESCE(LastName,"")) as name',
            'Age',
            'gender',
            'CONCAT(address," ", city) place',
            't.diagnosis',
            't.Trtment',
            't.AddedBy',
            '(REPLACE((t.department),"_"," ")) department',
            'COALESCE(t.sub_department,"-") sub_department',
            'CameOn',
            'd.ref_room ref_dept'
        );

        $where_cond = " WHERE CameOn >='" . $conditions['start_date'] . "' AND CameOn <='" . $conditions['end_date'] . "' ";
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
                    case 'sub_department':
                        $where_cond .= ($val != 'both') ? " AND t.sub_department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        //$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";

        $query = "SELECT " . implode(',', $columns) . " 
            FROM treatmentdata t 
            JOIN patientdata p ON t.OpdNo=p.OpdNo 
            JOIN deptper d ON t.department=d.dept_unique_code $where_cond ORDER BY t.ID ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }
}
