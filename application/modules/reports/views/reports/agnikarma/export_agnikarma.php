<div class="table-wrap">
    <table class="dataTable table table-bordered" id="agnikarma_table" width='100%' style="">
        <thead>
            <tr>
                <th class="sl_no" style="width: 50px;text-align: left;">Sl.No</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.OPD</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.IPD</th>
                <th style="width: 120px;text-align: left;">Treat ID</th>
                <th style="width: 160px;text-align: left;">Ref Date</th>
                <th style="width: 150px;text-align: left;">Doctor Name</th>
                <th style="width: 200px;text-align: left;">Treatment Notes</th>
                <th style="width: 160px;text-align: left;">Last Updates</th>
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
                    $tr .= '<td>' . $row['ipd_no'] . '</td>';
                    $tr .= '<td>' . $row['treat_id'] . '</td>';
                    $tr .= '<td>' . $row['ref_date'] . '</td>';
                    $tr .= '<td>' . $row['doctor_name'] . '</td>';
                    $tr .= '<td>' . $row['treatment_notes'] . '</td>';
                    $tr .= '<td>' . $row['last_updates'] . '</td>';
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