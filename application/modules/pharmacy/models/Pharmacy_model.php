<?php

class Pharmacy_model extends CI_Model
{
    var $table = "medicines";
    var $column_order = array(null, 'name', 'batch_no', 'expiry_date', 'price', 'gst', 'discount', 'stock');
    var $column_search = array('name', 'batch_no', 'expiry_date');
    var $order = array('id' => 'desc');

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_medicines_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function insert_medicine($data)
    {
        return $this->db->insert('medicines', $data);
    }

    public function get_medicine_by_id($id)
    {
        return $this->db->get_where('medicines', array('id' => $id))->row_array();
    }

    public function update_medicine($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('medicines', $data);
    }

    public function delete_medicine($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('medicines');
    }
}
