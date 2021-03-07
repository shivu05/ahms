<?php

/**
 * Description of nursing_model
 *
 * @author Shivaraj
 */
class Nursing_model extends CI_Model {

    function get_xray_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('x.ID', 'x.OpdNo', 'x.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.LastName', 'p.Age',
            'p.gender', 'p.address', 'p.deptOpdNo', '(REPLACE(ucfirst(t.department),"_"," ")) as department',
            'display_date(x.refDate) as refDate', 'display_date(x.xrayDate) as xrayDate', 'x.xrayNo', 'x.partOfXray', 'x.filmSize',
            't.deptOpdNo', 't.diagnosis');

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

    function update_xray_info($post_valyes, $id) {
        if ($id) {
            return $this->db->update('xrayregistery', $post_valyes, array('ID' => $id));
        }
        return false;
    }

    function get_usg_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('u.ID', 'u.OpdNo', 'u.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.MidName', 'p.LastName', 'p.Age',
            'p.gender', 'p.address', 't.deptOpdNo', 'u.usgDate', 't.CameOn as entrydate',
            '(REPLACE(ucfirst(t.department),"_"," ")) as department','t.diagnosis');

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
            'p.gender', 'p.address', 'p.deptOpdNo', 'refDate', 'e.ecgDate', '(REPLACE(ucfirst(t.department),"_"," ")) as department', 't.diagnosis');

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

    function get_birth_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('b.OpdNo', 'b.deliveryDetail', 'b.babyBirthDate', 'b.birthtime', 'b.treatby', 'b.babyWeight', 'b.deliverytype', 'i.IpNo', 'i.deptOpdNo',
            'i.FName', 'i.Age', 'i.diagnosis');

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

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM birthregistery b
        JOIN inpatientdetails i ON b.OpdNo = i.OpdNo ,(SELECT @a:= 0) AS a $where_cond ORDER BY DoAdmission ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM birthregistery')->num_rows();
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
        $columns = array('l.opdno', 'p.deptOpdNo', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 't.AddedBy', 'p.LastName', 'p.Age', 'p.gender', 'p.address',
            't.deptOpdNo', '(REPLACE(ucfirst(t.department),"_"," ")) dept', 't.diagnosis disease', 'GROUP_CONCAT(treatment) as treatment',
            'GROUP_CONCAT(`procedure`) as `procedure`', 'GROUP_CONCAT(l.date) as `date`', 't.notes', 'docname',
            'GROUP_CONCAT(proc_end_date) as proc_end_date');

        /* $where_cond = " WHERE l.opdno = p.OpdNo AND l.treatid = t.ID  AND ((l.proc_end_date >='" . $conditions['start_date'] . "' 
          AND l.proc_end_date <='" . $conditions['end_date'] . "')) ";
          //OR (l.proc_end_date <='" . $conditions['end_date'] . "')) "; */
        $where_cond = " WHERE l.opdno = p.OpdNo AND l.treatid = t.ID  AND (l.proc_end_date >='" . $conditions['start_date'] . "' 
            AND l.proc_end_date <='" . $conditions['end_date'] . "') ";

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
        JOIN patientdata p ON l.opdno = p.OpdNo JOIN treatmentdata t ON l.treatid = t.ID ,(SELECT @a:= 0) AS a $where_cond group by l.treatid ORDER BY date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        //echo $this->db->last_query();exit;
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM panchaprocedure l JOIN treatmentdata t ON l.treatid = t.ID JOIN patientdata p ON t.OpdNo = p.OpdNo')->num_rows();
        return $return;
    }

    function get_panchakarma_procedure_count($conditions, $export_flag = false) {
        $query = "select treatment,`procedure`, count(`procedure`) as procedure_count from panchaprocedure p 
             WHERE p.date >='" . $conditions['start_date'] . "' AND p.proc_end_date <= '" . $conditions['end_date'] . "' group by treatment,`procedure`";
        $query = $this->db->query($query);
        //echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            return $query->result_array(); //if data is true
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
        //'GROUP_CONCAT(li.lab_test_reference) testrange',
        $columns = array('l.OpdNo', 'CONCAT(p.FirstName," ", p.LastName) as name', 'p.Age', 'p.gender', 'p.deptOpdNo',
            't.diagnosis as labdisease', 't.department', 'l.refDocName', 'l.testDate',
            ' GROUP_CONCAT(testrange) testrange', 'GROUP_CONCAT(testvalue) testvalue', 'GROUP_CONCAT(lt.lab_test_name) lab_test_type',
            'GROUP_CONCAT(lc.lab_cat_name) lab_test_cat', 'GROUP_CONCAT(li.lab_inv_name) testName', 'l.testDate', 'l.refDocName', 'l.tested_date');
        $query = "SELECT @a:=@a+1 serial_number, " . implode(',', $columns) . "
                FROM labregistery l,(SELECT @a:= 0) AS a 
                JOIN patientdata p 
                JOIN treatmentdata t   
                JOIN lab_investigations li 
                JOIN lab_tests lt
                JOIN lab_categories lc
                WHERE l.OpdNo = p.OpdNo AND l.treatID = t.ID  AND li.lab_inv_id=l.testName 
                AND l.lab_test_type=lt.lab_test_id AND l.lab_test_cat = lc.lab_cat_id
                AND l.testName <>'' AND l.tested_date >='" . $conditions['start_date'] . "' AND  l.tested_date <= '" . $conditions['end_date'] . "' group by l.treatID order by l.tested_date asc";
        $result = $this->db->query($query);
        //echo $this->db->last_query();exit;
        if ($result->num_rows() > 0) {
            return $result->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_lab_report_count($conditions, $export_flag = false) {
        //$query = "SELECT * FROM labregistery WHERE testDate >= '" . $conditions['start_date'] . "' AND testDate<= '" . $conditions['end_date'] . "'";
        $query = "SELECT lab_inv_name, lab_cat_name,count(lab_inv_name) as ccount 
                  FROM labregistery l 
                  JOIN lab_investigations li ON li.lab_inv_id=l.testName 
                  JOIN lab_tests lt ON l.lab_test_type=lt.lab_test_id 
                  JOIN lab_categories lc ON l.lab_test_cat = lc.lab_cat_id 
                  GROUP BY lab_inv_name";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result_array(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function delete_record($table, $where) {
        return $this->db->delete($table, $where);
    }

    function get_kriyakalp_data($conditions, $export_flag = FALSE) {
        $return = array();
        $columns = array('t.OpdNo', 't.PatType', 't.deptOpdNo', 'CONCAT(FirstName," ",LastName) as name', 'FirstName', 'LastName', 'p.Age', 'p.gender', 't.AddedBy',
            'p.city', 'Trtment', 't.diagnosis', 'CameOn', 'attndedby', '(REPLACE(ucfirst(t.department),"_"," ")) department', 't.procedures', 'sub_dept', 'IpNo');

        $where_cond = " WHERE t.OpdNo=p.OpdNo AND LOWER(t.department)=LOWER('SHALAKYA_TANTRA') AND CameOn >='" . $conditions['start_date'] . "' AND CameOn <='" . $conditions['end_date'] . "'";
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
            FROM treatmentdata t, (SELECT @a:= 0) AS a 
            JOIN patientdata p 
            LEFT JOIN inpatientdetails ip ON ip.OpdNo=p.OpdNo
            $where_cond ORDER BY CameOn ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo LEFT JOIN inpatientdetails ip ON ip.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

}
