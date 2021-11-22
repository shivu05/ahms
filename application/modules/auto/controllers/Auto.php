<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

class Auto extends SHV_Controller {

    private $_is_admin = false;

    function __construct() {
        parent::__construct();
        if (!$this->rbac->is_admin()) {
            redirect('dashboard');
        }
        $this->load->model('auto/m_auto');
        $this->_is_admin = $this->rbac->is_admin();
    }

    function index() {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Patients";
        $this->layout->title = "Record analysis";
        if (md5($this->input->post('key_word')) == "9eb80e31381f8509f062dd01a8b2b758") {
            $this->layout->render(array('view' => 'auto/auto/move'));
        } else {
            echo "No Results Found";
            redirect('dashboard', 'refresh');
        }
    }

    function move() {
        $data['title'] = "Analysis";
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

    function show_reference_data() {
        $this->layout->title = "X-Ray";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "X-Ray";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_reference_data() {
        $input_array = array();
//        foreach ($this->input->post('search_form') as $search_data) {
//            $input_array[$search_data['name']] = $search_data['value'];
//        }
        $search_key = $this->input->post('search');
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $input_array['keyword'] = $search_key['value'];
        $data = $this->m_auto->get_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

}

//end of c_auto