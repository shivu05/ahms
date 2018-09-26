<?php
//pma($deptbed, 1);
?>
<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body">
                <div class="col-8 offset-2">
                    <?php
                    foreach ($dept_bed_count as $dept) {
                        $cur_year = date('Y');
                        ?>
                        <table class="table table-bordered">
                            <tr class="table-success" style="font-weight: bold;">
                                <td colspan="2"><?php echo 'Department: ' . $dept->department . ""; ?></td>
                                <td></td>
                                <td><?php echo "Alloted beds : " . $dept->sum; ?></td>
                            </tr>
                            <tr align='center'>
                                <th>Month</th><th>Days</th><th>Occupied Days</th><th> Percentage (%)</th>
                            </tr>
                            <tbody>
                                <?php
                                echo "<tr>";
                                foreach ($deptbed as $d) {
                                    foreach ($d as $m => $mon) {
                                        $days = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($m . "-$cur_year")), $cur_year);
                                        echo "<tr><td>" . $m . "</td><td align='center'>" . $days . "</td>";
                                        foreach ($mon as $dd) {
                                            foreach ($dd as $d => $ar) {
                                                if ($d == $dept->department) {
                                                    echo "<td align='center'>" . $ar[0]->sum . "</td>";
                                                    echo "<td align='center'>";
                                                    echo round(((($ar[0]->sum) * (100))) / ($dept->sum * $days), 2) . ' %';
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
                        <?php
                    }//end of foreach
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>