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
class ipd_model extends CI_Model {

    function get_ipd_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('IpNo', 'OpdNo', 'deptOpdNo', 'FName', 'Age', 'Gender', 'department', 'WardNo', 'BedNo', 'diagnosis', 'DoAdmission', 'DoDischarge',
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
                        $where_cond .= ($val != 1) ? " AND t.department = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

//$query = "SELECT " . join(',', $columns) . " FROM patientdata $where_cond";
        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM inpatientdetails,
        (SELECT @a:= 0) AS a  $where_cond ORDER BY DoAdmission ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM inpatientdetails')->num_rows();
        return $return;
    }

    function get_ipd_patients_count($conditions) {

        if ($conditions['department'] == 1) {
            $query = "SELECT t.department,count(*) as Total,SUM(case when t.Gender='Male' then 1 else 0 end) Male,
			SUM(case when t.Gender='Female' then 1 else 0 end) Female 
			FROM (`inpatientdetails` t) WHERE DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') >= '" . $conditions['start_date'] . "' 
			AND DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') <= '" . $conditions['end_date'] . "' 
			group by t.department";
        } else {
            $query = "SELECT department,Total,Male,Female from( SELECT t.department,count(*) as Total,SUM(case when t.Gender='Male' then 1 else 0 end) Male,
			SUM(case when t.Gender='Female' then 1 else 0 end) Female 
			FROM (`inpatientdetails` t) WHERE DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') >= '" . $conditions['start_date'] . "' 
			AND DATE_FORMAT(t.DoAdmission,'%Y-%m-%d') <= '" . $conditions['end_date'] . "' AND t.department='" . $conditions['department'] . "'
			group by t.department
			UNION ALL select department,0 Total,0 Male,0 Female from deptper ) A group by department;";
        }
        return $this->db->query($query)->result_array();
    }

    function get_bed_occpd_patients($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('IpNo', 'OpdNo', 'deptOpdNo', 'FName', 'Age', 'Gender', 'department', 'WardNo', 'BedNo', 'diagnosis', 'DoAdmission', 'DoDischarge',
            'DischargeNotes', 'NofDays', 'Doctor', 'DischBy', 'treatId');

        //$where_cond = " WHERE 1 = 1 ";
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
        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM inpatientdetails,
 (SELECT @a:=0) AS a $where_cond ORDER BY DoAdmission ASC";
        $result = $this->db->query($query . ' ' . $limit);
        //echo $this->db->last_query();exit;
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM inpatientdetails')->num_rows();
        return $return;
    }

    function get_bed_occupied_statistics($where) {
        $query = $this->db->query("CALL get_bed_occupancy('" . $where['department'] . "', '" . $where['start_date'] . "', '" . $where['end_date'] . "')");
        mysqli_next_result($this->db->conn_id); //imp
        if ($query->num_rows() > 0) {
            return $query->result_array(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_departmentwise_bed_count() {
        $query = $this->db->query("SELECT count(*) as sum,department from bed_details where department NOT IN ('Aatyayikachikitsa','Swasthavritta') group by department");
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_monthwise_bed_occupancy($month, $dept) {
        $query = $this->db->query("SELECT sum(nofDays) as sum FROM inpatientdetails where department='$dept' AND MONTHNAME(DoAdmission)='$month';");
        return $query->result();
    }

}
