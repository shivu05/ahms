<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Month-wise IPD-OPD report:</h3>
                <a target="_blank" href="<?php echo base_url('reports/Ipd/monthly_ipd_opd_report_pdf/'); ?>" class="pull-right btn btn-info btn-sm" id="export_pdf"><i class="fa fa-download"></i> Export</a>
            </div>
            <div class="box-body">
                <?php
                if (empty($result)) {
                    echo "<h4 class='center red'>No Records found</h4>";
                } else {
                    ?>
                    <table id="example" class="table table-bordered" cellspacing="0"  width="90%" nobr="true">
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
                        $total_new = $total_old = $total_tot = $total_ipd = $total_male = $total_female = 0;
                        foreach ($result['opd'] as $dept_data => $val_arr) {
                            echo "<tr><td colspan=9><b>" . $dept_data . "</b></td></tr>";
                            $total = $new = $old = $ipd = $male = $female = $count = 0;
                            foreach ($val_arr as $val) {
                                $ipd_count = 0;
                                if (!empty($result['ipd'][$dept_data][$count]['total'])) {
                                    $ipd_count = $result['ipd'][$dept_data][$count]['total'];
                                }
                                $tr = "<tr nobr='true'>";
                                $tr .= "<td></td>";
                                $tr .= "<td>" . $val['month'] . "</td>";
                                $tr .= "<td>" . $val['NEW'] . "</td>";
                                $tr .= "<td>" . $val['OLD'] . "</td>";
                                $tr .= "<td>" . $val['total'] . "</td>";
                                $tr .= "<td>" . $ipd_count . "</td>";
                                $tr .= "<td>" . $val['Male'] . "</td>";
                                $tr .= "<td>" . $val['Female'] . "</td>";
                                $tr .= "</tr>";

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

                            echo "<tr nobr='true'><td></td><td></td><td><b>" . $new . "</b></td><td><b>" . $old . "</b></td><td><b>" . $total . "</b></td><td><b>" . $ipd . "</b></td><td><b>" . $male . "</b></td><td><b>" . $female . "</b></td></tr>";
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