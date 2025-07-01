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

    /**
     * Handles the arrival of new medicine batches.
     * If the batch number for a given medicine already exists, it updates the stock.
     * Otherwise, it inserts a new batch record.
     *
     * @param int $medicine_id The ID of the medicine (from the Medicines table).
     * @param string $batch_number The unique batch number provided by the manufacturer.
     * @param string $expiry_date The expiry date of this batch (YYYY-MM-DD format).
     * @param int $quantity_received The quantity of medicine received in this delivery.
     * @param int $supplier_id The ID of the supplier (from the Suppliers table).
     * @param string $storage_location The physical storage location in the pharmacy.
     * @param string $date_received The date this batch was received (YYYY-MM-DD format).
     * @return bool True if the operation was successful, false otherwise.
     */
    public function handle_medicine_batch(
        $input_data
    ) {
        // Start a database transaction. This ensures that either all operations
        // within this block succeed, or none of them do, maintaining data integrity.
        $this->db->trans_start();

        // 1. Check if a batch with the given MedicineID and BatchNumber already exists.
        $this->db->where('medicine_id', $input_data['medicine_id']);
        $this->db->where('batch_number', $input_data['batch_number']);
        $query = $this->db->get($this->table);
        $existing_batch = $query->row(); // Get the single row result if it exists

        if ($existing_batch) {
            // 2. If the batch exists, update its quantities and other relevant details.
            // We add the new quantity to the existing QuantityReceived and QuantityInStock.
            $data = array(
                'quantity_received'  => $existing_batch->quantity_received + $input_data['quantity_received'],
                'quantity_instock'   => $existing_batch->quantity_instock + $input_data['quantity_received'], // Update stock by adding the new quantity
                'expiry_date'        => $input_data['expiry_date'],       // Update expiry date (in case of re-entry with new info)
                'supplier_id'        => $input_data['supplier_id'],      // Update supplier (if different for subsequent receipts)
                'storage_location'   => $input_data['storage_location'], // Update storage location
                'date_received'      => $input_data['date_received']     // Update date received (to the latest receipt date)
            );

            // Specify the condition for the update: by BatchID to ensure we update the correct record.
            $this->db->where('id', $existing_batch->id);
            $this->db->update($this->table, $data);
        } else {
            // 3. If the batch does not exist, insert a new record into the Batches table.
            $data = array(
                'medicine_id'        => $input_data['medicine_id'],
                'batch_number'       => $input_data['batch_number'],
                'expiry_date'        => $input_data['expiry_date'],
                'quantity_received'  => $input_data['quantity_received'],
                'quantity_instock'   => $input_data['quantity_received'], // For a new batch, initial stock equals quantity received
                'supplier_id'        => $input_data['supplier_id'],
                'storage_location'   => $input_data['storage_location'],
                'date_received'      => $input_data['date_received']
            );

            $this->db->insert($this->table, $data);
        }

        // Complete the transaction. If any query failed, trans_complete() will return FALSE
        // and automatically roll back the changes.
        $this->db->trans_complete();

        // Check the transaction status.
        if ($this->db->trans_status() === FALSE) {
            // Log the error for debugging purposes
            log_message('error', 'Database transaction failed in handle_new_batch: ' . $this->db->error()['message']);
            return FALSE; // Indicate failure
        }

        return TRUE; // Indicate success
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

    public function get_medicine_names()
    {
        $this->db->select('id, name');
        $this->db->from('medicines');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_supplier_names()
    {
        $this->db->select('id, supplier_name');
        $this->db->from('suppliers');
        $query = $this->db->get();
        return $query->result_array();
    }
}
