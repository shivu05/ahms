<?php
$dept_room_array = array(
    'KAYACHIKITSA' => 1,
    'SHALYA_TANTRA' => 2,
    'PANCHAKARMA' => 3,
    'PRASOOTI_&_STRIROGA' => 4,
    'BALAROGA' => 5,
    'SHALAKYA_TANTRA' => 6,
    'AATYAYIKACHIKITSA' => 7,
    'SWASTHAVRITTA' => 8,
);
?>
<table width="100%" class="table">
    <thead>
        <tr>
            <th width="3%">Sl No.</th>
            <th width="5%">Yr.No.</th>
            <th width="5%">M.No.</th>
            <th width="6%">C.OPD</th>
            <?php if ($department != 1) { ?>
                <th width="6%">D.OPD</th>
            <?php } ?>	
            <th width="3%">Type</th>
            <th width="20%">Name</th>
            <th width="3%">Age</th>
            <th width="5%">Gender</th>
            <th width="10%">Place</th>
            <?php if ($department != 1) { ?>
                <th width="10%">Diagnosis</th>
                <th width="20%">Treatment</th>
                <th width="10%">Doctor</th>
            <?php } else {
                ?>
                <!--<th width="17%">Complaints</th>-->
            <?php } ?>
            <th width="13%">Department</th>
            <?php if ($department == 1) { ?>
                <th width="3%">Ref.<br/>Room No.</th>
            <?php } ?>
            <th width="7%">Date</th>
        </tr>   
    </thead>
    <tbody>
        <?php
        if (empty($opd_patients)) {
            echo "No data";
        } else {
            $i = 0;
            foreach ($opd_patients as $patients) {
                $patType = patient_type($patients['PatType']);
                $i++;
                echo "<tr>";
                echo "<td><center>" . $i . "</center></td>";
                echo "<td><center>" . $patients['ID'] . "</center></td>";
                echo "<td><center>" . $patients['msd'] . "</center></td>";
                echo "<td><center>" . $patients['OpdNo'] . "</center></td>";
                if ($department != 1) {
                    echo "<td><center>" . $patients['deptOpdNo'] . "</center></td>";
                }
                echo "<td><center>" . $patType . "</center></td>";
                echo "<td>" . $patients['name'] . "</td>";
                echo "<td><center>" . $patients['Age'] . "</center></td>";
                echo "<td>" . $patients['gender'] . "</td>";
                echo "<td>" . $patients['address'] . " " . $patients['city'] . "</td>";
                if ($department != 1) {
                    echo "<td>" . $patients['diagnosis'] . "</td>";
                    echo "<td>" . $patients['Trtment'] . "</td>";
                    echo "<td>" . $patients['AddedBy'] . "</td>";
                } else {
                    //echo "<td>" . $patients['diagnosis'] . "</td>";
                }
                echo "<td>" . $patients['department'] . "</td>";
                if ($department == 1) {
                    echo "<td style='text-align: right;'><center>" . $dept_room_array[$patients['ref_dept']] . "</center></td>";
                }
                echo "<td>" . format_date($patients['CameOn']) . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>
<?php
$table = '';
if (!empty($opd_stats)) {
    $total = $male_total = $female_total = 0;
    $table = "<br/><table width='75%' class='table' style='margin-top:2 !important;margin-left: auto;margin-right: auto;'>";
    $table .= "<thead><tr><th width='35%'><center>Department</center></th><th width='5%'><center>Old</center></th><th width='5%'><center>New</center></th>
        <th width='5%'><center>Total</center></th><th width='5%'><center>Male</center></th><th width='5%'><center>Female</center></th>"
            . "<th>Netra-Roga Vibhaga</th><th>karna-Nasa-Mukha & Danta Vibhaga</th></tr></thead>";
    $table .= "<tbody>";
    foreach ($opd_stats as $pdata) {
        $table .= "<tr>";
        $table .= "<td>" . $pdata['department'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['OLD'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['NEW'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['Total'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['Male'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['Female'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['NRV'] . "</td>";
        $table .= "<td style='text-align: right;'>" . $pdata['KNMDV'] . "</td>";
        $table .= "</tr>";
        $total = $total + $pdata['Total'];
        $male_total = $male_total + $pdata['Male'];
        $female_total = $female_total + $pdata['Female'];
    }
    $table .= "<tr><td colspan=3 align=right><b>Total No:</b></td><td style='text-align: right;' class='alert-info'><b>" . $total . "</b></td><td style='text-align: right;'><b>" . $male_total . "</b></td><td style='text-align: right;'><b>" . $female_total . "</b></td></tr>";
    $table .= "</tbody>";
    $table .= "</table>";
}
echo $table;
?>