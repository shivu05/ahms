<?php
$patient = $data;
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="5%">Sl no.</th>
                <th width="7%">C.OPD</th>
                <th width="20%">Name</th>
                <th width="4%">Age</th>
                <th width="7%">Gender</th>
                <th width="13%">Place</th>
                <th width="12%">Department</th>
                <th width="10%">Entry Date</th>
                <th width="10%">ECG Date</th>
                <th width="13%">Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                echo "<tr>";
                echo "<td><center>" . $count . "</center></td>";
                echo "<td><center>" . $row['OpdNo'] . "</center></td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td><center>" . $row['Age'] . "</center></td>";
                echo "<td style='text-align:center;'>" . $row['gender'] . "</td>";
                echo "<td>" . trim($row['address']) . "</td>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td style='text-align:center;'>" . $row['refDate'] . "</td>";
                echo "<td style='text-align:center;'>" . format_date($row['ecgDate']) . "</td>";
                echo "<td>" . $row['refDocName'] . "</td>";
                echo "</tr>";
            }
            echo "<tr><td><b>Total:</b></td><td colspan='9'>" . $count . "</td></tr>";
            ?>
        </tbody>
    </table>
    <?php
}
?>