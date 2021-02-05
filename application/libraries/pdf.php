<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . 'third_party/TCPDF-main/tcpdf.php';
ini_set("memory_limit", -1);

class Pdf extends TCPDF {

    private $_Report_title = 'Report';
    public $template;
    private $_ci;

    public function __construct($title = NULL) {
        parent::__construct();
        $this->_Report_title = $title;
        $this->_ci = & get_instance();
    }

    function setData($template) {
        $this->template = $template;
    }

    function vcell($c_width, $c_height, $x_axis, $text, $char_lenght = 20, $allignment = 'L') {
        if ($char_lenght == NULL) {
            $char_lenght = 20;
        }
        $w_w = $c_height / 4;
        $w_w_1 = $w_w + 2;
        $w_w1 = $w_w + $w_w + $w_w + 3;
// $w_w2=$w_w+$w_w+$w_w+$w_w+3;// for 3 rows wrap
        $len = strlen($text); // check the length of the cell and splits the text into 7 character each and saves in a array 
        if ($len > $char_lenght) {
            //$w_text = preg_split("/\s+(?=\S*+$)/", $text);

            $w_text = str_split($text, $char_lenght); // splits the text into length of 7 and saves in a array since we need wrap cell of two cell we took $w_text[0], $w_text[1] alone.
// if we need wrap cell of 3 row then we can go for    $w_text[0],$w_text[1],$w_text[2]
            $this->SetX($x_axis);
            $this->Cell($c_width, $w_w_1, $w_text[0], '', '', '');
            $this->SetX($x_axis);
            $this->Cell($c_width, $w_w1, $w_text[1], '', '', '');
//$this->SetX($x_axis);
// $this->Cell($c_width,$w_w2,$w_text[2],'','','');// for 3 rows wrap but increase the $c_height it is very important.
            $this->SetX($x_axis);
            $this->Cell($c_width, $c_height, '', 'LTRB', 0, 'L', 0);
        } else {
            $this->SetX($x_axis);
            $this->Cell($c_width, $c_height, $text, 'LTRB', 0, $allignment, 0);
        }
    }

    //  Page header
    public function Header() {

        $image_file = FCPATH . '/assets/your_logo.png';
        //$this->Image($image_file, 0, 0, 90, 90, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Image($image_file, 10, 5, 20, 20, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont(PDF_FONT, 'B', 16);
        //$this->SetTextColorArray(array(252, 124, 39));
        // Title
        $college_name = $this->_ci->db->select('college_name')->from('config')->get()->row()->college_name;
        $this->Cell(0, 20, @$college_name, 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColorArray(array(252, 124, 39));
        $this->Cell(0, 4, $this->template['report_title'], 0, 1, 'C');
        //$this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height)
        $this->Line(5, 30, $this->w - 9, 30);

        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont(PDF_FONT, 'B', 8);
        //$this->SetTextColorArray(array(252, 124, 39));
        //$this->SetLineStyle(array('width' => (-1 / $this->k), 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(25, 124, 39)));
        $this->Cell(10, 0, 'Exported on: ' . date('d-m-Y') . '', 'T', 0, 'L');

        // Page number
        $this->Cell(0, 0, 'Copyright © ' . date('Y') . ' by Ayush Softwares', 'T', 0, 'C');
        $this->Cell(0, 0, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 'T', 0, 'R');
        $this->SetTextColorArray(array(0, 0, 0));
        $this->Cell(0, 0, '', 'T', 0, 'R');
    }

}

/* End of class tcpdf*/