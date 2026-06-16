<?php
$patient = isset($patient) ? $patient : array();
$treatments = isset($treatments) ? $treatments : array();
$vitals = isset($vitals) ? array_reverse($vitals) : array();

$raw = function ($data, $key, $fallback = '') {
    return isset($data[$key]) && trim((string) $data[$key]) !== '' ? trim((string) $data[$key]) : $fallback;
};
$safe = function ($data, $key, $fallback = '') use ($raw) {
    return html_escape($raw($data, $key, $fallback));
};
$collect = function ($rows, $key) {
    $values = array();
    foreach ($rows as $row) {
        if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
            $values[] = trim((string) $row[$key]);
        }
    }
    return array_values(array_unique($values));
};
$clinical_text = function ($rows, $key) use ($collect) {
    $values = $collect($rows, $key);
    return empty($values) ? '' : nl2br(html_escape(implode("\n", $values)));
};

$full_name = trim($raw($patient, 'FirstName') . ' ' . $raw($patient, 'LastName'));
$full_name = $full_name !== '' ? $full_name : $raw($patient, 'FName');
$age = $raw($patient, 'patient_age', $raw($patient, 'Age'));
$gender = $raw($patient, 'patient_gender', $raw($patient, 'Gender'));
$department = format_department_name($raw($patient, 'department'));
$doa = format_discharge_date($raw($patient, 'DoAdmission')) . ($raw($patient, 'admit_time') !== '' ? ' ' . $raw($patient, 'admit_time') : '');
$dod = format_discharge_date($raw($patient, 'DoDischarge')) . ($raw($patient, 'discharge_time') !== '' ? ' ' . $raw($patient, 'discharge_time') : '');
$diagnosis = $raw($patient, 'diagnosis', implode("\n", $collect($treatments, 'diagnosis')));
$treatment = $clinical_text($treatments, 'Trtment');
$procedures = $clinical_text($treatments, 'procedures');
$clinical_notes = $clinical_text($treatments, 'notes');
$medicines = format_medicine_bullets(implode(',', $collect($treatments, 'Trtment')));
$advice = $raw($patient, 'DischargeNotes');
$consultant = $raw($patient, 'Doctor');

