<?php

/**
 * Description of Settings
 *
 * @author Shivaraj
 */
class Settings extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('home/settings_model');
        $this->layout->title = "Settings";
    }

    function index() {
        $data = array();
        $data['settings'] = $this->settings_model->get_configuration_settings();
        $data['user_profile'] = $this->settings_model->get_user_profile($this->rbac->get_uid());
        $this->layout->data = $data;
        $this->layout->render();
    }

    function save_app_settings() {
        if (!empty($_FILES['college_logo']['name'])) {
            $config['upload_path'] = './assets/';
            $config['allowed_types'] = 'jpg|png';
            $config['file_name'] = 'your_logo.png';
            $config['overwrite'] = TRUE;
        } else {
            $config = array();
        }

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('college_logo')) {
            if ($this->input->post('logo_name') && empty($_FILES['college_logo']['name'])) {
                if ($this->input->post('logo_name')) {
                    $_POST['logo'] = $this->input->post('logo_name');
                }
            } else {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error['error']);
            }
        } else {
            $_POST['logo'] = $config['file_name'];
            $data_logo = array('upload_data' => $this->upload->data());
        }

        $form_data = array(
            'college_name' => $this->input->post('college_name'),
            'place' => $this->input->post('place'),
            'printing_style' => $this->input->post('printing_style'),
            'logo' => $this->input->post('logo'),
            'admin_email' => $this->input->post('config_email'),
            'logo_img' => file_get_contents($_FILES['college_logo']['tmp_name'])
        );
        $this->settings_model->save_settings($form_data);
        redirect('home/Settings', true);
    }

    function reset_beds() {
        $dept_data = $this->db->get('deptper')->result_array();
        $this->db->query('DELETE FROM bed_details');
        $this->db->query('ALTER TABLE bed_details AUTO_INCREMENT = 1');
        $i = $k = 0;
        foreach ($dept_data as $row) {
            ++$k;
            echo $row['department'] . '-' . $row['bed_count'] . '</br>';
            $n = $row['bed_count'];
            if ($n == 0) {
                $data = array(
                    'department' => trim($row['department']),
                    'bedno' => $n,
                    'wardno' => $n,
                    'bedstatus' => 'Not Available'
                );
                $this->db->insert('bed_details', $data);
            } else {
                for ($j = 0; $j < $n; $j++) {
                    $data = array(
                        'department' => trim($row['department']),
                        'bedno' => ++$i,
                        'wardno' => $k,
                        'bedstatus' => 'Available'
                    );
                    $this->db->insert('bed_details', $data);
                }
            }
        }
    }

    function remove_data() {
        $this->layout->render();
    }

    function delete_patients() {
        $date = $this->input->post('cdate');
        $is_deleted = false;
        if (!empty($date)) {
            $is_deleted = $this->settings_model->remove_patient_data_by_date($date);
        }
        if ($is_deleted) {
            $this->session->set_flashdata('noty_msg_del', 'Deleted successfully');
        } else {
            $this->session->set_flashdata('noty_msg_del', 'Failed to delete, try again');
        }
        redirect('delete-data');
    }
}
