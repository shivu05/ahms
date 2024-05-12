<?php

/**
 * Description of Fumigation
 *
 * @author shiva
 */
class Fumigation extends SHV_Controller {

    private $_is_admin = false;

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Fumigation";
        $this->_is_admin = $this->rbac->is_admin();
    }

    function index() {
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');

        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'print-fumigation-list', true, false);
        $data['is_admin'] = $this->_is_admin;

        $this->layout->data = $data;
        $this->layout->render();
    }

    function fetch_list() {
        $this->load->model('register/fumigation_register');
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->fumigation_register->get_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_data() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $this->load->model('register/fumigation_register');
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->fumigation_register->get_data($input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'register/fumigation/print_grid'), true);
        $title = array(
            'report_title' => 'FUMIGATION REGISTER',
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'FUMIGATION_REGISTER_' . $current_date, true, true, 'D');
        exit;
    }

    function store_data() {
        $this->load->model('register/fumigation_register');
        $post_values = $this->input->post();
        $is_inserted = $this->fumigation_register->store($post_values);
        if ($is_inserted) {
            echo json_encode(array('msg' => 'Added Successfully', 'status' => 'ok'));
        } else {
            echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
        }
    }
}
