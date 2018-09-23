<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('to_excel')) {

    /**
     * $data should be associative array
     */
    function to_excel($data, $filename, $fields, $worksheet_name, $freeze_column, $report_meta = null, $filter_data = null, $rows = null, $comment_data = null, $path = null, $ajax_download = null, $heading_wrap = FALSE, $heading_height = FALSE) {

        require_once APPPATH . "/third_party/PHPExcel.php";
        // Create the object
        $excel = new PHPExcel();
        // Splitting the data to get the rows
        $rowval = $data;
        // Getting the count of rows
        $rowcount = count($data);
        // Getting the field/ column count
        $colcount = count($fields);

        // Write the heading value to the xl sheet start
        $xcol = '';

        if (isset($filter_data)) {
            $excel->createSheet();
            $excel->setActiveSheetIndex(1);

            $col = 0;
            foreach ($filter_data as $key => $val) {
                //print_r($key);exit;
                $row1 = 1;
                foreach ($val as $key1 => $val1) {
                    //print_r($key1);exit;
                    $excel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row1, $val1);
                    $row1++;
                }

                $col++;
            }
            $excel->getActiveSheet()->setTitle('hide');
            $excel->getSheetByName('hide')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
            $excel->getActiveSheet()->freezePane($freeze_column);
        }

        for ($col = 0; $col < $colcount; $col++) {
            if ($xcol == '') {
                $xcol = 'A';
            } else {
                $xcol++;
            }
            $excel->setActiveSheetIndex(0);
            $excel->getActiveSheet()->getColumnDimension($xcol)->setWidth(25);
            $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(45);
            $excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, strip_tags($fields[$col]));
        }

        $cellRange = "A1:" . $xcol . '1';
        $style_overlay = array('font' => array('color' => array('rgb' => '000000'), 'bold' => false),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCFF')),
            'alignment' => array('wrap' => true, 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP),
            'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        $excel->getActiveSheet()->getStyle($cellRange)->applyFromArray($style_overlay); // applying style for the heading
        $excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode('000000000');

        $excel->getActiveSheet()->setAutoFilter($cellRange);

        if (isset($filter_data)) {
            $ref_index = 'A';
            foreach ($filter_data as $key => $val) {
                $row_index = "$" . count($val);
                if (isset($rows)) {
                    $row_count = $rows;
                } else {
                    $row_count = 50;
                }
                for ($i = 2; $i <= $row_count; $i++) {
                    $objValidation2 = $excel->getActiveSheet()->getCell($key . $i)->getDataValidation();
                    $objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $objValidation2->setAllowBlank(false);
                    $objValidation2->setShowInputMessage(true);
                    $objValidation2->setShowErrorMessage(true);
                    $objValidation2->setShowDropDown(true);
                    $objValidation2->setPromptTitle('Pick from list');
                    $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                    $objValidation2->setErrorTitle('Input error');
                    $objValidation2->setError('Value is not in list');
                    $objValidation2->setFormula1("hide!$ref_index$1:$ref_index$row_index");
                }
                $ref_index++;
            }
        }

        // Field names in the first row
        $col = 0;
        $col_letter = 'A';
        foreach ($fields as $field) {
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            if ($comment_data != '') {
                if (array_key_exists($col_letter, $comment_data) && !empty($comment_data)) {
                    $objCommentRichText = $excel->getActiveSheet()->getComment($col_letter . "1")->getText()->createTextRun($comment_data[$col_letter]);
                    $objCommentRichText->getFont()->setBold(true);
                }
            }
            $col++;
            $col_letter++;
        }

        $row = 2;

        $db_columns = array_keys($data[0]);
        foreach ($data as $data_val) {

            $col = 0;
            foreach ($db_columns as $field) {
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, trim($data_val[$field]));
                $col++;
            }

            $row++;
        }

        if (isset($report_meta)) {
            if (is_array($report_meta)) {
                $incr = 0;
                foreach ($report_meta as $filter => $filter_val) {
                    if ($filter_val != '') {
                        $excel->getActiveSheet()->insertNewRowBefore(++$incr, 1);
                        if ($filter_val == 'heading') {
                            $excel->getActiveSheet()->mergeCells('A' . $incr . ':D' . $incr);
                            $excel->getActiveSheet()->getCell('A' . $incr)->setValue($filter);
                            $excel->getActiveSheet()->getStyle('A' . $incr)->getFont()->setBold(true);
                            if ($heading_height) {
                                $excel->getActiveSheet()->getRowDimension($incr)->setRowHeight($heading_height);
                            }
                            if ($heading_wrap) {
                                $excel->getActiveSheet()->getStyle('A' . $incr . ':D' . $incr)->getAlignment()->setWrapText(true);
                            }
                        } else {
                            $excel->getActiveSheet()->mergeCells('B' . $incr . ':D' . $incr);
                            $excel->getActiveSheet()->getCell('A' . $incr)->setValue($filter);
                            $excel->getActiveSheet()->getCell('B' . $incr)->setValue($filter_val);
                        }
                    }
                }
            }
        }
        $excel->getActiveSheet()->setTitle($worksheet_name);
        $excel->getActiveSheet()->freezePane($freeze_column);
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        ob_end_clean();

        if (isset($path)) {
            //$objWriter->save('./uploads/invalid_import_record/' . $filename);
            $objWriter->save($path . $filename);
            //exit();
        } else if ($ajax_download) {
            ob_start();
            $objWriter->save("php://output");
            $xlsData = ob_get_contents();
            //app_log(('CUSTOM','APP', print_r($xlsData,true));
            ob_end_clean();
            $response = array(
                'op' => 'ok',
                'file' => "data:application/octet-stream;base64," . base64_encode($xlsData),
                'file_name' => $filename
            );

            echo json_encode($response);
            exit();
        } else {
            header('Pragma: ');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        }
    }

}

