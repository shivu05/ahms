<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lab_model
 *
 * @author hp
 */
class Lab_model extends CI_Model {

    function dashboard_stats() {
        $query = "SELECT count(*) as TOTAL, 
            SUM(case when tested_date is NULL AND testvalue is NULL then 1 else 0 end) PENDING, 
            SUM(case when tested_date is NOT NULL AND testvalue is NOT NULL then 1 else 0 end) COMPLETED 
            FROM labregistery";
        return $this->db->query($query)->row_array();
    }

    function get_pending_labs($conditions, $export_flag = FALSE) {
        ini_set('max_execution_time', 0); // 0 = Unlimited
        $this->db->query("DELETE FROM labregistery where testName=''");
        $return = array();
        $columns = array(
            'l.ID', 'l.OpdNo', 'refDocName', 'testName', 'testDate', 'treatID', 'testrange', 'testvalue', 'tested_date', 'CONCAT(FirstName," ",LastName) as name',
            'IF(l.ipdno is null ,(REPLACE((t.department),"_"," ")) ,(REPLACE((it.department),"_"," ")) ) department',
            'IF(l.ipdno is null ,(REPLACE((t.diagnosis),"_"," ")) ,(REPLACE((it.diagnosis),"_"," ")) ) diagnosis',
            'tested_date', 'testvalue'
        );

        $cur_date = date('Y-m-d');
        $search_data = $this->input->post('search');
        $search_value = trim($search_data['value']);
        $search = (isset($search_data) && $search_value != '') ? 'AND (l.OpdNo like "%' . $search_value . '%" 
            OR lower(t.department) like "%' . strtolower($search_value) . '%" ) ' : '';
        $where_cond = " WHERE 1=1  $search ";
        //(tested_date is NULL AND testvalue IS NULL)
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
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number," . join(',', $columns) . " FROM labregistery l 
           JOIN treatmentdata t ON l.treatID=t.ID 
           LEFT JOIN ipdtreatment it on l.ipdno=it.ipdno
           JOIN patientdata p ON p.OpdNo=l.OpdNo,(SELECT @a:= 0) AS a $where_cond GROUP BY l.treatID ORDER BY tested_date";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT l.treatID FROM labregistery l 
           JOIN treatmentdata t ON l.treatID=t.ID 
           JOIN patientdata p ON p.OpdNo=t.OpdNo')->num_rows();
        return $return;
    }

    function update($update_data, $id) {
        $this->db->where('ID', $id);
        return $this->db->update('labregistery', $update_data);
    }

    function get_lab_categories() {
        return $this->db->get('lab_categories')->result_array();
    }

    function get_lab_tests_list() {
        return $this->db->get('lab_tests')->result_array();
    }

    function get_lab_data($where) {
        return $this->db->select('ID as lab_id,OpdNo,refDocName,treatId,lab_cat_name,lab_inv_name,lab_test_name,lab_test_reference,testDate,testvalue')
                        ->from('labregistery lb')
                        ->join('lab_investigations li', 'lb.testName = li.lab_inv_id')
                        ->join('lab_tests lt', 'li.lab_test_id=lt.lab_test_id')
                        ->join('lab_categories lc', 'lt.lab_cat_id=lc.lab_cat_id')
                        ->where($where)
                        ->get()
                        ->result_array();
    }
}
