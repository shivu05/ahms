<?php

class Menus extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->rbac->is_login()) {
            $this->session->set_flashdata('message', 'Please login first');
            redirect('login');
        }
        $this->load->model('idvs_perm_master');
        $this->initialize_rest_client('dvs');
    }

    function index() {
        $data['BASE_URL'] = base_url();
        $this->layout->title = "Menu";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Access management";
        $this->layout->navDescr = "Role Based Access Control";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        //if ($this->rbac->has_permission('MENU_SETTING')) {
        $this->scripts_include->includePlugins(array('datatable', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $parent_menus = $this->idvs_perm_master->get_parent_menus();
        $data['parent_menus'] = create_dropdown_options_v2($parent_menus, 'perm_id', 'perm_desc', true, null, null, null, false, null, false, 'd');
        $data['menus_list'] = $this->get_menu_list();
        $this->layout->data = $data;
        $this->layout->render();
        /* } else {
          $this->layout->data = $data;
          $this->layout->render(array('error' => '401'));
          } */
    }

    function get_menu_list() {
        return json_decode($this->rest_client->post('webapi/menu_management/get_menu_details'));
    }

    function get_sub_menus() {
        if ($this->input->is_ajax_request()) {
            $post_params = $this->input->post();
            echo $this->rest_client->post('webapi/menu_management/get_application_menus', $post_params);
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
            $return = $this->idvs_perm_master->is_menu_order_exists(array('menu_order' => $menu_order, 'parent' => $parent, 'is_edit' => $is_edit));
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