/**
 * @param:array $data
 * @param:string $filename
 * @param:array $headers =array('database_column_name'=>'xls column name','sdp_user_cuid'=>CUID);
 * @param:string $worksheet_name  EX: SKILLS REPORT
 * @param:string $freeze_column EX: C1
 * @param array $report_meta ex:array('date'=>date('D-M-Y'))
 * @param boolean $ajax_download will be used to download the file using ajax
 * @return NA
 * @desc: used to download the data in xls format using AJAX
 * @author:himansu 14th June 2017
 */
if (!function_exists('download_to_excel')) {

    function download_to_excel($data, $filename, $headers, $worksheet_name, $freeze_column, $report_meta = null, $ajax_download = false, $heading_wrap = FALSE, $heading_height = FALSE, $audit_track = TRUE, $append_extra_content = FALSE, $extra_content = array(), $head_title = NULL) {
        //app_log('CUSTOM','APP','toexcel start ='.(date('H:i:s')));
        $CI = get_instance();
        require_once APPPATH . "/third_party/PHPExcel.php";
        // Create the object
        $excel = new PHPExcel();
        $xrow = 1;
        if ($head_title) {
            $excel->getActiveSheet()->SetCellValue('A' . 1, $head_title);
            $styleArray = array(
                'font' => array(
                    'bold' => true
                )
            );
            $excel->getActiveSheet()->getStyle('A' . 1)->applyFromArray($styleArray);
            $xrow = 2;
        }
        if (isset($report_meta) && is_array($report_meta)) {
            $incr = 1;
            foreach ($report_meta as $filter => $filter_val) {
                if ($filter_val) {
                    if ($filter_val == 'heading') {
                        $excel->getActiveSheet()->mergeCells('A' . $incr . ':D' . $incr);
                        $excel->getActiveSheet()->getCell('A' . $incr)->setValue($filter);
                        $excel->getActiveSheet()->getStyle('A' . $incr)->getFont()->setBold(true);
                        if (isset($heading_height)) {
                            $excel->getActiveSheet()->getRowDimension($incr)->setRowHeight($heading_height);
                        }
                        if (isset($heading_wrap)) {
                            $excel->getActiveSheet()->getStyle('A' . $incr . ':D' . $incr)->getAlignment()->setWrapText(true);
                        }
                    } else {
                        $excel->getActiveSheet()->mergeCells('B' . $incr . ':D' . $incr);
                        $excel->getActiveSheet()->getCell('A' . $incr)->setValue($filter);
                        $excel->getActiveSheet()->getStyle("A$incr:A$incr")->getFont()->setBold(true);
                        ;
                        $excel->getActiveSheet()->getCell('B' . $incr)->setValue($filter_val);
                    }
                    $incr++;
                }
            }
            $xrow = ($incr > 0) ? $incr : 1;
        }
        $col = 0;
        //setting xls file header
        // Write the heading value to the xls sheet start
        $xcol = '';
        foreach ($headers as $db_col => $header) {
            if ($xcol == '') {
                $xcol = 'A';
            } else {
                $xcol++;
            }
            $excel->setActiveSheetIndex(0);
            $excel->getActiveSheet()->getColumnDimension($xcol)->setWidth(25);
            //$excel->getActiveSheet()->getRowDimension($xrow)->setRowHeight(45);
            $excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            if (is_array($header)) {
                if (isset($header['heading'])) {
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $xrow, strip_tags($header['heading']));
                    $col++;
                } else {
                    /* updated:himansu-2ndAug18
                     * Scenario: when mapped db column is same but heading is different
                     * Ref Page: analytical report raw data export controller analytical_reports/export_raw_data()
                     */
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $xrow, strip_tags($db_col));
                    $col++;
                }
            } else {
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $xrow, strip_tags($header));
                $col++;
            }
        }

        //set xls column style
        $cellRange = "A$xrow:" . $xcol . $xrow;
        $style_overlay = array(
            'font' => array('color' => array('rgb' => '000000'), 'bold' => false),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCFF')),
            'alignment' => array(
                'wrap' => true, 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
            ),
            'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        $excel->getActiveSheet()->getStyle($cellRange)->applyFromArray($style_overlay); // applying style for the heading
        $excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode('00');
        //$excel->getActiveSheet()->setAutoFilter($cellRange);
        //set freezing column
        if (isset($freeze_column)) {
            //ex:$freeze_column='C1';
            $excel->getActiveSheet()->setTitle('hide');
            $excel->getSheetByName('hide')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
            $excel->getActiveSheet()->freezePane($freeze_column);
        }

        $xrow++; //increase row no after header created.
        //fill the xls file cell value
        foreach ($data as $data_val) {
            $col = 0;
            foreach ($headers as $db_col => $field) {
                if (is_array($field)) {
                    $column_value = '';
                    foreach ($field as $key_col => $key_val) {
                        if (array_key_exists($key_col, $data_val) && $key_val != 'heading') {
                            $column_value.=trim($data_val[$key_col]) . $key_val; //here $key_val can be treated as separator
                        } else {
                            /* updated:himansu-2ndAug18
                             * Scenario: when mapped db column is same but heading is different
                             * Ref Page: analytical report raw data export controller analytical_reports/export_raw_data()
                             */
                            //app_log('CUSTOM','APP', $key_col);
                            if ($key_col == 'db_column' && array_key_exists($key_val, $data_val)) {
                                $column_value = $data_val[$key_val];
                            }
                        }
                    }
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $xrow, $column_value);
                } else {
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $xrow, trim($data_val[$db_col]));
                }
                $col++;
            }
            $xrow++;
        }

        if ($append_extra_content) {
            if (!empty($extra_content['content'])) {
                $row = $excel->getActiveSheet()->getHighestRow() + 1;
                $row = $row + 2;
                $excel->getActiveSheet()->SetCellValue('A' . $row, $extra_content['title']);
                $styleArray = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $excel->getActiveSheet()->getStyle('A' . $row)->applyFromArray($styleArray);
                $row++;
                $excel->getActiveSheet()->SetCellValue('A' . $row, $extra_content['row_no']);
                $excel->getActiveSheet()->getStyle('A' . $row)->applyFromArray($style_overlay);
                foreach ($extra_content['content'] as $cont) {
                    $row++;
                    $excel->getActiveSheet()->SetCellValue('A' . $row, $cont['id']);
                    $excel->getActiveSheet()->SetCellValue('B' . $row, $cont['tbody']);
                }
            }
        }

        if (isset($worksheet_name)) {
            $excel->getActiveSheet()->setTitle($worksheet_name);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();

        if ($ajax_download) {
            ob_start();
            $objWriter->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();
            $response = array(
                'op' => 'ok',
                'file' => "data:application/octet-stream;base64," . base64_encode($xlsData),
                'file_name' => $filename
            );
            //pma($response,1);
            echo json_encode($response);
            //app_log('CUSTOM','APP','toexcel end ='.(date('H:i:s')));            
            exit();
        } else {
            //$objWriter->setPreCalculateFormulas(false);
            //full path with file name
            //return $objWriter->save($filename);
            header('Pragma: ');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        }
    }

}

if (!function_exists('download_to_csv')) {

    function download_to_csv($data, $filename, $headers, $ajax_download = null) {

        if ($ajax_download) {
            ob_start();
            $objWriter->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();
            $response = array(
                'op' => 'ok',
                'file' => "data:application/octet-stream;base64," . base64_encode($xlsData),
                'file_name' => $filename
            );
            echo json_encode($response);
            //app_log('CUSTOM','APP','toexcel end ='.(date('H:i:s')));            
            exit();
        } else {
            $objWriter->setPreCalculateFormulas(false);
            //full path with file name
            return $objWriter->save($filename);
        }
    }

}
?>