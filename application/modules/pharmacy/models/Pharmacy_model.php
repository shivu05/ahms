<?php

// Pharmacy Model (CRUD for Medicines)
class Pharmacy_model extends CI_Model {

    private $table = 'medicines';
    private $column_order = ['name', 'brand', 'price', 'stock', 'expiry_date'];
    private $column_search = ['name', 'brand'];
    private $order = ['id' => 'desc'];

    public function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    private function _get_datatables_query() {
        $this->db->from($this->table);

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function add_medicine($data) {
        $this->db->insert($this->table, $data);
    }

    public function update_medicine($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    public function delete_medicine($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }
}
