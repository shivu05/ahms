<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nursing_model
 *
 * @author Shivaraj
 */
class Nursing_model extends CI_Model {

    function get_xray_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('x.ID', 'x.OpdNo', 'x.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.LastName', 'p.Age',
            'p.gender', 'p.address', 'p.deptOpdNo', '(t.department) as department', 'x.xrayDate', 'x.xrayNo', 'x.partOfXray', 'x.filmSize', 't.deptOpdNo');

        $where_cond = " WHERE x.OpdNo = p.OpdNo AND x.treatID=t.ID AND x.xrayDate >='" . $conditions['start_date'] . "' AND x.xrayDate <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM xrayregistery x,
        (SELECT @a:= 0) AS a JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY x.xrayDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM xrayregistery x JOIN treatmentdata t WHERE x.OpdNo=t.OpdNo')->num_rows();
        return $return;
    }

    function get_usg_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('u.ID', 'u.OpdNo', 'u.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.MidName', 'p.LastName', 'p.Age',
            'p.gender', 'p.address', 'p.deptOpdNo', 'u.usgDate', 't.CameOn as entrydate', '(t.department) as department');

        $where_cond = " WHERE u.OpdNo = p.OpdNo AND u.treatId=t.ID AND u.usgDate >='" . $conditions['start_date'] . "' AND u.usgDate <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM usgregistery u,
        (SELECT @a:= 0) AS a JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY u.usgDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM usgregistery u JOIN treatmentdata t WHERE u.treatId=t.ID')->num_rows();
        return $return;
    }

    function get_ecg_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('e.ID', 'e.OpdNo', 'e.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.LastName', 'p.Age',
            'p.gender', 'p.address', 'p.deptOpdNo', 'refDate', 'e.ecgDate', '(t.department) as department');

        $where_cond = " WHERE e.OpdNo = p.OpdNo AND e.treatId=t.ID AND e.ecgDate >='" . $conditions['start_date'] . "' AND e.ecgDate <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM ecgregistery e,
        (SELECT @a:= 0) AS a JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY e.ecgDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM ecgregistery e JOIN treatmentdata t WHERE e.treatId=t.ID')->num_rows();
        return $return;
    }

