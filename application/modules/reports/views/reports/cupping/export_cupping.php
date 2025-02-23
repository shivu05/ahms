<div class="table-wrap">
    <table class="dataTable table table-bordered" id="cupping_table" width='100%' style="">
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
                <th style="width: 150px;text-align: left;">Type of Cupping</th>
                <th style="width: 150px;text-align: left;">Site of Application</th>
                <th style="width: 150px;text-align: left;">Number of Cups Used</th>
                <th style="width: 150px;text-align: left;">Diagnosis</th>
                <th style="width: 150px;text-align: left;">Treatment Notes</th>
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
                    $tr .= '<td>' . $row['type_of_cupping'] . '</td>';
                    $tr .= '<td>' . $row['site_of_application'] . '</td>';
                    $tr .= '<td>' . $row['no_of_cups_used'] . '</td>';
                    $tr .= '<td>' . $row['diagnosis'] . '</td>';
                    $tr .= '<td>' . $row['treatment_notes'] . '</td>';
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