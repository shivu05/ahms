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
            '/assets/js/download.js',
            '/assets/js/common_jsfunctions.js',
            '/assets/bower_components/jquery/dist/jquery.min.js',
            '/assets/bower_components/jquery-ui/jquery-ui.js',
            '/assets/bower_components/bootstrap-notify-master/bootstrap-notify.min.js',
            '/assets/bower_components/chart.js/Chart.js'
        ),
        'common' => array(
            '/assets/bower_components/bootstrap/dist/js/bootstrap.min.js',
            '/assets/bower_components/bootstrap/dist/js/bootstrap-dialog.js',
            '/assets/plugins/themes/admin/dist/js/adminlte.min.js',
            //'/assets/bower_components/bootstrap3-dialog/src/js/bootstrap-dialog.js',
            '/assets/bower_components/select2/dist/js/select2.full.min.js',
            '/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
            '/assets/js/download.js',
            '/assets/plugins/jquery-validation/dist/jquery.validate.min.js',
            '/assets/plugins/jquery-validation/dist/additional-methods.min.js',
            '/assets/plugins/jquery-validation/formValidation.js',
            '/assets/plugins/chosen/chosen.jquery.js',
        ),
        'datatables' => array(
            '/assets/plugins/datatables/jquery.dataTables.min.js',
            '/assets/plugins/datatables/dataTables.bootstrap4.min.js'
        ),
        'jq_validation' => array(
            '/assets/plugins/jq_validation/jquery.validate.js',
            '/assets/plugins/jq_validation/formValidation.js',
        ),
        'chosen' => array(
            '/assets/plugins/chosen/chosen.jquery.js',
        ),
        'typeahead' => array(
            '/assets/plugins/typeahead/typeahead.js',
        ),
        'chartjs' => array(
        )
    );
    private $__cssFiles = array(
        'common' => array(
            '/assets/bower_components/bootstrap/dist/css/bootstrap.min.css',
            '/assets/bower_components/bootstrap/dist/css/bootstrap-dialog.min.css',
            '/assets/bower_components/font-awesome/css/font-awesome.min.css',
            '/assets/bower_components/Ionicons/css/ionicons.min.css',
            '/assets/bower_components/select2/dist/css/select2.min.css',
            '/assets/plugins/themes/admin/dist/css/AdminLTE.min.css',
            '/assets/plugins/themes/admin/dist/css/skins/_all-skins.min.css',
            '/assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
            '/assets/plugins/jQueryUI/jquery-ui.css',
            '/assets/css/custom_styles.css',
            '/assets/plugins/chosen/bootstrap-chosen.css',
            '/assets/css/myApp.css',
        ),
        'datatables' => array(
            '/assets/plugins/datatables/dataTables.bootstrap4.min.css',
            '/assets/plugins/datatables/responsive.bootstrap.min.css',
            '/assets/plugins/datatables/fixedHeader.bootstrap.min.css'
        ),
        'chosen' => array(
            '/assets/plugins/chosen/bootstrap-chosen.css',
        )
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
                        $str .= '<link rel="stylesheet" href="' . base_url($files) . '" />';
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
                                $str .= $scripts;
                            }
                        } else {
                            $str .= '<script src="' . base_url($files) . '" ></script>';
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
            $str .= '<script src="' . base_url($files) . '" ></script>';
        }
        return $str;
        ;
    }

}