//b.OpdNo,b.deliveryDetail,b.babyBirthDate,b.birthtime,b.treatby,b.babyWeight,b.deliverytype,i.IpNo,i.deptOpdNo,i.FName,i.Age,i.diagnosis
    function get_children_birth_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('b.OpdNo', 'b.deliveryDetail', 'b.babyBirthDate', 'b.birthtime', 'b.treatby', 'b.babyWeight', 'b.deliverytype', 'i.IpNo',
            'i.deptOpdNo', 'i.FName', 'i.Age', 'i.diagnosis');

        $where_cond = " WHERE b.OpdNo = i.OpdNo AND e.OpdNo=t.OpdNo AND b.babyBirthDate >='" . $conditions['start_date'] . "' AND b.babyBirthDate <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM ecgregistery e,
        (SELECT @a:= 0) AS a JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY e.ecgDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM ecgregistery e JOIN treatmentdata t WHERE e.OpdNo=t.OpdNo')->num_rows();
        return $return;
    }

    function get_diet_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('IpNo', 'OpdNo', 'FName', 'BedNo', 'diagnosis', 'DoAdmission', 'DoDischarge', 'department');

        $where_cond = " WHERE DoAdmission >='" . $conditions['start_date'] . "' AND DoAdmission <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM inpatientdetails,
        (SELECT @a:= 0) AS a $where_cond ORDER BY DoAdmission ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM inpatientdetails')->num_rows();
        return $return;
    }

    function get_ksharasutra_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('k.OpdNo', 't.ID', 'k.surgeon', 'k.ksharsType', 'k.ksharsDate', 'k.ksharaname', 'k.asssurgeon', 'k.anaesthetic',
            'CONCAT(p.FirstName," ",p.LastName) as name', 'p.Age', 'p.gender', 'p.address', 'p.deptOpdNo', 'p.dept', 't.diagnosis', 't.notes');

        $where_cond = " WHERE k.OpdNo = p.OpdNo AND k.OpdNo=t.OpdNo AND ksharsDate >='" . $conditions['start_date'] . "' AND ksharsDate <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM ksharsutraregistery k,
        (SELECT @a:= 0) AS a  JOIN patientdata p JOIN treatmentdata t $where_cond ORDER BY k.ksharsDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM ksharsutraregistery k JOIN treatmentdata t WHERE k.OpdNo=t.OpdNo')->num_rows();
        return $return;
    }

    function get_surgery_data($conditions, $export_flag = false) {
        $return = array();
        $columns = array('s.OpdNo', 'i.IpNo', 's.surgName', 's.surgType', 's.surgDate', 's.anaesthetic', 's.asssurgeon',
            's.surgeryname', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.Age', 'p.gender', 'p.address', 'p.deptOpdNo', 'p.dept', 's.surgType', 's.surgDate', 't.diagnosis', 't.notes');

        $where_cond = " WHERE s.OpdNo = p.OpdNo AND s.treatId = t.ID AND s.OpdNo=i.OpdNo AND surgDate >='" . $conditions['start_date'] . "' AND surgDate <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM surgeryregistery s, 
        (SELECT @a:= 0) AS a  JOIN patientdata p JOIN treatmentdata t JOIN inpatientdetails i  $where_cond ORDER BY surgDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM surgeryregistery s JOIN patientdata p JOIN treatmentdata t JOIN inpatientdetails i WHERE s.OpdNo = p.OpdNo AND s.treatId = t.ID AND s.OpdNo=i.OpdNo')->num_rows();
        return $return;
    }

    function get_panchakarma_data($conditions, $export_flag = false) {
        $return = array();
        $columns = array('l.opdno', 'p.deptOpdNo', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 't.AddedBy', 'p.MidName', 'p.LastName', 'p.Age', 'p.gender', 'p.address',
            'p.deptOpdNo', 'p.dept', 'l.disease', 'l.treatment', 'l.procedure', 'l.date', 't.notes', 'l.docname');

        $where_cond = " WHERE l.opdno = p.OpdNo AND l.treatid = t.ID  AND l.date >='" . $conditions['start_date'] . "' AND l.date <='" . $conditions['end_date'] . "'";

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
                        $where_cond .= ($val != 1) ? " AND UPPER(replace(t.department,' ','_')) = '$val'" : '';
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM panchaprocedure l
        JOIN patientdata p ON l.opdno = p.OpdNo JOIN treatmentdata t ON l.treatid = t.ID ,(SELECT @a:= 0) AS a $where_cond ORDER BY date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM panchaprocedure l JOIN patientdata p ON l.opdno = p.OpdNo JOIN treatmentdata t ON l.treatid = t.ID')->num_rows();
        return $return;
    }

    function get_panchakarma_procedure_count($conditions, $export_flag = false) {
        $query = "SELECT * from panchaprocedure p WHERE p.date >='" . $conditions['start_date'] . "' AND p.date <= '" . $conditions['end_date'] . "'";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_surgery_count($conditions, $export_flag = false) {
        $query = "SELECT surgeryname,count(surgeryname) as count,surgType from surgeryregistery WHERE surgDate >= '" . $conditions['start_date'] . "' 
            AND surgDate <= '" . $conditions['end_date'] . "' AND surgeryname !='' group by surgeryname";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_lab_report($conditions, $export_flag = false) {
        $query = "SELECT p.deptOpdNo,t.AddedBy,l.OpdNo,p.FirstName,p.MidName,p.LastName,p.Age,p.gender,p.address,p.deptOpdNo,p.dept,l.testName,l.testrange,l.testvalue,l.labdisease,l.testName,l.testDate,t.notes 
            FROM labregistery l,patientdata p,treatmentdata t WHERE l.OpdNo = p.OpdNo AND l.treatID = t.ID AND l.testDate >='" . $conditions['start_date'] . "' AND  l.testDate <= '" . $conditions['end_date'] . "' order by l.testDate asc";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_lab_report_count($conditions, $export_flag = false) {
        $query = "SELECT * FROM labregistery WHERE testDate >= '" . $conditions['start_date'] . "' AND testDate<= '" . $conditions['end_date'] . "'";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

}
