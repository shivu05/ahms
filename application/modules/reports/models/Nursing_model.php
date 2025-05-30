<?php

/**
 * Description of nursing_model
 *
 * @author Shivaraj
 */
class Nursing_model extends CI_Model {

    function get_nursing_ipd_indent($start_date, $end_date, $department) {
        $this->db->query("set session group_concat_max_len = 5000");
        if ($department == "1") {
            $query = "SELECT p.OpdNo,i.ipdno,p.deptOpdNo,p.DoAdmission,p.DoDischarge,p.DischargeNotes,p.Doctor,p.BedNo,p.FName,p.IpNo,
                p.Age,p.Gender,p.department,p.Doctor,GROUP_CONCAT(i.product) as product,
                GROUP_CONCAT(i.indentdate order by i.indentdate asc) as indentdate,p.WardNo,(REPLACE((p.department),'_',' ')) department
                from inpatientdetails p,indent i WHERE i.ipdno = p.IpNo AND i.indentdate >= '" . $start_date . "'
                    AND i.indentdate <='" . $end_date . "' group by i.treatid order by i.indentdate asc";
        } else {
            $query = "SELECT p.OpdNo,i.ipdno,p.deptOpdNo,p.DoAdmission,p.DoDischarge,p.DischargeNotes,p.Doctor,p.BedNo,p.FName,p.IpNo,p.Age,
                p.Gender,p.department,p.Doctor,GROUP_CONCAT(i.product) as product,
                GROUP_CONCAT(i.indentdate order by i.indentdate asc) as indentdate,p.WardNo,(REPLACE((p.department),'_',' ')) department
                from inpatientdetails p,indent i WHERE i.ipdno = p.IpNo AND i.indentdate >= '" . $start_date . "'
                    AND i.indentdate <= '" . $end_date . "'  AND p.department LIKE '%" . $department . "%' group by i.treatid
                        order by i.indentdate asc";
        }
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_nursing_indent($start_date, $end_date, $department) {
        $this->db->query("set session group_concat_max_len = 5000");
        if ($department == "1") {
            $query = "SELECT p.OpdNo,p.FName,p.IpNo,p.deptOpdNo,p.Age,p.Gender,(REPLACE((p.department),'_',' ')) department,p.Doctor,GROUP_CONCAT(i.product) as product,GROUP_CONCAT(i.indentdate order by i.indentdate asc) as indentdate,
                GROUP_CONCAT(i.morning) as morning,GROUP_CONCAT(i.afternoon) as afternoon,GROUP_CONCAT(i.night) as night,GROUP_CONCAT(i.totalqty) as totalqty from inpatientdetails p,indent i
                WHERE i.ipdno = p.IpNo AND i.indentdate >= '" . $start_date . "' AND i.indentdate <= '" . $end_date . "'
                    group by i.treatid order by i.id";
        } else {
            $query = "SELECT p.OpdNo,p.FName,p.IpNo,p.deptOpdNo,p.Age,p.Gender,(REPLACE((p.department),'_',' ')) department,p.Doctor,GROUP_CONCAT(i.product) as product,GROUP_CONCAT(i.indentdate order by i.indentdate asc) as indentdate,
                GROUP_CONCAT(i.morning) as morning,GROUP_CONCAT(i.afternoon) as afternoon,GROUP_CONCAT(i.night) as night,GROUP_CONCAT(i.totalqty) as totalqty from inpatientdetails p,indent i
                WHERE i.indentdate >= '" . $start_date . "' AND i.indentdate <= '" . $end_date . "'
                    AND i.ipdno = p.IpNo AND p.department LIKE '%" . $department . "%' group by i.treatid order by i.id,i.indentdate asc ";
        }
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function get_xray_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('x.ID', 'x.OpdNo', 'x.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.LastName', 'p.Age',
            'p.gender', 'CONCAT(p.address," ",p.city) address', 'p.deptOpdNo', '(REPLACE((t.department),"_"," ")) as department',
            '(x.refDate) as refDate', '(x.xrayDate) as xrayDate', 'x.xrayNo', 'x.partOfXray', 'x.filmSize',
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

    function update_sugery_info($post_valyes, $id) {
        if ($id) {
            return $this->db->update('surgeryregistery', $post_valyes, array('ID' => $id));
        }
        return false;
    }

    function update_ksharasutra_info($post_valyes, $id) {
        if ($id) {
            return $this->db->update('ksharsutraregistery', $post_valyes, array('ID' => $id));
        }
        return false;
    }

    function get_usg_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('u.ID', 'u.OpdNo', 'u.refDocName', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 'p.MidName', 'p.LastName', 'p.Age',
            'p.gender', 'CONCAT(p.address," ",p.city) address', 't.deptOpdNo', 'u.usgDate', 't.CameOn as entrydate',
            '(REPLACE((t.department),"_"," ")) as department', 't.diagnosis');

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
            'p.gender', 'CONCAT(p.address," ",p.city) address', 'p.deptOpdNo', 'refDate', 'e.ecgDate', '(REPLACE((t.department),"_"," ")) as department', 't.diagnosis');

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
        $columns = array('b.ID', 'b.OpdNo', 'b.deliveryDetail', 'b.babyBirthDate', 'b.birthtime', 'b.treatby', 'b.babyWeight', 'b.deliverytype',
            'i.IpNo', 'i.deptOpdNo', 'i.FName', 'i.Age', 'i.diagnosis', 'babygender', 'babyWeight', 'fatherName', 'DoAdmission');

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
        $columns = array('dt.ID', 'IpNo', 'OpdNo', 'FName', 'BedNo', 'diagnosis', 'DoAdmission', 'DoDischarge', '(REPLACE((department),"_"," ")) department', 'dt.morning', 'dt.after_noon', 'dt.evening');

        //$where_cond = " WHERE DoAdmission >='" . $conditions['start_date'] . "' AND DoAdmission <='" . $conditions['end_date'] . "'";
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

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM inpatientdetails ipd LEFT JOIN diet_register dt ON ipd.IpNo=dt.ipd_no,
        (SELECT @a:= 0) AS a $where_cond ORDER BY serial_number ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM inpatientdetails')->num_rows();
        return $return;
    }

    function update_diet_register($input_data, $where) {
        $this->db->where($where);
        return $this->db->update('diet_register', $input_data);
    }

    function get_ksharasutra_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array('k.OpdNo', 'ip.IpNo', 'k.ID', 'k.surgeon', 'k.ksharsType', 'k.ksharsDate', 'k.ksharaname', 'k.asssurgeon',
            'k.anaesthetic', 'CONCAT(coalesce(p.FirstName,"")," ",coalesce(p.LastName,"")) as name', 'p.Age', 'p.gender', 'CONCAT(p.address," ",p.city) address', 'p.deptOpdNo',
            'p.dept', 't.diagnosis', 't.notes');

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

        /* $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM ksharsutraregistery k,
          (SELECT @a:= 0) AS a  JOIN patientdata p JOIN treatmentdata t LEFT JOIN inpatientdetails ip ON ip.OpdNo = p.OpdNo $where_cond ORDER BY k.ksharsDate ASC";
         */
        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM ksharsutraregistery k
                  JOIN treatmentdata t ON k.treatId=t.ID
                  JOIN patientdata p  ON t.OpdNo = p.OpdNo
                  LEFT JOIN inpatientdetails ip ON ip.OpdNo = p.OpdNo and t.ID=ip.treatId, (SELECT @a:= 0) AS a $where_cond ORDER BY k.ksharsDate ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT k.OpdNo FROM ksharsutraregistery k JOIN treatmentdata t WHERE k.treatId=t.ID ')->num_rows();
        return $return;
    }

