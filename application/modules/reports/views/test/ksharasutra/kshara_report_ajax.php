<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table  class="table table-bordered" width="100%" style="margin:auto;">
        <thead>
            <tr>
                <th width="5%">Sl no.</th>
                <th width="10%">C.OPD</th>
                <th width="10%">C.IPD</th>
                <th width="25%">Name</th>
                <th width="5%">Age</th>
                <th width="10%">Gender</th>
                <th width="25%">Address</th>
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
                echo "<td style='text-align:center;'>" . $row['IpNo'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['Age'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['gender'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "</tr>";
                ?>
                <tr>
                    <td colspan='7' align='center'>
                        <table class="table table-bordered table-condensed" width='100%'>
                            <tr><td colspan='7' align='center'><b>Ksharasutra Details:</b></caption></td></tr>
                            <tr>
                                <th width="20%">Diagnosis</th>
                                <th  width="20%">Name of Ksharsutra</th>
                                <th  width="20%">Type of Ksharsutra</th>
                                <th  width="10%">Date Of Ksharasutra</th>
                                <th  width="10%">Surgeon</th>
                                <th  width="10%">Asst. Surgeon</th>
                                <th  width="10%">Anesthetist</th>
                            </tr>
                            <tr>
                                <td><?php echo $row['diagnosis']; ?></td>
                                <td><?php echo $row['ksharaname']; ?></td>
                                <td><?php echo $row['ksharsType']; ?></td>
                                <td><?php echo format_date($row['ksharsDate']); ?></td>
                                <td><?php echo $row['surgeon']; ?></td>
                                <td><?php echo $row['asssurgeon']; ?></td>
                                <td><?php echo $row['anaesthetic']; ?></td>
                            </tr>
                        </table> 
                    </td>
                </tr>
                <tr><td colspan=7>&nbsp;</td></tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>