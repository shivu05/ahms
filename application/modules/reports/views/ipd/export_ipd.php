<table class="table" width="100%">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>C.OPD</th>
            <th>C.IPD</th>
            <th>D.OPD</th>
            <th>Name</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Ward</th>
            <th>Bed</th>
            <th>Diagnosis</th>
            <th>DOA</th>
            <th>DOD</th>
            <th>Days</th>
            <th>Doctor</th>
            <th>Department</th>
        </tr>   
    </thead>
    <tbody>
        <?php
        if (empty($ipd_patients)) {
            echo "No data";
        } else {
            $i = 0;
            foreach ($ipd_patients as $patients) {
                $i++;
                echo "<tr>";
                echo "<td><center>" . $i . "</center></td>";
                echo "<td><center>" . $patients['OpdNo'] . "</center></td>";
                echo "<td><center>" . $patients['IpNo'] . "</center></td>";
                echo "<td><center>" . $patients['deptOpdNo'] . "</center></td>";
                echo "<td>" . $patients['FName'] . "</td>";
                echo "<td><center>" . $patients['Age'] . "</center></td>";
                echo "<td>" . $patients['Gender'] . "</td>";
                echo "<td><center>" . $patients['WardNo'] . "</center></td>";
                echo "<td><center>" . $patients['BedNo'] . "</center></td>";
                echo "<td>" . $patients['diagnosis'] . "</td>";
                echo "<td>" . format_date($patients['DoAdmission']) . " " . $patients['admit_time'] . "</td>";
                $disch_time = ($patients['status'] == 'stillin') ? '' : $patients['discharge_time'];
                echo "<td>" . format_date($patients['DoDischarge']) . " " . $disch_time . "</td>";
                echo "<td><center>" . $patients['NofDays'] . "</center></td>";
                echo "<td>" . $patients['Doctor'] . "</td>";
                echo "<td>" . prepare_dept_name($patients['department']) . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>
<?php
if (!empty($ipd_count)) {
    ?>
    <br/>
    <table class="table" width="50%" style="margin:auto;">
        <thead>
            <tr>
                <th>Department</th>
                <th>Total</th>
                <th>Male</th>
                <th>Female</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $male = 0;
            $female = 0;
            foreach ($ipd_count as $row) {
                echo "<tr>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td><center>" . $row['Total'] . "</center></td>";
                echo "<td><center>" . $row['Male'] . "</center></td>";
                echo "<td><center>" . $row['Female'] . "</center></td>";
                echo "</tr>";
                $total = $total + $row['Total'];
                $male = $male + $row['Male'];
                $female = $female + $row['Female'];
            }
            echo "<tr>";
            echo "<td style='text-align:right;'><b>Total No.:</b></td>";
            echo "<td style='text-align:center;'>" . $total . "</td>";
            echo "<td style='text-align:center;'>" . $male . "</td>";
            echo "<td style='text-align:center;'>" . $female . "</td>";
            echo "</tr>";
            ?>
        </tbody>
    </table>
    <?php
}
?>