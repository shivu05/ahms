<?php
$months_arr = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
if (empty($dept_bed_count)) {
    echo "<h4>No bed data found</h4>";
} else {
    foreach ($dept_bed_count as $dept) {
        ?>
        <div class="alert alert-info">
            <div class="pull-left"><b><?php echo $dept['department'] . " :"; ?>
                    <?php echo "ALLOTED BED:" . $dept['sum']; ?></b>
            </div>
        </div>
        <table class="table" width="70%" style="margin:auto;">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>No Of Days</th>
                    <th>Bed OCC Days</th>
                    <th>% of bed occupancy</th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<tr>";
                foreach ($deptbed as $d) {
                    foreach ($d as $m => $mon) {
                        $days = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($m . "-2018")), 2018);
                        echo "<tr><td>" . $m . "</td><td>" . $days . "</td>";
                        foreach ($mon as $dd) {
                            foreach ($dd as $d => $ar) {
                                if ($d == $dept->department) {
                                    echo "<td>" . $ar[0]->sum . "</td>";
                                    echo "<td>";
                                    echo round(((($ar[0]->sum) * (100))) / ($dept->sum * $days), 2);
                                    echo "</td>";
                                }
                            }
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <br />
        <?php
    }//end of foreach
}
?>