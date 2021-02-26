<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Laboratory
 *
 * @author Abhilasha
 */
class Laboratory extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/lab_investigations');
    }

    function index() {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $data = array();
        $data['result_set'] = $this->lab_investigations->get_lab_data();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function update_lab_reference() {
        if ($this->input->is_ajax_request()) {
            $test_range = $this->input->post('test_range');
            $inv_id = $this->input->post('inv_id');
            $update_arr = array(
                'lab_test_reference' => $test_range
            );
            $is_updated = $this->lab_investigations->update($update_arr, $inv_id);
            if ($is_updated) {
                echo json_encode(array('status' => true));
            } else {
                echo json_encode(array('status' => false));
            }
        }
    }

    function pancha_set() {
        $result = $this->db->get('vedic_soft_bamch_2021.panchaprocedure')->result_array();
        $this->db->query("TRUNCATE panchaprocedure");
        //pma($result);
        foreach ($result as $row) {
            $i = 0;
            $treatment = explode(',', $row['treatment']);
            $procs = explode(',', $row['procedure']);
            foreach ($procs as $test) {

                $query = "select * from master_panchakarma_sub_procedures where sub_proc_name  = '$test'";
                $res = $this->db->query($query)->row_array();
                //echo $this->db->last_query();
                $this->db->query("INSERT INTO panchaprocedure (opdno, disease, treatment, `procedure`, `date`, docname, treatid)
                     VALUES('" . $row['opdno'] . "','" . $row['opdno'] . "','" . $treatment[$i] . "','" . $procs[$i] . "','" . $row['date'] . "','" . $row['docname'] . "','" . $row['treatid'] . "')");
                //pma($res);
                $i++;
            }
        }
    }

    function insert_lab() {
        $result = $this->db->get('vedic_soft_bamch_2021.labregistery')->result_array();
        //pma($result);
        $i = 0;
        $this->db->query('TRUNCATE labregistery');
        foreach ($result as $row) {

            $testName = explode(',', $row['testName']);
            $testVal = explode(',', $row['testvalue']);
            $testRange = explode(',', $row['testrange']);
            //pma($testName);
            $j = 0;
            foreach ($testName as $test) {
                $i++;
                $key = explode(" ", trim($test));
                $s = $key[0];
                if (isset($key[1])) {
                    $s = $key[1];
                }
                $d = $this->db->query('SELECT * FROM lab_investigations WHERE lab_inv_name="' . $s . '"')->row_array();
                if (!empty($d)) {
                    $query = "INSERT INTO labregistery ( OpdNo, refDocName, lab_test_cat, lab_test_type, testName, testDate, treatID, testrange, testvalue, labdisease, tested_date)
                         VALUES('" . $row['OpdNo'] . "','" . $row['refDocName'] . "',NULL,NULL,'" . $d['lab_inv_id'] . "','" . $row['testDate'] . "','" . $row['treatID'] . "','" . $testRange[$j] . "','" . $testVal[$j] . "','" . $row['labdisease'] . "','" . $row['testDate'] . "')";
                    $this->db->query($query);
                    echo $test . ' exists</br>';
                }
                $j++;
            }
        }
    }

}
