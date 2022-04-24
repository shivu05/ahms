<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered" width="100%">
        <thead>
            <tr>
                <th width="4%">Sl.No</th>
                <th width="8%">C.IPD</th>
                <th width="8%">C.OPD</th>
                <th width="8%">D.OPD</th>
                <th width="20%">Name</th>
                <th width="3%">Age</th>
                <th width="5%">Gender</th>
                <th width="4%">Ward</th>
                <th width="4%">Bed</th>
                <th width="18%">Diagnosis</th>
                <th width="8%">DoA</th>
                <th width="8%">DoD</th>
                <th width="12%">Doctor</th>
                <th width="15%">Department</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                echo "<tr>";
                ?> <td><center><?php echo $count; ?></center></td>
            <?php
            echo "<td><center>" . $row['IpNo'] . "</center></td>";
            echo "<td><center>" . $row['OpdNo'] . "</center></td>";
            echo "<td><center>" . $row['deptOpdNo'] . "</center></td>";
            echo "<td>" . $row['FName'] . "</td>";
            echo "<td><center>" . $row['Age'] . "</center></td>";
            echo "<td>" . $row['Gender'] . "</td>";
            echo "<td><center>" . $row['WardNo'] . "</center></td>";
            echo "<td><center>" . $row['BedNo'] . "</center></td>";
            echo "<td>" . $row['diagnosis'] . "</td>";
            echo "<td><center>" . format_date($row['DoAdmission']) . "</center></td>";
            echo "<td><center>" . format_date($row['DoDischarge']) . "</center></td>";
            echo "<td>" . $row['Doctor'] . "</td>";
            echo "<td>" . prepare_dept_name($row['department']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
    </table>
    <?php
}
if (!empty($pat_count)) {
    ?>
    <br/>
    <table class="table table-bordered dataTable" style="margin:1px auto; width:70%;">
        <thead>
            <tr>
                <th>Department</th>
                <th>Total</th>
                <th>Discharged</th>
                <th>BA</th>
                <th>Male</th>
                <th>Female</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tot_dis = $tot_ba = $tot_male = $tot_female = $total = 0;
            foreach ($pat_count as $count) {
                echo "<tr>";
                echo "<td>" . prepare_dept_name($count['department']) . "</td>";
                echo "<td><center>" . $count['total'] . "</center></td>";
                echo "<td><center>" . $count['discharged'] . "</center></td>";
                echo "<td><center>" . $count['onbed'] . "</center></td>";
                echo "<td><center>" . $count['Male'] . "</center></td>";
                echo "<td><center>" . $count['Female'] . "</center></td>";
                echo "</tr>";
                $total = $total + $count['total'];
                $tot_dis = $tot_dis + $count['discharged'];
                $tot_ba = $tot_ba + $count['onbed'];
                $tot_male = $tot_male + $count['Male'];
                $tot_female = $tot_female + $count['Female'];
            }
            ?>
            <tr>
                <td style="text-align:right;"><b>Total</b></td>
                <td style="text-align: center;"><?php echo $total; ?></td>
                <td style="text-align: center;"><?php echo $tot_dis; ?></td>
                <td style="text-align: center;"><?php echo $tot_ba; ?></td>
                <td style="text-align: center;"><?php echo $tot_male; ?></td>
                <td style="text-align: center;"><?php echo $tot_female; ?></td>
            </tr>
        </tbody>
    </table>
    <?php
}
?>