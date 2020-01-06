<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-book"></i> IPD: Bed occupancy report</h3>
                <div class="btn-group pull-right" role="group" id="export">
                    <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" target="_blank" href="<?= base_url('reports/Ipd/bed_occupancy_chart_pdf') ?>" id="export_to_pdf">.pdf</a></li>
                            <!--<li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-8 col-md-offset-2">
                    <?php
                    foreach ($dept_bed_count as $dept) {
                        $cur_year = date('Y');
                        ?>
                        <table class="table table-bordered table-hover">
                            <tr class="bg-aqua-gradient" style="font-weight: bold;">
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