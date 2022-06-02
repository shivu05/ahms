<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="3%">#</th>
                <th width="5%"> C.IPD</th>
                <th width="5%"> C.OPD</th>
                <th width="20%">Name</th>
                <th width="5%">Bed</th>
                <th width="25%">Diagnosis</th>
                <th width="10%">DOA</th>
                <th width="10%">DOD</th>
                <th width="12%">Morning</th>
                <th width="12%">Afternoon</th>
                <th width="12%">Night</th>
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
            echo "<td>" . $row['IpNo'] . "</td>";
            echo "<td>" . $row['OpdNo'] . "</td>";
            echo "<td>" . $row['FName'] . "</td>";
            echo "<td>" . $row['BedNo'] . "</td>";
            echo "<td>" . $row['diagnosis'] . "</td>";
            echo "<td>" . $row['DoAdmission'] . "</td>";
            if ($row['DoDischarge'] !== "--") {
                echo "<td>" . $row['DoDischarge'] . "</td>";
            } else {
                echo "<td>--</td>";
            }
            echo "<td>" . $row['morning'] . "</td>";
            echo "<td>" . $row['after_noon'] . "</td>";
            echo "<td>" . $row['evening'] . "</td>";
            echo "</tr>";
        }//end of for each
        ?>
    </tbody>
    </table>
    <?php
}
?>