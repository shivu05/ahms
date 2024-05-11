<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menus extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->rbac->is_login()) {
            $this->session->set_flashdata('message', 'Please login first');
            redirect('login');
        }
        $this->load->model('perm_master');
        $this->initialize_rest_client();
        $this->layout->title="Menus";
    }

    function index() {
        $data['BASE_URL'] = base_url();
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $parent_menus = $this->perm_master->get_parent_menus();
        $data['parent_menus'] = create_dropdown_options_v2($parent_menus, 'perm_id', 'perm_desc', true, null, null, null, false, null, false, 'd');
        $data['menus_list'] = $this->get_menu_list();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_menu_list() {
        $perm_id = $this->input->post('perm_id');
        $result = $this->perm_master->get_menu_details_by_id($perm_id);
        $response = array('num_rows' => $result['num_rows'], 'data' => $result['data'], 'status' => true);
        return json_encode($response);
    }

    function get_sub_menus() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            $input_array = array();
            foreach ($this->input->post('search_form') as $search_data) {
                $input_array[$search_data['name']] = $search_data['value'];
            }
            $input_array['start'] = $this->input->post('start');
            $input_array['length'] = $this->input->post('length');
            $input_array['order'] = $this->input->post('order');
            $result = $this->perm_master->get_child_menu_by_parent($input_array);
            $response = array("recordsTotal" => $result['total_rows'], "recordsFiltered" => $result['found_rows'], 'data' => $result['data']);
            //$response = array("sEcho" => 0, "iTotalRecords" => $result['found_rows'], "iTotalDisplayRecords" => $result['total_rows'], 'data' => $result['data']);
            echo json_encode($response);
        }
    }

    function check_menu_code() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            echo $this->rest_client->post('webapi/menu_management/is_menu_code_exists', $post_params);
        }
    }

    function check_menu_order() {
        if ($this->input->is_ajax_request()) {
            $menu_order = $this->input->post('menu_order');
            $parent = $this->input->post('parent');
            $is_edit = $this->input->post('is_edit');
            $return = $this->perm_master->is_menu_order_exists(array('menu_order' => $menu_order, 'parent' => $parent, 'is_edit' => $is_edit));
            echo json_encode($return);
        }
    }

    function save_menu() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            $post_params['uid'] = $this->rbac->get_uid();
            echo $this->rest_client->post('webapi/menu_management/save_menu_details', $post_params);
        }
    }

    function update_menu() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            $post_params['uid'] = $this->rbac->get_uid();
            echo $this->rest_client->post('webapi/menu_management/update_menu_details', $post_params);
        }
    }

    function get_menu_details() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            echo $this->rest_client->post('webapi/menu_management/get_menu_details', $post_params);
        }
    }

    function get_menu_access_permissions() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            echo $this->rest_client->post('webapi/menu_management/get_menu_access_permissions', $post_params);
        }
    }

    function update_menu_access_permissions() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            echo $this->rest_client->post('webapi/menu_management/update_menu_access_permissions', $post_params);
        }
    }

}
