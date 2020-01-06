<?php
if (empty($result)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered" cellspacing="0"  width="100%">
        <thead>
            <tr>
                <th width='1%'></th>
                <th>Month</th>
                <th><center>Total</center></th>
    <th><center>Male</center></th>
    <th><center>Female</center></th>
    </tr>
    </thead>
    <tbody>
        <?php
        foreach ($result as $dept_data => $val_arr) {
            echo "<tr><td colspan=5><b>" . $dept_data . "</b></td></tr>";
            $total = $male = $female = 0;
            foreach ($val_arr as $val) {
                echo "<tr><td width='1%'></td><td>" . $val['month'] . "</td><td><center>" . $val['total'] . "</center></td><td><center>" . $val['Male'] . "</center></td><td><center>" . $val['Female'] . "</center></td></tr>";
                $total = $total + $val['total'];
                $male = $male + $val['Male'];
                $female = $female + $val['Female'];
            }
            echo "<tr style='height:20px;'><td></td><td><b>Total: </b></td><td><b><center>" . $total . "</center></b></td><td><b><center>" . $male . "</center></b></td><td><b><center>" . $female . "</center></b></td></tr>";
            $grand_total = $grand_total + $total;
            $grand_male_total = $grand_male_total + $male;
            $grand_female_total = $grand_female_total + $female;
        }
        echo "<tr><td colspan=5></td><tr>";
        echo "<tr class='alert alert-success'><td></td><td><b>Grand Total: </b></td><td><b>" . $grand_total . "</b></td><td><b>" . $grand_male_total . "</b></td><td><b>" . $grand_female_total . "</b></td></tr>";
        ?>

    </tbody>
    </table>
    <?php
}
?>