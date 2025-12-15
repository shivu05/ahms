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
                    // Determine report year: prefer a variable passed to the view, then config item 'report_year', then current year
                    $cur_year = isset($report_year) ? $report_year : (isset($this->config) && $this->config->item('report_year') ? $this->config->item('report_year') : date('Y'));

                    // Leap-year aware year-days
                    $is_leap = (($cur_year % 4 == 0) && ($cur_year % 100 != 0 || $cur_year % 400 == 0));
                    $year_days = $is_leap ? 366 : 365;

                    // Build a lookup: dept_month_lookup[department][MonthName] = occupied_days (aggregated)
                    $dept_month_lookup = [];
                    foreach ($deptbed as $d) {
                        foreach ($d as $monthName => $monEntries) {
                            foreach ($monEntries as $entry) {
                                foreach ($entry as $deptName => $ar) {
                                    $sum = isset($ar[0]->sum) ? (int)$ar[0]->sum : 0;
                                    if (!isset($dept_month_lookup[$deptName][$monthName])) {
                                        $dept_month_lookup[$deptName][$monthName] = 0;
                                    }
                                    $dept_month_lookup[$deptName][$monthName] += $sum;
                                }
                            }
                        }
                    }

                    // Month order (show full year Jan..Dec to support missing months)
                    $month_names = [
                        'January','February','March','April','May','June','July','August','September','October','November','December'
                    ];

                    // Grand totals
                    $ts = 0; // total occupied days across all departments
                    $total_beds_across_depts = 0;
                    foreach ($dept_bed_count as $dtmp) {
                        $total_beds_across_depts += (int) $dtmp->sum;
                    }

                    foreach ($dept_bed_count as $dept) {
                        ?>
                        <table class="table table-bordered table-hover">
                            <tr class="bg-aqua-gradient" style="font-weight: bold;">
                                <td colspan="2"><?php echo 'Department: ' . str_replace("_", " ", ucfirst(strtolower($dept->department))); ?></td>
                                <td></td>
                                <td><?php echo "Alloted beds : " . (int) $dept->sum; ?></td>
                            </tr>
                            <tr align='center' style="background-color: lightgrey">
                                <th>Month</th><th>Days</th><th>Occupied Days</th><th> Percentage (%)</th>
                            </tr>
                            <tbody>
                                <?php
                                $sm = 0; // dept occupied days for the year

                                foreach ($month_names as $m) {
                                    // days in this month for the selected year
                                    $month_numeric = date("m", strtotime($m . "-" . $cur_year));
                                    $days = cal_days_in_month(CAL_GREGORIAN, (int)$month_numeric, (int)$cur_year);

                                    // safe lookup for occupied days
                                    $occupied_for_month = isset($dept_month_lookup[$dept->department][$m]) ? (int)$dept_month_lookup[$dept->department][$m] : 0;
                                    $sm += $occupied_for_month;

                                    // Percentage for this month: occupied / (allocated beds * days in month)
                                    $available_days_month = ((int)$dept->sum) * $days;
                                    $bed_per = $available_days_month > 0 ? round((($occupied_for_month * 100) / $available_days_month), 2) : 0.00;

                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($m) . "</td>";
                                    echo "<td align='center'>" . (int)$days . "</td>";
                                    echo "<td align='center'>" . number_format($occupied_for_month) . "</td>";
                                    echo "<td align='center'>" . number_format($bed_per, 2) . ' %' . "</td>";
                                    echo "</tr>";
                                }

                                // department totals for the year
                                $ts += $sm;
                                $available_days_year = ((int)$dept->sum) * $year_days;
                                $dept_year_pct = $available_days_year > 0 ? round((($sm * 100) / $available_days_year), 2) : 0.00;

                                echo "<tr style='background-color: lightgrey'><td></td><td><b>Total: </b></td><td align='center'>" . number_format($sm) . "</td><td align='center'><b>" . number_format($dept_year_pct, 2) . " %</b></td></tr>";
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }//end of foreach

                    // Grand total and overall percentage across all departments for the selected year
                    $grand_available_days = ($total_beds_across_depts > 0) ? ($total_beds_across_depts * $year_days) : 0;
                    $grand_pct = $grand_available_days > 0 ? round((($ts * 100) / $grand_available_days), 2) : 0.00;
                    ?>
                    <h4 class="pull-right">GRAND TOTAL (Bed occupied days): <?= number_format($ts) ?> &nbsp; &nbsp; <small>Occupancy: <strong><?= number_format($grand_pct, 2) ?> %</strong></small></h4>
                </div>
            </div>
        </div>
    </div>
</div>