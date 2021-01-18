<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" width="100%">
        <thead>
            <tr>
                <th width="5%">Sl no.</th>
                <th width="6%">Central OPD.</th>
                <th width="6%">Dept. OPD.</th>
                <th width="20%">Patient Name</th>
                <th width="5%">Age</th>
                <th width="8%">Gender</th>
                <th width="10%">Doctor</th>
                <th width="15%">Disease</th>
                <th width="8%">Test Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                echo "<tr>";
                echo "<td style='text-align:center;'>" . $count . "</td>";
                echo "<td style='text-align:center;'>" . $row->OpdNo . "</td>";
                echo "<td style='text-align:center;'>" . $row->deptOpdNo . "</td>";
                echo "<td>" . $row->name  . "</td>";
                echo "<td style='text-align:center;'>" . $row->Age . "</td>";
                echo "<td style='text-align:center;'>" . $row->gender . "</td>";
                echo "<td>" . $row->refDocName . "</td>";
                echo "<td>" . $row->labdisease . "</td>";
                echo "<td style='text-align:center;'>" . $row->testDate . "</td>";
                $testname = array_from_delimeted_string($row->testName, ",", true);
                $lab_test_type = array_from_delimeted_string($row->lab_test_type, ",", true);
                $lab_cat_name = array_from_delimeted_string($row->lab_test_cat, ",", true);
                $testrange = array_from_delimeted_string($row->testrange, ",", true);
                $testvalue = array_from_delimeted_string($row->testvalue, ",", true);
                echo "</tr>";
                ?>
                <tr>
                    <td></td>
                    <td colspan='7'>
                        <table class="table table-bordered table-condensed" width="100%" style="margin:auto;">
                            <tr>
                                <td colspan='6' align='left'><b>Test Details:</b></caption></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Range</th>
                            </tr>
                            <tr> 
                                <td><?= $lab_cat_name ?></td>
                                <td><?= $lab_test_type ?></td>
                                <td><?= $testname ?></td>
                                <td><?= $testvalue ?></td>
                                <td><?= $testrange ?></td>
                            </tr>
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