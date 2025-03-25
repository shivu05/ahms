<?php

class Medicines_model extends CI_Model {

    private $table = 'medicines';
    private $column_order = ['id', 'name', 'generic_name', 'dosage', 'form', 'manufacturer', 'ndc', 'description', 'controlled_substance', 'requires_prescription', 'storage_conditions', 'side_effects', 'interactions', 'unit_price', 'reorder_level', 'category', 'image', 'date_added', 'last_updated'];
    private $column_search = ['name', 'generic_name', 'category'];
    private $order = ['id' => 'asc'];

    public function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->db->count_all($this->table),
            "recordsFiltered" => $this->_count_filtered(),
            "data" => $query->result()
        ];
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
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    private function _count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ["id" => $id])->row();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
    }

    public function delete_by_id($id) {
        $this->db->delete($this->table, ["id" => $id]);
    }
}
