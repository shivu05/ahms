<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Script_manage
 *
 * @author Himansu
 */
class Scripts_include {

    public $jsFile = array('common');
    public $cssFile = array('common');
    private $__jsFiles = array(
        'top' => array(
            '/assets/plugins/theme/js/jquery-3.2.1.min.js',
            '/assets/plugins/theme/js/popper.min.js',
            '/assets/plugins/theme/js/plugins/chart.js',
            '/assets/js/download.js',
            '/assets/js/common_jsfunctions.js',
        ),
        'common' => array(
            '/assets/plugins/theme/js/bootstrap.min.js',
            '/assets/plugins/theme/js/main.js',
            '/assets/plugins/theme/js/plugins/pace.min.js',
            '/assets/plugins/theme/js/plugins/bootstrap-datepicker.min.js',
            '/assets/plugins/theme/js/plugins/bootstrap-notify.min.js',
        ),
        'datatables' => array(
            '/assets/plugins/datatables/jquery.dataTables.min.js',
            '/assets/plugins/datatables/dataTables.bootstrap4.min.js'
        ),
    );
    private $__cssFiles = array(
        'common' => array(
            '/assets/plugins/theme/css/main.css',
        ),
        'datatables' => array(
            '/assets/plugins/datatables/dataTables.bootstrap4.min.css'
        ),
    );

    function __construct() {
        
    }

    public function includePlugins($plugins = null, $type = null) {
        if (is_array($plugins) && $type == 'css') {
            $this->cssFile = array_merge($this->cssFile, $plugins);
        } elseif (is_array($plugins) && $type == 'js') {
            $this->jsFile = array_merge($this->jsFile, $plugins);
        } else {
            $this->cssFile = array_merge($this->cssFile, $plugins);
            $this->jsFile = array_merge($this->jsFile, $plugins);
        }
    }

    public function includeCss() {
        $str = '';
        if (is_array($this->cssFile)) {
            foreach ($this->cssFile as $pluginName) {
                if (array_key_exists($pluginName, $this->__cssFiles)) {
                    foreach ($this->__cssFiles[$pluginName] as $files) {
                        $str.='<link rel="stylesheet" href="' . base_url($files) . '" />';
                    }
                }
            }
        }
        return $str;
    }

    public function includeJs() {
        $str = '';

        if (is_array($this->jsFile)) {
            foreach ($this->jsFile as $key => $pluginName) {
                if (array_key_exists($pluginName, $this->__jsFiles)) {
                    foreach ($this->__jsFiles[$pluginName] as $key => $files) {
                        if (is_array($files)) {
                            foreach ($files as $scripts) {
                                $str.=$scripts;
                            }
                        } else {
                            $str.='<script src="' . base_url($files) . '" ></script>';
                        }
                    }
                }
            }
        }
        return $str;
    }

    public function preJs() {
        $str = '';
        foreach ($this->__jsFiles['top'] as $files) {
            $str.='<script src="' . base_url($files) . '" ></script>';
        }
        return $str;
        ;
    }

}
