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
function pdf_create($title = array(), $content = '', $filename = 'ahms_report', $pstyle = NULL, $download_type = 'I', $print_header = true) {
    $CI = & get_instance();
    $config = $CI->db->get('config')->row_array();
    $orientation = (empty($pstyle)) ? $config["printing_style"] : $pstyle;
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

    //Main content
    $html = '<style type="text/css">' . file_get_contents(FCPATH . '/assets/css/print_table.css') . '</style>';
    $html .= "<body><div id='content'>" . $top_heading . $content . "</div></body>";


    $CI->load->library('pdf');
    //ob_start();
    $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    // set document information
    $pdf->SetMargins(5, 32, 10, false);
    $pdf->setPrintHeader($print_header);
    $pdf->SetFont(PDF_FONT, '', 10);
    $pdf->AddPage($orientation);
    $pdf->SetMargins(5, 32, 10, true);
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->SetFooterMargin(1.5);

    ob_end_clean();
    $pdf->Output($filename . '.pdf', $download_type);
    return;
}
