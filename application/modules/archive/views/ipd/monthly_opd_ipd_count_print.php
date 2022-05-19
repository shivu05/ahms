<?php
if (empty($result)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table id="example" border='' class="table table-bordered" cellspacing="0"  width="100%">
        <thead>
            <tr>
                <th>Month</th>
                <th>NEW</th>
                <th>OLD</th>
                <th>TOTAL</th>
                <th>No.Of IPD</th>
                <th>Male</th>
                <th>Female</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $table_body = '';
            $total_new = $total_old = $total_tot = $total_ipd = $total_male = $total_female = 0;
            foreach ($result['opd'] as $dept_data => $val_arr) {
                $table_body .= "<tr><td colspan=7><b>" . prepare_dept_name($dept_data) . "</b></td></tr>";
                $total = $new = $old = $ipd = $male = $female = $count = 0;
                foreach ($val_arr as $val) {
                    $ipd_count = 0;
                    if (!empty($result['ipd'][$dept_data][$count]['total'])) {
                        $ipd_count = $result['ipd'][$dept_data][$count]['total'];
                    }
                    $table_body .= "<tr><td>" . $val['month'] . "</td><td>" . $val['NEW'] . "</td><td>" . $val['OLD'] . "</td><td>" . $val['total'] . "</td><td>" . $ipd_count . "</td><td>" . $val['Male'] . "</td><td>" . $val['Female'] . "</td></tr>";

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

                $table_body .= "<tr><td>Total</td><td align='right'><b>" . $new . "</b></td><td align='right'><b>" . $old . "</b></td><td align='right'><b>" . $total . "</b></td><td align='right'><b>" . $ipd . "</b></td><td align='right'><b>" . $male . "</b></td><td align='right'><b>" . $female . "</b></td></tr>";
            }
            $table_body .= "<tr><td colspan=7></td></tr>";
            $table_body .= "<tr><td><b>Grand Total:</b></td><td align='right'>" . $total_new . "</td><td align='right'>" . $total_old . "</td><td align='right'>" . $total_tot . "</td><td align='right'>" . $total_ipd . "</td><td align='right'>" . $total_male . "</td><td align='right'>" . $total_female . "</td></tr>";
            echo $table_body;
            ?>
        </tbody>
    </table>
    <?php
}
?>