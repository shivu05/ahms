<?php

//pma($duty_list);


function group_by($array, $key) {
    $return = array();

    foreach ($array as $val) {
        //$return[$val->$key][] = $val;
        //  ou para gettype($val) = array
        $return[$val[$key]][] = $val;
    }
    return $return;
}

if (!empty($duty_list)) {

    $doctos_list = group_by($duty_list, 'user_department');
    //pma($doctos_list);
    if (!empty($doctos_list)) {
        $table = '<table class="table table-bordered" width="100%">';
        foreach ($doctos_list as $key => $value) {
            $table .= '<tr><th colspan=4 style="text-align:left">' . $key . '</th></tr>';
            if (!empty($value)) {
                $table .= '<tr><th>Sl.No</th><th>Day</th><th>Timing</th><th>Doctor</th></tr>';
                foreach ($value as $row) {
                    $table .= '<tr><td style="text-align:center;">' . $row['day'] . '</td><td>' . $row['week_day'] . '</td><td> 9AM-5PM</td><td>' . $row['user_name'] . '</td></tr>';
                }
            }
        }
        $table .= '</table>';
    }
}//main if
echo $table;
