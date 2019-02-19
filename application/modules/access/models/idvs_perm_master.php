<?php

class idvs_perm_master extends CI_Model {

    private $_uid;

    public function __construct() {
        parent::__construct();
        $this->load->library('rbac');
        $this->_uid = $this->rbac->get_uid();
    }

    function get_parent_menus() {
        $query = "SELECT * FROM ( 
                        SELECT * FROM perm_master WHERE perm_parent=0 
                        UNION ALL 
                        SELECT * FROM perm_master WHERE perm_id IN ( 
                            SELECT i.perm_parent FROM perm_master i where perm_parent<>0 group by perm_parent 
                        ) 
                ) A GROUP BY perm_code ORDER BY perm_id DESC";
        return $this->db->query($query)->result_array();
    }

    function get_child_menu_by_parent($conditions) {
        $columns = array(
            'm1.perm_id', 'm1.perm_code', 'm1.perm_desc', 'm1.perm_order','m1.perm_attr','m1.perm_class',
            'm1.perm_parent', 'm1.perm_url', 'm1.perm_status', 'm1.last_updated_id', 'm1.last_updated_date',
            'm2.perm_code as parent_perm_code', 'imu.user_name'
        );
        $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
        $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
        $limit = ' LIMIT ' . $start . ',' . ($start + $length);

        //unset column that comes form datatable end
        unset($conditions['start'], $conditions['length'], $conditions['order']);
        $where_cond = " WHERE 1=1";
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'menu_p':
                        $where_cond .= " AND m1.perm_parent ='$val'";
                        break;
                endswitch;
            }
        }
        $query = "SELECT " . join(',', $columns) . " FROM perm_master m1 
            LEFT JOIN perm_master m2 ON m1.perm_parent=m2.perm_id 
            LEFT JOIN users imu ON imu.ID=m1.last_updated_id $where_cond order by m1.perm_id desc $limit ";
        $result = $this->db->query($query);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $result->num_rows();

        $rows = $this->db->query("SELECT COUNT(*) as total_count FROM perm_master m1 
            LEFT JOIN perm_master m2 ON m1.perm_parent=m2.perm_id 
            LEFT JOIN users imu ON imu.ID=m1.last_updated_id $where_cond")->row_array();
        $return['total_rows'] = $rows['total_count'];

        return $return;
    }

    function is_menu_code_exists($code) {
        $this->db->where('perm_code', $code);
        $result = $this->db->get('perm_master');
        return ($result->num_rows() > 0) ? FALSE : TRUE;
    }

    function is_menu_order_exists($input) {
        if ($input['is_edit'] != $input['menu_order']) {
            if (!empty($input['parent']) && $input['parent'] != '') {
                $this->db->where('perm_parent', $input['parent']);
            }
            $this->db->where('perm_order', $input['menu_order']);
            $result = $this->db->get('perm_master');
            return ($result->num_rows() > 0) ? FALSE : TRUE;
        } else {
            return true;
        }
    }

    function save_manu($post) {
        $parent_menu = (!empty($post['is_child'])) ? $post['parent_menu'] : 0;
        $form_arr = array(
            'perm_code' => $post['menu_code'],
            'perm_desc' => $post['menu_title'],
            'perm_order' => $post['menu_order'],
            'perm_parent' => $parent_menu,
            'perm_url' => $post['menu_url'],
            'perm_status' => $post['menu_status'],
            'last_updated_id' => $post['uid']
        );
        return $this->db->insert('perm_master', $form_arr);
    }

    function update_manu($post) {

        $parent_menu = (!empty($post['is_child'])) ? $post['parent_menu'] : 0;
        $form_arr = array(
            'perm_desc' => $post['menu_title'],
            'perm_order' => $post['menu_order'],
            'perm_parent' => $parent_menu,
            'perm_url' => $post['menu_url'],
            'perm_status' => $post['menu_status'],
            'last_updated_id' => $post['uid']
        );
        $this->db->update('perm_master', $form_arr, array('perm_code' => $post['menu_code']));
        echo $this->db->last_query();
    }

    function get_menu_details_by_id($perm_id = NULL) {
        if ($perm_id) {
            $this->db->where('perm_id', $perm_id);
        }
        $result = $this->db->get('perm_master');
        $return['data'] = $result->result_array();
        $return['num_rows'] = $result->num_rows();
        return $return;
    }

    function get_menu_access_permissions($perm_id) {
        $query = "SELECT role_id, role_name, role_code, role_perm_id,perm_id,access_perm,status FROM ( 
            SELECT imr.role_id, role_name, role_code, role_perm_id,perm_id,access_perm,status FROM role_master imr 
            JOIN role_perm irp ON imr.role_id=irp.role_id where irp.perm_id='$perm_id' 
            UNION ALL 
            SELECT imr.role_id, role_name, role_code,0 role_perm_id,0 perm_id,0 access_perm,0 status FROM role_master imr 
         ) A GROUP BY role_code;";
        return $this->db->query($query)->result_array();
    }

    function update_menu_access_permissions($post_values) {
        $count = $post_values['count'];
        $perm_id = $post_values['perm_id'];
        $uid = $this->rbac->get_uid();
        $date = new DateTime();
        $this->db->trans_start();
        for ($i = 0; $i < $count; $i++) {
            $role_id = $post_values['role_info_' . $i . ''];
            $is_enabled = (isset($post_values['is_enabled_' . $i . '']) ? TRUE : FALSE);
            $is_active = (isset($post_values['is_enabled_' . $i . '']) ? 'Active' : 'Inactive');
            $access_perm = (isset($post_values['rw_perm_' . $i . ''])) ? $post_values['rw_perm_' . $i . ''] : 0;
            $rows_count = $this->db->get_where('role_perm', array('role_id' => $role_id, 'perm_id' => $perm_id))->num_rows();
            if ($rows_count == 0 && $is_enabled) {
                $input_arr = array(
                    'role_id' => $role_id,
                    'perm_id' => $perm_id,
                    'status' => $is_active,
                    'access_perm' => $access_perm,
                    'last_updated_id' => $uid
                );
                $this->db->insert('role_perm', $input_arr);
            } else {
                $input_arr = array(
                    'role_id' => $role_id,
                    'perm_id' => $perm_id,
                    'status' => $is_active,
                    'access_perm' => $access_perm,
                    'last_updated_id' => $uid
                );
                $this->db->update('role_perm', $input_arr, array('role_id' => $role_id, 'perm_id' => $perm_id));
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}
