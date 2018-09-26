<?php

/**
 * NOTE:-
 * 1. Declare only the constants for global configuration
 * 2. Define all the constants in all env cases.
 */
defined('APP_ENV') OR define('APP_ENV', 'dev'); //dev/uat/prod
switch (APP_ENV) {
    case 'prod':
        /*         * **********************************************
         * 
         *  SET ALL THE PROD ENVIRONMENT RELATED CONSTANTS
         *
         * *********************************************** */
        break;
    case 'uat':
        /*         * **********************************************
         * 
         *  SET ALL THE UAT ENVIRONMENT RELATED CONSTANTS
         *
         * *********************************************** */
        break;
    default:

        /*         * **********************************************
         * 
         *  SET ALL THE DEV ENVIRONMENT RELATED CONSTANTS
         *
         * *********************************************** */

        defined('SITE_IS_DOWN') OR define('SITE_IS_DOWN', '0');
        defined('SITE_DOWN_MSG') OR define('SITE_DOWN_MSG', 'SITEDOWN MESSAGE HERE...');
        defined('GLOBAL_NOTICE') OR define('GLOBAL_NOTICE', '');
        defined('APP_HOST') OR define('APP_HOST', $_SERVER['HTTP_HOST']);
        defined('APP_DOC_ROOT') OR define('APP_DOC_ROOT', 'ahms');
        defined('APP_REQEST_TYPE') OR define('APP_REQEST_TYPE', $_SERVER['REQUEST_SCHEME']); //http/https
        defined('APP_BASE') OR define('APP_BASE', APP_REQEST_TYPE . '://' . APP_HOST . '/' . APP_DOC_ROOT . '/');
        defined('DB_HOST') OR define('DB_HOST', 'localhost');
        defined('DB_NAME') OR define('DB_NAME', 'ahms_samcy_2018');
        defined('DB_USER') OR define('DB_USER', 'root');
        defined('DB_PASS') OR define('DB_PASS', '');
        defined('ENC_KEY') OR define('ENC_KEY', 'cinaacabcdef123ef4578z1k2l3i4p5o67q890n98b');
        /*         * ***** DEFAULT DEBUG ******* */
        defined('DEBUG') OR define('DEBUG', '1'); //array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4);
        /*         * ***** CUSTOM DEBUG ******* */
        defined('C_DEBUG') OR define('C_DEBUG', '1');
        defined('DEBUG_SCRIPT') OR define('DEBUG_SCRIPT', '0');
        defined('CUSTOM_APP_LOG') OR define('CUSTOM_APP_LOG', 'APP');
        defined('DB_QUERIES_LOG') OR define('DB_QUERIES_LOG', '1');
        defined('APP_AUTH_FLAG') OR define('APP_AUTH_FLAG', '1');
}
