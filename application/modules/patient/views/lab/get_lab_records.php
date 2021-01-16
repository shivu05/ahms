<?php

echo '<h4>OPD: ' . $opd.'</h4><hr>';
if (!empty($records)) {
    $table = "<table class='table table-bordered'><thead><tr><th>Category</th><th>Test</th><th>Investigation</th><th>Test value</th><th>Test range</th></tr></thead>";
    $table .= "<tbody>";
    foreach ($records as $row) {
        $table .= "<tr>";
        $table .= "<td>" . $row['lab_cat_name'] . "</td>";
        $table .= "<td>" . $row['lab_test_name'] . "</td>";
        $table .= "<td>" . $row['lab_inv_name'] . "</td>";
        $table .= '<td><input class="form-control" id="test_value" name="test_value" type="text" aria-describedby="test_dateHelp" placeholder="Enter Test value"></td>';
        $table .= '<td><input class="form-control" id="test_range" name="test_range" type="text" aria-describedby="test_dateHelp" placeholder="Enter Test range"></td>';
        $table .= "</tr>";
    }
    $table .= '<tr><td colspan="4" style="text-align:right;"><b>Tested date: </b></td><td><input class="form-control date_picker" id="test_date" name="test_date" type="text" aria-describedby="test_dateHelp" placeholder="Enter Lab date"></td></tr>';
    $table .= "</tbody>";
    $table .= "</table>";

    echo $table;
} else {
    echo 'No records found';
}