<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of settings_model
 *
 * @author Shivaraj
 */
class Settings_model extends CI_Model {

    public function get_configuration_settings($column = NULL) {
        if ($column) {
            $this->db->select($column);
        }
        return $this->db->get('config')->row_array();
    }

    function save_settings($form_data) {
        return $this->db->update('config', $form_data);
    }

    function get_user_profile($id = NULL) {
        if ($id) {
            $this->db->where('ID', $id);
        }
        return $this->db->get('users')->result_array();
    }

    function get_doctors_by_dept($dept) {
        //and d.day=DAYOFWEEK(SYSDATE())
        $query = "SELECT * FROM users u JOIN doctorsduty d ON u.id=d.doc_id where LOWER(user_department)=LOWER('$dept') GROUP BY u.id";
        return $this->db->query($query)->result_array();
    }

    function get_packaging_types() {
        return $this->db->get('packagin_type')->result_array();
    }

    function get_medicine_frequency() {
        return $this->db->get('medicine_frequency')->result_array();
    }

    function get_medicine_list() {
        $query = "SELECT name,product_master_id as id FROM product_master p JOIN purchase_variables pv where p.product_master_id=pv.id";
        return $this->db->query($query)->result_array();
    }

    function get_config_variable_value($var_name = '') {
        return $this->db->get_where('config_variables', array('config_var_name' => $var_name))->row()->config_var_value;
    }

    function remove_patient_data_by_date($date = '') {

        $this->db->trans_begin();

        $this->db->query('SET SQL_SAFE_UPDATES = 0');
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->db->query("DELETE FROM panchaprocedure where date >='$date'");
        $this->db->query("DELETE FROM labregistery where testDate>='$date'");
        $this->db->query("DELETE FROM treatmentdata where CameOn>='$date'");
        $this->db->query("DELETE FROM sales_entry where date >='$date'");
        $this->db->query("DELETE FROM usgregistery where usgDate>='$date'");
        $this->db->query("DELETE FROM ecgregistery where ecgDate >='$date'");
        $this->db->query("DELETE FROM indent where indentdate >='$date'");
        $this->db->query("DELETE FROM patientdata where entrydate >='$date'");
        $this->db->query("DELETE FROM inpatientdetails where DoAdmission>='$date'");
        $this->db->query("DELETE FROM ipdtreatment where attndedon>='$date'");
        $this->db->query("DELETE FROM ksharsutraregistery where ksharsDate >='$date'");
        $this->db->query("DELETE FROM xrayregistery where xrayDate >='$date'");
        $this->db->query("DELETE FROM surgeryregistery where surgDate >='$date'");

        $this->db->query("ALTER TABLE panchaprocedure AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE labregistery AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE treatmentdata AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE sales_entry AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE usgregistery AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE ecgregistery AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE indent AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE patientdata AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE inpatientdetails AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE ipdtreatment AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE ksharsutraregistery AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE xrayregistery AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE surgeryregistery AUTO_INCREMENT = 1");

        $this->db->query("SET SQL_SAFE_UPDATES = 1");
        $this->db->query("SET FOREIGN_KEY_CHECKS=1");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
    }

}
