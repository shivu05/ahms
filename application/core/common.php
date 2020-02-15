<?php

/*
 * Declare all the common functions here
 */

if (!function_exists('restructure_string')) {

    function restructure_string($string = '') {
        $string = preg_replace('/\s+/', '_', $string);
        $string = trim(strtolower($string));
        return $string;
    }

}

if (!function_exists('get_short_gender')) {

    function get_short_gender($str = '') {
        if (trim($str) == 'Female') {
            return 'F';
        } else if (trim($str) == 'Male') {
            return 'M';
        } else {
            $str;
        }
    }

}

if (!function_exists('split_string')) {

    function split_string($longString = '', $maxLineLength = 18) {
        $arrayOutput = array();

        $arrayWords = explode(' ', $longString);

        $currentLength = 0;
        $index = 0;

        foreach ($arrayWords as $word) {
            // +1 because the word will receive back the space in the end that it loses in explode()
            $wordLength = strlen($word) + 1;

            if (($currentLength + $wordLength) <= $maxLineLength) {
                if (isset($arrayOutput[$index]))
                    $arrayOutput[$index] .= $word . ' ';

                $currentLength += $wordLength;
            } else {
                $index += 1;

                $currentLength = $wordLength;

                $arrayOutput[$index] = $word;
            }
        }
        return $arrayOutput;
    }

}


if (!function_exists('pma')) {

    /**
     * @method: pma
     * @param	string/array core function/user defined function/array of both type function
     * @return	formated value
     * @desc format as per the call back function passed
     */
    function pma($arr_val, $exit_flag = 0) {
        echo '<pre>';
        if (is_array($arr_val)) {
            print_r($arr_val);
        } else if (is_object($arr_val)) {
            var_dump($arr_val);
        } else {
            echo $arr_val;
        }
        echo '</pre>';
        if ($exit_flag) {
            exit('Exited here..');
        }
    }

}

if (!function_exists('in_array_r')) {

    function in_array_r($needle, $haystack) {
        $found = false;
        foreach ($haystack as $item) {
            if ($item === $needle) {
                $found = true;
                break;
            } elseif (is_array($item)) {
                $found = in_array_r($needle, $item);
                if ($found) {
                    break;
                }
            }
        }
        return $found;
    }

}

if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }

}

if (!function_exists('generate_table_pdf')) {

    function generate_table_pdf($headers, $data = null) {
        $table = '';
        if ($headers) {
            $table .= '<table width="100%" class="table table-bordered">';
            if (!empty($headers)) {
                $table .= '<thead>';
                $table .= '<tr>';
                foreach ($headers as $head) {
                    $table .= '<th>' . $head['name'] . '</th>';
                }
                $table .= '</tr>';
                $table .= '</thead>';
                $table .= '<tbody>';
                if (!empty($data)) {
                    foreach ($data as $row) {
                        $table .= '<tr>';
                        foreach ($headers as $k => $v) {
                            $align = (isset($v['align']) && $v['align'] == 'C') ? ' align="center"' : '';
                            $width = (isset($v['width'])) ? ' width="' . $v['width'] . '%"' : '';

                            $table .= '<td ' . $width . ' ' . $align . '>' . $row[$k] . '</th>';
                        }
                        $table .= '</tr>';
                    }
                } else {
                    $table .= '<tr><td>No data found</td></tr>';
                }
                $table .= '</tbody>';
            }
            $table .= '</table>';
        }
        return $table;
    }

}

if (!function_exists('patient_type')) {

    function patient_type($type = '') {
        if ($type = 'New Patient') {
            return 'New';
        } else {
            return 'Old';
        }
    }

}

if (!function_exists('format_date')) {

    function format_date($date = '') {
        if ($date && $date != '--') {
            return date("d-m-Y", strtotime($date));
        } else {
            return NULL;
        }
    }

}
if (!function_exists('display_department')) {

    function display_department($dept) {
        return ucfirst(str_replace('_', ' ', $dept));
    }

}

