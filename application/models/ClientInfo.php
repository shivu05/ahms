<?php

class ClientInfo extends CI_Model {

    private $_tableName = '';
    private $_dbName = '';

    public function __construct() {
        parent::__construct();
        $this->_dbName = 'vhms_main';
        $this->_tableName = 'client_information';
    }

    public function fetch_config_years($key) {
        /*if (trim(strtolower(SHORT_NAME)) != 'ahms') {
            $this->db->where('client_short_name', SHORT_NAME);
        }*/
        return $this->db->get($this->_dbName . '.' . $this->_tableName)->result_array();
    }

}
