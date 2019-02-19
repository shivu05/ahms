<?php

require APPPATH . 'modules/webapi/libraries/REST_Server.php';

class Menu_management extends REST_Server {

    public function __construct() {
        parent::__construct();
        $this->request->format = 'json'; //set default format to json
        $this->load->model('access/idvs_perm_master');
    }

    function get_application_menus_post() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $result = $this->idvs_perm_master->get_child_menu_by_parent($input_array);
        $response = array("sEcho" => 0, "iTotalRecords" => $result['found_rows'], "iTotalDisplayRecords" => $result['total_rows'], 'data' => $result['data']);
        $this->response($response, 200); // 200 being the HTTP response code
    }

    function is_menu_code_exists_post() {
        $menu_code = $this->input->post('menu_code');
        $return = $this->idvs_perm_master->is_menu_code_exists($menu_code);
        $this->response($return, 200);
    }

    function save_menu_details_post() {
        $post_array = $this->input->post();
        $response = $this->idvs_perm_master->save_manu($post_array);
        $this->response($response, 200); // 200 being the HTTP response code
    }

    function update_menu_details_post() {
        $post_array = $this->input->post();
        $response = $this->idvs_perm_master->update_manu($post_array);
        $this->response($response, 200);
    }

    function get_menu_details_post() {
        $perm_id = $this->input->post('perm_id');
        $result = $this->idvs_perm_master->get_menu_details_by_id($perm_id);
        $response = array('num_rows' => $result['num_rows'], 'data' => $result['data'], 'status' => true);
        $this->response($response, 200);
    }

    function get_menu_access_permissions_post() {
        $perm_id = $this->input->post('perm_id');
        $result = $this->idvs_perm_master->get_menu_access_permissions($perm_id);
        $response = array('data' => $result, 'status' => true);
        $this->response($response, 200);
    }

    function update_menu_access_permissions_post() {
        $response = $this->idvs_perm_master->update_menu_access_permissions($this->input->post());
        $this->response($response, 200);
    }

    function validate_user_get() {
        $this->load->model('webapi/i_m_users', 'user_table', true);
        $form_data = array(
            'idvs_user_email' => $this->input->get('username'),
            'idvs_user_password' => md5($this->input->get('password')),
        );
        if ($this->user_table->validate_credentials($form_data)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

}
