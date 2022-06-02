<?php
$patient = $data;
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sl no.</th>
                <th>C.PD</th>
                <th>D.OPD</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Place</th>
                <th>Department</th>
                <th>Diagnosis</th>
                <th>X-Ray No.</th>
                <th>Part</th>
                <th>Film Size</th>
                <th>Ref. Date</th>
                <th>X-ray Date</th>
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
                echo "<td><center>" . $row['Age'] . "</center></td>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . trim($row['address']) . "</td>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td>" . $row['diagnosis'] . "</td>";
                echo "<td><center>" . $row['ID'] . "</center></td>";
                echo "<td>" . $row['partOfXray'] . "</td>";
                echo "<td>" . $row['filmSize'] . "</td>";
                echo "<td>" . format_date($row['refDate']) . "</td>";
                echo "<td>" . format_date($row['xrayDate']) . "</td>";
                echo "</tr>";
            }
            echo "<tr><td><b>Total:</b></td><td colspan='12'><b>" . $count . "</b></td></tr>";
            ?>
        </tbody>
    </table>
    <?php
}
?>