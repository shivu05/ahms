<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once './vendor/autoload.php';

function generate_pdf($html, $page_orientation = 'L', $title = NULL, $filename = 'ahms_report', $header_flag = true, $footer_flag = true, $output = "D") {
    $CI = & get_instance();
    ini_set("pcre.backtrack_limit", "10000000");
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

    $top_heading = "";
    if (!empty($title)) {
        $department = (isset($title['department'])) ? '<b>DEPARTMENT</b>:' . $title['department'] . '' : '';
        $report_title = (isset($title['report_title'])) ? $title['report_title'] : '';
        $from_date = (isset($title['start_date'])) ? '<b>FROM:</b>' . $title['start_date'] : '';
        $to_date = (isset($title['end_date'])) ? '<b>TO: </b>' . $title['end_date'] : '';

        $top_heading .= '<table width="100%" style="border: none;">';
        $top_heading .= '<tr>';
        $top_heading .= '<td width="33%">' . $department . '</td>
            <td width="33%" text-align:"center"; align="center"><h2>' . $report_title . '</h2></td>
                <td width="33%" style="text-align:center;">'
                . $from_date .
                '&nbsp;
      ' . $to_date . '</td>';
        $top_heading .= '</tr>';
        $top_heading .= '</table><br/><br/>';
        $top_heading .= @$title['extradata'];
    }


    $html = '<body>' . $top_heading . $html . '</body>';
    $mt = 2;
    if ($header_flag) {
        $mt = 38;
    }
    $configs = array(
        'mode' => 'utf-8',
        'orientation' => $page_orientation,
        'margin_top' => $mt
    );
    ob_clean();
    $mpdf = new \Mpdf\Mpdf($configs);
    $mpdf->SetTitle('VHMS');
    $mpdf->SetCreator('Ayush Softwares');
    $mpdf->SetDisplayMode('fullpage');

    $mpdf->SetCompression(true);
    $mpdf->table_error_report_param = true;
    //$css = '<style type="text/css">' . file_get_contents(FCPATH . '/assets/css/print_table.css') . '</style>';
    $css = file_get_contents(FCPATH . '/assets/css/print_table.css');
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
    $mpdf->packTableData = false;
    //$mpdf->SetWatermarkImage('./assets/your_logo.png');
    //$mpdf->SetWatermarkImage($src, $alpha, $size, $pos);
    //$mpdf->SetWatermarkText('VHMS',-2);
    //$mpdf->showWatermarkText = true;
    /* $mpdf->SetWatermarkImage(
      './assets/your_logo.png',
      -10,
      'F',
      array(70, 50)
      );
      $mpdf->showWatermarkImage = true; */
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
//    $chunks = explode("chunk", $html);
//    if (!empty($chunks)) {
//        foreach ($chunks as $key => $val) {
//            $mpdf->WriteHTML($val);
//        }
//    }
    foreach (array_chunk(explode('chunk', $html), 1000) as $lines) {
        $mpdf->WriteHTML(implode('chunk', $lines), \Mpdf\HTMLParserMode::HTML_BODY);
    }
    //$mpdf->WriteHTML($css . $html);
    $mpdf->Output($filename . '.pdf', $output);
    exit;
}
