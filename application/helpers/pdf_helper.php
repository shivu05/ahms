<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function generate_pdf($content = "", $dtype = 'I', $pdfOutputFilename = 'test_file.pdf', $contentLast = '') {
    
    $CI = & get_instance();
    $CI->load->library('pdf');
    $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
    
    // set document information
    $pdf->setPrintHeader(false);
    // set header and footer fonts
    $pdf->SetFont('Helvetica', '', 10);
// ---------------------------------------------------------
    // add a page
    // set some text to print
    // print a block of text using Write()
    $pdf->SetFooterMargin(1.5);
    $pdf->AddPage('L');
    $pdf->writeHTML($content, true, false, true, false, 'true');
// ---------------------------------------------------------

    $pdf->SetTitle($pdfOutputFilename);
    $pdf->lastPage();
    $pdf->writeHTML($contentLast, true, true, false, false, 'false');
    $pdf->writeHTMLCell($w = 0, $h = 0, 10, 180, $pdfOutputFilename, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
// Print text using writeHTMLCell()
    ob_end_clean();
   return $pdf->Output("$pdfOutputFilename", 'D');
}
