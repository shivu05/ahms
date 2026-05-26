<?php

function bed_pdf_value($data, $key, $default = '') {
    return isset($data[$key]) && $data[$key] !== null && $data[$key] !== '' ? $data[$key] : $default;
}

function bed_pdf_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function bed_pdf_status($row) {
    return bed_pdf_value($row, 'bedstatus') === 'Available' ? 'Available' : 'Occupied';
}
?>
<?php if (empty($beds_list)) { ?>
    <h4 class="center red">No Records found</h4>
<?php } else { ?>
    <table class="table table-bordered" width="100%">
        <thead>
            <tr>
                <th width="4%">Sl.No</th>
                <th width="5%">Bed</th>
                <th width="7%">Bed Code</th>
                <th width="5%">Ward</th>
                <th width="7%">Type</th>
                <th width="12%">Department</th>
                <th width="6%">C.OPD</th>
                <th width="6%">C.IPD</th>
                <th width="16%">Patient Name</th>
                <th width="4%">Age</th>
                <th width="6%">Gender</th>
                <th width="7%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($beds_list as $row) {
                $count++;
                $opd_no = bed_pdf_value($row, 'current_opd_no', bed_pdf_value($row, 'OpdNo'));
                $ipd_no = bed_pdf_value($row, 'current_ipd_no', bed_pdf_value($row, 'IpNo'));
                ?>
                <tr>
                    <td align="center"><?php echo $count; ?></td>
                    <td align="center"><?php echo bed_pdf_escape(bed_pdf_value($row, 'bedno')); ?></td>
                    <td align="center"><?php echo bed_pdf_escape(bed_pdf_value($row, 'bed_short_code')); ?></td>
                    <td align="center"><?php echo bed_pdf_escape(bed_pdf_value($row, 'wardno')); ?></td>
                    <td><?php echo bed_pdf_escape(bed_pdf_value($row, 'bed_category')); ?></td>
                    <td><?php echo bed_pdf_escape(bed_pdf_value($row, 'department')); ?></td>
                    <td align="center"><?php echo bed_pdf_escape($opd_no); ?></td>
                    <td align="center"><?php echo bed_pdf_escape($ipd_no); ?></td>
                    <td><?php echo bed_pdf_escape(bed_pdf_value($row, 'patient_name')); ?></td>
                    <td align="center"><?php echo bed_pdf_escape(bed_pdf_value($row, 'patient_age')); ?></td>
                    <td><?php echo bed_pdf_escape(bed_pdf_value($row, 'patient_gender')); ?></td>
                    <td align="center"><?php echo bed_pdf_escape(bed_pdf_status($row)); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
