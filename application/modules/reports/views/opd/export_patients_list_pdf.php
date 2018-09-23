<?php
if (!empty($patients)) {
    $table = '<table border="1">';
    $table .= '<tr><th>OPD</th><th>NAME</th><th>AGE</th><th>GENDER</th><th>CITY</th></tr>';
    foreach ($patients as $patient) {
        $table .='<tr>';
        $table .='<td>' . $patient['OpdNo'] . '</td>';
        $table .='<td>' . $patient['FirstName'] . ' ' . $patient['LastName'] . '</td>';
        $table .='<td>' . $patient['Age'] . '</td>';
        $table .='<td>' . $patient['gender'] . '</td>';
        $table .='<td>' . $patient['city'] . '</td>';
        $table .='</tr>';
    }
    $table .= '</table>';
    echo $table;
} else {
    echo 'No patients found';
}

