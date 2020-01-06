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
function pdf_create($title = array(), $content = '', $filename = 'ahms_report', $pstyle = NULL, $download_type = 'I', $water_marks = TRUE, $full_page_print = FALSE) {
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
        $department = (isset($title['department'])) ? '<b>DEPARTMENT</b>:' . $title['department'] . '' : '';
        $report_title = (isset($title['report_title'])) ? $title['report_title'] : '';
        $from_date = (isset($title['start_date'])) ? '<b>FROM:</b>' . $title['start_date'] : '';
        $to_date = (isset($title['end_date'])) ? '<b>TO: </b>' . $title['end_date'] : '';

        $top_heading .= '<table width="100%" style="border: none;">';
        $top_heading .= '<tr>';
        $top_heading .= '<td width="33%">' . $department . '</td><td width="33%" text-align:"center"; align="center"><h2>' . $report_title . '</h2></td><td width="33%" style="text-align:center;">'
                . $from_date .
                '&nbsp;
        ' . $to_date . '</td>';
        $top_heading .= '</tr>';
        $top_heading .= '</table>';
    }

    //Main content
    $html = "<body><div id='content'>" . $content . "</div></body>";

    //$mpdf = new \mPDF('utf-8', '', '', 0, 15, 15, 30, 16, 9, 10, 9, 11, 'A4');
    //mPDF('encode','page','font-size','font-style','m-left','m-right','m-top','m-bottom')
    $mpdf = NULL;
    if ($full_page_print) {
        $mpdf = new \mPDF('', 'A4', 8, '', 4, 4, 4, 4);
    } else {
        $mpdf = new \mPDF('', 'A4', 12, '', 15, 15, 30, 16);
    }

    //$mpdf->debug = 'true';
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->SetWatermarkImage('./assets/your_logo.png', 0.1);
    $mpdf->showWatermarkImage = $water_marks;
    $stylesheet = file_get_contents('assets/css/print_table.css');
    if (strtoupper($orientation) == 'L') {
        $mpdf->AddPage('L');
    }

    $mpdf->WriteHTML($stylesheet, 1);
    if (!$full_page_print) {
        $mpdf->SetHTMLHeader($header, 'O', true);
        $mpdf->SetHTMLFooter($footer, 'O', true);
    }
    $mpdf->use_kwt = true;
    $mpdf->shrink_tables_to_fit = 1;
    $mpdf->useSubstitutions = false;
    $mpdf->simpleTables = true;

    $mpdf->WriteHTML($top_heading, 2);
    $mpdf->WriteHTML($html, 2);
    //s$mpdf->writeBarcode('12345678');
    //pma($html,1);
    $mpdf->Output($filename . '.pdf', $download_type);
    return;
}
