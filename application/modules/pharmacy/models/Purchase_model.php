<?php

class Purchase_model extends CI_Model {

    function get_purchase_variables($conditions, $export_flag = false) {
        $return = array();
        $columns = array('id', 'type', 'name', 'extrainfo');
        $where_cond = " WHERE name is NOT NULL ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'type':
                        $where_cond .= " AND LOWER(type)=LOWER('$val')";
                        break;
                    case 'name':
                        $where_cond .= " AND name LIKE '%$val%'";
                /* default:
                  $where_cond .= " AND $col = '$val'"; */
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM purchase_variables, (SELECT @a:= 0) AS a $where_cond";
        $result = $this->db->query($query . ' ' . $limit);

        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM purchase_variables")->num_rows();
        return $return;
    }

    function get_purchase_types_master() {
        return $this->db->get('purchase_types_master')->result_array();
    }

    function save_purchase_type_master($post_data) {
        return $this->db->insert('purchase_variables', $post_data);
    }

    function get_purchase_items($type = '') {
        $this->db->where('type', $type);
        return $this->db->get('purchase_variables')->result_array();
    }

    function save_product($post_values) {
        return $this->db->insert('product_master', $post_values);
    }

    function get_product_list($conditions, $export_flag = false) {
        $return = array();
        $columns = array('product_id', 'product_unique_id', 'product_master_id', 'product_batch', 'supplier_id', 'packing_name', 'product_mfg', 'product_group',
            'product_type', 'manifacture_date', 'exp_date', 'purchase_rate', 'mrp', 'sale_rate', 'vat', 'discount', 'reorder_point',
            'weight', 'pv1.name as product_name', 'pv2.name as supplier_name');
        $where_cond = " WHERE product_master_id is NOT NULL ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'type':
                        $where_cond .= " AND LOWER(type)=LOWER('$val')";
                        break;
                    case 'name':
                        $where_cond .= " AND name LIKE '%$val%'";
                /* default:
                  $where_cond .= " AND $col = '$val'"; */
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM product_master p
            join purchase_variables pv1 on p.product_master_id=pv1.id
            join purchase_variables pv2 on p.supplier_id=pv2.id
            ,(SELECT @a:= 0) AS a $where_cond";


        $result = $this->db->query($query . ' ' . $limit);

        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM purchase_variables")->num_rows();
        return $return;
    }

    function get_temp_products_return_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array(
            'billno', 'date', 'refno', 'price', 'opbal', 'product', 'batch', 'prate', 'mrp', 'discount'
        );
        $where_cond = " WHERE 1=1 ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM purchase_return_temp,(SELECT @a:= 0) AS a $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('purchase_return_temp');
        return $return;
    }

    function get_stock_variables($column = null) {
        if ($column) {
            $this->db->distinct();
            $this->db->select($column);
            return $this->db->get('stock')->result_array();
        }
        return false;
    }

    function get_products_by_supplier($supplies_id = NULL) {
        $this->db->from('product_master pd');
        $this->db->join('purchase_variables pv', 'pd.product_master_id=pv.id');
        if ($supplies_id) {
            $this->db->where('supplier_id', $supplies_id);
        }
        return $this->db->get()->result_array();
        //echo $this->db->last_query();exit;
    }

    function get_product_variables($column = null, $where = null) {
        if ($column) {
            //$this->db->distinct();
            $this->db->select($column);
        }
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get('product_master')->result_array();
    }

    function get_product_info($product_id, $batch) {
        $this->db->where('product_id', $product_id);
        $this->db->where('batch', $batch);
        $this->db->limit(1);
        return $this->db->get('product_details')->result_array();
    }

    function save_temp_purchase_data($post_values) {
        return $this->db->insert('temp_purchase_entry', $post_values);
    }

    function get_temp_purchase_data() {
        $user_id = $this->rbac->get_uid();
        $this->db->where('user_id', $user_id);
        return $this->db->get('temp_purchase_entry')->row_array();
    }

    function save_purchase_entry($bill_no) {
        $uid = $this->rbac->get_uid();
        $this->db->trans_begin();
        //fetech temp data to main table
        $this->db->query("INSERT INTO purchase_entry (supplier_id, billno, date, refno, price, opbal, product, batch, pty, fty, prate, mrp, discount, vat, btype, total, user_id) 
            SELECT supplier_id, billno, date, refno, price, opbal, product, batch, pty, fty, prate, mrp, discount, vat, btype, total, user_id 
            FROM temp_purchase_entry WHERE user_id='" . $uid . "' and billno='" . $bill_no . "'");
        $temp_records = $this->db->query("SELECT * FROM temp_purchase_entry WHERE user_id='" . $uid . "' and billno='" . $bill_no . "'")->result_array();
        if (!empty($temp_records)) {
            foreach ($temp_records as $row) {
                /* $stock_details = $this->db->query("SELECT t.supplier_id,t.product, batch FROM temp_purchase_entry t 
                  JOIN stock s ON t.supplier_id=s.supplier_id AND t.product = s.product AND t.batch =s.batchno"); */
                $check_stock_data = $this->db->query("select * from stock where supplier_id='" . $row['supplier_id'] . "' 
                    and product='" . $row['product'] . "' and batchno='" . $row['batch'] . "'")->row_array();
                if (empty($check_stock_data)) {
                    $this->db->query("INSERT INTO stock (supplier_id, product, batchno, cstock, price, amount, billno, status,date) 
                        SELECT supplier_id, product, batch , pty, mrp, total, billno, '1',date FROM temp_purchase_entry where id='" . $row['id'] . "'");
                } else {
                    $this->db->query("update stock s 
                        JOIN temp_purchase_entry t ON t.supplier_id=s.supplier_id AND t.product = s.product AND t.batch =s.batchno 
                        set s.cstock = (s.cstock+t.pty), status='1',s.amount=t.total,s.price=t.mrp,s.date=t.date where t.supplier_id='" . $row['supplier_id'] . "'
                            AND t.product='" . $row['product'] . "' AND t.batch= '" . $row['batch'] . "'");
                }
            }
        }
        $this->db->where('user_id', $uid)->delete('temp_purchase_entry');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_bill_nos() {
        return $this->db->distinct()->select('billno')->get('purchase_entry')->result_array();
        //echo $this->db->last_query();exit;
    }

    public function get_purchase_details($billno) {
        $this->db->select('*');
        $this->db->from('purchase_entry p');
        $this->db->join('product_master pm', 'p.product=pm.product_id');
        $this->db->join('purchase_variables pv', 'pm.product_master_id=pv.id');
        $this->db->where('pv.type', 'product');
        return $this->db->where('p.billno', $billno)->get()->result_array();
        //echo $this->db->last_query();exit;
    }

}
