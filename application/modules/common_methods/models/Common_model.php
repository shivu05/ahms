<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Common_model
 *
 * @author hp
 */
class Common_model extends CI_Model {

    function get_patient_info_by_opd($opd) {
        return $this->db->get_where('patientdata', array('OpdNo' => $opd))->row_array();
    }

    function update_patient_data($form_data) {
        $update_data = array(
            'FirstName' => $form_data['first_name'],
            'LastName' => $form_data['last_name'],
            'Age' => $form_data['age'],
            'gender' => $form_data['gender'],
        );
        $this->db->where('OpdNo', $form_data['opd']);
        $is_updated = $this->db->update('patientdata', $update_data);
        $ipd_update = array(
            'FName' => $form_data['first_name'] . ' ' . $form_data['last_name'],
            'Age' => $form_data['age'],
            'Gender' => $form_data['gender'],
        );
        $this->db->where('OpdNo', $form_data['opd']);
        $is_ipd_updated = $this->db->update('inpatientdetails', $ipd_update);

        return $is_updated;
    }

    function get_laboratory_test_list($category = NULL) {
        if (!empty($category)) {
            $query = "SELECT * FROM lab_tests l WHERE lab_cat_id ='" . $category . "'";
            //IN (SELECT lab_cat_id FROM lab_categories WHERE lab_cat_name IN ('" . $category . "'))";
            return $this->db->query($query)->result_array();
        } else {
            return NULL;
        }
    }

    function get_laboratory_investigation_list($tests) {
        if (!empty($tests)) {
            $query = "SELECT * FROM lab_investigations l where lab_test_id ='" . $tests . "'";
            return $this->db->query($query)->result_array();
        } else {
            return NULL;
        }
    }

}
