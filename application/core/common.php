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
    