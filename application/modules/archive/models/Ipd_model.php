<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ipd_model
 *
 * @author Shivaraj
 */
class Ipd_model extends CI_Model {

    function get_ipd_patients($db, $conditions, $export_flag = FALSE) {
        // $query = "UPDATE inpatientdetails i, bed_details b SET i.WardNo=b.wardno WHERE i.BedNo = b.bedno";
        //$this->db->query($query);

        if (empty($db)) {
            return false;
        }
        unset($conditions['arch_year']);

        $return = array();
        $columns = array('IpNo', 'OpdNo', 'deptOpdNo', 'FName', 'Age', 'Gender', '(REPLACE((department),"_"," ")) department',
            'WardNo', 'BedNo', 'diagnosis', 'DoAdmission', 'DoDischarge',
            'DischargeNotes', 'NofDays', 'Doctor', 'DischBy', 'treatId');

        $where_cond = " WHERE DoAdmission >='" . $conditions['start_date'] . "' AND DoAdmission <='" . $conditions['end_date'] . "'";
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
                        $where_cond .= ($val != 1) ? " AND department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

//$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";
        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM inpatientdetails,
        (SELECT @a:= 0) AS a  $where_cond ORDER BY DoAdmission ASC";
        $result = $db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $db->query($query)->num_rows();
        $return['total_rows'] = $db->query('SELECT * FROM inpatientdetails')->num_rows();
        return $return;
    }

    function get_ipd_patients_count($db, $conditions) {

        if ($conditions['department'] == 1) {
            $query = "SELECT (REPLACE((department),'_',' ')) department,Total,Male,Female FROM(
                        SELECT t.department,count(*) as Total,SUM(case when t.Gender='Male' then 1 else 0 end) Male,
			SUM(case when t.Gender='Female' then 1 else 0 end) Female 
			FROM (`inpatientdetails` t) WHERE DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') >= '" . $conditions['start_date'] . "' 
			AND DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') <= '" . $conditions['end_date'] . "' 
			group by t.department
                        UNION ALL select dept_unique_code department,0 Total,0 Male,0 Female from deptper ) A group by department";
        } else {
            $query = "SELECT department,Total,Male,Female from( SELECT t.department,count(*) as Total,SUM(case when t.Gender='Male' then 1 else 0 end) Male,
			SUM(case when t.Gender='Female' then 1 else 0 end) Female 
			FROM (`inpatientdetails` t) WHERE DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') >= '" . $conditions['start_date'] . "' 
			AND DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') <= '" . $conditions['end_date'] . "' AND t.department='" . $conditions['department'] . "'
			group by t.department
			UNION ALL select dept_unique_code department,0 Total,0 Male,0 Female from deptper ) A group by department";
        }

        //echo $query;exit;
        return $db->query($query)->result_array();
    }

    function get_bed_occpd_patients($db, $conditions, $export_flag = FALSE) {
        $return = array();
        //pma($db->database, 1);
        $this->load->dbutil();
        if (empty($db->database)) {
            $return['data'] = array();
            $return['found_rows'] = 0;
            $return['total_rows'] = 0;
            return $return;
        }
        unset($conditions['arch_year']);

        $columns = array('IpNo', 'OpdNo', 'deptOpdNo', 'FName', 'Age', 'Gender', '(REPLACE((department),"_"," ")) department', 'WardNo', 'BedNo', 'diagnosis',
            'DoAdmission', 'DoDischarge', 'DischargeNotes', 'NofDays', 'Doctor', 'DischBy', 'treatId');

        $where_cond = " WHERE ((DoAdmission <= '" . $conditions['start_date'] . "' AND DoDischarge >= '" . $conditions['end_date'] . "')
          OR (DoAdmission <= '" . $conditions['start_date'] . "' AND status = 'stillin')) ";
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
                        $where_cond .= " AND OpdNo = '$val'";
                        break;
                    case 'name':
                        $where_cond .= " AND CONCAT(FirstName, ' ', LastName) LIKE '%$val%'";
                        break;
                    case 'department':
                        $where_cond .= ($val != 1) ? " AND department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        //$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";
        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM inpatientdetails, (SELECT @a:=0) AS a $where_cond ORDER BY DoAdmission ASC";
        $result = $db->query($query . ' ' . $limit);
        //echo $this->db->last_query();exit;
        $return['data'] = $result->result_array();
        $return['found_rows'] = $db->query($query)->num_rows();
        $return['total_rows'] = $db->query('SELECT * FROM inpatientdetails')->num_rows();
        return $return;
    }

    function get_bed_occupied_statistics($db, $where) {
        $query = $db->query("CALL get_bed_occupancy('" . $where['department'] . "', '" . $where['start_date'] . "', '" . $where['end_date'] . "')");
        mysqli_next_result($db->conn_id); //imp
        if ($query->num_rows() > 0) {
            return $query->result_array(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_departmentwise_bed_count($db) {
        $query = $db->query("SELECT count(*) as sum,department from bed_details where department NOT IN ('Aatyayikachikitsa','Swasthavritta') group by department");
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_bed_count_by_dept() {
        $query = "select bed_count,dept_unique_code department from deptper";
        return $this->db->query($query)->result_array();
    }

    function get_monthwise_bed_occupancy($db, $month, $dept) {
        $query = $db->query("SELECT coalesce(sum(nofDays),0) as sum FROM inpatientdetails where department='$dept' AND MONTHNAME(DoAdmission)='$month'");
        return $query->result();
    }

    function get_month_wise_opd_ipd_report($db) {
        $dept_res = $db->select('dept_unique_code')->get('deptper');
        $dept_res = $dept_res->result_array();
        $opd_ipd_arr = array();
        if (!empty($dept_res)) {
            $deptwise_opd_ipd_arr = array();
            foreach ($dept_res as $dept) {
                $dept_opd_ipd_res = $db->query("call get_monthly_opd_ipd_dept_wise('" . $dept['dept_unique_code'] . "');");
                $deptwise_opd_ipd_arr[$dept['dept_unique_code']] = $dept_opd_ipd_res->result_array();
                mysqli_next_result($db->conn_id); //imp
                $dept_ipd_res = $db->query("call get_ipd_patient_count_dept_wise('" . $dept['dept_unique_code'] . "');");
                $deptwise_ipd_arr[$dept['dept_unique_code']] = $dept_ipd_res->result_array();
                mysqli_next_result($db->conn_id); //imp
            }
            $opd_ipd_arr['opd'] = $deptwise_opd_ipd_arr;
            $opd_ipd_arr['ipd'] = $deptwise_ipd_arr;
            return $opd_ipd_arr;
        } else {
            return false;
        }
    }

    function get_month_wise_ipd_report($db) {
        $dept_res = $db->select('dept_unique_code')->get('deptper');
        $dept_res = $dept_res->result_array();
        if (!empty($dept_res)) {
            $deptwise_ipd_arr = array();
            foreach ($dept_res as $dept) {
                $dept_ipd_res = $db->query("call get_ipd_patient_count_dept_wise('" . $dept['dept_unique_code'] . "');");
                $deptwise_ipd_arr[$dept['dept_unique_code']] = $dept_ipd_res->result_array();
                mysqli_next_result($db->conn_id); //imp
            }
            return $deptwise_ipd_arr;
        } else {
            return false;
        }
    }

}