    function get_surgery_data($conditions, $export_flag = false) {
        $return = array();
        $columns = array('s.ID as id', 's.OpdNo', 'i.IpNo', 's.surgName', 's.surgType', 's.surgDate', 's.anaesthetic', 's.asssurgeon',
            's.surgeryname', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.Age', 'p.gender', 'CONCAT(p.address," ",p.city) address',
            't.deptOpdNo', 'p.dept', 's.surgType', 's.surgDate', 't.diagnosis', 't.notes');

        $where_cond = " WHERE surgDate >='" . $conditions['start_date'] . "' AND surgDate <='" . $conditions['end_date'] . "'";

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

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . "
            FROM surgeryregistery s
            JOIN treatmentdata t  ON s.treatId=t.ID
            JOIN patientdata p ON  t.OpdNo=p.OpdNo
            LEFT JOIN inpatientdetails i ON p.OpdNo=i.OpdNo and t.ID=i.treatId,
            (SELECT @a:= 0) AS a  $where_cond ORDER BY surgDate ASC";
        //JOIN inpatientdetails i ON i.OpdNo=s.OpdNo and i.treatId=s.treatId and i.IpNo=s.ipdno 
        //echo $query;exit;
        $result = $this->db->query($query . ' ' . $limit);

        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM surgeryregistery s JOIN treatmentdata t ON s.treatId = t.ID JOIN patientdata p ON s.OpdNo = p.OpdNo LEFT JOIN inpatientdetails i ON s.OpdNo=i.OpdNo WHERE 1=1 ')->num_rows();
        return $return;
    }

