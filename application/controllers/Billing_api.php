<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Billing_api extends SHV_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Billing_model', 'billing');
    }

    function index(){
        echo "API is working";
    }

    public function services_by_group()
    {
        $this->ensure_authenticated();
        $group_id = (int) $this->input->get_post('group_id', true);
        if ($group_id <= 0) {
            $this->output_json([
                'ok' => false,
                'msg' => 'Invalid group selected.',
            ], 400);
            return;
        }

        $services = $this->billing->get_services_by_group($group_id);
        $this->output_json([
            'ok' => true,
            'services' => $services,
        ]);
    }

    public function service_groups()
    {
        $this->ensure_authenticated();
        $groups = $this->billing->get_service_groups();
        $this->output_json([
            'ok' => true,
            'groups' => $groups,
        ]);
    }

    private function ensure_authenticated()
    {
        if (!$this->rbac->is_login()) {
            $this->output_json([
                'ok' => false,
                'msg' => 'Authentication required.',
            ], 401);
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
