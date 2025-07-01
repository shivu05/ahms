<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dispense_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Ensure the database library is loaded
    }

    /**
     * Retrieves all active medicines.
     * @return array An array of medicine objects.
     */
    public function get_all_medicines()
    {
        $this->db->select('MedicineID, MedicineName, Dosage, UnitPrice');
        $this->db->from('Medicines');
        // Optionally, add a condition for active medicines if you have a status field
        // $this->db->where('Status', 'active');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Retrieves available batches for a specific medicine, ordered by expiry date (FEFO).
     * Only returns batches with QuantityInStock > 0.
     * @param int $medicine_id The ID of the medicine.
     * @return array An array of batch objects.
     */
    public function get_batches_for_medicine($medicine_id)
    {
        $this->db->select('BatchID, BatchNumber, ExpiryDate, QuantityInStock');
        $this->db->from('Batches');
        $this->db->where('MedicineID', $medicine_id);
        $this->db->where('QuantityInStock >', 0); // Only batches with available stock
        $this->db->order_by('ExpiryDate', 'ASC'); // First-Expiry-First-Out (FEFO)
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Retrieves details for a specific medicine.
     * @param int $medicine_id The ID of the medicine.
     * @return object A medicine object or null if not found.
     */
    public function get_medicine_details($medicine_id)
    {
        $this->db->select('MedicineID, MedicineName, Dosage, UnitPrice');
        $this->db->from('Medicines');
        $this->db->where('MedicineID', $medicine_id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Searches for patients by first name, last name, or phone number.
     * @param string $search_term The term to search for.
     * @return array An array of patient objects.
     */
    public function search_patients($search_term)
    {
        $this->db->select('PatientID, FirstName, LastName, PhoneNumber');
        $this->db->from('Patients');
        $this->db->like('FirstName', $search_term);
        $this->db->or_like('LastName', $search_term);
        $this->db->or_like('PhoneNumber', $search_term);
        $this->db->limit(10); // Limit results for performance
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Retrieves details for a specific patient.
     * @param int $patient_id The ID of the patient.
     * @return object A patient object or null if not found.
     */
    public function get_patient_details($patient_id)
    {
        $this->db->select('PatientID, FirstName, LastName, PhoneNumber, Address');
        $this->db->from('Patients');
        $this->db->where('PatientID', $patient_id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Adds a new patient to the database.
     * @param array $data An associative array of patient data.
     * @return int The ID of the newly inserted patient, or 0 on failure.
     */
    public function add_patient($data)
    {
        $this->db->insert('Patients', $data);
        return $this->db->insert_id();
    }

    /**
     * Begins a database transaction.
     */
    public function start_transaction()
    {
        $this->db->trans_start();
    }

    /**
     * Completes a database transaction (commits or rolls back).
     * @return bool True if the transaction was successful, false otherwise.
     */
    public function complete_transaction()
    {
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Creates a new sales record.
     * @param int $patient_id The ID of the patient for this sale.
     * @param float $total_amount The total amount of the sale.
     * @return int The ID of the newly created sale, or 0 on failure.
     */
    public function create_sale($patient_id, $total_amount)
    {
        $data = array(
            'PatientID'  => $patient_id,
            'SaleDate'   => date('Y-m-d H:i:s'),
            'TotalAmount' => $total_amount
        );
        $this->db->insert('Sales', $data);
        return $this->db->insert_id();
    }

    /**
     * Adds an item to a sales record.
     * @param int $sale_id The ID of the sale.
     * @param int $medicine_id The ID of the medicine.
     * @param int $batch_id The ID of the specific batch dispensed.
     * @param int $quantity The quantity dispensed.
     * @param float $price The price per unit at the time of sale.
     * @return bool True on success, false on failure.
     */
    public function add_sale_item($sale_id, $medicine_id, $batch_id, $quantity, $price)
    {
        $data = array(
            'SaleID'    => $sale_id,
            'MedicineID' => $medicine_id,
            'BatchID'   => $batch_id,
            'Quantity'  => $quantity,
            'Price'     => $price
        );
        return $this->db->insert('SaleItems', $data);
    }

    /**
     * Updates the QuantityInStock for a specific batch.
     * @param int $batch_id The ID of the batch.
     * @param int $quantity_dispensed The quantity to subtract from stock.
     * @return bool True on success, false on failure.
     */
    public function update_batch_stock($batch_id, $quantity_dispensed)
    {
        $this->db->set('QuantityInStock', 'QuantityInStock - ' . (int)$quantity_dispensed, FALSE); // FALSE prevents escaping
        $this->db->where('BatchID', $batch_id);
        return $this->db->update('Batches');
    }
}