    function get_panchakarma_data($conditions, $export_flag = false) {
        $return = array();
        $columns = array('l.treatid', 'l.id', 'l.opdno', 'CONCAT_WS(" ", p.FirstName, p.LastName) AS name', 'p.FirstName', 't.AddedBy', 'p.LastName', 'p.Age', 'p.gender', 'p.address',
            't.deptOpdNo', '(REPLACE((t.department),"_"," ")) dept', 't.diagnosis disease', 'GROUP_CONCAT(treatment) as treatment',
            'GROUP_CONCAT(`procedure`) as `procedure`', 'GROUP_CONCAT(l.date) as `date`', 't.notes', 'docname',
            'GROUP_CONCAT(proc_end_date) as proc_end_date',
            '(CASE WHEN proc_end_date IS NOT NULL AND i.DoDischarge IS NOT NULL AND i.DoDischarge !="--" AND proc_end_date > i.DoDischarge THEN "" ELSE i.IpNo END) AS IpNo',
            '"' . $conditions['end_date'] . '" as selected_date');

        $where_cond = " WHERE l.opdno = p.OpdNo AND l.treatid = t.ID AND trim(l.procedure) <>''
            AND (l.date <= '" . $conditions['start_date'] . "' AND l.proc_end_date >= '" . $conditions['end_date'] . "')  ";

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

        $query = "SELECT " . join(',', $columns) . " FROM panchaprocedure l
            JOIN treatmentdata t ON l.treatid = t.ID
            JOIN patientdata p ON t.OpdNo = p.OpdNo
            LEFT JOIN inpatientdetails i on i.OpdNo=l.opdno and i.treatId=t.ID  $where_cond
            group by l.treatid ORDER BY l.date ASC";
        $main_query = "SELECT @a:=@a+1 serial_number,B.* FROM ( $query ) B,(SELECT @a:= 0) AS a order by serial_number ";
        $result = $this->db->query($main_query);
    //   echo $this->db->last_query();
    //    exit;
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM panchaprocedure l JOIN treatmentdata t ON l.treatid = t.ID JOIN patientdata p ON t.OpdNo = p.OpdNo')->num_rows();
        return $return;
    }

    function get_pancharama_procedure_details($id = NULL) {
        if ($id) {
            return $this->db->get_where('panchaprocedure', array('treatid' => $id))->result_array();
        }
        return null;
    }

    function update_panchakarma_details($post_values = NULL, $where = NULL) {
        if ($post_values) {
            //$this->db->where($where);
            return $this->db->update_batch('panchaprocedure', $post_values, 'id');
        }
        return false;
    }

