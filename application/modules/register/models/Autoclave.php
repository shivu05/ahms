<?php

/**
 * Description of Autoclave
 *
 * @author shiva
 */
class Autoclave extends SHV_Model {

    private $_table_name = 'autoclave_register';

    public function __construct() {
        parent::__construct($this->_table_name);
    }
}
