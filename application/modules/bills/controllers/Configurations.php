<?php

/**
 * Description of Configurations
 *
 * @author shiva
 */
class Configurations extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->layout->render();
    }
}
