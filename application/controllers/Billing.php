<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Billing extends SHV_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Billing_model', 'billing');
        $this->load->helper(['form', 'url', 'money', 'mpdf']);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->layout->layout = 'default';
    }

    public function opd($opd_no)
    {
        $this->ensure_authenticated();
        $opd_no = (int) $opd_no;
        if ($opd_no <= 0) {
            show_error('Invalid OPD number supplied.', 400);
        }

        $data = [
            'context' => 'OPD',
            'ref_no' => $opd_no,
            'service_groups' => $this->billing->get_service_groups(),
        ];

        $this->layout->title = 'OPD Billing';
        $this->layout->navTitleFlag = true;
        //$this->layout->navTitle = sprintf('Billing - OPD #%d', $opd_no);
        $this->layout->data = $data;
        $this->layout->render(['view' => 'billing/create']);
    }

    public function ipd($ipd_no)
    {
        $this->ensure_authenticated();
        $ipd_no = (int) $ipd_no;
        if ($ipd_no <= 0) {
            show_error('Invalid IPD number supplied.', 400);
        }

        $data = [
            'context' => 'IPD',
            'ref_no' => $ipd_no,
            'service_groups' => $this->billing->get_service_groups(),
        ];

        $this->layout->title = 'IPD Billing';
        $this->layout->navTitleFlag = true;
        //$this->layout->navTitle = sprintf('Billing - IPD #%d', $ipd_no);
        $this->layout->data = $data;
        $this->layout->render(['view' => 'billing/create']);
    }

    public function current_items()
    {
        $this->ensure_authenticated();
        try {
            $contextParam = $this->input->get('context', true);
            $context = is_string($contextParam) ? strtoupper(trim($contextParam)) : '';
            $ref_no = (int) $this->input->get('ref_no', true);
            if (!in_array($context, ['OPD', 'IPD'], true) || $ref_no <= 0) {
                throw new InvalidArgumentException('Invalid request parameters.');
            }

            $items = $this->billing->get_uninvoiced_items($context, $ref_no);
            $subtotal = array_reduce($items, function ($carry, $item) {
                return $carry + (float) $item['amount'];
            }, 0.0);

            $this->output_json([
                'ok' => true,
                'items' => $items,
                'subtotal' => round($subtotal, 2),
            ]);
        } catch (Throwable $th) {
            $this->output_json([
                'ok' => false,
                'msg' => $th->getMessage(),
            ], 400);
        }
    }

    public function add_item()
    {
        $this->ensure_authenticated();
        $payload = $this->input->post(null, true);
        $this->form_validation->set_data($payload);
        $this->form_validation->set_rules('context', 'Context', 'trim|required|in_list[OPD,IPD]');
        $this->form_validation->set_rules('ref_no', 'Reference number', 'trim|required|integer|greater_than[0]');
        $this->form_validation->set_rules('service_id', 'Service', 'trim|required|integer|greater_than[0]');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('billing_date', 'Billing date', 'trim');

        if (!$this->form_validation->run()) {
            $this->output_json([
                'ok' => false,
                'msg' => validation_errors(' ', ' '),
            ], 400);
            return;
        }

        $context = strtoupper($payload['context']);
        $ref_no = (int) $payload['ref_no'];
        $service_id = (int) $payload['service_id'];
        $amount = isset($payload['amount']) && $payload['amount'] !== '' ? (float) $payload['amount'] : null;
        $billing_date = isset($payload['billing_date']) ? trim($payload['billing_date']) : null;

        if ($billing_date) {
            $date_obj = DateTime::createFromFormat('Y-m-d', $billing_date);
            if (!$date_obj) {
                $this->output_json([
                    'ok' => false,
                    'msg' => 'Invalid billing date format. Use YYYY-MM-DD.',
                ], 400);
                return;
            }
            $billing_date = $date_obj->format('Y-m-d');
        }

        try {
            if ($context === 'OPD') {
                $line_id = $this->billing->add_opd_item($ref_no, $service_id, $billing_date, $amount);
            } else {
                $line_id = $this->billing->add_ipd_item($ref_no, $service_id, $billing_date, $amount);
            }

            $this->output_json([
                'ok' => true,
                'line_id' => $line_id,
            ]);
        } catch (Throwable $th) {
            log_message('error', 'Billing add_item failed: ' . $th->getMessage());
            $this->output_json([
                'ok' => false,
                'msg' => $th->getMessage(),
            ], 500);
        }
    }

    public function finalize()
    {
        $this->ensure_authenticated();
        $payload = $this->input->post(null, true);
        $this->form_validation->set_data($payload);
        $this->form_validation->set_rules('context', 'Context', 'trim|required|in_list[OPD,IPD]');
        $this->form_validation->set_rules('ref_no', 'Reference number', 'trim|required|integer|greater_than[0]');
        $this->form_validation->set_rules('discount', 'Discount', 'trim|numeric');
        $this->form_validation->set_rules('tax_percent', 'Tax percent', 'trim|numeric');

        if (!$this->form_validation->run()) {
            $this->output_json([
                'ok' => false,
                'msg' => validation_errors(' ', ' '),
            ], 400);
            return;
        }

        $context = strtoupper($payload['context']);
        $ref_no = (int) $payload['ref_no'];
        $discount = isset($payload['discount']) && $payload['discount'] !== '' ? (float) $payload['discount'] : 0;
        $tax_percent = isset($payload['tax_percent']) && $payload['tax_percent'] !== '' ? (float) $payload['tax_percent'] : 0;

        try {
            $result = $this->billing->finalize_invoice($context, $ref_no, $discount, $tax_percent);
            $this->output_json([
                'ok' => true,
                'invoice_id' => $result['invoice_id'],
                'invoice_no' => $result['invoice_no'],
            ]);
        } catch (Throwable $th) {
            log_message('error', 'Finalize invoice failed: ' . $th->getMessage());
            $this->output_json([
                'ok' => false,
                'msg' => $th->getMessage(),
            ], 400);
        }
    }

    public function invoice($invoice_id)
    {
        $this->ensure_authenticated();
        $invoice_id = (int) $invoice_id;
        if ($invoice_id <= 0) {
            show_error('Invalid invoice id.', 400);
        }

        $record = $this->billing->get_invoice($invoice_id);
        if (!$record) {
            show_404();
        }

        $data = [
            'invoice' => $record['invoice'],
            'lines' => $record['lines'],
            'payments' => $record['payments'],
            'payment_modes' => ['CASH', 'CARD', 'UPI', 'NEFT', 'OTHER'],
        ];

        if ($this->input->get('format', true) === 'pdf') {
            $html = $this->load->view('billing/invoice_pdf', $data, true);
            $meta = $record['invoice'];
            $title = [
                'report_title' => 'Invoice',
                'extradata' => '<table width="100%" style="font-size:12px;">'
                    . '<tr>'
                    . '<td><strong>Invoice No:</strong> ' . html_escape($meta['invoice_no']) . '</td>'
                    . '<td><strong>Date:</strong> ' . date('d-m-Y H:i', strtotime($meta['invoice_date'])) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td><strong>Type:</strong> ' . html_escape($meta['context']) . '</td>'
                    . '<td><strong>' . ($meta['context'] === 'OPD' ? 'OPD No' : 'IPD No') . ':</strong> ' . html_escape($meta['ref_no']) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td><strong>Status:</strong> ' . html_escape($meta['status']) . '</td>'
                    . '</tr>'
                    . '</table>'
            ];
            $filename = 'invoice_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $meta['invoice_no']);
            generate_pdf($html, 'P', $title, $filename, true, true, 'I');
            return;
        }

        $this->layout->title = 'Invoice ' . $record['invoice']['invoice_no'];
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = 'Invoice ' . $record['invoice']['invoice_no'];
        $this->layout->data = $data;
        $this->layout->render(['view' => 'billing/invoice']);
    }

    public function record_payment()
    {
        $this->ensure_authenticated();
        $payload = $this->input->post(null, true);
        $this->form_validation->set_data($payload);
        $this->form_validation->set_rules('invoice_id', 'Invoice', 'trim|required|integer|greater_than[0]');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('mode', 'Mode', 'trim|required|in_list[CASH,CARD,UPI,NEFT,OTHER]');
        $this->form_validation->set_rules('reference_no', 'Reference', 'trim|max_length[64]');
        $this->form_validation->set_rules('notes', 'Notes', 'trim|max_length[255]');

        if (!$this->form_validation->run()) {
            $this->output_json([
                'ok' => false,
                'msg' => validation_errors(' ', ' '),
            ], 400);
            return;
        }

        try {
            $this->billing->record_payment(
                (int) $payload['invoice_id'],
                (float) $payload['amount'],
                strtoupper($payload['mode']),
                $payload['reference_no'] ?? null,
                $payload['notes'] ?? null
            );
            $this->output_json(['ok' => true]);
        } catch (Throwable $th) {
            log_message('error', 'Record payment failed: ' . $th->getMessage());
            $this->output_json([
                'ok' => false,
                'msg' => $th->getMessage(),
            ], 400);
        }
    }

    public function list_invoices()
    {
        $this->ensure_authenticated();
        $contextParam = $this->input->get('context', true);
        $statusParam = $this->input->get('status', true);

        $filters = [
            'context' => is_string($contextParam) && $contextParam !== '' ? strtoupper(trim($contextParam)) : '',
            'ref_no' => $this->input->get('ref_no', true),
            'status' => is_string($statusParam) && $statusParam !== '' ? strtoupper(trim($statusParam)) : '',
            'date_from' => $this->input->get('date_from', true),
            'date_to' => $this->input->get('date_to', true),
        ];

        if (!in_array($filters['context'], ['OPD', 'IPD'], true)) {
            $filters['context'] = '';
        }
        if (!in_array($filters['status'], ['UNPAID', 'PARTIAL', 'PAID'], true)) {
            $filters['status'] = '';
        }
        $filters['ref_no'] = is_numeric($filters['ref_no']) ? (int) $filters['ref_no'] : '';

        $per_page = (int) $this->input->get('per_page', true);
        if ($per_page <= 0) {
            $per_page = 20;
        } elseif ($per_page > 100) {
            $per_page = 100;
        }

        $page = (int) $this->input->get('page', true);
        $page = $page > 0 ? $page : 1;
        $offset = ($page - 1) * $per_page;

        $listing = $this->billing->list_invoices($filters, $per_page, $offset);
        $total = (int) $listing['total'];
        $total_pages = $per_page > 0 ? (int) ceil($total / $per_page) : 1;

        $data = [
            'rows' => $listing['rows'],
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $per_page,
                'total_records' => $total,
                'total_pages' => $total_pages,
            ],
            'query_string' => http_build_query(array_filter([
                'context' => $filters['context'],
                'status' => $filters['status'],
                'ref_no' => $filters['ref_no'],
                'date_from' => $filters['date_from'],
                'date_to' => $filters['date_to'],
                'per_page' => $per_page,
            ], function ($value) {
                return $value !== '' && $value !== null;
            })),
        ];

        $this->layout->title = 'Invoices';
        $this->layout->navTitleFlag = true;
        //$this->layout->navTitle = 'Invoices';
        $this->layout->data = $data;
        $this->layout->render(['view' => 'billing/list']);
    }

    private function ensure_authenticated()
    {
        if (!$this->rbac->is_login()) {
            if ($this->input->is_ajax_request()) {
                $this->output_json([
                    'ok' => false,
                    'msg' => 'Authentication required.',
                ], 401);
            } else {
                redirect('login');
            }
            exit;
        }
    }

    private function output_json(array $payload, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
