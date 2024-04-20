<div class="table-wrap">
    <table class="dataTable table table-bordered" id="panchakarma_table" width='100%' style="">
        <thead>
            <tr>
                <th class="sl_no" style="width: 50px;text-align: left;">Sl.No</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.OPD</th>
                <th style="width: 120px;text-align: left;">Name</th>
                <th style="width: 40px;text-align: left;">Age</th>
                <th style="width: 80px;text-align: left;">Gender</th>
                <th style="width: 160px;text-align: left;">Swarnaprashana date & time</th>
                <th style="width: 160px;text-align: left;">Department</th>
                <th style="width: 150px;text-align: left;">Ref. doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($data)) {
                $tr = '';
                foreach ($data as $row) {
                    $tr .= '<tr class="warning">';
                    $tr .= '<td>' . $row['serial_number'] . '</td>';
                    $tr .= '<td>' . $row['opd_no'] . '</td>';
                    $tr .= '<td>' . $row['name'] . '</td>';
                    $tr .= '<td>' . $row['Age'] . '</td>';
                    $tr .= '<td>' . $row['gender'] . '</td>';
                    $tr .= '<td>' . $row['date_month'] . ' ' . $row['dose_time'] . '</td>';
                    $tr .= '<td>' . $row['department'] . '</td>';
                    $tr .= '<td>' . $row['consultant'] . '</td>';
                    $tr .= '</tr>';
                }
                echo $tr;
            } else {
                echo '<tr><td colspan=8>No records found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>