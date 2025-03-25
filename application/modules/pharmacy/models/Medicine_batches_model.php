<?php

class Medicine_batches_model extends CI_Model
{

    var $table = 'medicine_batches';
    var $column_order = array('medicine_id', 'batch_number', 'expiry_date', 'quantity_received', 'quantity_instock', 'supplier_id', 'storage_location', 'date_received');
    var $column_search = array('batch_number', 'expiry_date', 'quantity_received', 'quantity_instock', 'supplier_id', 'storage_location', 'date_received');
    var $order = array('medicines.id' => 'asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->join('suppliers', 'suppliers.id = medicine_batches.supplier_id', 'left');
        $this->db->join('medicines', 'medicines.id = medicine_batches.medicine_id', 'left');

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $query->result()
        ];
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data)
    {
        $this->db->from($this->table);
        $this->db->where('batch_number', $data['batch_number']);
        $this->db->where('medicine_id', $data['medicine_id']);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            // Update the existing record
            $this->db->where('batch_number', $data['batch_number']);
            $this->db->where('medicine_id', $data['medicine_id']);
            $this->db->update($this->table, $data);
            return $this->db->affected_rows();
        } else {
            // Insert a new record
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function get_medicine_names() {
        $this->db->select('id, name');
        $this->db->from('medicines');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_supplier_names() {
        $this->db->select('id, supplier_name');
        $this->db->from('suppliers');
        $query = $this->db->get();
        return $query->result_array();
    }
}
