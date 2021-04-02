<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="3%">Sl no.</th>
                <th width="5%"> C.IPD</th>
                <th width="5%"> C.OPD</th>
                <th width="20%">Name</th>
                <th width="5%">Bed</th>
                <th width="25%">Diagnosis</th>
                <th width="10%">DOA</th>
                <th width="10%">DOD</th>
                <th>Morning<br>Bread/Bis/Tea</th>
                <th>Afternoon<br>Chapathi Rice</th>
                <th>Night<br>Chapathi Rice</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                echo "<tr>";
                ?> 
            <td style='text-align:center;'><?php echo $count; ?></td>
            <?php
            echo "<td style='text-align:center;'>" . $row['IpNo'] . "</td>";
            echo "<td style='text-align:center;'>" . $row['OpdNo'] . "</td>";
            echo "<td>" . $row['FName'] . "</td>";
            echo "<td style='text-align:center;'>" . $row['BedNo'] . "</td>";
            echo "<td>" . $row['diagnosis'] . "</td>";
            echo "<td style='text-align:center;'>" . $row['DoAdmission'] . "</td>";
            if ($row['DoDischarge'] !== "--") {
                echo "<td style='text-align:center;'>" . $row['DoDischarge'] . "</td>";
            } else {
                echo "<td style='text-align:center;'>--</td>";
            }
            echo "<td style='text-align:center;'>Yes</td>";
            echo "<td style='text-align:center;'>Yes</td>";
            echo "<td style='text-align:center;'>Yes</td>";
            echo "</tr>";
        }//end of for each
        ?>
    </tbody>
    </table>
    <?php
}
?>