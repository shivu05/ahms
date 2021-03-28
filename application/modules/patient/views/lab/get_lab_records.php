<?php

if (!empty($records)) {
    $table = "<table class='table table-bordered'><thead><tr><th>Category</th><th>Test</th><th>Investigation</th><th>Test value</th><th>Test range</th></tr></thead>";
    $table .= "<tbody>";
    foreach ($records as $row) {
        $table .= "<tr>";
        $table .= "<td>" . $row['lab_cat_name'] . "</td>";
        $table .= "<td>" . $row['lab_test_name'] . "</td>";
        $table .= "<td>" . $row['lab_inv_name'] . "</td>";
        //$table .= "<td>" . $row['diagnosis'] . "</td>";
        $table .= '<td><input type="hidden" name="lab_id[]" id="lab_id" value="' . $row['lab_id'] . '"/><input class="form-control required" id="test_value" name="test_value[]" type="text" aria-describedby="test_dateHelp" placeholder="Enter Test value"></td>';
        $table .= '<td><input class="form-control" value="' . $row['lab_test_reference'] . '" id="test_range" name="test_range[]" type="text" aria-describedby="test_dateHelp" placeholder="Enter Test range"></td>';
        $table .= "</tr>";
    }
    $table .= '<tr><td style="text-align:right;"><b>Refferred date: </b></td><td><input class="form-control date_picker required" value="' . $row['testDate'] . '" id="test_refdate" name="test_refdate" type="text" aria-describedby="test_dateHelp" placeholder="Enter refferred date"></td><td style="text-align:right;"><b>Tested date: </b></td><td><input class="form-control date_picker required" id="test_date" name="test_date" type="text" aria-describedby="test_dateHelp" placeholder="Enter Lab date"></td></tr>';
    $table .= "</tbody>";
    $table .= "</table>";

    echo $table;
} else {
    echo 'No records found';
}