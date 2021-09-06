<?php

/**
 * Description of Custom_db
 *
 * @author Abhilasha
 */
class Custom_db extends CI_Model {

    /**
     * Function to get database connection 
     * @param: dbname
     * @returns: database object
     */
    public function getdatabase($db_name) {
        $config = array(
            'dsn' => '',
            'hostname' => DB_HOST,
            'username' => DB_USER,
            'password' => DB_PASS,
            'database' => $db_name,
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );
        $cust_db = $this->load->database($config, TRUE);
        return $cust_db;
    }

}
