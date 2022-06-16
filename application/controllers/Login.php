<?php

/**
 * Description of Login
 *
 * @author Shivaraj
 */
class Login extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->layout = 'login_layout';
        $this->layout->title = 'Login';
    }

    function index() {
        $key = fetchProjectDir();
        $this->load->model('ClientInfo');
        $accessConfig = $this->ClientInfo->fetch_config_years($key);
        if (empty($accessConfig)) {
            echo 'no data';
        }
        $data = array();
        $data['app_settings'] = $this->application_settings(array('college_name'));
        $data['accessConfig'] = $accessConfig;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function validate() {
        $data["title"] = "Login Page";
        $this->form_validation->set_rules('loginname', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('selection_year', 'Year', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['app_settings'] = $this->application_settings(array('college_name'));
            $this->layout->data = $data;
            $this->layout->render(array('view' => 'login/index'));
        } else {
            //if ($this->simpleloginsecure->check_license() == 1) {

            $year = $this->input->post('selection_year');
            $db = base64_decode(trim($year));
            $db_exists = $this->db->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'vhms_" . $db . "'")->row_array();
            if (empty($db_exists) && !isset($db_exists['SCHEMA_NAME'])) {
                show_error('Unathorised access! Please contact administrator <a href="' . base_url() . '" >Home</a>', 500);
                exit;
            }
            $this->session->set_userdata('randkey', $year);
            $this->session->set_userdata('configs', $this->input->post());
            //$this->load->database($config, TRUE);
            //pma($this->session->userdata('randkey'), 1);
            redirect('login/authenticate');
            // $this->authenticate($this->input->post());

            /* } else {
              $data["invalid"] = "License Expired please contact admin";
              $this->load->view('login/login_vw', $data);
              } */
        }
    }

    function authenticate() {
        $postvalues = $this->session->userdata('configs');
        $this->session->set_userdata('configs', NULL);
        $dbname = $this->session->userdata('randkey');
        if ($this->simpleloginsecure->login($postvalues['loginname'], $postvalues['password'], $dbname)) {
            redirect('login/home');
        } else {
            $this->session->set_flashdata('noty_msg', 'Wrong username / password ');
            redirect(base_url(), true);
        }
    }

    function logout() {
        $this->simpleloginsecure->logout();
        //remove all session data
        $this->output->set_header('refresh:3; url=' . base_url());
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user');
        //$this->session->sess_destroy();
        $this->db->cache_delete_all();
        redirect(base_url(), true);
    }

    function change_password() {
        if ($this->rbac->is_login()) {
            $this->layout->title = 'Change password';
            $this->layout->layout = 'default';
            $this->layout->headerFlag = FALSE;
            $this->layout->render();
        } else {
            redirect('logout');
        }
    }

    function check_current_pasword() {
        $user_name = $this->rbac->get_email();
        $current_password = $this->input->post('current_password');
        if ($this->simpleloginsecure->login($user_name, $current_password)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    function update_password() {
        $user_name = $this->rbac->get_email();
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');
        if ($this->simpleloginsecure->edit_password($user_name, $current_password, $new_password)) {
            echo json_encode(array('status' => true, 'msg' => 'Password updated successfully', 'label' => 'OK', 'class' => 'btn-primary'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Failed to update password. Try again', 'label' => 'Cancel', 'class' => 'btn-danger'));
        }
    }

    function home() {
        if ($this->rbac->is_admin()) {
            redirect('admin-dashboard');
        } else if ($this->rbac->is_doctor()) {
            redirect('home/doctor');
        } else if ($this->rbac->has_role('XRAY')) {
            redirect('home/xray');
        } else if ($this->rbac->has_role('ECG')) {
            redirect('home/ecg');
        } else if ($this->rbac->has_role('USG')) {
            redirect('home/usg');
        } else if ($this->rbac->has_role('LAB')) {
            redirect('home/lab');
        } else {
            redirect('home/user');
        }
    }

    function generate_meds() {
        $this->db->limit(10);
        $this->db->where('department <>', 'Swasthavritta');
        $treatment_data = $this->db->get('treatmentdata')->result_array();
        foreach ($treatment_data as $data) {
            $digits = 4;
            $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            $products = explode(',', $data['Trtment']);
            $n = count($products);
            for ($i = 0; $i < $n; $i++) {
                if (strlen(trim($products[$i])) > 0) {
                    $med_arr = array(
                        'opdno' => $data['OpdNo'],
                        'billno' => $four_digit_random_number,
                        'batch' => 947,
                        'date' => $data['CameOn'],
                        'qty' => 1,
                        'product' => $products[$i],
                        'treat_id' => $data['ID']
                    );
                    $this->db->insert('sales_entry', $med_arr);
                    pma($med_arr);
                    //echo $i . ':';
                }
            }
        }


        /*    $treatment_data = $this->db->query("SELECT * FROM ipdtreatment i JOIN inpatientdetails ip On i.ipdno=ip.IpNo where ip.department !='Swasthavritta'")->result_array();
          foreach ($treatment_data as $data) {
          $digits = 4;
          $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
          $products = explode(',', $data['Trtment']);
          $n = count($products);
          for ($i = 0; $i < $n; $i++) {
          if (strlen(trim($products[$i])) > 0) {
          $med_arr = array(
          'opdno' => $data['OpdNo'],
          'billno' => $four_digit_random_number,
          'batch' => 947,
          'date' => $data['attndedon'],
          'qty' => 1,
          'ipdno' => $data['IpNo'],
          'product' => $products[$i],
          'treat_id' => $data['ID']
          );
          $this->db->insert('sales_entry', $med_arr);
          //pma($med_arr);
          echo $i . ':';
          }
          }
          } */
    }

}
