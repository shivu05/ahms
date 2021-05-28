<div class="table-wrap">
    <table class="dataTable table table-bordered" id="panchakarma_table" width='100%'>
        <thead>
            <tr>
                <th class="sl_no" style="width: 5% !important;text-align: left;">Sl.No</th>
                <th class="sl_no" style="width: 5% !important;text-align: left;">C.OPD</th>
                <th class="sl_no" style="width: 5% !important;text-align: left;">D.OPD</th>
                <th class="sl_no" style="width: 5% !important;text-align: left;">C.IPD</th>
                <th style="width: 15% !important;text-align: left;">Name</th>
                <th style="width: 5% !important;text-align: left;">Age</th>
                <th style="width: 8% !important;text-align: left;">Gender</th>
                <th style="width: 20% !important;text-align: left;">Disease</th>
                <th style="width: 15% !important;text-align: left;">Department</th>
                <th style="width: 15% !important;text-align: left;">Ref. doctor</th>
                <?php if ($is_print == false): ?>
                    <th style="width: 30px !important;text-align: center;">
                        <input type="checkbox" name="check_all" class="check_all" id="check_all" onclick="toggle(this)"/>
                    </th>
                <?php endif; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($data)) {
                $tr = '';
                $n=10;
                foreach ($data as $row) {
                    $tr .= '<tr class="warning">';
                    $tr .= '<td>' . $row['serial_number'] . '</td>';
                    $tr .= '<td>' . $row['opdno'] . '</td>';
                    $tr .= '<td>' . $row['deptOpdNo'] . '</td>';
                    $tr .= '<td>' . $row['IpNo'] . '</td>';
                    $tr .= '<td>' . $row['name'] . '</td>';
                    $tr .= '<td>' . $row['Age'] . '</td>';
                    $tr .= '<td>' . $row['gender'] . '</td>';
                    $tr .= '<td>' . $row['disease'] . '</td>';
                    $tr .= '<td>' . $row['dept'] . '</td>';
                    $tr .= '<td>' . $row['docname'] . '</td>';
                    if ($is_print == false):
                        $n = 11;
                        $tr .= '<td style="text-align:center;"><input type="checkbox" name="check_del[]" class="check_xray" id="checkbx' . $row['id'] . '" value="' . $row['id'] . '"/></td>';
                    endif;
                    $tr .= '</tr>';
                    $tr .= '<tr><td colspan="'.$n.'" style="page-break-inside:avoid;text-align:center; padding: 10px;">';
                    $treatment = explode(',', $row['treatment']);
                    $procedures = explode(',', $row['procedure']);
                    $start_date = explode(',', $row['date']);
                    $end_date = explode(',', $row['proc_end_date']);
                    if (!empty($procedures)) {
                        $i = 0;
                        $tr .= '<table class="table table-bordered" width="75%" style="text-align:left; ">';
                        $tr .= '<thead><tr class="info" style="color:black"><th>Treatment</th><th>procedure</th><th>Start date</th><th>End date</th></tr></thead>';
                        $tr .= '<tbody>';
                        foreach ($procedures as $r) {
                            $tr .= '<tr>';
                            $tr .= '<td>' . $treatment[$i] . '</td>';
                            $tr .= '<td>' . $r . '</td>';
                            $tr .= '<td>' . $start_date[$i] . '</td>';
                            $tr .= '<td>' . $end_date[$i] . '</td>';
                            $tr .= '</tr>';
                            $i++;
                        }
                        $tr .= '</tbody>';
                        $tr .= '</table>';
                    }
                    $tr .= '</td></tr>';
                }
                echo $tr;
            } else {
                echo '<tr><td colspan=8>No records found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>