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
                echo "<td>" . $row->FirstName . ' ' . $row->LastName . "</td>";
                echo "<td style='text-align:center;'>" . $row->Age . "</td>";
                echo "<td style='text-align:center;'>" . $row->gender . "</td>";
                echo "<td>" . $row->AddedBy . "</td>";
                echo "<td>" . $row->labdisease . "</td>";
                echo "<td style='text-align:center;'>" . $row->testDate . "</td>";
                $testname = explode(",", $row->testName);
                $sizeoftest = sizeof($testname);
                echo "</tr>";
                ?>
                <tr>
                    <td></td>
                    <td colspan='7'>
                        <table class="table table-bordered table-condensed" width="100%" style="margin:auto;">
                            <tr>
                                <td colspan='6' align='center'><b>Test Details:</b></caption></td>
                            </tr>
                            <tr>
                                <th width="33.3333%">Test Name</th>
                                <th width="33.3333%" style='text-align:center;'>Test Value</th>
                                <th width="33.3333%" style='text-align:center;'>Test Range</th>
                            </tr>
                            <tr> 
                                <td>
                                    <?php
                                    for ($i = 0; $i < $sizeoftest; $i++) {
                                        echo $testname[$i] . "<br>";
                                    }
                                    ?>
                                </td>  
                                <?php
                                $testvalue = explode(",", $row->testvalue);
                                $sizeofvalue = sizeof($testvalue);
                                ?>

                                <td style='text-align:center;'>
                                    <?php
                                    for ($j = 0; $j < $sizeoftest; $j++) {
                                        echo $testvalue[$j] . "<br>";
                                    }
                                    ?>
                                </td>
                                <?php
                                $testrange = explode(",", $row->testrange);
                                $sizeofrange = sizeof($testrange);
                                ?>							
                                <td style='text-align:center;'>
                                    <?php
                                    for ($h = 0; $h < $sizeoftest; $h++) {
                                        echo (!empty($testrange[$h])) ? $testrange[$h]. "<br>" : '' . "<br>";
                                    }
                                    ?>
                                </td>
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