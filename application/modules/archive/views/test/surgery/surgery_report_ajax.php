<?php
$patient = $data;
//pma($patient,1);
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" cellspacing="0"  width="100%" style="font-size:14px;">
        <thead>
            <tr>
                <th width="5%" style="text-align:center;">Sl no</th>
                <th width="5%" style="text-align:center;"> Ctrl.OPD</th>
                <th width="5%" style="text-align:center;"> Dept.OPD</th>
                <th width="5%" style="text-align:center;"> Ctrl.IPD</th>
                <th width="20%">Patient Name</th>
                <th width="5%" style="text-align:center;">Age</th>
                <th width="5%" style="text-align:center;">Gender</th>
                <th width="10%">Address</th>
                <th width="10%">Remarks</th>	
            </tr>
        </thead>
        <tbody>	
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                echo "<tr>";
                echo "<td style='text-align:center;'>" . $count . "</td>";
                echo "<td style='text-align:center;'>" . $row['OpdNo'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['deptOpdNo'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['IpNo'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['Age'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['gender'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . ( ($row['notes'] == 0) ? "" : $row['notes'] ) . "</td>";
                echo "</tr>";
                ?>
                <tr>
                    <td colspan='9'>
                        <table class="table table-bordered table-condensed" cellspacing="0" width="100%">
                            <tr>
                                <td colspan='7' align='center'><b>Surgery Details:</b></caption></td>
                            </tr>
                            <tr>
                                <th width="15%">Diagnosis</th>
                                <th width="10%">Name of Surgery</th>
                                <th width="10%">Type of Surgery</th>
                                <th width="10%">Surgeon Name</th>
                                <th width="10%">Asst. Surgeon</th>
                                <th width="10%">Anesthetist</th>
                                <th width="5%">Date</th>
                            </tr>
                            <tr> 
                                <td><?php echo $row['diagnosis']; ?>  </td>
                                <td><?php echo $row['surgeryname']; ?>  </td>
                                <td><?php echo $row['surgType']; ?>  </td>
                                <td><?php echo $row['surgName']; ?>  </td>
                                <td><?php echo $row['asssurgeon']; ?>  </td>
                                <td><?php echo $row['anaesthetic']; ?>  </td>
                                <td><?php echo format_date($row['surgDate']); ?>  </td>
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
?>