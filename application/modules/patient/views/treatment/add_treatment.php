<style type="text/css">
    .accordion h1,
    .accordion a {
        color: #007b5e;
    }

    .accordion .btn-link {
        font-weight: 400;
        color: #007b5e;
        background-color: transparent;
        text-decoration: none !important;
        font-size: 12px;
        font-weight: bold;
        padding-left: 25px;
    }

    .accordion .card-body {
        border-top: 2px solid #007b5e;
        font-size: 12px !important;
    }

    .accordion .card-header .btn.collapsed .fa.main {
        display: none;
    }

    .accordion .card-header .btn .fa.main {
        background: #007b5e;
        padding: 10px 8px;
        color: #ffffff;
        width: 30px;
        height: 35px;
        position: absolute;
        left: -1px;
        top: 10px;
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
        display: block;
    }

    .treatment-screen .box {
        border-radius: 6px;
    }

    .treatment-screen .box-primary > .box-body {
        display: flex;
        flex-direction: column;
    }

    .treatment-back-row {
        order: 1;
    }

    .patient-overview-row {
        order: 2;
    }

    .vitals-panel {
        order: 3;
        margin-bottom: 10px;
    }

    .clinical-entry {
        order: 4;
        margin-bottom: 10px;
    }

    .patient-timeline-panel {
        order: 5;
    }

    .procedure-timeline-panel {
        order: 6;
    }

    .procedure-cards-row {
        order: 8;
    }

    .procedure-cards-divider {
        order: 7;
    }

    .admin_div {
        order: 9;
    }

    .action-bar {
        order: 10;
    }

    .sticky-patient-header {
        display: none;
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        z-index: 1030;
        padding: 8px 18px;
        background: #ffffff;
        border-bottom: 1px solid #dfe5e8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
    }

    .sticky-patient-header.is-visible {
        display: block;
    }

    .sticky-patient-inner {
        max-width: 1280px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        color: #263238;
        font-size: 13px;
    }

    .sticky-patient-name {
        font-size: 15px;
        font-weight: 700;
    }

    .patient-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .patient-summary img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #d7dde3;
    }

    .patient-summary-name {
        min-width: 180px;
    }

    .patient-summary-name h3 {
        margin: 0 0 4px;
        font-size: 22px;
        line-height: 1.2;
    }

    .patient-summary .label-primary {
        background-color: #0b79b7;
        font-size: 12px;
        padding: 5px 8px;
    }

    .patient-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(120px, 1fr));
        gap: 10px 18px;
        flex: 1;
        min-width: 320px;
    }

    .summary-label,
    .visit-label {
        display: block;
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .summary-value,
    .visit-value {
        font-weight: 600;
        color: #263238;
        word-break: break-word;
    }

    .diagnosis-chip {
        display: inline-block;
        max-width: 100%;
        padding: 4px 8px;
        border-radius: 4px;
        background: #e8f4fd;
        color: #0b5f8f;
        font-weight: 700;
        line-height: 1.3;
    }

    .section-title {
        margin: 0 0 12px;
        font-size: 16px;
        font-weight: 700;
        color: #263238;
    }

    .visit-history-panel .box-body {
        max-height: 430px;
        overflow: auto;
    }

    .visit-item {
        border-bottom: 1px solid #eef1f4;
        padding: 10px 0;
    }

    .visit-item:first-child {
        padding-top: 0;
    }

    .visit-item:last-child {
        border-bottom: 0;
    }

    .visit-diagnosis {
        margin: 4px 0;
        font-weight: 700;
        color: #374151;
    }

    .visit-treatment {
        color: #4b5563;
        font-size: 12px;
        line-height: 1.35;
    }

    .history-modal-table {
        font-size: 13px;
    }

    .history-modal-table td {
        vertical-align: top !important;
    }

    .history-treatment-cell {
        max-width: 420px;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .diagnosis-autocomplete {
        position: relative;
    }

    .diagnosis-suggestion-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1060;
        max-height: 230px;
        overflow-y: auto;
        margin-top: 2px;
        padding: 4px 0;
        background: #ffffff;
        border: 1px solid #cfd8dc;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .12);
    }

    .diagnosis-suggestion-item {
        display: block;
        width: 100%;
        padding: 8px 12px;
        color: #263238;
        text-align: left;
        background: #ffffff;
        border: 0;
    }

    .diagnosis-suggestion-item:hover,
    .diagnosis-suggestion-item.is-active {
        background: #eef6fb;
        color: #1565c0;
    }

    .diagnosis-entry-help {
        display: block;
        margin-top: 4px;
        color: #6b7280;
        font-size: 11px;
    }

    .procedure-timeline-panel .box-body {
        max-height: 320px;
        overflow: auto;
    }

    .procedure-filter-group {
        margin-bottom: 10px;
    }

    .procedure-timeline-item {
        border: 1px solid #e6ebef;
        border-left: 4px solid #90a4ae;
        border-radius: 4px;
        padding: 10px 12px;
        margin-bottom: 8px;
        background: #ffffff;
    }

    .procedure-timeline-item.status-completed {
        border-left-color: #00a65a;
    }

    .procedure-timeline-item.status-pending {
        border-left-color: #f39c12;
    }

    .procedure-timeline-item.status-cancelled {
        border-left-color: #dd4b39;
    }

    .procedure-timeline-title {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 6px;
    }

    .procedure-timeline-name {
        font-weight: 700;
        color: #263238;
    }

    .procedure-timeline-meta {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        color: #607d8b;
        font-size: 12px;
    }

    .procedure-timeline-remarks {
        margin-top: 6px;
        color: #4b5563;
        font-size: 12px;
        line-height: 1.35;
    }

    .vitals-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(90px, 1fr));
        gap: 10px;
    }

    .vitals-grid label {
        font-size: 12px;
        margin-bottom: 3px;
    }

    .vitals-grid .form-control {
        min-height: 36px;
    }

    .patient-timeline-panel .box-body {
        max-height: 240px;
        overflow: auto;
    }

    .clinical-timeline-item {
        display: grid;
        grid-template-columns: 96px 1fr;
        gap: 8px;
        border-left: 3px solid #90a4ae;
        padding: 5px 8px;
        margin-bottom: 4px;
        background: #fbfcfd;
    }

    .clinical-timeline-item.timeline-procedures {
        border-left-color: #00c0ef;
    }

    .clinical-timeline-item.timeline-vitals {
        border-left-color: #00a65a;
    }

    .clinical-timeline-item.timeline-ipd {
        border-left-color: #dd4b39;
    }

    .clinical-timeline-heading {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }

    .clinical-timeline-date {
        color: #607d8b;
        font-size: 12px;
        line-height: 22px;
    }

    .clinical-timeline-title {
        font-weight: 700;
        color: #263238;
    }

    .clinical-timeline-description {
        margin-top: 2px;
        color: #4b5563;
        font-size: 11px;
        line-height: 1.25;
    }

    .clinical-timeline-meta {
        color: #78909c;
        font-size: 11px;
        margin-top: 1px;
    }

    .bmi-badge {
        display: inline-block;
        margin-top: 4px;
        padding: 3px 7px;
        border-radius: 4px;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
    }

    .bmi-underweight {
        background: #3c8dbc;
    }

    .bmi-normal {
        background: #00a65a;
    }

    .bmi-overweight {
        background: #f39c12;
    }

    .bmi-obese {
        background: #dd4b39;
    }

    .procedure-card-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(150px, 1fr));
        gap: 10px;
        margin-bottom: 12px;
    }

    .procedure-card {
        position: relative;
        min-height: 116px;
        padding: 12px;
        border: 1px solid #dfe5e8;
        border-radius: 6px;
        background: #ffffff;
    }

    .procedure-card-icon {
        color: #1684b8;
        font-size: 20px;
        margin-bottom: 6px;
    }

    .procedure-card h4 {
        margin: 0 0 4px;
        font-size: 14px;
        font-weight: 700;
    }

    .procedure-card p {
        min-height: 30px;
        margin: 0 0 8px;
        color: #6b7280;
        font-size: 11px;
        line-height: 1.3;
    }

    .procedure-card-counts {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .clinical-entry .form-control {
        min-height: 42px;
    }

    .clinical-entry .box-body {
        padding-top: 10px;
    }

    .template-toolbar {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 6px;
    }

    .template-toolbar select {
        flex: 1;
    }

    .action-bar {
        border-top: 1px solid #edf0f2;
        padding: 15px;
        text-align: right;
        background: #fbfcfd;
    }

    @media (max-width: 991px) {
        .sticky-patient-header {
            top: 50px;
            padding: 7px 12px;
        }

        .sticky-patient-inner {
            gap: 8px;
        }

        .patient-summary-grid {
            grid-template-columns: repeat(2, minmax(120px, 1fr));
        }

        .vitals-grid,
        .procedure-card-grid {
            grid-template-columns: repeat(2, minmax(130px, 1fr));
        }
    }

    @media (max-width: 600px) {
        .sticky-patient-header {
            position: static;
            display: none !important;
        }

        .clinical-timeline-item {
            grid-template-columns: 1fr;
        }

        .vitals-grid,
        .procedure-card-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php
$panchakarma_markup = "";
if (!empty($pancha_procedures)) {
    foreach ($pancha_procedures as $proc) {
        $panchakarma_markup .= "<option value='" . $proc['proc_name'] . "'>" . $proc['proc_name'] . "</option>";
    }
}
$lab_categories_markup = "";
if (!empty($lab_categories)) {
    foreach ($lab_categories as $cat) {
        $lab_categories_markup .= '<option value="' . $cat['lab_cat_id'] . '">' . $cat['lab_cat_name'] . '</option>';
    }
}

$physiotherapy_markup = "";
if (!empty($physic_list)) {
    foreach ($physic_list as $row) {
        $physiotherapy_markup .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
    }
}

$other_proc_markup = "";
if (!empty($other_proc_list)) {
    foreach ($other_proc_list as $row) {
        $other_proc_markup .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
    }
}
$curr_dept = (isset($current_treatment['department']) && $current_treatment['department'] != '') ? $current_treatment['department'] : '';
$selected_doc = '';
if (!empty($doctors)) {
    $latest_data = NULL;
    if (!empty($treatment_details)) {
        end($treatment_details);
        $latest_data = key($treatment_details);
    }
    $selected_doc = (isset($current_treatment['AddedBy']) && $current_treatment['AddedBy'] != "") ? $current_treatment['AddedBy'] : '';
    if ($selected_doc === '' && $latest_data !== NULL && isset($treatment_details[$latest_data]['AddedBy'])) {
        $selected_doc = $treatment_details[$latest_data]['AddedBy'];
    }
}
$current_visit_date = isset($current_treatment['CameOn']) ? $current_treatment['CameOn'] : '';
$last_visit_date = isset($visit_summary['last_visit_date']) ? $visit_summary['last_visit_date'] : '';
$total_visits = isset($visit_summary['total_visits']) ? $visit_summary['total_visits'] : 0;
$current_diagnosis = '';
if (!empty($current_treatment['diagnosis'])) {
    $current_diagnosis = $current_treatment['diagnosis'];
} elseif (!empty($recent_visits)) {
    foreach ($recent_visits as $visit) {
        if (!empty($visit['diagnosis'])) {
            $current_diagnosis = $visit['diagnosis'];
            break;
        }
    }
} elseif (!empty($treatment_details)) {
    foreach (array_reverse($treatment_details) as $visit) {
        if (!empty($visit['diagnosis'])) {
            $current_diagnosis = $visit['diagnosis'];
            break;
        }
    }
}
$current_diagnosis_label = $current_diagnosis !== '' ? $current_diagnosis : 'Not Recorded';
$patient_full_name = trim($patient_details['FirstName'] . ' ' . $patient_details['LastName']);
$display_department = $curr_dept ? ucfirst(strtolower(str_replace('_', ' ', $curr_dept))) : '-';
$current_vitals = !empty($current_vitals) ? $current_vitals : array();
$procedure_status_counts = array();
if (!empty($procedure_timeline)) {
    foreach ($procedure_timeline as $procedure_item) {
        $type_key = strtolower($procedure_item['procedure_type']);
        if (!isset($procedure_status_counts[$type_key])) {
            $procedure_status_counts[$type_key] = array('pending' => 0, 'completed' => 0);
        }
        $count_key = ($procedure_item['status_group'] === 'completed') ? 'completed' : 'pending';
        $procedure_status_counts[$type_key][$count_key]++;
    }
}
$procedure_cards = array(
    array('type' => 'birth', 'name' => 'Birth', 'target' => '#birth', 'icon' => 'fa-child', 'description' => 'Delivery and birth register'),
    array('type' => 'ecg', 'name' => 'ECG', 'target' => '#ecg', 'icon' => 'fa-heartbeat', 'description' => 'Refer for ECG'),
    array('type' => 'usg', 'name' => 'USG', 'target' => '#usg', 'icon' => 'fa-stethoscope', 'description' => 'Refer for ultrasound'),
    array('type' => 'x-ray', 'name' => 'X-Ray', 'target' => '#xray', 'icon' => 'fa-picture-o', 'description' => 'Refer for radiology'),
    array('type' => 'ksharasutra', 'name' => 'Ksharasutra', 'target' => '#kshara', 'icon' => 'fa-medkit', 'description' => 'Refer for Ksharasutra'),
    array('type' => 'surgery', 'name' => 'Surgery', 'target' => '#surgery', 'icon' => 'fa-user-md', 'description' => 'Register surgery details'),
    array('type' => 'lab', 'name' => 'Laboratory', 'target' => '#lab', 'icon' => 'fa-flask', 'description' => 'Refer lab investigations'),
    array('type' => 'panchakarma', 'name' => 'Panchakarma', 'target' => '#panchakarma_treatment', 'icon' => 'fa-leaf', 'description' => 'Refer Panchakarma procedures'),
    array('type' => 'physiotherapy', 'name' => 'Physiotherapy', 'target' => '#physiotherapy', 'icon' => 'fa-wheelchair', 'description' => 'Refer physiotherapy'),
    array('type' => 'other procedure', 'name' => 'Other Procedures', 'target' => '#othertherapy', 'icon' => 'fa-plus-square', 'description' => 'Refer other procedures'),
    array('type' => 'kriyakalpa', 'name' => 'Kriyakalpa', 'target' => '#kriyakalpa', 'icon' => 'fa-eye', 'description' => 'Refer Kriyakalpa')
);
?>
<div class="sticky-patient-header" id="sticky_patient_header">
    <div class="sticky-patient-inner">
        <span class="sticky-patient-name"><?php echo html_escape($patient_full_name); ?> | OPD <?php echo html_escape($patient_details['OpdNo']); ?></span>
        <span><?php echo html_escape($patient_details['gender']); ?> | <?php echo html_escape($patient_details['Age']); ?>Y | <?php echo html_escape($display_department); ?></span>
        <span>Diagnosis: <span class="diagnosis-chip"><?php echo html_escape($current_diagnosis_label); ?></span></span>
    </div>
</div>
<div class="treatment-screen">
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="">
            <div class="">
                <form name="add_treatment_form" id="add_treatment_form" action="<?php echo base_url('patient/treatment/save'); ?>" method="POST">
                    <!--hiddden fields-->
                    <input type="hidden" name="treat_id" id="treat_id" value="<?php echo $treat_id; ?>" />
                    <input type="hidden" name="opd_no" id="opd_no" value="<?php echo $opd; ?>" />
                    <input type="hidden" name="department" id="department" value="<?php echo $curr_dept; ?>" />
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="clearfix treatment-back-row" style="margin-bottom: 12px;">
                                <a class="btn btn-info btn-sm pull-right" style="color:white;" href="<?php echo base_url('patient/treatment/show_patients'); ?>"><i class="fa fa-backward"></i> Back</a>
                            </div>
                            <div class="row patient-overview-row" id="patient_overview_row">
                                <div class="col-md-8 col-sm-12">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title section-title">Patient Summary</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="patient-summary">
                                                <img class="img-responsive" src="<?php echo base_url('assets/img/user_icon.png') ?>" alt="Patient photo" />
                                                <div class="patient-summary-name">
                                                    <h3><?php echo html_escape($patient_full_name); ?></h3>
                                                    <span class="label label-primary">OPD <?php echo html_escape($patient_details['OpdNo']); ?></span>
                                                </div>
                                                <div class="patient-summary-grid">
                                                    <div><span class="summary-label">Age</span><span class="summary-value"><?php echo html_escape($patient_details['Age']); ?></span></div>
                                                    <div><span class="summary-label">Gender</span><span class="summary-value"><?php echo html_escape($patient_details['gender']); ?></span></div>
                                                    <div><span class="summary-label">Place</span><span class="summary-value"><?php echo html_escape($patient_details['city']); ?></span></div>
                                                    <div><span class="summary-label">Department</span><span class="summary-value"><?php echo html_escape($display_department); ?></span></div>
                                                    <div><span class="summary-label">Doctor</span><span class="summary-value"><?php echo $selected_doc ? html_escape($selected_doc) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Current Visit</span><span class="summary-value"><?php echo $current_visit_date ? html_escape($current_visit_date) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Last Visit</span><span class="summary-value"><?php echo $last_visit_date ? html_escape($last_visit_date) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Total Visits</span><span class="summary-value"><?php echo html_escape($total_visits); ?></span></div>
                                                    <div><span class="summary-label">Current Diagnosis</span><span class="summary-value diagnosis-chip"><?php echo html_escape($current_diagnosis_label); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-solid visit-history-panel">
                                        <div class="box-header with-border">
                                            <h3 class="box-title section-title">Recent Visits</h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#full_history_modal" <?php echo empty($treatment_details) ? 'disabled="disabled"' : ''; ?>>View full history</button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <?php if (!empty($recent_visits)): ?>
                                                <?php foreach ($recent_visits as $visit): ?>
                                                    <div class="visit-item">
                                                        <span class="visit-label"><?php echo html_escape($visit['attndedon'] ?: $visit['CameOn']); ?></span>
                                                        <div class="visit-diagnosis"><?php echo html_escape($visit['diagnosis'] ?: '-'); ?></div>
                                                        <div class="visit-treatment"><?php echo html_escape(strlen($visit['Trtment']) > 120 ? substr($visit['Trtment'], 0, 120) . '...' : $visit['Trtment']); ?></div>
                                                        <div class="text-muted" style="font-size: 12px; margin-top: 4px;">
                                                            <?php echo html_escape($visit['attndedby'] ?: $visit['AddedBy']); ?> &middot;
                                                            <?php echo html_escape(ucfirst(strtolower(str_replace('_', ' ', $visit['department'])))); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-muted">No previous visits recorded.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-solid procedure-timeline-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title section-title">Procedure Timeline</h3>
                                </div>
                                <div class="box-body">
                                    <div class="btn-group btn-group-xs procedure-filter-group" role="group" aria-label="Procedure timeline filters">
                                        <button type="button" class="btn btn-primary procedure-filter active" data-filter="all">All</button>
                                        <button type="button" class="btn btn-default procedure-filter" data-filter="pending">Pending/Referred</button>
                                        <button type="button" class="btn btn-default procedure-filter" data-filter="completed">Completed</button>
                                        <button type="button" class="btn btn-default procedure-filter" data-filter="panchakarma">Panchakarma</button>
                                        <button type="button" class="btn btn-default procedure-filter" data-filter="lab">Lab/Investigation</button>
                                    </div>

                                    <?php if (!empty($procedure_timeline)): ?>
                                        <div id="procedure_timeline_list">
                                            <?php foreach ($procedure_timeline as $procedure): ?>
                                                <?php
                                                $status_group = isset($procedure['status_group']) ? $procedure['status_group'] : 'pending';
                                                $status_class = ($status_group === 'completed') ? 'success' : (($status_group === 'cancelled') ? 'danger' : 'warning');
                                                $department_name = !empty($procedure['department_name']) ? ucfirst(strtolower(str_replace('_', ' ', $procedure['department_name']))) : '';
                                                ?>
                                                <div class="procedure-timeline-item status-<?php echo html_escape($status_group); ?>" data-status-group="<?php echo html_escape($status_group); ?>" data-category="<?php echo html_escape($procedure['category']); ?>">
                                                    <div class="procedure-timeline-title">
                                                        <span class="label label-info"><?php echo html_escape($procedure['procedure_type']); ?></span>
                                                        <span class="procedure-timeline-name"><?php echo html_escape($procedure['procedure_name']); ?></span>
                                                        <span class="label label-<?php echo html_escape($status_class); ?>"><?php echo html_escape($procedure['status']); ?></span>
                                                    </div>
                                                    <div class="procedure-timeline-meta">
                                                        <span><strong>Referred:</strong> <?php echo $procedure['referred_date'] ? html_escape($procedure['referred_date']) : '-'; ?></span>
                                                        <?php if (!empty($procedure['completed_date'])): ?>
                                                            <span><strong>Completed:</strong> <?php echo html_escape($procedure['completed_date']); ?></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($procedure['doctor_name'])): ?>
                                                            <span><strong>Doctor:</strong> <?php echo html_escape($procedure['doctor_name']); ?></span>
                                                        <?php endif; ?>
                                                        <?php if ($department_name !== ''): ?>
                                                            <span><strong>Department:</strong> <?php echo html_escape($department_name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if (!empty($procedure['remarks'])): ?>
                                                        <div class="procedure-timeline-remarks"><?php echo html_escape($procedure['remarks']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="text-muted" id="procedure_timeline_filter_empty" style="display: none;">No procedures match this filter.</p>
                                    <?php else: ?>
                                        <p class="text-muted">No procedures referred yet for this patient.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="box box-solid vitals-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title section-title">Vitals</h3>
                                </div>
                                <div class="box-body">
                                    <div class="vitals-grid">
                                        <div>
                                            <label for="vital_bp">BP</label>
                                            <input type="text" class="form-control" name="vital_bp" id="vital_bp" maxlength="20" placeholder="120/80" value="<?php echo html_escape(isset($current_vitals['blood_pressure']) ? $current_vitals['blood_pressure'] : ''); ?>" />
                                        </div>
                                        <div>
                                            <label for="vital_pulse">Pulse</label>
                                            <input type="number" class="form-control" name="vital_pulse" id="vital_pulse" min="0" step="1" value="<?php echo html_escape(isset($current_vitals['pulse_rate']) ? $current_vitals['pulse_rate'] : ''); ?>" />
                                        </div>
                                        <div>
                                            <label for="vital_weight">Weight (kg)</label>
                                            <input type="number" class="form-control vital-bmi-source" name="vital_weight" id="vital_weight" min="0" step="0.01" value="<?php echo html_escape(isset($current_vitals['weight']) ? $current_vitals['weight'] : ''); ?>" />
                                        </div>
                                        <div>
                                            <label for="vital_height">Height (cm)</label>
                                            <input type="number" class="form-control vital-bmi-source" name="vital_height" id="vital_height" min="0" step="0.01" value="<?php echo html_escape(isset($current_vitals['height']) ? $current_vitals['height'] : ''); ?>" />
                                        </div>
                                        <div>
                                            <label for="vital_bmi">BMI</label>
                                            <input type="number" class="form-control" name="vital_bmi" id="vital_bmi" step="0.01" readonly="readonly" value="<?php echo html_escape(isset($current_vitals['bmi']) ? $current_vitals['bmi'] : ''); ?>" />
                                            <span class="bmi-badge" id="vital_bmi_badge" style="display: none;"></span>
                                        </div>
                                        <div>
                                            <label for="vital_temperature">Temperature</label>
                                            <input type="number" class="form-control" name="vital_temperature" id="vital_temperature" step="0.01" value="<?php echo html_escape(isset($current_vitals['body_temperature']) ? $current_vitals['body_temperature'] : ''); ?>" />
                                        </div>
                                        <div>
                                            <label for="vital_spo2">SpO2 (%)</label>
                                            <input type="number" class="form-control" name="vital_spo2" id="vital_spo2" min="0" max="100" step="1" value="<?php echo html_escape(isset($current_vitals['spo2']) ? $current_vitals['spo2'] : ''); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-solid patient-timeline-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title section-title">Patient Timeline</h3>
                                </div>
                                <div class="box-body">
                                    <div class="btn-group btn-group-xs procedure-filter-group" role="group" aria-label="Patient timeline filters">
                                        <button type="button" class="btn btn-primary clinical-timeline-filter active" data-filter="all">All</button>
                                        <button type="button" class="btn btn-default clinical-timeline-filter" data-filter="visits">Visits</button>
                                        <button type="button" class="btn btn-default clinical-timeline-filter" data-filter="vitals">Vitals</button>
                                        <button type="button" class="btn btn-default clinical-timeline-filter" data-filter="prescriptions">Prescriptions</button>
                                        <button type="button" class="btn btn-default clinical-timeline-filter" data-filter="procedures">Procedures</button>
                                        <button type="button" class="btn btn-default clinical-timeline-filter" data-filter="ipd">IPD</button>
                                    </div>
                                    <?php if (!empty($clinical_timeline)): ?>
                                        <div id="clinical_timeline_list">
                                            <?php foreach ($clinical_timeline as $event): ?>
                                                <?php $event_department = !empty($event['department_name']) ? ucfirst(strtolower(str_replace('_', ' ', $event['department_name']))) : ''; ?>
                                                <div class="clinical-timeline-item timeline-<?php echo html_escape($event['category']); ?>" data-category="<?php echo html_escape($event['category']); ?>">
                                                    <div class="clinical-timeline-date"><?php echo $event['event_date'] ? html_escape($event['event_date']) : '-'; ?></div>
                                                    <div>
                                                        <div class="clinical-timeline-heading">
                                                            <span class="label label-info"><?php echo html_escape($event['event_type']); ?></span>
                                                            <span class="clinical-timeline-title"><?php echo html_escape($event['event_title']); ?></span>
                                                            <span class="label label-default"><?php echo html_escape($event['status']); ?></span>
                                                        </div>
                                                        <?php if (!empty($event['description'])): ?>
                                                            <div class="clinical-timeline-description"><?php echo html_escape(strlen($event['description']) > 120 ? substr($event['description'], 0, 120) . '...' : $event['description']); ?></div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($event['doctor_name']) || $event_department !== ''): ?>
                                                            <div class="clinical-timeline-meta">
                                                                <?php echo html_escape($event['doctor_name']); ?>
                                                                <?php echo (!empty($event['doctor_name']) && $event_department !== '') ? ' &middot; ' : ''; ?>
                                                                <?php echo html_escape($event_department); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="text-muted" id="clinical_timeline_filter_empty" style="display: none;">No timeline records match this filter.</p>
                                    <?php else: ?>
                                        <p class="text-muted">No timeline records found for this patient.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="box box-solid clinical-entry">
                                <div class="box-header with-border">
                                    <h3 class="box-title section-title">Clinical Entry</h3>
                                </div>
                                <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <input type="checkbox" name="add_prescription" id="add_prescription" style="margin-top: 7px;" />
                                    <label>Add prescription: <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label>Complaints: <span class="text-danger">*</span></label>
                                    <!--<select class="form-control select2 prescription_inputs" name="complaints[]" id="complaints" multiple="multiple" data-placeholder="Choose complaints">
                                        <option value="">choose complaints</option>
                                    <?php
                                    if (!empty($complaints)) {
                                        foreach ($complaints as $row) {
                                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    </select>-->
                                    <textarea class="form-control prescription_inputs" onkeyup="this.value = this.value.toUpperCase();" name="complaints" id="complaints"></textarea>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label>Diagnosis: <span class="text-danger">*</span></label>
                                    <!--<select class="form-control select2 prescription_inputs" name="diagnosis[]" id="diagnosis" multiple="multiple" data-placeholder="Choose diagnosis">
                                        <option value="">choose diagnosis</option>
                                    <?php
                                    if (!empty($diagnosis)) {
                                        foreach ($diagnosis as $row) {
                                            echo '<option value="' . $row['diagnosis_name'] . '">' . $row['diagnosis_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    </select>-->
                                    <input type="hidden" name="diagnosis_master_id" id="diagnosis_master_id" value="" />
                                    <div class="diagnosis-autocomplete">
                                        <input type="text" class="form-control prescription_inputs" name="diagnosis" id="diagnosis" autocomplete="off" placeholder="Type diagnoses separated by commas" aria-autocomplete="list" aria-controls="diagnosis_suggestion_menu" />
                                        <div class="diagnosis-suggestion-menu" id="diagnosis_suggestion_menu" role="listbox"></div>
                                    </div>
                                    <small class="diagnosis-entry-help">Enter multiple diagnoses separated by commas. Custom diagnoses are allowed.</small>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label>Treatment: <span class="text-danger">*</span></label>
                                    <div class="template-toolbar">
                                        <select class="form-control prescription_inputs" id="treatment_template" <?php echo empty($treatment_templates) ? 'disabled="disabled"' : ''; ?>>
                                            <option value="">Use Template</option>
                                            <?php if (!empty($treatment_templates)): ?>
                                                <?php foreach ($treatment_templates as $template): ?>
                                                    <option value="<?php echo html_escape($template['id']); ?>" data-treatment="<?php echo html_escape($template['treatment_text']); ?>">
                                                        <?php echo html_escape($template['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <button type="button" class="btn btn-default btn-sm prescription_inputs" id="apply_template" <?php echo empty($treatment_templates) ? 'disabled="disabled"' : ''; ?>>
                                            <i class="fa fa-magic"></i> Apply
                                        </button>
                                    </div>
                                    <!-- <select class="form-control select2 prescription_inputs" name="treatment[]" id="treatment" multiple="multiple" data-placeholder="Choose Medicines">
                                        <option value="">choose medicine</option>
                                    <?php
                                    if (!empty($medicines)) {
                                        foreach ($medicines as $row) {
                                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    </select>-->
                                    <textarea class="form-control prescription_inputs" onkeyup="this.value = this.value.toUpperCase();" name="treatment" id="treatment"></textarea>
                                </div>
                                <!--<div class="col-md-6 col-sm-12">
                                    <label>Panchakarma procedures: <span class="text-danger">*</span></label>
                                    <textarea class="form-control prescription_inputs" name="panch_procedures" id="panch_procedures"></textarea>
                                </div-->
                                <div class="col-md-6 col-sm-12">
                                    <label>Notes:</label>
                                    <textarea class="form-control prescription_inputs" name="notes" id="notes"></textarea>
                                </div>
                                <!--<div class="col-md-2 col-sm12">
                                    <label>Days</label>
                                    <input type="text" name="panch_days" id="panch_days" placeholder="Days" class="form-control" value="7"/>
                                </div>-->
                            </div>
                            <hr />
                            <!-- <div class="row">
                                 <div class="col-md-4">
                                     <label>Medicine name: <span class="text-danger">*</span></label>
                                     <select name="medicine" id="medicine" class="form-control  required chosen-select">
                                         <option>Choose medicine</option>

                            <?php
                            if (!empty($medicines)) {
                                foreach ($medicines as $medicine) {
                                    echo '<option value="' . $medicine['id'] . '">' . $medicine['name'] . '</option>';
                                }
                            }
                            ?>
                                     </select>
                                 </div>
                                 <div class="col-md-4">
                                     <label>When to take: <span class="text-danger">*</span></label>
                                     <select name="med_freq" id="med_freq" class="form-control  required chosen-select">
                                         <option>Choose frequency</option>
                            <?php
                            if (!empty($med_freqs)) {
                                foreach ($med_freqs as $frq) {
                                    echo '<option value="' . $frq['med_freq'] . '">' . $frq['med_freq'] . '</option>';
                                }
                            }
                            ?>
                                     </select>
                                 </div>
                                 <div class="col-md-2">
                                     <label>Days: <span class="text-danger">*</span></label>
                                     <input type="text" name="days" id="days" class="form-control required numbers-only" placeholder="Days" />
                                 </div>
                                 <div class="col-md-2">
                                     <label>Before food: <span class="text-danger">*</span></label>
                                     <input type="checkbox" name="before_food" id="before_food" class="form-control required numbers-only" />
                                 </div>
                             </div>
                             <hr/>-->
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <label>Doctor: </label>
                                    <!--<input type="text" class="form-control" name="doctor_name" id="doctor_name" value="<?= $current_treatment['AddedBy']; ?>" readonly="readonly"/>-->
                                    <select class="form-control " name="doctor_name" id="doctor_name" readonly="readonly">
                                        <option value="">Choose doctor</option>
                                        <?php
                                        if (!empty($doctors)) {
                                            foreach ($doctors as $doctor) {
                                                $selected = (strtolower($selected_doc) == strtolower($doctor['user_name'])) ? 'selected="selected"' : '';
                                                echo '<option value="' . $doctor['user_name'] . '" ' . $selected . '>' . $doctor['user_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <label>Treated date: </label>
                                    <input type="text" name="attened_date" id="attened_date" class="form-control date_picker prescription_inputs" value="<?php echo date('Y-m-d') ?>" />
                                </div>
                            </div><!-- end of trearment div-->
                                </div>
                            </div>

                            <!-- Tests -->
                            <hr class="procedure-cards-divider" />
                            <div class="row procedure-cards-row" style="margin-bottom: 2rem;">
                                <div class="col-md-12">
                                    <h3>Procedures:</h3>
                                    <div class="procedure-card-grid">
                                        <?php foreach ($procedure_cards as $card): ?>
                                            <?php
                                            $card_counts = isset($procedure_status_counts[$card['type']]) ? $procedure_status_counts[$card['type']] : array('pending' => 0, 'completed' => 0);
                                            ?>
                                            <div class="procedure-card">
                                                <div class="procedure-card-icon"><i class="fa <?php echo html_escape($card['icon']); ?>"></i></div>
                                                <h4><?php echo html_escape($card['name']); ?></h4>
                                                <p><?php echo html_escape($card['description']); ?></p>
                                                <div class="procedure-card-counts">
                                                    <span class="label label-warning">Pending <?php echo (int) $card_counts['pending']; ?></span>
                                                    <span class="label label-success">Completed <?php echo (int) $card_counts['completed']; ?></span>
                                                </div>
                                                <button type="button" class="btn btn-default btn-xs procedure-card-open" data-target="<?php echo html_escape($card['target']); ?>">
                                                    <i class="fa fa-folder-open"></i> Open
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#classic_procedure_forms" aria-expanded="false" aria-controls="classic_procedure_forms">
                                        <i class="fa fa-list"></i> Classic Procedure Forms
                                    </button>
                                    <div class="collapse" id="classic_procedure_forms" style="margin-top: 12px;">
                                    <div class="bs-component">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a class="nav-link disabled" data-toggle="tab" href="#birth" title="disabled">Birth</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ecg">ECG</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#usg">USG</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#xray">X-Ray</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kshara">Ksharasutra</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#surgery">Surgery</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lab">Laboratory</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#panchakarma_treatment">Panchakarma</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#physiotherapy">Physiotherapy</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#othertherapy">Other Procedures</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kriyakalpa">Kriyakalpa</a></li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade" id="birth">
                                                <div id="Birth">
                                                    <h5 style="margin-top: 2%;"><input type="checkbox" name="birth_check" id="birth_check" /> Birth Register</h5>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="delivery">Details of delivery:</label>
                                                            <div class="controls">
                                                                <textarea id="delivery" type="text" name="delivery" placeholder="Delivery details" class="form-control birth_input"></textarea>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="birthdate">Birth Date/Time Of Baby:</label>
                                                            <div class="controls">
                                                                <input id="birthdate" type="text" name="birthdate" class="form-control birth_input date_picker" placeholder="Birth date" autocomplete="off">
                                                                <br>
                                                                <input id="birthtime" placeholder="Time __:__" type="text" name="birthtime" class="form-control birth_input" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="weight">Baby weight:</label>
                                                            <div class="controls">
                                                                <input id="weight" type="text" name="weight" class="form-control birth_input" placeholder="Baby weight">
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="weight">Baby Gender:</label>
                                                            <div class="controls">
                                                                <select class="form-control birth_input" id="babygender" name="babygender">
                                                                    <option value="">Choose one</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                    <option value="others">others</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="babyblood">Baby Blood Group:</label>
                                                            <div class="controls">
                                                                <input id="babyblood" type="text" name="babyblood" class="form-control birth_input" placeholder="Baby blood group">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="motherblood">Mother Blood Group:</label>
                                                            <div class="controls">
                                                                <input id="motherblood" type="text" name="motherblood" placeholder="Mother blood group" class="form-control birth_input">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="fathername">Father Name:</label>
                                                            <div class="controls">
                                                                <input id="fathername" type="text" name="fathername" placeholder="Father name" class="form-control birth_input" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgeonname">Name Of Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="surgeonname" value="" type="text" name="surgeonname" placeholder="surgeon name" class="form-control birth_input" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anaesthetic">Name Of Anesthetic:</label>
                                                            <div class="controls">
                                                                <input id="anaesthetic" type="text" name="anaesthetic" placeholder="Anesthetic" class="form-control birth_input" />
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anesthesia_type">Type of Anesthesia:</label>
                                                            <div class="controls">
                                                                <select class="form-control birth_input" name="anesthesia_type" id="anesthesia_type">
                                                                    <option value="">Choose one</option>
                                                                    <option value="LOCAL">LOCAL</option>
                                                                    <option value="SPINAL">SPINAL</option>
                                                                    <option value="GENERAL">GENERAL</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anaesthetic">Delivery Type:</label>
                                                            <div class="controls">
                                                                <select class="form-control birth_input" name="deliverytype" id="deliverytype">
                                                                    <option value="normal">Normal</option>
                                                                    <option value="LSCS">LSCS</option>
                                                                    <option value="cesarean">cesarean</option>
                                                                    <option value="Forceps">Forceps</option>
                                                                    <option value="Vacuum">Vacuum</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="ecg">
                                                <div id="ECG">
                                                    <h5 style="margin-top: 2%;"><input type="checkbox" name="ecg_check" id="ecg_check" /> ECG Register</h5>
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="ecgdocname">Referred Doctor Name:</label>
                                                        <div class="controls">
                                                            <input id="ecgdocname" value="" type="text" name="ecgdocname" class="form-control ecg_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="ecgdate">Referred date:</label>
                                                        <div class="controls">
                                                            <input id="ecgdate" type="text" name="ecgdate" class="form-control date_picker ecg_inputs" placeholder="Enter ECG referred date" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="usg">
                                                <div id="USG" style="display: block;margin-top: 2%;">
                                                    <h5><input type="checkbox" name="usg_check" id="usg_check" /> USG Register</h5>
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="usgdocname">Referred Doctor Name:</label>
                                                        <div class="controls">
                                                            <input id="usgdocname" value="" type="text" name="usgdocname" class="form-control usg_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="usgdate">Referred date:</label>
                                                        <div class="controls">
                                                            <input id="usgdate" type="text" name="usgdate" class="form-control date_picker usg_inputs" placeholder="Enter USG referred date" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="xray">
                                                <div id="Xray" style="margin-top: 2%">
                                                    <h5><input type="checkbox" name="xray_check" id="xray_check" /> X-ray Register</h5>
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="xraydocname">Referred Doctor Name:</label>
                                                        <div class="controls">
                                                            <input id="xraydocname" value="" type="text" name="xraydocname" class="form-control xray_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="partxray">Part of X-Ray:</label>
                                                        <div class="controls">
                                                            <input id="partxray" type="text" name="partxray" class="form-control xray_inputs" placeholder="Enter X-Ray Part" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <!--<div class="control-group col-4">
                                                        <label class="control-label" for="filmsize">Film Size:</label>
                                                        <div class="controls">
                                                            <input id="filmsize" type="text" name="filmsize" class="form-control xray_inputs" placeholder="Enter Film Size" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    <!--</div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="xraydate">Referred date:</label>
                                                        <div class="controls">
                                                            <input id="xraydate" type="text" name="xraydate" class="form-control date_picker xray_inputs" placeholder="Enter referred date" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kshara">
                                                <div id="Kshara" style="margin-top: 2%">
                                                    <h4><input type="checkbox" name="kshara_check" id="kshara_check" /> Ksharasutra Register</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharatype">Type of Ksharasutra:</label>
                                                            <div class="controls">
                                                                <input id="ksharatype" type="text" name="ksharatype" class="form-control kshara_inputs" placeholder="Enter Type of ksharasutra" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharatype">Name of Ksharasutra:</label>
                                                            <div class="controls">
                                                                <input id="ksharaname" type="text" name="ksharaname" class="form-control kshara_inputs" placeholder="Enter Name of ksharasutra" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharasurgeonname">Name Of Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="ksharasurgeonname" value="" type="text" name="ksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharasurgeonname">Name Of Asst. Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="assksharasurgeonname" type="text" name="assksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharaanaesthetist">Name Of Anesthetist:</label>
                                                            <div class="controls">
                                                                <input id="ksharaanaesthetist" type="text" name="ksharaanaesthetist" class="form-control kshara_inputs" placeholder="Enter Name " autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anesthesia_type">Type of Anesthesia:</label>
                                                            <div class="controls">
                                                                <select class="form-control kshara_inputs" name="anesthesia_type" id="anesthesia_type">
                                                                    <option value="">Choose one</option>
                                                                    <option value="LOCAL">LOCAL</option>
                                                                    <option value="SPINAL">SPINAL</option>
                                                                    <option value="GENERAL">GENERAL</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharadate">Ksharasutra Date:</label>
                                                            <div class="controls">
                                                                <input id="ksharadate" type="text" name="ksharadate" class="form-control date_picker kshara_inputs" placeholder="Enter Date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="surgery">
                                                <div id="Surgery" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="surgery_check" id="surgery_check" /> Surgery Register</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="SurgeryDocname">Name of Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="SurgeryDocname" value="" type="text" name="SurgeryDocname" class="form-control surgery_inputs" placeholder="Enter Surgeon Name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="AssSurgeryDocname">Name of Assistant Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="AssSurgeryDocname" type="text" name="AssSurgeryDocname" class="form-control surgery_inputs" placeholder="Enter Assistant Surgeon Name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surganaesthetist">Name of Anesthetist:</label>
                                                            <div class="controls">
                                                                <input id="surganaesthetist" type="text" name="surganaesthetist" class="form-control surgery_inputs" placeholder="Enter Anaesthetist" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anesthesia_type">Type of Anesthesia:</label>
                                                            <div class="controls">
                                                                <select class="form-control surgery_inputs" name="anesthesia_type" id="anesthesia_type">
                                                                    <option value="">Choose one</option>
                                                                    <option value="LOCAL">LOCAL</option>
                                                                    <option value="SPINAL">SPINAL</option>
                                                                    <option value="GENERAL">GENERAL</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgeryname">Name of surgery:</label>
                                                            <div class="controls">
                                                                <input id="surgeryname" type="text" name="surgeryname" class="form-control surgery_inputs" placeholder="Enter Name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgerytype">Type of surgery:</label>
                                                            <div class="controls">
                                                                <input id="surgerytype" type="text" name="surgerytype" class="form-control surgery_inputs" placeholder="Enter Type" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgerydate">Date of surgery:</label>
                                                            <div class="controls">
                                                                <input id="surgerydate" type="text" name="surgerydate" class="form-control date_picker surgery_inputs" placeholder="Enter Date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="lab">
                                                <div id="Lab" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="lab_check" id="lab_check" /> Laboratory Register</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="labdocname">Referred Doctor Name:</label>
                                                            <div class="controls">
                                                                <input id="labdocname" required value="" type="text" name="labdocname" class="form-control lab_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                                <input type="hidden" name="tab_lab_row_count" id="tab_lab_row_count" value="1" />
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="testname">Referred Date:</label>
                                                            <div class="controls">
                                                                <input id="labdate" type="text" required name="testdate" class="form-control date_picker lab_inputs required" placeholder="Enter Date" autocomplete="off">
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>

                                                    <div class="col-12" id="lab_cats">
                                                        <table class="table table-borderless" id="lab_cat_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Category</th>
                                                                    <th>Test</th>
                                                                    <th>Investigations</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <select name="lab_category[]" id="lab_category" class="form-control lab_category chosen-select" data-placeholder="Select Category">
                                                                            <option value="">Choose Category</option>
                                                                            <?= $lab_categories_markup ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="lab_test[]" id="lab_test" class="form-control lab_test chosen-select" data-placeholder="Choose test"></select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="lab_investigations[]" id="lab_investigations" class="lab_investigations form-control chosen-select multiple" multiple data-placeholder="Choose investigation"></select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="2">&nbsp;</td>
                                                                    <td class="pull-right">
                                                                        <button type="button" name="add_lab_row" id="add_lab_row" class="btn btn-sm btn-primary">
                                                                            <i class="fa fa-plus"></i> Add more</button>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="panchakarma_treatment">
                                                <div id="panchakarma_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="panchakarma_check" id="panchakarma_check" /> Refer for Panchakarma</h4>
                                                    <input type="hidden" name="tab_row_count" id="tab_row_count" value="1" />
                                                    <table class="table table-borderless" id="panchakarma_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30% !important;">Procedure</th>
                                                                <th style="width: 30% !important;">Sub procedure</th>
                                                                <th style="width: 20% !important;">Start date</th>
                                                                <th style="width: 20% !important;">End date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control chosen-select pancha_procedure pancha_input" required name="pancha_procedure[]" id="pancha_procedure_1">
                                                                        <option value="">Choose procedure</option>
                                                                        <?= $panchakarma_markup ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control pancha_sub_procedure chosen-select pancha_input" required name="pancha_sub_procedure[]" id="pancha_sub_procedure">
                                                                        <option value="">Choose sub procedure</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" class="form-control date_picker pancha_input required" required name="pancha_proc_start_date[]" id="pancha_proc_start_date" /></td>
                                                                <td><input type="text" class="form-control date_picker pancha_input required" required name="pancha_proc_end_date[]" id="pancha_proc_end_date" /></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3">&nbsp;</td>
                                                                <td class="pull-right">
                                                                    <button type="button" name="" id="add_row" class="btn btn-sm btn-primary">
                                                                        <i class="fa fa-plus"></i> Add more procedure</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="physiotherapy">
                                                <div id="physiotherapy_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="physiotherapy_check" id="physiotherapy_check" /> Refer for Physiotherapy</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="physic_name">Physiotherapy name:</label>
                                                            <div class="controls">
                                                                <select id="physic_name" value="" type="text" name="physic_name" class="chosen-select form-control physic_inputs">
                                                                    <option value="">Choose</option>
                                                                    <?= $physiotherapy_markup ?>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="start_date">Start date:</label>
                                                            <div class="controls">
                                                                <input id="physic_date" type="text" name="start_date" class="form-control date_picker physic_inputs" placeholder="Enter Start date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="end_date">End date:</label>
                                                            <div class="controls">
                                                                <input id="physic_date" type="text" name="end_date" class="form-control date_picker physic_inputs" placeholder="Enter End date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="physic_doc">Physician name:</label>
                                                            <div class="controls">
                                                                <input id="physic_doc" type="text" name="physic_doc" class="form-control physic_inputs" placeholder="Enter Doctor name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="othertherapy">
                                                <div id="physiotherapy_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="other_proc_check" id="other_proc_check" /> Refer for Other Procedures</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="other_proc_name">Procedure name:</label>
                                                            <div class="controls">
                                                                <select id="other_proc_name" value="" type="text" name="other_proc_name" class="chosen-select form-control othr_proc_inputs">
                                                                    <option value="">Choose</option>
                                                                    <?= $other_proc_markup ?>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="other_start_date">Start date:</label>
                                                            <div class="controls">
                                                                <input id="other_start_date" type="text" name="other_start_date" class="form-control date_picker othr_proc_inputs" placeholder="Enter Start date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="other_end_date">End date:</label>
                                                            <div class="controls">
                                                                <input id="other_end_date" type="text" name="other_end_date" class="form-control date_picker othr_proc_inputs" placeholder="Enter End date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="oth_proc_doc">Physician name:</label>
                                                            <div class="controls">
                                                                <input id="oth_proc_doc" type="text" name="oth_proc_doc" class="form-control othr_proc_inputs" placeholder="Enter Doctor name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kriyakalpa">
                                                <div id="kriyakalpa_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="kriyakalpa_check" id="kriyakalpa_check" /> Refer for Kriyakalpa</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="kriya_procedures">Procedures:</label>
                                                            <div class="controls">
                                                                <input id="kriya_procedures" type="text" name="kriya_procedures" class="form-control kriya_inputs" placeholder="Enter Procedures" />
                                                                <p class="help-block">Enter comma separated data</p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="kriya_start_date">Date:</label>
                                                            <div class="controls">
                                                                <input id="kriya_start_date" type="text" name="kriya_start_date" class="form-control date_picker kriya_inputs" placeholder="Enter Date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!--end of tests-->
                            <div class="admin_div">
                                <?php if (isset($ipd_status) && $ipd_status !== false): ?>
                                    <div class="alert alert-warning">
                                        Patient is already admitted and IPD: <?php echo $ipd_status; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="toggle-flip">
                                    <label>
                                        <input type="checkbox" name="admit" id="admit"> Admit
                                    </label>
                                </div>
                                <?php
                                $bed_select = '';
                                if (!empty($wards)) {
                                    foreach ($wards as $ward) {
                                        $bed_select .= '<optgroup label="' . html_escape($ward['department']) . '">';
                                        $bedstatus = explode(',', $ward['bedstatus']);
                                        if (!empty($bedstatus)) {
                                            foreach ($bedstatus as $bed) {
                                                $bed_stat = array_pad(explode('#', $bed, 3), 3, '');
                                                $bed_no = trim($bed_stat[0]);
                                                if ($bed_no === '') {
                                                    continue;
                                                }

                                                $bed_status = strtolower(trim($bed_stat[1]));
                                                $bed_category = trim($bed_stat[2]);
                                                $bed_label = $bed_no;
                                                if ($bed_category !== '') {
                                                    $bed_label .= ' (' . ucwords(str_replace('_', ' ', $bed_category)) . ')';
                                                }

                                                $is_disabled = ($bed_status === 'not available') ? 'disabled="disabled" style="color:red;"' : '';
                                                $bed_select .= '<option value="' . html_escape($bed_no) . '" ' . $is_disabled . '>' . html_escape($bed_label) . '</option>';
                                            }
                                        }
                                        $bed_select .= '</optgroup>';
                                    }
                                }
                                ?>
                                <div id="admit_form" class="well well" style="display: none;margin-left: 2%;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <select class="form-control" name="bed_no" id="bed_no">
                                                <option value="">Select bed</option>
                                                <?php echo $bed_select; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        <div class="col-md-4 col-sm-12">
                                            <label>Admission date: </label>
                                            <input type="text" name="admit_date" id="admit_date" class="form-control date_picker" placeholder="Admission date" />
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label>Time: </label>
                                            <input type="time" name="admit_time" id="admit_time" class="form-control" placeholder="Time" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action-bar">
                            <button class="btn btn-primary" type="submit" name="save_action" value="save"><i class="fa fa-save"></i> Save</button>
                            <!-- TODO Phase 2: wire Save & Print after save flow supports post-save redirect to print_case_sheet. -->
                            <button class="btn btn-default" type="button" disabled="disabled"><i class="fa fa-print"></i> Save &amp; Print</button>
                            <!-- TODO Phase 2: enable after WhatsApp integration is available. -->
                            <button class="btn btn-default" type="button" disabled="disabled"><i class="fa fa-whatsapp"></i> Save &amp; WhatsApp</button>
                            <button class="btn btn-danger" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="full_history_modal" tabindex="-1" role="dialog" aria-labelledby="full_history_modal_label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="full_history_modal_label">Visit History - <?php echo html_escape(trim($patient_details['FirstName'] . ' ' . $patient_details['LastName'])); ?></h4>
            </div>
            <div class="modal-body">
                <?php if (!empty($treatment_details)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped history-modal-table">
                            <thead>
                                <tr>
                                    <th style="width: 110px;">Visit Date</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                    <th style="width: 140px;">Doctor</th>
                                    <th style="width: 140px;">Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($treatment_details as $history_visit): ?>
                                    <?php
                                    $history_date = !empty($history_visit['attndedon']) ? $history_visit['attndedon'] : $history_visit['CameOn'];
                                    $history_doctor = !empty($history_visit['attndedby']) ? $history_visit['attndedby'] : $history_visit['AddedBy'];
                                    $history_department = !empty($history_visit['department']) ? ucfirst(strtolower(str_replace('_', ' ', $history_visit['department']))) : '-';
                                    ?>
                                    <tr>
                                        <td><?php echo html_escape($history_date ?: '-'); ?></td>
                                        <td><?php echo html_escape($history_visit['diagnosis'] ?: '-'); ?></td>
                                        <td class="history-treatment-cell"><?php echo html_escape($history_visit['Trtment'] ?: '-'); ?></td>
                                        <td><?php echo html_escape($history_doctor ?: '-'); ?></td>
                                        <td><?php echo html_escape($history_department); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No previous visits recorded.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('.chosen-select').chosen({
            width: '100%'
        });
        $('.chosen-select-deselect').chosen({
            allow_single_deselect: true
        });
    });
    var procedure_div_ids = ['prescription_inputs', 'birth_input', 'ecg_inputs', 'usg_inputs', 'xray_inputs', 'kshara_inputs', 'surgery_inputs', 'lab_inputs', 'physic_inputs', 'pancha_input', 'othr_proc_inputs', 'kriya_inputs'];
    var panchakarma_markup = "<?= $panchakarma_markup ?>";
    $(document).ready(function() {
        var templateFetchUrl = '<?php echo base_url('patient/treatment/fetch_treatment_templates'); ?>';
        var currentDepartment = '<?php echo html_escape($curr_dept); ?>';
        var diagnosisSuggestions = <?php
        $diagnosis_autocomplete = array();
        if (!empty($diagnosis_suggestions)) {
            foreach ($diagnosis_suggestions as $row) {
                $diagnosis_autocomplete[] = array(
                    'label' => $row['diagnosis_name'],
                    'value' => $row['diagnosis_name'],
                    'id' => $row['id']
                );
            }
        }
        echo json_encode($diagnosis_autocomplete);
        ?>;
        var activeDiagnosisSuggestionIndex = -1;

        function getCurrentDiagnosisToken() {
            var diagnosisParts = $('#diagnosis').val().split(',');
            return $.trim(diagnosisParts[diagnosisParts.length - 1]).toUpperCase();
        }

        function syncDiagnosisMasterId() {
            var typedDiagnosis = getCurrentDiagnosisToken();
            var diagnosisId = '';
            $.each(diagnosisSuggestions, function(i, item) {
                if ($.trim(item.value).toUpperCase() === typedDiagnosis) {
                    diagnosisId = item.id;
                    return false;
                }
            });
            $('#diagnosis_master_id').val(diagnosisId);
            refreshTreatmentTemplates(diagnosisId);
        }

        function hideDiagnosisSuggestions() {
            activeDiagnosisSuggestionIndex = -1;
            $('#diagnosis_suggestion_menu').hide().empty();
            $('#diagnosis').attr('aria-expanded', 'false');
        }

        function setActiveDiagnosisSuggestion(index) {
            var suggestionItems = $('#diagnosis_suggestion_menu .diagnosis-suggestion-item');
            if (!suggestionItems.length) {
                return;
            }
            activeDiagnosisSuggestionIndex = (index + suggestionItems.length) % suggestionItems.length;
            suggestionItems.removeClass('is-active').eq(activeDiagnosisSuggestionIndex).addClass('is-active');
        }

        function applyDiagnosisSuggestion(item) {
            var diagnosisParts = $('#diagnosis').val().split(',');
            diagnosisParts[diagnosisParts.length - 1] = item.value;
            diagnosisParts = $.grep($.map(diagnosisParts, function(part) {
                return $.trim(part);
            }), function(part) {
                return part !== '';
            });

            $('#diagnosis').val(diagnosisParts.join(', '));
            $('#diagnosis_master_id').val(item.id || '');
            refreshTreatmentTemplates(item.id || '');
            hideDiagnosisSuggestions();
            $('#diagnosis').focus();
        }

        function renderDiagnosisSuggestions() {
            var currentToken = getCurrentDiagnosisToken();
            var menu = $('#diagnosis_suggestion_menu');

            if (!currentToken || $('#diagnosis').is(':disabled')) {
                hideDiagnosisSuggestions();
                return;
            }

            var matches = $.grep(diagnosisSuggestions, function(item) {
                return item.value.toUpperCase().indexOf(currentToken) !== -1;
            }).slice(0, 10);

            menu.empty();
            if (!matches.length) {
                hideDiagnosisSuggestions();
                return;
            }

            $.each(matches, function(index, item) {
                $('<button type="button" class="diagnosis-suggestion-item" role="option"></button>')
                    .text(item.value)
                    .data('diagnosis-item', item)
                    .on('mousedown', function(event) {
                        event.preventDefault();
                        applyDiagnosisSuggestion($(this).data('diagnosis-item'));
                    })
                    .appendTo(menu);
            });

            menu.show();
            $('#diagnosis').attr('aria-expanded', 'true');
            setActiveDiagnosisSuggestion(0);
        }

        $('#diagnosis')
            .on('input focus', function() {
                var caretPosition = this.selectionStart;
                this.value = this.value.toUpperCase();
                if (typeof caretPosition === 'number' && this.setSelectionRange) {
                    this.setSelectionRange(caretPosition, caretPosition);
                }
                renderDiagnosisSuggestions();
            })
            .on('keydown', function(event) {
                var suggestionItems = $('#diagnosis_suggestion_menu .diagnosis-suggestion-item');
                if (!suggestionItems.length) {
                    return;
                }
                if (event.which === 40) {
                    event.preventDefault();
                    setActiveDiagnosisSuggestion(activeDiagnosisSuggestionIndex + 1);
                } else if (event.which === 38) {
                    event.preventDefault();
                    setActiveDiagnosisSuggestion(activeDiagnosisSuggestionIndex - 1);
                } else if (event.which === 13 && activeDiagnosisSuggestionIndex >= 0) {
                    event.preventDefault();
                    applyDiagnosisSuggestion(suggestionItems.eq(activeDiagnosisSuggestionIndex).data('diagnosis-item'));
                } else if (event.which === 27) {
                    hideDiagnosisSuggestions();
                }
            })
            .on('change blur', function() {
                window.setTimeout(function() {
                    syncDiagnosisMasterId();
                    hideDiagnosisSuggestions();
                }, 150);
            });

        $(document).on('mousedown', function(event) {
            if (!$(event.target).closest('.diagnosis-autocomplete').length) {
                hideDiagnosisSuggestions();
            }
        });

        $('.procedure-filter').on('click', function() {
            var filter = $(this).data('filter');
            var visibleCount = 0;

            $('.procedure-filter').removeClass('btn-primary active').addClass('btn-default');
            $(this).removeClass('btn-default').addClass('btn-primary active');

            $('.procedure-timeline-item').each(function() {
                var item = $(this);
                var statusGroup = item.data('status-group');
                var category = item.data('category');
                var shouldShow = filter === 'all' ||
                    (filter === 'pending' && statusGroup === 'pending') ||
                    (filter === 'completed' && statusGroup === 'completed') ||
                    (filter === 'panchakarma' && category === 'panchakarma') ||
                    (filter === 'lab' && (category === 'lab' || category === 'investigation'));

                item.toggle(shouldShow);
                if (shouldShow) {
                    visibleCount++;
                }
            });

            $('#procedure_timeline_filter_empty').toggle(visibleCount === 0 && $('.procedure-timeline-item').length > 0);
        });

        function updateBmiBadge(bmiValue) {
            var bmi = parseFloat(bmiValue);
            var badge = $('#vital_bmi_badge');
            badge.removeClass('bmi-underweight bmi-normal bmi-overweight bmi-obese').hide().text('');
            if (isNaN(bmi) || bmi <= 0) {
                return;
            }
            if (bmi < 18.5) {
                badge.addClass('bmi-underweight').text('Underweight').show();
            } else if (bmi < 25) {
                badge.addClass('bmi-normal').text('Normal').show();
            } else if (bmi < 30) {
                badge.addClass('bmi-overweight').text('Overweight').show();
            } else {
                badge.addClass('bmi-obese').text('Obese').show();
            }
        }

        function calculateBmi() {
            var weight = parseFloat($('#vital_weight').val());
            var heightCm = parseFloat($('#vital_height').val());
            if (!isNaN(weight) && !isNaN(heightCm) && heightCm > 0) {
                var heightMetres = heightCm / 100;
                var bmi = (weight / (heightMetres * heightMetres)).toFixed(2);
                $('#vital_bmi').val(bmi);
                updateBmiBadge(bmi);
            } else {
                $('#vital_bmi').val('');
                updateBmiBadge('');
            }
        }

        $('.vital-bmi-source').on('input change', calculateBmi);
        updateBmiBadge($('#vital_bmi').val());

        function toggleStickyPatientHeader() {
            var overview = $('#patient_overview_row');
            if (!overview.length) {
                return;
            }
            var threshold = overview.offset().top + overview.outerHeight() - 70;
            $('#sticky_patient_header').toggleClass('is-visible', $(window).scrollTop() > threshold);
        }

        $(window).on('scroll resize', toggleStickyPatientHeader);
        toggleStickyPatientHeader();

        $('.clinical-timeline-filter').on('click', function() {
            var filter = $(this).data('filter');
            var visibleCount = 0;

            $('.clinical-timeline-filter').removeClass('btn-primary active').addClass('btn-default');
            $(this).removeClass('btn-default').addClass('btn-primary active');

            $('.clinical-timeline-item').each(function() {
                var item = $(this);
                var shouldShow = filter === 'all' || item.data('category') === filter;
                item.toggle(shouldShow);
                if (shouldShow) {
                    visibleCount++;
                }
            });

            $('#clinical_timeline_filter_empty').toggle(visibleCount === 0 && $('.clinical-timeline-item').length > 0);
        });

        $('.procedure-card-open').on('click', function() {
            var target = $(this).data('target');
            $('#classic_procedure_forms').collapse('show');
            $('a[data-toggle="tab"][href="' + target + '"]').tab('show');
            window.setTimeout(function() {
                var forms = $('#classic_procedure_forms');
                if (forms.length) {
                    $('html, body').animate({ scrollTop: forms.offset().top - 70 }, 250);
                }
            }, 200);
        });

        function refreshTreatmentTemplates(diagnosisId) {
            $.ajax({
                url: templateFetchUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    department: currentDepartment,
                    diagnosis_id: diagnosisId
                },
                success: function(response) {
                    var list = response.status ? response.data : [];
                    var options = '<option value="">Use Template</option>';
                    $.each(list, function(i, item) {
                        options += '<option value="' + item.id + '" data-treatment="' + $('<div/>').text(item.treatment_text || '').html() + '">' + $('<div/>').text(item.name || '').html() + '</option>';
                    });
                    $('#treatment_template').html(options);
                    $('#treatment_template, #apply_template').prop('disabled', list.length === 0 || $('#add_prescription').is(':not(:checked)'));
                }
            });
        }

        $('#apply_template').on('click', function() {
            var templateText = $('#treatment_template option:selected').data('treatment') || '';
            var currentText = $('#treatment').val();
            if (!templateText) {
                return;
            }
            if (currentText && !confirm('Treatment already has text. Replace it with the selected template? Choose Cancel to append.')) {
                $('#treatment').val(currentText + "\n" + templateText.toUpperCase());
            } else {
                $('#treatment').val(templateText.toUpperCase());
            }
            $('#treatment').focus();
        });

        var ipd_status = '<?php echo  $ipd_status; ?>';
        if (ipd_status) {
            $('#admit').prop('disabled', true);
            $.notify({
                title: "Admission Status:",
                message: "Patient is already admitted",
                icon: 'fa fa-info-circle',
            }, {
                type: "warning",
            });
        }

        $('#admit').click(function() {
            if ($(this).is(":checked")) {
                $('#admit_form').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#admit_form').hide();
            }
        });

        $.each(procedure_div_ids, function(i) {
            $('.' + procedure_div_ids[i]).attr('disabled', 'disabled');
            $('.' + procedure_div_ids[i]).prop('disabled', true).trigger("chosen:updated");
        });

        $('#add_prescription').click(function() {
            if ($(this).is(":checked")) {
                $('.prescription_inputs').removeAttr('disabled');
                $('#diagnosis').prop('disabled', false);
                $('#treatment_template, #apply_template').prop('disabled', $('#treatment_template option').length <= 1);
            } else if ($(this).is(":not(:checked)")) {
                $('.prescription_inputs').attr('disabled', 'disabled');
                $('#diagnosis').prop('disabled', true);
                $('#treatment_template, #apply_template').prop('disabled', true);
            }
        });

        $('#ecg_check').click(function() {
            if ($(this).is(":checked")) {
                $('.ecg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.ecg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#birth_check').click(function() {
            if ($(this).is(":checked")) {
                $('.birth_input').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.birth_input').attr('disabled', 'disabled');
            }
        });

        $('#usg_check').click(function() {
            if ($(this).is(":checked")) {
                $('.usg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#usgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.usg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#xray_check').click(function() {
            if ($(this).is(":checked")) {
                $('.xray_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#xraydocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.xray_inputs').attr('disabled', 'disabled');
            }
        });

        $('#kshara_check').click(function() {
            if ($(this).is(":checked")) {
                $('.kshara_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.kshara_inputs').attr('disabled', 'disabled');
            }
        });

        $('#surgery_check').click(function() {
            if ($(this).is(":checked")) {
                $('.surgery_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.surgery_inputs').attr('disabled', 'disabled');
            }
        });

        $('#lab_check').click(function() {
            if ($(this).is(":checked")) {
                $('.lab_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#labdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.lab_inputs').attr('disabled', 'disabled');
            }
        });

        $('#panchakarma_check').click(function() {
            if ($(this).is(":checked")) {
                $('.pancha_input').prop('disabled', false).trigger("chosen:updated");
                $('.pancha_input').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.pancha_input').attr('disabled', 'disabled');
                $('.pancha_input').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#physiotherapy_check').click(function() {
            if ($(this).is(":checked")) {
                $('.physic_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.physic_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.physic_inputs').attr('disabled', 'disabled');
                $('.physic_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#other_proc_check').click(function() {
            if ($(this).is(":checked")) {
                $('.othr_proc_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.othr_proc_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.othr_proc_inputs').attr('disabled', 'disabled');
                $('.othr_proc_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#kriyakalpa_check').click(function() {
            if ($(this).is(":checked")) {
                $('.kriya_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.kriya_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.kriya_inputs').attr('disabled', 'disabled');
                $('.kriya_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#copy_diagnosis').click(function() {
            if ($(this).is(":checked")) {
                if ($('#diagnosis').val().length <= 0) {
                    $.notify({
                        title: "Treatment:",
                        message: "Please enter diagnosis to copy it",
                        icon: 'fa fa-cross',
                    }, {
                        type: "danger",
                    });
                    $("#copy_diagnosis").prop("checked", false);
                } else {
                    copy_input_text('#diagnosis', '#pancha_disease');
                }
            } else if ($(this).is(":not(:checked)")) {
                $('#pancha_disease').val('');
            }
        });
        $('#admit').click(function() {
            if ($(this).is(":checked")) {
                $('#admit_form').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#admit_form').hide();
            }
        });
        $('#lab_cat_table').on('change', '.lab_category', function() {
            var dom = $(this);
            $.ajax({
                url: base_url + 'common_methods/fetch_laboratory_test_list',
                type: 'POST',
                data: {
                    category: $(this).val()
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        var list = res.data;
                        var options = '<option value="">Choose test</option>';
                        $.each(list, function(i, item) {
                            options += '<option value="' + item.lab_test_id + '">' + item.lab_test_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.lab_test').html(options);
                        dom.closest('td').next('td').find('.lab_test').trigger("chosen:updated");
                    } else {
                        alert('something went wrong try again');
                    }
                },
                error: function(error) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#lab_cat_table').on('change', '.lab_test', function() {
            var dom = $(this);
            $.ajax({
                url: base_url + 'common_methods/fetch_laboratory_investigation_list',
                type: 'POST',
                data: {
                    tests: $(this).val()
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        var list = res.data;
                        var options = '<option value="">Choose Investigations</option>';
                        $.each(list, function(i, item) {
                            options += '<option value="' + item.lab_inv_id + '">' + item.lab_inv_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.lab_investigations').html(options);
                        dom.closest('td').next('td').find('.lab_investigations').trigger("chosen:updated");
                    } else {
                        alert('something went wrong try again');
                    }
                },
                error: function(error) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#panchakarma_table').on('change', '.pancha_procedure', function() {
            var panchaprocedure = $(this).val();
            var dom = $(this);
            $.ajax({
                url: base_url + 'master/panchakarma/fetch_sub_procedures',
                data: {
                    'proc_name': panchaprocedure
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    var sub_proc_options = '<option value="">choose sub procedure</option>';
                    if (response.status) {
                        var sub_proc = response.data;
                        $.each(sub_proc, function(i) {
                            sub_proc_options += '<option value="' + sub_proc[i].sub_proc_name + '">' + sub_proc[i].sub_proc_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.pancha_sub_procedure').html(sub_proc_options);
                        dom.closest('td').next('td').find('.pancha_sub_procedure').trigger("chosen:updated");
                    }
                },
                error: function(xhr, errorType, exception) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#add_row').on('click', function() {
            var n = $('#tab_row_count').val();
            $('#tab_row_count').val(n + 1);
            var markup = '<tr>' + '<td>' +
                '<select class="form-control chosen-select pancha_procedure" name="pancha_procedure[]" id="pancha_procedure_' + (n + 1) + '">' +
                '<option value="">Choose procedure</option>' +
                panchakarma_markup +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select class="form-control pancha_sub_procedure chosen-select" name="pancha_sub_procedure[]" id="pancha_sub_procedure">' +
                '<option value="">Choose sub procedure</option>' +
                '</select>' +
                '</td>' +
                '<td>' + '<input type="text" class="form-control date_picker" name = "pancha_proc_start_date[]" id = "pancha_proc_start_date" />' + '</td>' +
                '<td>' + '<input type="text" class="form-control date_picker" name = "pancha_proc_end_date[]" pancha_proc_end_date />' + '</td>' +
                '</tr>';
            $('#panchakarma_table').append(markup);
            $(".pancha_procedure").chosen({
                width: '100%'
            });
            $(".pancha_sub_procedure").chosen({
                width: '100%'
            });
            $('.date_picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                daysOfWeekHighlighted: "0"
            });
            $('.date_picker').attr('autocomplete', 'off');
        });
        var lab_cat_markup = '<?= $lab_categories_markup ?>';

        $('#add_lab_row').on('click', function() {
            var n = $('#tab_lab_row_count').val();
            $('#tab_lab_row_count').val(n + 1);
            var markup = '<tr>' + '<td>' +
                '<select class="form-control chosen-select lab_category" name="lab_category[]" id="lab_category_' + (n + 1) + '">' +
                '<option value="">Choose Category</option>' +
                lab_cat_markup +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select class="form-control lab_test chosen-select" name="lab_test[]" id="lab_test_' + (n + 1) + '">' +
                '<option value="">Choose Test</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select class="form-control lab_investigations chosen-select" name="lab_investigations[]" multiple id="lab_investigations_' + (n + 1) + '">' +
                '<option value="">Choose Investigations</option>' +
                '</select>' +
                '</td>' +
                '</tr>';
            $('#lab_cat_table').append(markup);
            $(".lab_category").chosen({
                width: '100%'
            });
            $(".lab_test").chosen({
                width: '100%'
            });
            $(".lab_investigations").chosen({
                width: '100%'
            });
        });
    });
</script>
