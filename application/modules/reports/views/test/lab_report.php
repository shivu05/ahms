<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed dataTable" width="100%">
        <thead>
            <tr>
                <th width="5%">Sl no.</th>
                <th width="5%">C.OPD.</th>
                <th width="5%">D.OPD</th>
                <th width="15%">Name</th>
                <th width="5%">Age</th>
                <th width="5%">Gender</th>
                <th width="10%">Doctor</th>
                <th width="15%">Disease</th>
                <th width="8%">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                echo "<tr class='warning'>";
                echo "<td style='text-align:center;'>" . $count . "</td>";
                echo "<td style='text-align:center;'>" . $row->OpdNo . "</td>";
                echo "<td style='text-align:center;'>" . $row->deptOpdNo . "</td>";
                echo "<td>" . $row->name . "</td>";
                echo "<td style='text-align:left;'>" . $row->Age . "</td>";
                echo "<td style='text-align:left;'>" . $row->gender . "</td>";
                echo "<td>" . $row->refDocName . "</td>";
                echo "<td>" . $row->labdisease . "</td>";
                echo "<td style='text-align:left;'>" . $row->testDate . "</td>";
                // $testname = array_from_delimeted_string($row->testName, ",", false);
                // $lab_test_type = array_from_delimeted_string($row->lab_test_type, ",", false);
                // $lab_cat_name = array_from_delimeted_string($row->lab_test_cat, ",", false);
                // $testrange = array_from_delimeted_string($row->testrange, ";", false);
                // $testvalue = array_from_delimeted_string($row->testvalue, ";", false);
                $testname = explode(",", $row->testName);
                $lab_test_type = explode(",", $row->lab_test_type);
                $lab_cat_name = explode(",", $row->lab_test_cat);
                $testrange = explode("#", $row->testrange);
                $testvalue = explode("#", $row->testvalue);
                echo "</tr>";
//                echo $row->OpdNo;
//                pma($row->lab_test_cat);
//                pma($row->testvalue);
                ?>
                <tr>
                    <td></td>
                    <td colspan='7'>
                        <table class="table table-bordered table-condensed" width="100%" style="margin:auto;">
                            <tr>
                                <td colspan='6' align='left'><b>Test Details:</b></caption></td>
                            </tr>
                            <tr class="info" style="color:black">
                                <th>Category</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Range</th>
                            </tr>
                            <?php
                            $i = 0;
                            foreach ($testvalue as $val) {
                                $tr = '<tr>';
                                $tr .= '<td>' . $lab_cat_name[$i] . '</td>';
                                $tr .= '<td>' . $lab_test_type[$i] . '</td>';
                                $tr .= '<td>' . $testname[$i] . '</td>';
                                $tr .= '<td>' . $val . '</td>';
                                $tr .= '<td>' . $testrange[$i] . '</td>';
                                $i++;
                                echo $tr;
                            }
                            ?>
                            <!-- <tr>
                                <td><?= $lab_cat_name ?></td>
                                <td><?= $lab_test_type ?></td>
                                <td><?= $testname ?></td>
                                <td><?= $testvalue ?></td>
                                <td><?= $testrange ?></td>
                            </tr> -->
                        </table>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
