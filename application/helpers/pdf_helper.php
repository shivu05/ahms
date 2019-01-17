<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description	:	Helper to create PDF from Html 

 * Created		:	Jan 12th, 2016

 * Author		:	 Shivaraj Badiger

 * Modification History:
 *   Date                Modified By                         Description
 */
/*
 * function: to create pdf file from HTML content
 * params: Html content, file name, page orientation
 * return: produces pdf file from passed HTML content
 */
function pdf_create($title = array(), $content = '', $filename = 'ahms_report', $time = "", $pstyle = NULL) {
    $CI = & get_instance();
    $config = $CI->db->get('config');
    $config = $config->row_array();
    $orientation = (empty($pstyle)) ? $config["printing_style"] : $pstyle;
    //Html page design
    $header = '<div class="row"><div class="col first">'
            . '<img src="' . base_url('assets/your_logo.png') . '" width="60" height="60" alt="logo">
                    </div>
                    <div class="col half">
                    <h3 align="center">' . $config["college_name"] . '</h3>
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
        $top_heading .= '<table width="100%" style="border: none;">';
        $top_heading .= '<tr>';
        $top_heading .= '<td width="33%"><b>DEPARTMENT</b>:' . $title['department'] . '</td><td width="33%" text-align:"center"; align="center"><h2>' . $title['report_title'] . '</h2></td><td width="33%" style="text-align:center;">
            <b>FROM:</b>' . $title['start_date'] .
                '&nbsp; <b>TO: </b>' . $title['end_date'] . '</td>';
        $top_heading .= '</tr>';
        $top_heading .= '</table>';
    }

    //Main content
    $html = "<body><div id='content'>" . $content . "</div></body>";

    //$mpdf = new \mPDF('utf-8', '', '', 0, 15, 15, 30, 16, 9, 10, 9, 11, 'A4');
    $mpdf = new \mPDF('', 'A4', 12, '', 15, 15, 30, 16);
    //$mpdf->debug = 'true';
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->SetWatermarkImage('./assets/your_logo.png', 0.1);
    $mpdf->showWatermarkImage = true;
    $stylesheet = file_get_contents('assets/css/print_table.css');
    if (strtoupper($orientation) == 'L') {
        $mpdf->AddPage('L');
    }
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->SetHTMLHeader($header, 'O', true);

    $mpdf->SetHTMLFooter($footer, 'O', true);
    $mpdf->use_kwt = true;
    $mpdf->shrink_tables_to_fit = 1;
    $mpdf->useSubstitutions = false;
    $mpdf->simpleTables = true;
    $mpdf->WriteHTML($top_heading, 2);
    $mpdf->WriteHTML($html, 2);
    $mpdf->Output();
    return;
}
