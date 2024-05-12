<table class="table table-hover table-bordered dataTable no-footer" width="100%" role="grid">
    <thead>
        <tr role="row">
            <th style='width: 50px;'>Sl.No</th>
            <th style='width: 80px;'>Date</th>
            <th style='width: 150px;'>Fumigation method</th>
            <th style='width: 180px;'>Chemicals used</th>
            <th style='width: 80px;'>Start time</th>
            <th style='width: 80px;'>End time</th>
            <th style='width: 80px;'>OT No</th>
            <th style='width: 200px;'>Neutralization</th>
            <th style='width: 180px;'>Supervisor name</th>
            <th style='width: 180px;'>Signature</th>
            <th style='width: 180px;'>Signature matron</th>
            <th style='width: 200px;'>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($data)) {
            $tr = '';
            foreach ($data as $row):
                $tr .= '<tr>';
                $tr .= '<td>' . $row['serial_number'] . '</td>';
                $tr .= '<td>' . $row['f_date'] . '</td>';
                $tr .= '<td>' . $row['fumigation_mothod'] . '</td>';
                $tr .= '<td>' . $row['chemical_used'] . '</td>';
                $tr .= '<td>' . $row['start_time'] . '</td>';
                $tr .= '<td>' . $row['end_time'] . '</td>';
                $tr .= '<td>' . $row['ot_number'] . '</td>';
                $tr .= '<td>' . $row['neutralization'] . '</td>';
                $tr .= '<td>' . $row['superviser_name'] . '</td>';
                $tr .= '<td>&nbsp;&nbsp;</td>';
                $tr .= '<td>&nbsp;&nbsp;</td>';
                $tr .= '<td>' . $row['Remarks'] . '</td>';
                $tr .= '</tr>';
            endforeach;
            echo $tr;
        }
        ?>
    </tbody>
</table>