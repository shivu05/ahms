<?php
$patient = $data;
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" cellspacing="0" width='100%'>
        <thead>
            <tr>
                <th>Sl no.</th>
                <th>C.OPD</th>
                <th>D.OPD</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Place</th>
                <th>Department</th>
                <th>Date</th>
                <th>USG Date</th>
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
                echo "<td><center>" . $row['deptOpdNo'] . "</center></td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td style='text-align:center;'><cente>" . $row['Age'] . "</center></td>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td style='text-align:center;'>" . format_date($row['entrydate']) . "</td>";
                echo "<td style='text-align:center;'>" . format_date($row['usgDate']) . "</td>";
                echo "</tr>";
            }
            echo "<tr><td><b>Total:</b></td><td colspan='9'><b>" . $count . "</b></td></tr>";
            ?>	
        </tbody>
    </table>
    <?php
}
?>