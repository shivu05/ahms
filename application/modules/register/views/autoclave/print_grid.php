<table class="table table-hover table-bordered dataTable no-footer" width="100%" role="grid">
    <thead>
        <tr role="row">
            <th>#</th>
            <th style='width: 120px;'>Drum No</th>
            <th style='width: 120px;'>Start Date</th>
            <th style='width: 120px;'>End Date</th>
            <th style='width: 200px;'>Supervisor Name</th>
            <th style='width: 150px;'>Supervisor Sign</th>
            <th style='width: 150px;'>OT Nurse Sign</th>
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
                $tr .= '<td>' . $row['DrumNo'] . '</td>';
                $tr .= '<td>' . $row['DrumStartTime'] . '</td>';
                $tr .= '<td>' . $row['DrumEndTime'] . '</td>';
                $tr .= '<td>' . $row['SupervisorName'] . '</td>';
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