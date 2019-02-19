<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        echo 'Moduler DVS';
    }

    function update_bluebooks() {
		exit;
        $crs_id = 103;
        $event = 17;
        $this->db->trans_start();
        $result = $this->db->where(array('isc.idvs_course_id' => $crs_id, 'isc.idvs_event' => $event))
                ->from('i_student_course isc')
                ->join('i_m_students ims', 'isc.idvs_student_id=ims.idvs_student_id')
                ->join('i_m_courses imc', 'isc.idvs_course_id=imc.idvs_crs_id')
                ->get();
        if ($result->num_rows() > 0) {
            $students_data = $result->result_array();

            $settings = $this->db->get('idvs_config_settings')->row_array();
            if (!empty($settings)) {
                $year = $settings['idvs_year'];
            } else {
                $year = date('Y');
            }

            foreach ($students_data as $_postData):
                $blue_book_name = $year . "_" . $_postData['idvs_usno'] . "_" . $_postData['idvs_crs_code'] . "_" . $event;
                if (file_exists('./ref_files/file_1.jpg') && file_exists('./ref_files/file_2.jpg')) {
                    copy('./ref_files/file_1.jpg', './assets/images/BlueBooks/' . $blue_book_name . '_1.jpg');
                    copy('./ref_files/file_2.jpg', './assets/images/BlueBooks/' . $blue_book_name . '_2.jpg');
                }

                $this->db->where('idvs_student_id', $_postData['idvs_student_id']);
                $this->db->where('idvs_course_id', $_postData['idvs_course_id']);
                $this->db->where('idvs_event', $_postData['idvs_event']);
                $this->db->where('idvs_event_year', $year);
                $query = $this->db->get('i_student_course');
                $student_course = $query->result_array();
                $idvs_stu_cou_id = $student_course[0]['idvs_stu_cou_id'];

                $this->db->where('idvs_stu_crs_id', $idvs_stu_cou_id);
                $query1 = $this->db->get('i_m_scanbluebooks');
                $result = $query1->result_array();
                if (empty($result)) {
                    //Insert the blue books data into i_m_studentbluebooks table
                    $data = array(
                        'idvs_stu_crs_id' => $idvs_stu_cou_id,
                        'idvs_bluebook_name' => $blue_book_name,
                        'idvs_bluebook_startpage' => 1,
                        'idvs_bluebook_endpage' => 2,
                        'idvs_bluebook_correction_status' => 0,
                    );
                    $this->db->insert('i_m_scanbluebooks', $data);
                } else {
                    $idvs_scanbluebook_id = $result[0]['idvs_scanbluebook_id'];
                    $data = array(
                        'idvs_stu_crs_id' => $idvs_stu_cou_id,
                        'idvs_bluebook_name' => $blue_book_name,
                        'idvs_bluebook_startpage' => 1,
                        'idvs_bluebook_endpage' => 2,
                        'idvs_bluebook_correction_status' => 0,
                    );
                    $this->db->where('idvs_scanbluebook_id', $idvs_scanbluebook_id);
                    $this->db->update('i_m_scanbluebooks', $data);
                }

                $data1 = array(
                    'idvs_scan_status' => 1
                );
                $this->db->where('idvs_stu_cou_id', $idvs_stu_cou_id);
                $this->db->where('idvs_event', $_postData['idvs_event']);
                $this->db->update('i_student_course', $data1);
            endforeach;
            $this->db->trans_complete();
            if ($this->db->trans_status() == TRUE) {
                echo 'success';
            } else {
                echo 'failed';
            }
        }
    }

}
