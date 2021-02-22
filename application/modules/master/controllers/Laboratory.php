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
            $j=0;
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
                         VALUES('" . $row['OpdNo'] . "','" . $row['refDocName'] . "',NULL,NULL,'" . $d['lab_inv_id'] . "','" . $row['testDate'] . "','" . $row['treatID'] . "','".$testRange[$j]."','".$testVal[$j]."','" . $row['labdisease'] . "','" . $row['testDate'] . "')";
                    $this->db->query($query);
                    echo $test . ' exists</br>';
                }
                $j++;
            }
        }
    }

}