$section = 'background-color:#e5e7eb;border:1px solid #9ca3af;font-size:9pt;font-weight:bold;padding:3px 5px;margin-top:5px;';
$label = 'border:1px solid #b6b6b6;background-color:#f5f5f5;font-size:7.5pt;font-weight:bold;padding:3px 4px;vertical-align:top;';
$cell = 'border:1px solid #b6b6b6;font-size:8pt;padding:3px 4px;vertical-align:top;';
$clinical_label = 'border:1px solid #b6b6b6;background-color:#f5f5f5;font-size:7.5pt;font-weight:bold;padding:4px;width:22%;vertical-align:top;';
$clinical_cell = 'border:1px solid #b6b6b6;font-size:8pt;padding:4px;vertical-align:top;';
?>
<div style="font-family:sans-serif;color:#222222;font-size:8pt;">
    <div style="<?php echo $section; ?>">Patient Information</div>
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td style="<?php echo $label; ?>">OPD No</td><td style="<?php echo $cell; ?>"><?php echo $safe($patient, 'OpdNo'); ?></td>
            <td style="<?php echo $label; ?>">IPD No</td><td style="<?php echo $cell; ?>"><?php echo $safe($patient, 'IpNo'); ?></td>
            <td style="<?php echo $label; ?>">Patient Name</td><td style="<?php echo $cell; ?>"><?php echo html_escape($full_name); ?></td>
        </tr>
        <tr>
            <td style="<?php echo $label; ?>">Age</td><td style="<?php echo $cell; ?>"><?php echo html_escape($age); ?></td>
            <td style="<?php echo $label; ?>">Gender</td><td style="<?php echo $cell; ?>"><?php echo html_escape($gender); ?></td>
            <td style="<?php echo $label; ?>">Department</td><td style="<?php echo $cell; ?>"><?php echo html_escape($department); ?></td>
        </tr>
        <tr>
            <td style="<?php echo $label; ?>">Ward</td><td style="<?php echo $cell; ?>"><?php echo $safe($patient, 'WardNo'); ?></td>
            <td style="<?php echo $label; ?>">Bed</td><td style="<?php echo $cell; ?>"><?php echo $safe($patient, 'BedNo'); ?></td>
            <td style="<?php echo $label; ?>">Consultant Doctor</td><td style="<?php echo $cell; ?>"><?php echo html_escape($consultant); ?></td>
        </tr>
        <tr>
            <td style="<?php echo $label; ?>">DOA</td><td style="<?php echo $cell; ?>"><?php echo html_escape(trim($doa)); ?></td>
            <td style="<?php echo $label; ?>">DOD</td><td style="<?php echo $cell; ?>"><?php echo html_escape(trim($dod)); ?></td>
            <td style="<?php echo $label; ?>">Length of Stay</td><td style="<?php echo $cell; ?>"><?php echo $safe($patient, 'NofDays'); ?></td>
        </tr>
    </table>

    <div style="<?php echo $section; ?>">Clinical Summary</div>
    <table width="100%" style="border-collapse:collapse;">
        <tr><td style="<?php echo $clinical_label; ?>">Final Diagnosis</td><td style="<?php echo $clinical_cell; ?>"><?php echo nl2br(html_escape($diagnosis)); ?></td></tr>
        <tr><td style="<?php echo $clinical_label; ?>">Treatment Given</td><td style="<?php echo $clinical_cell; ?>"><?php echo $treatment; ?></td></tr>
        <tr><td style="<?php echo $clinical_label; ?>">Procedures Performed</td><td style="<?php echo $clinical_cell; ?>"><?php echo $procedures; ?></td></tr>
        <tr><td style="<?php echo $clinical_label; ?>">Investigations</td><td style="<?php echo $clinical_cell; ?>"></td></tr>
        <tr><td style="<?php echo $clinical_label; ?>">Clinical Notes</td><td style="<?php echo $clinical_cell; ?>"><?php echo $clinical_notes; ?></td></tr>
    </table>

    <div style="<?php echo $section; ?>">Discharge Medications</div>
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <th width="55%" style="<?php echo $label; ?>">Medicine Name</th>
            <th width="20%" style="<?php echo $label; ?>">Dosage</th>
            <th width="25%" style="<?php echo $label; ?>">Frequency</th>
        </tr>
        <tr>
            <td style="<?php echo $cell; ?>"><?php echo $medicines; ?></td>
            <td style="<?php echo $cell; ?>"></td>
            <td style="<?php echo $cell; ?>"></td>
        </tr>
    </table>

    <?php if (!empty($vitals)): ?>
        <div style="<?php echo $section; ?>">Vitals At Admission</div>
        <table width="100%" style="border-collapse:collapse;">
            <tr>
                <th style="<?php echo $label; ?>">Date</th>
                <th style="<?php echo $label; ?>">BP (mmHg)</th>
                <th style="<?php echo $label; ?>">Pulse (bpm)</th>
                <th style="<?php echo $label; ?>">Respiration (/min)</th>
                <th style="<?php echo $label; ?>">Temperature (°C)</th>
                <th style="<?php echo $label; ?>">SpO2 (%)</th>
                <th style="<?php echo $label; ?>">Weight (kg)</th>
            </tr>
            <?php foreach ($vitals as $vital): ?>
                <tr>
                    <td style="<?php echo $cell; ?>"><?php echo html_escape(format_discharge_date($raw($vital, 'date'))); ?></td>
                    <td style="<?php echo $cell; ?>"><?php echo $safe($vital, 'blood_pressure'); ?></td>
                    <td style="<?php echo $cell; ?>"><?php echo $safe($vital, 'pulse_rate'); ?></td>
                    <td style="<?php echo $cell; ?>"><?php echo $safe($vital, 'respiratory_rate'); ?></td>
                    <td style="<?php echo $cell; ?>"><?php echo $safe($vital, 'body_temperature'); ?></td>
                    <td style="<?php echo $cell; ?>"><?php echo $safe($vital, 'spo2'); ?></td>
                    <td style="<?php echo $cell; ?>"><?php echo $safe($vital, 'weight'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <div style="<?php echo $section; ?>">Advice At Discharge</div>
    <table width="100%" style="border-collapse:collapse;">
        <tr><td style="<?php echo $clinical_label; ?>">Advice At Discharge</td><td style="<?php echo $clinical_cell; ?>"><?php echo $advice !== '' ? nl2br(html_escape($advice)) : 'Not Recorded'; ?></td></tr>
        <tr><td style="<?php echo $clinical_label; ?>">Follow-up Instructions</td><td style="<?php echo $clinical_cell; ?>">Not Recorded</td></tr>
        <tr><td style="<?php echo $clinical_label; ?>">Review Date</td><td style="<?php echo $clinical_cell; ?>">Not Recorded</td></tr>
    </table>

    <table width="100%" style="margin-top:8px;">
        <tr>
            <td width="58%"></td>
            <td width="42%" align="center" style="font-size:8pt;padding-top:10px;">
                <div style="border-top:1px solid #333333;padding-top:3px;"><strong>Consultant Doctor</strong></div>
                <div><?php echo html_escape($consultant); ?></div>
                <div style="font-size:7pt;color:#555555;">Signature</div>
            </td>
        </tr>
    </table>
</div>
