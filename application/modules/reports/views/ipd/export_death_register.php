<table class="table" width="100%">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Death Reg. No</th>
            <th>UHID</th>
            <th>C.OPD</th>
            <th>C.IPD</th>
            <th>Name</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Department</th>
            <th>Ward</th>
            <th>Bed</th>
            <th>DOA</th>
            <th>Date & Time of Death</th>
            <th>Doctor</th>
            <th>Immediate Cause</th>
            <th>Antecedent Cause</th>
            <th>Underlying Cause</th>
            <th>MCCD</th>
            <th>MLC</th>
            <th>Police</th>
            <th>Body Handed To</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (empty($records)) {
            echo "<tr><td colspan='22'>No data</td></tr>";
        } else {
            $i = 0;
            foreach ($records as $record) {
                $i++;
                echo "<tr>";
                echo "<td><center>" . $i . "</center></td>";
                echo "<td>" . $record['death_register_no'] . "</td>";
                echo "<td>" . $record['uhid'] . "</td>";
                echo "<td><center>" . $record['opd_no'] . "</center></td>";
                echo "<td><center>" . $record['ipd_no'] . "</center></td>";
                echo "<td>" . $record['patient_name'] . "</td>";
                echo "<td><center>" . $record['age'] . "</center></td>";
                echo "<td>" . $record['gender'] . "</td>";
                echo "<td>" . prepare_dept_name($record['department']) . "</td>";
                echo "<td><center>" . $record['ward_no'] . "</center></td>";
                echo "<td><center>" . $record['bed_no'] . "</center></td>";
                echo "<td>" . format_date($record['admission_date']) . " " . $record['admission_time'] . "</td>";
                echo "<td>" . format_date($record['death_date']) . " " . $record['death_time'] . "</td>";
                echo "<td>" . $record['certifying_doctor'] . "</td>";
                echo "<td>" . $record['immediate_cause'] . "</td>";
                echo "<td>" . $record['antecedent_cause'] . "</td>";
                echo "<td>" . $record['underlying_cause'] . "</td>";
                echo "<td><center>" . ($record['mccd_form4_issued'] ? 'Yes' : 'No') . "</center></td>";
                echo "<td><center>" . ($record['mlc_case'] ? 'Yes' : 'No') . "</center></td>";
                echo "<td><center>" . ($record['police_informed'] ? 'Yes' : 'No') . "</center></td>";
                echo "<td>" . $record['body_handed_over_to'] . "</td>";
                echo "<td>" . $record['remarks'] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>
<?php
if (!empty($statistics)) {
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
            foreach ($statistics as $row) {
                echo "<tr>";
                echo "<td>" . prepare_dept_name($row['department']) . "</td>";
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
