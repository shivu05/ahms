<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Month-wise IPD Report</h3>
                <?php if (!empty($result)) { ?>
                    <a target="_blank" href="<?php echo base_url('reports/Ipd/monthly_ipd_report_pdf/'); ?>" class="pull-right btn btn-info btn-sm" id="export_pdf"><i class="fa fa-download "></i> Export</a>
                <?php } ?>
            </div>
            <div class="box-body">
                <?php
                if (empty($result)) {
                    echo "<h4 class='center red'>No Records found</h4>";
                } else {
                    ?>
                    <table id="example" border='1' class="table table-condensed" cellspacing="0"  width="90%">

                        <thead>
                            <tr>
                                <th></th>
                                <th>Month</th>
                                <th>Total</th>
                                <th>Male</th>
                                <th>Female</th>
                            </tr>
                        </thead>
                        <?php
                        $grand_total = 0;
                        $grand_male_total = 0;
                        $grand_female_total = 0;
                        foreach ($result as $dept_data => $val_arr) {
                            echo "<tr><td colspan=5><b>" . $dept_data . "</b></td></tr>";
                            $total = 0;
                            $male = 0;
                            $female = 0;
                            foreach ($val_arr as $val) {
                                echo "<tr><td></td><td>" . $val['month'] . "</td><td>" . $val['total'] . "</td><td>" . $val['Male'] . "</td><td>" . $val['Female'] . "</td></tr>";
                                $total = $total + $val['total'];
                                $male = $male + $val['Male'];
                                $female = $female + $val['Female'];
                            }
                            echo "<tr><td></td><td><b>Total: </b></td><td><b>" . $total . "</b></td><td><b>" . $male . "</b></td><td><b>" . $female . "</b></td></tr>";

                            $grand_total = $grand_total + $total;
                            $grand_male_total = $grand_male_total + $male;
                            $grand_female_total = $grand_female_total + $female;
                        }
                        echo "<tr><td colspan=5></td><tr>";
                        echo "<tr class='alert alert-success'><td></td><td><b>Grand Total: </b></td><td><b>" . $grand_total . "</b></td><td><b>" . $grand_male_total . "</b></td><td><b>" . $grand_female_total . "</b></td></tr>";
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