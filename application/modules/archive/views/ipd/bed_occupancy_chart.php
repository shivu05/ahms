<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?php
        $ts = 0;
        foreach ($dept_bed_count as $dept) {
            $cur_year = date('Y');
            ?>
            <table class="table table-bordered table-hover">
                <tr class="bg-aqua-gradient" style="font-weight: bold;">
                    <td colspan="2"><?php echo 'Department: ' . str_replace("_", " ", ucfirst(strtolower($dept->department))) . ""; ?></td>
                    <td></td>
                    <td><?php echo "Alloted beds : " . $dept->sum; ?></td>
                </tr>
                <tr align='center' style="background-color: lightgrey">
                    <th>Month</th><th>Days</th><th>Occupied Days</th><th> Percentage (%)</th>
                </tr>
                <tbody>
                    <?php
                    echo "<tr>";
                    $sm = 0;
                    foreach ($deptbed as $d) {
                        $dept_wise_per = 0;
                        $bed_avg = 0;
                        foreach ($d as $m => $mon) {
                            $days = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($m . "-$cur_year")), $cur_year);
                            echo "<tr><td>" . $m . "</td><td align='center'>" . $days . "</td>";

                            foreach ($mon as $dd) {
                                foreach ($dd as $d => $ar) {
                                    if ($d == $dept->department) {
                                        $sm = $sm + $ar[0]->sum;
                                        $bed_per = round(((($ar[0]->sum) * (100))) / ($dept->sum * $days), 2);
                                        echo "<td align='center'>" . $ar[0]->sum . "</td>";
                                        echo "<td align='center'>" . $bed_per . ' %' . "</td>";
                                        $dept_wise_per = $dept_wise_per + $bed_per;
                                        $bed_avg = $dept_wise_per;
                                    }
                                }
                            }
                        }
                        echo "</tr>";
                    }
                    $ts = $ts + $sm;
                    echo "<tr style='background-color: lightgrey'><td></td><td><b>Total: </b></td><td align='center'>" . $sm . "</td><td></td></tr>";
                    ?>
                </tbody>
            </table>
            <?php
        }//end of foreach
        ?>
        <h4 class="pull-right">GRAND TOTAL (Bed occupied days): <?= $ts ?></h4>
    </div>
</div>
