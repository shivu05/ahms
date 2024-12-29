<?php

class BillingModel extends CI_Model {

    public function add_opd_billing($data) {
        $this->db->insert('opd_billing', $data);
    }

    public function add_ipd_billing($data) {
        $this->db->insert('ipd_billing', $data);
    }
}
