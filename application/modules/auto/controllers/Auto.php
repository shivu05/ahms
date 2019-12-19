<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

class Auto extends SHV_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->rbac->is_admin()) {
            redirect('dashboard');
        }
        $this->load->model('auto/m_auto');
    }

    function index() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Patients";
        $this->layout->navDescr = "Record analysis";
        if (md5($this->input->post('key_word')) == "d6ca3fd0c3a3b462ff2b83436dda495e") {
            $this->layout->render(array('view' => 'auto/auto/move'));
        } else {
            echo "No Results Found";
            redirect('dashboard', 'refresh');
        }
    }

    function move() {
        $data['title'] = "AUTO GEN";
        $target = $this->input->post('target');
        $cdate = $this->input->post('cdate');
        $newpatient = $this->input->post('newpatient');
        $pancha_count = $this->input->post('pancha_count');
        $data['message'] = $this->m_auto->auto_master($target, $cdate, $newpatient, $pancha_count);
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_doc() {
        echo $this->m_auto->get_day_doctor();
    }

}

//end of c_auto