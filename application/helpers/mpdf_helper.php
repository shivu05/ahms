<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once './vendor/autoload.php';

function generate_pdf($html, $page_orientation = 'L') {
    $mpdf = new \Mpdf\Mpdf([
        'orientation' => 'L'
    ]);
    $css = '<style type="text/css">' . file_get_contents(FCPATH . '/assets/css/print_table.css') . '</style>';
    $mpdf->WriteHTML($css . $html);
    $mpdf->Output();
}
