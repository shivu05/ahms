<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title"></h3></div>
            <div class="box-body">
                <?php
                if (empty($result)) {
                    echo "<h4 class='center red'>No Records found</h4>";
                } else {
                    ?>
                    <a target="_blank" href="<?php echo base_url('report/reports/print_MonthlyOpdIpdCount/'); ?>" class="pull-right btn btn-info" id="export_pdf"><i class="icon-book"></i> Export</a>
                    <div class="clearfix"></div>
                    <hr>
                    <table id="example" border='1' class="table table-condensed" cellspacing="0"  width="90%">

                        <thead>
                            <tr>
                                <th></th>
                                <th>Month</th>
                                <th>NEW</th>
                                <th>OLD</th>
                                <th>TOTAL</th>
                                <th>No.Of IPD</th>
                                <th>Male's</th>
                                <th>Female's</th>
                            </tr>
                        </thead>
                        <?php
                        $total_new = 0;
                        $total_old = 0;
                        $total_tot = 0;
                        $total_ipd = 0;
                        $total_male = 0;
                        $total_female = 0;
                        foreach ($result['opd'] as $dept_data => $val_arr) {
                            echo "<tr><td colspan=9><b>" . $dept_data . "</b></td></tr>";
                            $total = 0;
                            $new = 0;
                            $old = 0;
                            $ipd = 0;
                            $male = 0;
                            $female = 0;
                            $count = 0;
                            foreach ($val_arr as $val) {
                                $ipd_count = 0;
                                if (!empty($result['ipd'][$dept_data][$count]['total'])) {
                                    $ipd_count = $result['ipd'][$dept_data][$count]['total'];
                                }
                                echo "<tr><td></td><td>" . $val['month'] . "</td><td>" . $val['NEW'] . "</td><td>" . $val['OLD'] . "</td><td>" . $val['total'] . "</td><td>" . $ipd_count . "</td><td>" . $val['Male'] . "</td><td>" . $val['Female'] . "</td></tr>";

                                $total = $total + $val['total'];
                                $male = $male + $val['Male'];
                                $female = $female + $val['Female'];
                                $new = $new + $val['NEW'];
                                $old = $old + $val['OLD'];
                                $ipd = $ipd + $ipd_count;
                                $count++;
                            }
                            $total_new = $total_new + $new;
                            $total_old = $total_old + $old;
                            $total_tot = $total_tot + $total;
                            $total_ipd = $total_ipd + $ipd;
                            $total_male = $total_male + $male;
                            $total_female = $total_female + $female;

                            echo "<tr><td></td><td></td><td><b>" . $new . "</b></td><td><b>" . $old . "</b></td><td><b>" . $total . "</b></td><td><b>" . $ipd . "</b></td><td><b>" . $male . "</b></td><td><b>" . $female . "</b></td></tr>";
                        }
                        echo "<tr><td colspan=8></td></tr>";
                        echo "<tr><td></td><td><b>Total:</b></td><td>" . $total_new . "</td><td>" . $total_old . "</td><td>" . $total_tot . "</td><td>" . $total_ipd . "</td><td>" . $total_male . "</td><td>" . $total_female . "</td></tr>";
                        ?>

                        <tbody>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>