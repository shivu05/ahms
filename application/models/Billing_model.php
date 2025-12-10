<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Billing_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    public function get_service_groups()
    {
        return $this->db->order_by('group_name', 'ASC')->get('service_groups')->result_array();
    }

    public function get_services_by_group($group_id)
    {
        return $this->db->where('group_id', (int) $group_id)
            ->order_by('service_name', 'ASC')
            ->get('bill_services')
            ->result_array();
    }

    public function get_service($service_id)
    {
        return $this->db->where('service_id', (int) $service_id)
            ->get('bill_services')
            ->row_array();
    }

    public function add_opd_item($opd_no, $service_id, $date = null, $amount = null)
    {
        $service = $this->get_service($service_id);
        if (!$service) {
            throw new InvalidArgumentException('Invalid service selected.');
        }

        $billing_date = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d');
        $line_amount = $amount !== null ? (float) $amount : (float) $service['price'];

        $this->db->trans_start();
        $this->db->insert('opd_billing', [
            'opd_no' => (int) $opd_no,
            'service_id' => (int) $service_id,
            'billing_date' => $billing_date,
            'amount' => $line_amount,
            'invoice_id' => null,
        ]);
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new RuntimeException('Failed to add OPD item.');
        }

        return $this->db->insert_id();
    }

    public function add_ipd_item($ipd_no, $service_id, $date = null, $amount = null)
    {
        $service = $this->get_service($service_id);
        if (!$service) {
            throw new InvalidArgumentException('Invalid service selected.');
        }

        $billing_date = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d');
        $line_amount = $amount !== null ? (float) $amount : (float) $service['price'];

        $this->db->trans_start();
        $this->db->insert('ipd_billing', [
            'ipd_no' => (int) $ipd_no,
            'service_id' => (int) $service_id,
            'billing_date' => $billing_date,
            'amount' => $line_amount,
            'invoice_id' => null,
        ]);
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new RuntimeException('Failed to add IPD item.');
        }

        return $this->db->insert_id();
    }

    public function get_uninvoiced_items($context, $ref_no)
    {
        $ref_no = (int) $ref_no;
        if ($context === 'OPD') {
            return $this->db
                ->select('ob.billing_id, ob.billing_date, ob.amount, ob.service_id, bs.service_name')
                ->from('opd_billing ob')
                ->join('bill_services bs', 'bs.service_id = ob.service_id', 'left')
                ->where('ob.opd_no', $ref_no)
                ->where('ob.invoice_id IS NULL', null, false)
                ->order_by('ob.billing_date', 'ASC')
                ->get()
                ->result_array();
        }

        if ($context === 'IPD') {
            return $this->db
                ->select('ib.billing_id, ib.billing_date, ib.amount, ib.service_id, bs.service_name')
                ->from('ipd_billing ib')
                ->join('bill_services bs', 'bs.service_id = ib.service_id', 'left')
                ->where('ib.ipd_no', $ref_no)
                ->where('ib.invoice_id IS NULL', null, false)
                ->order_by('ib.billing_date', 'ASC')
                ->get()
                ->result_array();
        }

        throw new InvalidArgumentException('Invalid billing context.');
    }

    public function finalize_invoice($context, $ref_no, $discount = 0, $tax_percent = 0)
    {
        $context = strtoupper($context);
        if (!in_array($context, ['OPD', 'IPD'], true)) {
            throw new InvalidArgumentException('Unsupported context.');
        }

        $ref_no = (int) $ref_no;
        $discount = (float) $discount;
        $tax_percent = (float) $tax_percent;

        $items = $this->get_uninvoiced_items($context, $ref_no);
        if (empty($items)) {
            throw new RuntimeException('No items available to finalize.');
        }

        $subtotal = array_reduce($items, function ($carry, $item) {
            return $carry + (float) $item['amount'];
        }, 0.0);

        if ($discount < 0) {
            throw new InvalidArgumentException('Discount cannot be negative.');
        }
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }
        if ($tax_percent < 0) {
            throw new InvalidArgumentException('Tax percent cannot be negative.');
        }

        $taxable = max($subtotal - $discount, 0);
        $tax_amount = round($taxable * ($tax_percent / 100), 2);
        $grand_total = round($taxable + $tax_amount, 2);

        $invoice_no = $this->generate_invoice_number($context);

        $now = date('Y-m-d H:i:s');

        $this->db->trans_start();

        $this->db->insert('invoices', [
            'context' => $context,
            'ref_no' => $ref_no,
            'invoice_no' => $invoice_no,
            'invoice_date' => $now,
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax_percent' => round($tax_percent, 2),
            'tax_amount' => $tax_amount,
            'grand_total' => $grand_total,
            'paid_amount' => 0,
            'balance_amount' => $grand_total,
            'status' => 'UNPAID',
        ]);

        $invoice_id = $this->db->insert_id();

        if ($context === 'OPD') {
            $this->db->where('opd_no', $ref_no)
                ->where('invoice_id IS NULL', null, false)
                ->update('opd_billing', ['invoice_id' => $invoice_id]);
        } else {
            $this->db->where('ipd_no', $ref_no)
                ->where('invoice_id IS NULL', null, false)
                ->update('ipd_billing', ['invoice_id' => $invoice_id]);
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new RuntimeException('Failed to finalize invoice.');
        }

        return [
            'invoice_id' => $invoice_id,
            'invoice_no' => $invoice_no,
        ];
    }

    public function get_invoice($invoice_id)
    {
        $invoice = $this->db->where('id', (int) $invoice_id)->get('invoices')->row_array();
        if (!$invoice) {
            return null;
        }

        $lines = $this->db
            ->where('invoice_id', (int) $invoice_id)
            ->order_by('billing_date', 'ASC')
            ->get('vw_invoice_lines')
            ->result_array();

        $payments = $this->db
            ->where('invoice_id', (int) $invoice_id)
            ->order_by('paid_on', 'ASC')
            ->get('payments')
            ->result_array();

        return [
            'invoice' => $invoice,
            'lines' => $lines,
            'payments' => $payments,
        ];
    }

    public function record_payment($invoice_id, $amount, $mode = 'CASH', $reference_no = null, $notes = null)
    {
        $invoice = $this->db->where('id', (int) $invoice_id)->get('invoices')->row_array();
        if (!$invoice) {
            throw new RuntimeException('Invoice not found.');
        }

        $amount = round((float) $amount, 2);
        if ($amount <= 0) {
            throw new InvalidArgumentException('Payment amount must be positive.');
        }

        $this->db->trans_start();

        $this->db->insert('payments', [
            'invoice_id' => (int) $invoice_id,
            'amount' => $amount,
            'mode' => $mode,
            'reference_no' => $reference_no,
            'notes' => $notes,
        ]);

        $paid_sum = $this->db
            ->select_sum('amount')
            ->where('invoice_id', (int) $invoice_id)
            ->get('payments')
            ->row()
            ->amount;

        $paid_sum = $paid_sum !== null ? (float) $paid_sum : 0.0;
        $balance = max($invoice['grand_total'] - $paid_sum, 0);

        $status = 'UNPAID';
        if ($paid_sum >= $invoice['grand_total']) {
            $status = 'PAID';
        } elseif ($paid_sum > 0) {
            $status = 'PARTIAL';
        }

        $this->db->where('id', (int) $invoice_id)->update('invoices', [
            'paid_amount' => round($paid_sum, 2),
            'balance_amount' => round($balance, 2),
            'status' => $status,
        ]);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new RuntimeException('Failed to record payment.');
        }

        return true;
    }

    public function list_invoices($filters, $limit = 20, $offset = 0)
    {
        $filters = is_array($filters) ? $filters : [];

        $this->db->start_cache();
        $this->db->from('invoices');

        if (!empty($filters['context'])) {
            $this->db->where('context', $filters['context']);
        }
        if (!empty($filters['ref_no'])) {
            $this->db->where('ref_no', (int) $filters['ref_no']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(invoice_date) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(invoice_date) <=', $filters['date_to']);
        }

        $this->db->stop_cache();

        $total = $this->db->count_all_results();

        $query = $this->db
            ->order_by('invoice_date', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get('invoices');

        $this->db->flush_cache();

        return [
            'total' => $total,
            'rows' => $query->result_array(),
        ];
    }

    public function add_ipd_daily_room_charges($ipd_no, $start_date, $end_date, $tariff_service_id, $tariff_amount = null)
    {
        $service = $this->get_service($tariff_service_id);
        if (!$service) {
            throw new InvalidArgumentException('Invalid room tariff service.');
        }

        $amount = $tariff_amount !== null ? (float) $tariff_amount : (float) $service['price'];

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        if ($end < $start) {
            throw new InvalidArgumentException('End date cannot be earlier than start date.');
        }

        $period_end = (clone $end)->modify('+1 day');
        $period = new DatePeriod($start, new DateInterval('P1D'), $period_end);

        $this->db->trans_start();
        foreach ($period as $day) {
            $this->db->insert('ipd_billing', [
                'ipd_no' => (int) $ipd_no,
                'service_id' => (int) $tariff_service_id,
                'billing_date' => $day->format('Y-m-d'),
                'amount' => $amount,
                'invoice_id' => null,
            ]);
        }
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new RuntimeException('Failed to add room charges.');
        }

        return true;
    }

    private function generate_invoice_number($context)
    {
        $prefix = sprintf('INV-%s-%s-', strtoupper($context), date('Ym'));
        $this->db->select('invoice_no')
            ->from('invoices')
            ->like('invoice_no', $prefix, 'after')
            ->order_by('invoice_no', 'DESC')
            ->limit(1);
        $last = $this->db->get()->row_array();

        $next = 1;
        if (!empty($last['invoice_no'])) {
            $sequence = (int) substr($last['invoice_no'], -4);
            $next = $sequence + 1;
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}

