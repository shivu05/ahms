<?php

/**
 * Description of Common_model
 *
 * @author Shiv
 */
class Common_model extends CI_Model {

    function get_patient_info_by_opd($opd) {
        return $this->db->get_where('patientdata', array('OpdNo' => $opd))->row_array();
    }

    function get_patient_info_by_ipd($where) {
        $columns = array('p.OpdNo', 'p.FirstName', 'p.LastName', 'p.Age', 'p.gender', 'i.IpNo',
            'i.DoAdmission', 'i.DoDischarge', 'i.NofDays', 'i.BedNo', 't.diagnosis', 't.Trtment', 't.procedures' ,'i.Doctor');
        $this->db->select($columns);
        $this->db->join('patientdata p', 'p.OpdNo=i.OpdNo');
        $this->db->join('ipdtreatment t', 't.ipdno=i.IpNo');
        return $this->db->get_where('inpatientdetails i', $where)->row_array();
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

    function update_ipd_data($update) {
        $num_days = NULL;
        if (isset($update['NofDays']) && $update['NofDays'] != 0 && trim($update['NofDays']) != '') {
            $num_days = $update['NofDays'];
        }
        if (trim($update['cur_bed_no']) != trim($update['selected_bed_no'])) {
            $update_data = array(
                'OpdNo' => NULL,
                'bedstatus' => 'Available',
                'treatId' => NULL
            );
            $this->db->where('bedno', $update['cur_bed_no']);
            $this->db->update('bed_details', $update_data);

            $update_data_new = array(
                'OpdNo' => $update['opd'],
                'bedstatus' => 'Not Available',
                'IpNo' => $update['ipd']
            );
            $this->db->where('bedno', $update['selected_bed_no']);
            $this->db->update('bed_details', $update_data_new);
        }

        $ipd_info = array(
            'DoAdmission' => $update['DoAdmission'],
            'DoDischarge' => $update['DoDischarge'],
            'NofDays' => $num_days,
            'diagnosis' => $update['pat_diagnosis'],
            'BedNo' => $update['selected_bed_no'],
            'Doctor' => $update['pat_assigned_doctor']
        );
        $this->db->where('IpNo', $update['ipd']);
        $this->db->where('OpdNo', $update['opd']);

        $is_updated = $this->db->update('inpatientdetails', $ipd_info);

        $it_info = array(
            'Trtment' => $update['pat_treatment'],
            'diagnosis' => $update['pat_diagnosis'],
            'procedures' => $update['pat_procedure']
        );

        $is_itupdated = $this->db->update('ipdtreatment', $it_info, array('ipdno' => $update['ipd']));
        if ($is_updated || $is_itupdated) {
            return true;
        } else {
            return false;
        }
    }

    function get_archived_years() {
        return $this->db->get('archived_data')->result_array();
    }

    function delete($table_name, $where) {
        if ($this->db->table_exists($table_name)) {
            return $this->db->delete($table_name, $where);
        }
        return false;
    }

}
