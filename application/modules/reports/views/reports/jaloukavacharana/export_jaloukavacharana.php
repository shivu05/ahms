<div class="table-wrap">
    <table class="dataTable table table-bordered" id="jaloukavacharana_table" width='100%' style="">
        <thead>
            <tr>
                <th class="sl_no" style="width: 50px;text-align: left;">Sl.No</th>
                <th style="width: 100px;text-align: left;">Ref Date</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.OPD</th>
                <th class="sl_no" style="width: 50px;text-align: left;">C.IPD</th>
                <th style="width: 120px;text-align: left;">Name</th>
                <th style="width: 40px;text-align: left;">Age</th>
                <th style="width: 60px;text-align: left;">Gender</th>
                <th style="width: 150px;text-align: left;">Doctor Name</th>
                <th style="width: 150px;text-align: left;">Procedure Details</th>
                <th style="width: 150px;text-align: left;">Doctor Remarks</th>
                <th style="width: 150px;text-align: left;">Diagnosis</th>
                <th style="width: 150px;text-align: left;">Last Updated</th>
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
                    $tr .= '<td>' . $row['doctor_name'] . '</td>';
                    $tr .= '<td>' . $row['procedure_details'] . '</td>';
                    $tr .= '<td>' . $row['doctor_remarks'] . '</td>';
                    $tr .= '<td>' . $row['diagnosis'] . '</td>';
                    $tr .= '<td>' . $row['last_updated'] . '</td>';
                    $tr .= '</tr>';
                }
                echo $tr;
            } else {
                echo '<tr><td colspan=12>No records found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>