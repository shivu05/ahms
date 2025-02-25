<div class="table-wrap">
    <table class="dataTable table table-bordered" id="wound_dressing_table" width='100%' style="">
        <thead>
            <tr>
                <th class="sl_no" style="width: 50px;text-align: left;">Sl.No</th>
                <th style="width: 100px;text-align: left;">Ref Date</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.OPD</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.IPD</th>
                <th style="width: 120px;text-align: left;">Name</th>
                <th style="width: 40px;text-align: left;">Age</th>
                <th style="width: 60px;text-align: left;">Gender</th>
                <th style="width: 150px;text-align: left;">Wound Location</th>
                <th style="width: 150px;text-align: left;">Wound Type</th>
                <th style="width: 150px;text-align: left;">Dressing Material</th>
                <th style="width: 150px;text-align: left;">Doctor Name</th>
                <th style="width: 150px;text-align: left;">Next Dressing Date</th>
                <th style="width: 150px;text-align: left;">Doctor Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($data)) {
                $tr = '';
                foreach ($data as $row) {
                    $tr .= '<tr class="warning">';
                    $tr .= '<td>' . $row['serial_number'] . '</td>';
                    $tr .= '<td>' . $row['ref_date'] . '</td>';
                    $tr .= '<td>' . $row['opd_no'] . '</td>';
                    $tr .= '<td>' . $row['ipd_no'] . '</td>';
                    $tr .= '<td>' . $row['FirstName'] . '</td>';
                    $tr .= '<td>' . $row['Age'] . '</td>';
                    $tr .= '<td>' . $row['gender'] . '</td>';
                    $tr .= '<td>' . $row['wound_location'] . '</td>';
                    $tr .= '<td>' . $row['wound_type'] . '</td>';
                    $tr .= '<td>' . $row['dressing_material'] . '</td>';
                    $tr .= '<td>' . $row['doctor_name'] . '</td>';
                    $tr .= '<td>' . $row['next_dressing_date'] . '</td>';
                    $tr .= '<td>' . $row['doctor_remarks'] . '</td>';
                    $tr .= '</tr>';
                }
                echo $tr;
            } else {
                echo '<tr><td colspan=13>No records found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>