    function get_panchakarma_complete_data() {
        $return = array();
        $columns = array('l.id', 'l.opdno', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 't.AddedBy', 'p.LastName', 'p.Age', 'p.gender', 'p.address',
            't.deptOpdNo', '(REPLACE((t.department),"_"," ")) dept', 't.diagnosis disease', 'GROUP_CONCAT(treatment) as treatment',
            'GROUP_CONCAT(`procedure`) as `procedure`', 'GROUP_CONCAT(l.date) as `date`', 't.notes', 'docname',
            'GROUP_CONCAT(proc_end_date) as proc_end_date', 'i.IpNo', '"' . $conditions['end_date'] . '" as selected_date');

        $where_cond = " WHERE l.opdno = p.OpdNo AND l.treatid = t.ID AND trim(l.procedure) <>'' ";

        $query = "SELECT " . join(',', $columns) . " FROM panchaprocedure l
            JOIN treatmentdata t ON l.treatid = t.ID
            JOIN patientdata p ON t.OpdNo = p.OpdNo
            LEFT JOIN inpatientdetails i on i.OpdNo=l.opdno and i.treatId=t.ID  $where_cond
            group by l.treatid ORDER BY l.date ASC";
        $main_query = "SELECT @a:=@a+1 serial_number,B.* FROM ( $query ) B,(SELECT @a:= 0) AS a order by serial_number ";
        $result = $this->db->query($main_query);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM panchaprocedure l JOIN treatmentdata t ON l.treatid = t.ID JOIN patientdata p ON t.OpdNo = p.OpdNo')->num_rows();
        return $return;
    }

    function get_panchakarma_procedure_count($conditions, $export_flag = false) {
        /* $query = "select treatment,`procedure`, count(`procedure`) as procedure_count from panchaprocedure p
          WHERE p.date >='" . $conditions['start_date'] . "' AND p.proc_end_date <= '" . $conditions['end_date'] . "' group by treatment,`procedure`"; */
        $query = "SELECT treatment,`procedure`,days as procedure_count 
                  FROM ( 
                    SELECT p.*,SUM((DATEDIFF(proc_end_date,`date`) + 1)) days 
                    FROM panchaprocedure p 
                    WHERE p.date >='" . $conditions['start_date'] . "' AND p.proc_end_date <= '" . $conditions['end_date'] . "'
                    GROUP BY treatment,`procedure` 
                  ) A 
                 GROUP BY treatment,`procedure`";
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
                WHERE l.testName <>'' AND l.tested_date >='" . $conditions['start_date'] . "' AND  l.tested_date <= '" . $conditions['end_date'] . "' group by l.treatID order by l.tested_date asc";
        $result = $this->db->query($query);

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
            JOIN lab_tests lt ON li.lab_test_id=lt.lab_test_id
            JOIN lab_categories lc ON lt.lab_cat_id = lc.lab_cat_id
            WHERE testDate >= '" . $conditions['start_date'] . "' AND testDate<= '" . $conditions['end_date'] . "'
            GROUP BY lab_inv_name
            ORDER BY lab_cat_name";
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
        $columns = array('t.OpdNo', 't.PatType', 't.deptOpdNo', 'CONCAT(FirstName," ",LastName) as name', 'FirstName', 'LastName', 'p.Age',
            'p.gender', 't.AddedBy', 'p.city', 'Trtment', 't.diagnosis', 'CameOn', 'attndedby',
            '(REPLACE((t.department),"_"," ")) department', 'k.kriya_procedures procedures', 'sub_department sub_dept', 'ip.IpNo', 'k.id as kid', 'kriya_date');

        $where_cond = " WHERE k.OpdNo=t.OpdNo AND k.treat_id=t.ID AND t.OpdNo=p.OpdNo
            AND LOWER(t.department)=LOWER('SHALAKYA_TANTRA') AND kriya_date >='" . $conditions['start_date'] . "'
                AND kriya_date <='" . $conditions['end_date'] . "'";
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
            FROM kriyakalpa k
            JOIN treatmentdata t, (SELECT @a:= 0) AS a
            JOIN patientdata p
            LEFT JOIN inpatientdetails ip ON ip.OpdNo=p.OpdNo
            $where_cond ORDER BY kriya_date ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();

        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM kriyakalpa k
            JOIN treatmentdata t ON k.OpdNo=t.OpdNo AND k.treat_id=t.ID
            JOIN patientdata p ON t.OpdNo=p.OpdNo
            LEFT JOIN inpatientdetails ip ON ip.OpdNo=p.OpdNo')->num_rows();
        return $return;
    }

    function update_kriyakalpa_info($post_values, $where) {
        if ($post_values) {
            $this->db->where($where);
            return $this->db->update('kriyakalpa', $post_values);
        }
        return false;
    }
}
