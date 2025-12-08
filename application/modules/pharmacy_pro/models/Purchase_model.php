<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Purchase Model - Handle purchase operations
 */
class Purchase_model extends SHV_Model {

    protected $table = 'medicine_purchases';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get suppliers
     */
    public function get_suppliers() {
        $this->db->where('status', 'active');
        $this->db->order_by('supplier_name', 'ASC');
        $query = $this->db->get('medicine_suppliers');
        return $query->result_array();
    }

    /**
     * Add supplier
     */
    public function add_supplier($data) {
        $data['supplier_code'] = $this->generate_supplier_code();
        return $this->db->insert('medicine_suppliers', $data);
    }

    /**
     * Generate supplier code
     */
    private function generate_supplier_code() {
        $prefix = 'SUP';
        
        $this->db->select('supplier_code');
        $this->db->from('medicine_suppliers');
        $this->db->like('supplier_code', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_code = $query->row()->supplier_code;
            $last_number = intval(substr($last_code, 3));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create purchase order
     */
    public function create_purchase_order($po_data, $po_items) {
        $this->db->trans_start();
        
        try {
            // Generate PO number
            $po_data['po_number'] = $this->generate_po_number();
            
            // Insert purchase order
            $this->db->insert('purchase_orders', $po_data);
            $po_id = $this->db->insert_id();
            
            // Insert PO items
            foreach ($po_items as $item) {
                $item['po_id'] = $po_id;
                $this->db->insert('purchase_order_items', $item);
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed to create purchase order');
            }
            
            return array(
                'status' => true,
                'po_id' => $po_id,
                'po_number' => $po_data['po_number']
            );
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Generate PO number
     */
    private function generate_po_number() {
        $prefix = 'PO' . date('Ymd');
        
        $this->db->select('po_number');
        $this->db->from('purchase_orders');
        $this->db->like('po_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_po = $query->row()->po_number;
            $last_number = intval(substr($last_po, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Process goods receipt
     */
    public function process_goods_receipt($purchase_data, $purchase_items) {
        $this->db->trans_start();
        
        try {
            // Insert purchase record
            $this->db->insert('medicine_purchases', $purchase_data);
            $purchase_id = $this->db->insert_id();
            
            // Process each purchase item
            foreach ($purchase_items as $item) {
                // Insert purchase item
                $item['purchase_id'] = $purchase_id;
                $this->db->insert('medicine_purchase_items', $item);
                
                // Check if batch already exists
                $existing_batch = $this->check_existing_batch($item['medicine_id'], $item['batch_number']);
                
                if ($existing_batch) {
                    // Update existing batch
                    $new_stock = $existing_batch['current_stock'] + $item['quantity'] + $item['free_quantity'];
                    $this->db->where('id', $existing_batch['id']);
                    $this->db->update('medicine_batches', array(
                        'current_stock' => $new_stock,
                        'purchase_price' => $item['unit_purchase_price'],
                        'selling_price' => $item['unit_selling_price'],
                        'mrp' => $item['mrp']
                    ));
                } else {
                    // Create new batch
                    $batch_data = array(
                        'medicine_id' => $item['medicine_id'],
                        'batch_number' => $item['batch_number'],
                        'manufacturing_date' => $item['manufacturing_date'],
                        'expiry_date' => $item['expiry_date'],
                        'mrp' => $item['mrp'],
                        'purchase_price' => $item['unit_purchase_price'],
                        'selling_price' => $item['unit_selling_price'],
                        'gst_percentage' => $item['gst_percentage'],
                        'discount_percentage' => $item['discount_percentage'],
                        'opening_stock' => $item['quantity'] + $item['free_quantity'],
                        'current_stock' => $item['quantity'] + $item['free_quantity'],
                        'rack_number' => $item['rack_number'],
                        'supplier_id' => $purchase_data['supplier_id'],
                        'purchase_invoice_number' => $purchase_data['invoice_number'],
                        'purchase_date' => $purchase_data['purchase_date']
                    );
                    
                    $this->db->insert('medicine_batches', $batch_data);
                }
                
                // Update PO item if linked
                if (!empty($purchase_data['po_id'])) {
                    $this->db->set('quantity_received', 'quantity_received + ' . $item['quantity'], FALSE);
                    $this->db->where('po_id', $purchase_data['po_id']);
                    $this->db->where('medicine_id', $item['medicine_id']);
                    $this->db->update('purchase_order_items');
                }
            }
            
            // Update PO status if linked
            if (!empty($purchase_data['po_id'])) {
                $this->update_po_status($purchase_data['po_id']);
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed to process goods receipt');
            }
            
            return array('status' => true, 'purchase_id' => $purchase_id);
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Check if batch already exists
     */
    private function check_existing_batch($medicine_id, $batch_number) {
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('batch_number', $batch_number);
        $query = $this->db->get('medicine_batches');
        return $query->row_array();
    }

    /**
     * Update PO status based on received quantities
     */
    private function update_po_status($po_id) {
        $this->db->select('SUM(quantity_ordered) as total_ordered, SUM(quantity_received) as total_received');
        $this->db->where('po_id', $po_id);
        $query = $this->db->get('purchase_order_items');
        $result = $query->row();
        
        if ($result->total_received == 0) {
            $status = 'sent';
        } elseif ($result->total_received < $result->total_ordered) {
            $status = 'partial';
        } else {
            $status = 'received';
        }
        
        $this->db->where('id', $po_id);
        $this->db->update('purchase_orders', array('status' => $status));
    }

    /**
     * Get purchase orders with datatables
     */
    public function get_purchase_orders_datatables() {
        $this->_get_purchase_orders_datatables_query();
        
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_purchase_orders_datatables_query() {
        $this->db->select('po.*, ms.supplier_name');
        $this->db->from('purchase_orders po');
        $this->db->join('medicine_suppliers ms', 'po.supplier_id = ms.id');
        
        $search = $_POST['search']['value'];
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('po.po_number', $search);
            $this->db->or_like('ms.supplier_name', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('po.po_date', 'DESC');
    }

    public function count_all_purchase_orders() {
        return $this->db->count_all_results('purchase_orders');
    }

    public function count_filtered_purchase_orders() {
        $this->_get_purchase_orders_datatables_query();
        return $this->db->count_all_results();
    }

    /**
     * Get purchase by ID
     */
    public function get_purchase_by_id($id) {
        $this->db->select('mp.*, ms.supplier_name');
        $this->db->from('medicine_purchases mp');
        $this->db->join('medicine_suppliers ms', 'mp.supplier_id = ms.id');
        $this->db->where('mp.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get purchase items
     */
    public function get_purchase_items($purchase_id) {
        $this->db->select('mpi.*, m.medicine_name');
        $this->db->from('medicine_purchase_items mpi');
        $this->db->join('medicines m', 'mpi.medicine_id = m.id');
        $this->db->where('mpi.purchase_id', $purchase_id);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get purchase orders for dropdown
     */
    public function get_pending_purchase_orders($supplier_id = null) {
        $this->db->select('po.*, ms.supplier_name');
        $this->db->from('purchase_orders po');
        $this->db->join('medicine_suppliers ms', 'po.supplier_id = ms.id');
        $this->db->where_in('po.status', array('sent', 'partial'));
        
        if ($supplier_id) {
            $this->db->where('po.supplier_id', $supplier_id);
        }
        
        $this->db->order_by('po.po_date', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get supplier by ID
     */
    public function get_supplier_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('medicine_suppliers');
        return $query->row_array();
    }

    /**
     * Update supplier
     */
    public function update_supplier($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicine_suppliers', $data);
    }

    /**
     * Get purchase statistics
     */
    public function get_purchase_statistics() {
        $stats = array();
        
        // This month's purchases
        $this->db->select('COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total_amount');
        $this->db->from('medicine_purchases');
        $this->db->where('MONTH(purchase_date)', date('m'));
        $this->db->where('YEAR(purchase_date)', date('Y'));
        $query = $this->db->get();
        $stats['this_month'] = $query->row_array();
        
        // Pending POs
        $this->db->where_in('status', array('sent', 'partial'));
        $stats['pending_pos'] = $this->db->count_all_results('purchase_orders');
        
        // Top suppliers by volume
        $this->db->select('ms.supplier_name, COUNT(mp.id) as purchase_count, SUM(mp.total_amount) as total_amount');
        $this->db->from('medicine_purchases mp');
        $this->db->join('medicine_suppliers ms', 'mp.supplier_id = ms.id');
        $this->db->where('mp.purchase_date >=', date('Y-m-d', strtotime('-12 months')));
        $this->db->group_by('ms.id');
        $this->db->order_by('total_amount', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        $stats['top_suppliers'] = $query->result_array();
        
        return $stats;
    }

    /**
     * Generate invoice number for purchase
     */
    public function generate_purchase_invoice_number() {
        $prefix = 'PI' . date('Ymd');
        
        $this->db->select('invoice_number');
        $this->db->from('medicine_purchases');
        $this->db->like('invoice_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_invoice = $query->row()->invoice_number;
            $last_number = intval(substr($last_invoice, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}
