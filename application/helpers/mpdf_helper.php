<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once './vendor/autoload.php';

function generate_pdf($html, $page_orientation = 'L', $title = '', $filename = 'ahms_report', $header_flag = true, $footer_flag = true, $output = "D") {
    $CI = & get_instance();
    $config = $CI->db->get('config');
    $config = $config->row_array();
    $orientation = (empty($pstyle)) ? $config["printing_style"] : $pstyle;
    //Html page design
    $header = '<div class="row"><div class="col first">'
            . '<img src="' . base_url('assets/your_logo.png') . '" width="80" height="80" alt="logo">
                    </div>
                    <div class="col half">
                    <h2 align="center">' . $config["college_name"] . '</h2>
                    </div></div><hr>';
    $footer = '<table width="100%" style="color:#000000;border-top-style: solid;font-size:10px;">
    <tr>
        <td width="33%"></td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right;">©Ayush softwares </td>
    </tr>
</table>';

    $html = '<body>' . $html . '</body>';
    $mt = 2;
    if ($header_flag) {
        $mt = 38;
    }
    $configs = array(
        'mode' => 'utf-8',
        'orientation' => $page_orientation,
        'margin_top' => $mt
    );
    $mpdf = new \Mpdf\Mpdf($configs);
    $mpdf->SetTitle('VHMS');
    $mpdf->SetCreator('Ayush Softwares');
    $mpdf->SetDisplayMode('fullpage');
    $css = '<style type="text/css">' . file_get_contents(FCPATH . '/assets/css/print_table.css') . '</style>';
    if ($header_flag) {
        $mpdf->SetHTMLHeader($header);
    }
    if ($footer_flag) {
        $mpdf->SetHTMLFooter($footer);
    }
    $mpdf->use_kwt = true;
    $mpdf->shrink_tables_to_fit = 1;
    $mpdf->useSubstitutions = false;
    $mpdf->simpleTables = true;
    //$mpdf->SetWatermarkImage('./assets/your_logo.png');
//    $mpdf->SetWatermarkImage(
//            'http://www.yourdomain.com/images/logo.jpg',
//            1,
//            '',
//            array(160, 10)
//    );
//    $mpdf->showWatermarkImage = true;
    $mpdf->WriteHTML($css . $html);
    $mpdf->Output($filename . '.pdf', $output);
    exit;
}
