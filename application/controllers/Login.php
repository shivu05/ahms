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
        $data = array();
        $data['app_settings'] = $this->application_settings(array('college_name'));
        $this->layout->data = $data;
        $this->layout->render();
    }

    function validate() {
        $data["title"] = "Login Page";
        $this->form_validation->set_rules('loginname', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['app_settings'] = $this->application_settings(array('college_name'));
            $this->layout->data = $data;
            $this->layout->render(array('view' => 'login/index'));
        } else {
            //if ($this->simpleloginsecure->check_license() == 1) {
            if ($this->simpleloginsecure->login($this->input->post('loginname'), $this->input->post('password'))) {
                redirect('login/home');
            } else {
                $this->session->set_flashdata('noty_msg', 'Wrong username / password ');
                redirect('login');
            }
            /* } else {
              $data["invalid"] = "License Expired please contact admin";
              $this->load->view('login/login_vw', $data);
              } */
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
        redirect('Login', true);
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
