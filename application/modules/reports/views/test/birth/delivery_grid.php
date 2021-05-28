<?php
$patient = $patient['data'];
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered" cellspacing="0"  width="100%">
        <thead>
           <!-- <tr>
                <th colspan="5">Patient details</th>
                <th colspan="1">Treatment</th>
                <th colspan="3">Delivery details</th>
                <th colspan="2">Baby details</th>
                <th colspan="1"></th>-->
            </tr>
            <tr>
                <th>#</th>
                <th>C.OPD</th>
                <th>C.IPD</th>
                <th>Name</th>
                <th>Age</th>
                <th>Diagnosis</th>
                <th>Type</th>
                <th>Details</th>
                <th>Delivery Date</th>
                <th>Baby Weight</th>               
                <th>Baby Gender</th>               
                <th>DOA</th>  
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                ?> 
                <tr>
                    <td><?php echo $count; ?></td>
                    <?php
                    echo "<td>" . $row['OpdNo'] . "</td>";
                    echo "<td>" . $row['IpNo'] . "</td>";
                    echo "<td>" . $row['FName'] . "</td>";
                    echo "<td>" . $row['Age'] . "</td>";
                    echo "<td>" . $row['diagnosis'] . "</td>";
                    echo "<td>" . $row['deliverytype'] . "</td>";
                    echo "<td>" . $row['deliveryDetail'] . "</td>";
                    echo "<td>" . $row['babyBirthDate'] . "</td>";
                    echo "<td>" . $row['babyWeight'] . "</td>";
                    echo "<td>" . $row['babygender'] . "</td>";
                    echo "<td>" . $row['DoAdmission'] . "</td>";
                    echo "<td>" . $row['treatby'] . "</td>";
                    echo "</tr>";
                }
                ?>
        </tbody>
    </table>
    <?php
}
?>