if (!function_exists('prepare_pancha_table')) {

    function prepare_pancha_table($data = NULL) {
        if (!empty($data)) {
            $arr = explode(',', $data['sub_grp_name']);
            $daysarr = explode(',', $data['tdays']);
            $table = "<table class='table table-bordered'>";
            if (count($arr) > 0) {
                $i = 0;
                foreach ($arr as $row) {
                    $table .= '<tr><td width="45%">' . $row . '</td>';
                    $table .= '<td width="40%">' . $daysarr[$i] . '</td>';
                    $table .= '<td><i class="fa fa-edit hand_cursor text-primary"></i> | <i class="fa fa-trash text-danger hand_cursor"></i></td></tr>';
                    $i++;
                }
            }
            $table .= "</table>";
            return $table;
        } else {
            return '';
        }
    }

}
if (!function_exists('create_dropdown_options_v2')) {

    /**
     * @param 1.array $data : whole data array [Mandatory]
     * @param 2.string $key_col : data array index name which will treated as options key [Mandatory]
     * @param 3.string $val_col : data array index name which will treated as options value text [Mandatory]
     * @param 4.boolean $option_tag_flag: determines retured data should be in option tag or array [defaul: TRUE]
     * @param 5.string $selected_val : the value which should display as selected option [defaul: blank]
     * @param 6.string $selected_by : determines selected logic baseed on key or value [defaul: value]
     * @param 7.string $attributes : adds extra attributes in options tag [defaul: blank]
     * @param 8.string $chosen_flag : will add one blank index for cosen dropdown [defaul: FALSE]
     * @param 9. boolean $title_flag : display title or not
     * @param 10. string $title_col: which db column you want to dispay as title
     * @param 11. boolean $sort_flag : determines sort will applay or not 
     * @param 12. string $sort_type: determine sorting type a/d [default: a]
     * @param 13: $data_attr = array(
      'data-enrich' => array(
      'OSA_MISSIONS_ENRICHED_EN' => array(
      'sdp_pre_html' => '<div>',
      'sdp_post_html' => '</div>',
      ),
      'OSA_ACTIVITY_ENRICHED_EN' => array(
      'sdp_pre_html' => '<div>',
      'sdp_post_html' => '</div>',
      )
      ),
      'data-nonenrich' => array('OSA_MISSIONS_NONENRICHED_EN', 'OSA_ACTIVITY_NONENRICHED_EN')
      );
     * @return 
     * @desc : used to return only options part of dropdown
     * @author
     */
    function create_dropdown_options_v2($data, $key_col, $val_col, $option_tag_flag = TRUE, $selected_val = '', $selected_by = 'v', $attributes = '', $title_flag = FALSE, $title_col = '', $sort_flag = FALSE, $sort_type = 'a') {
        $opt = "";

        if ($option_tag_flag) {
            $unique = array();
            $sort_arr = array();
            $opt_arr = array();
            foreach ($data as $key => $val) {
                $selected = '';
                if ($selected_by === 'k') {
                    if ($val[$key_col] == $selected_val) {
                        $selected = " selected='selected' ";
                    }
                } else {
                    if ($val[$val_col] == $selected_val) {
                        $selected = " selected='selected' ";
                    }
                }
                $ttle = '';
                if (!in_array($val[$key_col], $unique)) {//ignore duplicates
                    $sort_arr[$val[$key_col]] = $val[$val_col];

                    if ($title_flag) {
                        $title = str_replace("\n", "", $val[$title_col]);
                        $opt_arr[$val[$key_col]] = "<option $selected $attributes  title='$title' value='$val[$key_col]'>" . $val[$val_col] . "</option>";
                    } else {
                        $opt_arr[$val[$key_col]] = "<option $selected $attributes  value='$val[$key_col]'>" . $val[$val_col] . "</option>";
                    }
                }
                $unique[] = $val[$key_col];
            }
            if ($sort_flag && $sort_type != 'a') {
                arsort($sort_arr); //reverse sorting the array data
            } else if ($sort_flag) {
                asort($sort_arr); //sorting the array data
            }

            $opt = '';
            foreach ($sort_arr as $key => $val) {
                $opt .= $opt_arr[$key];
            }
        } else {
            foreach ($data as $key => $val) {
                $opt[$val[$key_col]] = $val[$val_col];
            }
            sort($opt);
        }
        return $opt;
    }

}