<?php

use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\export\Exportable;

$gender_stats = isset($gender_count[0]) ? $gender_count[0] : array();
$male_count = isset($gender_stats['males']) ? (int) $gender_stats['males'] : 0;
$female_count = isset($gender_stats['females']) ? (int) $gender_stats['females'] : 0;
$total_patients = isset($gender_stats['total']) ? (int) $gender_stats['total'] : 0;

if (!function_exists('dashboard_metric_value')) {
    function dashboard_metric_value($value)
    {
        return is_numeric($value) ? $value : 0;
    }
}

if (!function_exists('dashboard_format_number')) {
    function dashboard_format_number($value)
    {
        return number_format((float) dashboard_metric_value($value));
    }
}

if (!function_exists('dashboard_format_money')) {
    function dashboard_format_money($value)
    {
        return 'Rs ' . number_format((float) dashboard_metric_value($value), 2);
    }
}

$koolarray = array();
if (!empty($dept_wise_data)) {
    foreach ($dept_wise_data as $pdata) {
        $koolarray[] = array(
            'Department' => ucfirst(strtolower(str_replace('_', ' ', $pdata['department']))),
            'OLD' => (int) $pdata['OLD'],
            'NEW' => (int) $pdata['NEW'],
            'Total' => (int) $pdata['Total'],
            'Male' => (int) $pdata['Male'],
            'Female' => (int) $pdata['Female'],
        );
    }
}

$ipdkoolarray = array();
if (!empty($ipd_data)) {
    foreach ($ipd_data as $pdata) {
        $ipdkoolarray[] = array(
            'Department' => ucfirst(strtolower(str_replace('_', ' ', $pdata['department']))),
            'Total' => (int) $pdata['Total'],
            'Male' => (int) $pdata['Male'],
            'Female' => (int) $pdata['Female'],
        );
    }
}

$opd_chart_data = array(
    'labels' => array(),
    'total' => array(),
    'old' => array(),
    'new' => array(),
    'male' => array(),
    'female' => array()
);
foreach ($koolarray as $row) {
    $opd_chart_data['labels'][] = $row['Department'];
    $opd_chart_data['total'][] = isset($row['Total']) ? (int) $row['Total'] : 0;
    $opd_chart_data['old'][] = isset($row['OLD']) ? (int) $row['OLD'] : 0;
    $opd_chart_data['new'][] = isset($row['NEW']) ? (int) $row['NEW'] : 0;
    $opd_chart_data['male'][] = isset($row['Male']) ? (int) $row['Male'] : 0;
    $opd_chart_data['female'][] = isset($row['Female']) ? (int) $row['Female'] : 0;
}

$ipd_chart_data = array(
    'labels' => array(),
    'total' => array(),
    'male' => array(),
    'female' => array()
);
foreach ($ipdkoolarray as $row) {
    $ipd_chart_data['labels'][] = $row['Department'];
    $ipd_chart_data['total'][] = isset($row['Total']) ? (int) $row['Total'] : 0;
    $ipd_chart_data['male'][] = isset($row['Male']) ? (int) $row['Male'] : 0;
    $ipd_chart_data['female'][] = isset($row['Female']) ? (int) $row['Female'] : 0;
}

if (!function_exists('dashboard_chart_total')) {
    function dashboard_chart_total($rows, $field)
    {
        $total = 0;
        foreach ($rows as $row) {
            $total += isset($row[$field]) ? (int) $row[$field] : 0;
        }
        return $total;
    }
}

if (!function_exists('dashboard_chart_top_department')) {
    function dashboard_chart_top_department($rows, $field)
    {
        $top_department = 'N/A';
        $top_total = 0;
        foreach ($rows as $row) {
            $value = isset($row[$field]) ? (int) $row[$field] : 0;
            if ($value > $top_total) {
                $top_total = $value;
                $top_department = isset($row['Department']) ? $row['Department'] : 'N/A';
            }
        }
        return array('department' => $top_department, 'total' => $top_total);
    }
}

$top_opd_department = dashboard_chart_top_department($koolarray, 'Total');
$top_ipd_department = dashboard_chart_top_department($ipdkoolarray, 'Total');

$today_cards = array(
    array('title' => "Today's OPD", 'value' => dashboard_format_number(isset($today_opd_count) ? $today_opd_count : 0), 'icon' => 'fa-stethoscope', 'color' => 'bg-aqua'),
    array('title' => "Today's IPD Admissions", 'value' => dashboard_format_number(isset($today_ipd_admissions) ? $today_ipd_admissions : 0), 'icon' => 'fa-hospital-o', 'color' => 'bg-green'),
    array('title' => "Today's Discharges", 'value' => dashboard_format_number(isset($today_discharges) ? $today_discharges : 0), 'icon' => 'fa-sign-out', 'color' => 'bg-yellow'),
    array('title' => "Today's Revenue", 'value' => dashboard_format_money(isset($today_revenue) ? $today_revenue : 0), 'icon' => 'fa-inr', 'color' => 'bg-purple'),
);

$month_cards = array(
    array('title' => 'Current Month OPD', 'value' => dashboard_format_number(isset($month_opd_count) ? $month_opd_count : 0), 'icon' => 'fa-calendar-check-o', 'color' => 'bg-light-blue'),
    array('title' => 'Current Month IPD', 'value' => dashboard_format_number(isset($month_ipd_count) ? $month_ipd_count : 0), 'icon' => 'fa-bed', 'color' => 'bg-teal'),
    array('title' => 'Bed Occupancy', 'value' => dashboard_metric_value(isset($bed_occupancy_percentage) ? $bed_occupancy_percentage : 0) . '%', 'icon' => 'fa-pie-chart', 'color' => 'bg-maroon'),
);

$year_cards = array(
    array('title' => 'Male Patients', 'value' => dashboard_format_number($male_count), 'icon' => 'fa-male', 'color' => 'bg-aqua'),
    array('title' => 'Female Patients', 'value' => dashboard_format_number($female_count), 'icon' => 'fa-female', 'color' => 'bg-green'),
    array('title' => 'Total Patients', 'value' => dashboard_format_number($total_patients), 'icon' => 'fa-users', 'color' => 'bg-yellow'),
    array('title' => 'Current Year Patients', 'value' => dashboard_format_number(isset($current_year_total_patients) ? $current_year_total_patients : 0), 'icon' => 'fa-calendar', 'color' => 'bg-purple'),
);

$compliance_cards = array(
    array('title' => 'ABHA Linked Patients', 'value' => '0', 'icon' => 'fa-id-card-o', 'color' => 'bg-gray'),
    array('title' => 'NCISM Compliance', 'value' => '0', 'icon' => 'fa-check-square-o', 'color' => 'bg-gray'),
    array('title' => 'WhatsApp Reminders', 'value' => '0', 'icon' => 'fa-whatsapp', 'color' => 'bg-gray'),
);

if (!function_exists('dashboard_render_cards')) {
    function dashboard_render_cards($cards, $columns = 4)
    {
        $col_class = ($columns === 3) ? 'col-md-4 col-sm-6 col-xs-12' : 'col-md-3 col-sm-6 col-xs-12';
        foreach ($cards as $card) {
            ?>
            <div class="<?php echo $col_class; ?>">
                <div class="info-box dashboard-kpi">
                    <span class="info-box-icon <?php echo $card['color']; ?>"><i class="fa <?php echo $card['icon']; ?>"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><?php echo $card['title']; ?></span>
                        <span class="info-box-number"><?php echo $card['value']; ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
<style>
    .dashboard-section-title {
        margin: 8px 0 15px;
        font-size: 18px;
        font-weight: 600;
        color: #3c8dbc;
    }

    .dashboard-kpi {
        min-height: 90px;
        border-radius: 4px;
    }

    .dashboard-kpi .info-box-icon {
        height: 90px;
        line-height: 90px;
    }

    .dashboard-kpi .info-box-content {
        min-height: 90px;
        padding-top: 14px;
    }

    .dashboard-kpi .info-box-text {
        font-weight: 600;
        white-space: normal;
    }

    .dashboard-kpi .info-box-number {
        margin-top: 8px;
        font-size: 22px;
        word-break: break-word;
    }

    .dashboard-chart-heading {
        margin-top: 0;
        font-weight: 600;
    }

    .department-chart-summary {
        margin-bottom: 14px;
    }

    .department-chart-summary .info-box {
        min-height: 74px;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .department-chart-summary .info-box-icon {
        width: 72px;
        height: 74px;
        line-height: 74px;
        font-size: 28px;
    }

    .department-chart-summary .info-box-content {
        margin-left: 72px;
        min-height: 74px;
        padding-top: 10px;
    }

    .department-chart-summary .info-box-text {
        white-space: normal;
        font-weight: 600;
    }

    .department-chart-summary .info-box-number {
        font-size: 18px;
        line-height: 1.25;
        word-break: break-word;
    }

    .department-chart-panel {
        border: 1px solid #e5edf3;
        border-radius: 4px;
        padding: 12px;
        background: #fff;
        min-height: 390px;
    }

    .department-chart-toolbar {
        margin-bottom: 12px;
    }

    .department-chart-toolbar .btn {
        margin: 0 4px 6px 0;
    }

    .department-chart-canvas-wrap {
        position: relative;
        width: 100%;
        min-height: 310px;
    }

    .department-chart-canvas {
        width: 100% !important;
        min-height: 310px;
    }

    .department-chart-empty {
        display: none;
        padding: 90px 15px;
        text-align: center;
        color: #777;
        border: 1px dashed #d2d6de;
        border-radius: 4px;
    }

    .department-chart-fallback {
        display: none;
    }

    .float-right {
        text-align: right !important;
    }
</style>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Today's Activity</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <?php dashboard_render_cards($today_cards); ?>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Current Month</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <?php dashboard_render_cards($month_cards, 3); ?>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Current Year</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <?php dashboard_render_cards($year_cards); ?>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Compliance Placeholders</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <?php dashboard_render_cards($compliance_cards, 3); ?>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Department Wise Patients</h3>
    </div>
    <div class="box-body">
        <div class="row department-chart-summary">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Top OPD Department</span>
                        <span class="info-box-number"><?php echo html_escape($top_opd_department['department']); ?> (<?php echo dashboard_format_number($top_opd_department['total']); ?>)</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total OPD</span>
                        <span class="info-box-number"><?php echo dashboard_format_number(dashboard_chart_total($koolarray, 'Total')); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Top IPD Department</span>
                        <span class="info-box-number"><?php echo html_escape($top_ipd_department['department']); ?> (<?php echo dashboard_format_number($top_ipd_department['total']); ?>)</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-teal"><i class="fa fa-bed"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total IPD</span>
                        <span class="info-box-number"><?php echo dashboard_format_number(dashboard_chart_total($ipdkoolarray, 'Total')); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="department-chart-panel">
                    <div class="clearfix">
                        <h4 class="text-blue dashboard-chart-heading pull-left">OPD Statistics</h4>
                        <div class="department-chart-toolbar pull-right" data-chart-toolbar="opdDepartmentChart">
                            <button type="button" class="btn btn-primary btn-xs active" data-mode="total">Total</button>
                            <button type="button" class="btn btn-default btn-xs" data-mode="oldNew">Old vs New</button>
                            <button type="button" class="btn btn-default btn-xs" data-mode="gender">Male vs Female</button>
                        </div>
                    </div>
                    <div class="department-chart-canvas-wrap">
                        <canvas id="opdDepartmentChart" class="department-chart-canvas" height="310"></canvas>
                        <div id="opdDepartmentChartEmpty" class="department-chart-empty">No OPD chart data available.</div>
                    </div>
                    <div id="opdDepartmentChartFallback" class="department-chart-fallback">
                        <?php
                        ColumnChart::create(array(
                            'dataSource' => $koolarray
                        ));
                        ?>
                    </div>
                </div>
                <hr/>
                <?php
                Table::create(array(
                    'showFooter' => 'bottom',
                    'dataSource' => $koolarray,
                    'columns' => array(
                        'Department' => array(),
                        'OLD' => array('footer' => 'sum', 'cssClass' => 'float-right'),
                        'NEW' => array('footer' => 'sum'),
                        'Total' => array('footer' => 'sum'),
                        'Male' => array('footer' => 'sum'),
                        'Female' => array('footer' => 'sum'),
                    ),
                    'cssClass' => array(
                        'table' => 'table table-hover table-bordered dataTable',
                        'tf' => 'alert-info'
                    )
                ));
                ?>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="department-chart-panel">
                    <div class="clearfix">
                        <h4 class="text-blue dashboard-chart-heading pull-left">IPD Statistics</h4>
                        <div class="department-chart-toolbar pull-right" data-chart-toolbar="ipdDepartmentChart">
                            <button type="button" class="btn btn-primary btn-xs active" data-mode="total">Total</button>
                            <button type="button" class="btn btn-default btn-xs" data-mode="gender">Male vs Female</button>
                        </div>
                    </div>
                    <div class="department-chart-canvas-wrap">
                        <canvas id="ipdDepartmentChart" class="department-chart-canvas" height="310"></canvas>
                        <div id="ipdDepartmentChartEmpty" class="department-chart-empty">No IPD chart data available.</div>
                    </div>
                    <div id="ipdDepartmentChartFallback" class="department-chart-fallback">
                        <?php
                        ColumnChart::create(array(
                            'dataSource' => $ipdkoolarray
                        ));
                        ?>
                    </div>
                </div>
                <hr/>
                <?php
                Table::create(array(
                    'showFooter' => 'bottom',
                    'dataSource' => $ipdkoolarray,
                    'columns' => array(
                        'Department' => array(),
                        'Total' => array('footer' => 'sum'),
                        'Male' => array('footer' => 'sum'),
                        'Female' => array('footer' => 'sum'),
                    ),
                    'cssClass' => array(
                        'table' => 'table table-hover table-bordered dataTable',
                        'tf' => 'alert-info'
                    )
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function () {
        var opdData = <?php echo json_encode($opd_chart_data); ?>;
        var ipdData = <?php echo json_encode($ipd_chart_data); ?>;
        var charts = {};

        function toNumbers(values) {
            var output = [];
            values = values || [];
            for (var i = 0; i < values.length; i++) {
                var parsed = parseInt(values[i], 10);
                output.push(isNaN(parsed) ? 0 : parsed);
            }
            return output;
        }

        function hasValues(values) {
            values = values || [];
            for (var i = 0; i < values.length; i++) {
                if (parseInt(values[i], 10) > 0) {
                    return true;
                }
            }
            return false;
        }

        function shortLabels(labels) {
            var output = [];
            labels = labels || [];
            for (var i = 0; i < labels.length; i++) {
                var label = labels[i] || 'N/A';
                output.push(label.length > 18 ? label.substr(0, 17) + '.' : label);
            }
            return output;
        }

        function chartConfig(type, source) {
            if (type === 'oldNew') {
                return [
                    {label: 'Old', values: source.old, fill: 'rgba(60,141,188,0.78)', stroke: 'rgba(60,141,188,1)'},
                    {label: 'New', values: source.new, fill: 'rgba(0,166,90,0.78)', stroke: 'rgba(0,166,90,1)'}
                ];
            }

            if (type === 'gender') {
                return [
                    {label: 'Male', values: source.male, fill: 'rgba(0,192,239,0.78)', stroke: 'rgba(0,192,239,1)'},
                    {label: 'Female', values: source.female, fill: 'rgba(221,75,57,0.78)', stroke: 'rgba(221,75,57,1)'}
                ];
            }

            return [
                {label: 'Total', values: source.total, fill: 'rgba(243,156,18,0.82)', stroke: 'rgba(243,156,18,1)'}
            ];
        }

        function showChartFallback(canvas, empty, fallback) {
            if (canvas) {
                canvas.style.display = 'none';
            }
            if (empty) {
                empty.style.display = 'none';
            }
            if (fallback) {
                fallback.style.display = 'block';
            }
        }

        function renderDepartmentChart(canvasId, source, mode) {
            var canvas = document.getElementById(canvasId);
            var empty = document.getElementById(canvasId + 'Empty');
            var fallback = document.getElementById(canvasId + 'Fallback');

            if (!canvas) {
                return;
            }

            if (typeof Chart === 'undefined' || typeof Chart.prototype === 'undefined') {
                showChartFallback(canvas, empty, fallback);
                return;
            }

            var datasets = chartConfig(mode, source);
            var hasChartData = false;
            var chartDatasets = [];
            for (var i = 0; i < datasets.length; i++) {
                var values = toNumbers(datasets[i].values);
                hasChartData = hasChartData || hasValues(values);
                chartDatasets.push({
                    label: datasets[i].label,
                    fillColor: datasets[i].fill,
                    strokeColor: datasets[i].stroke,
                    highlightFill: datasets[i].stroke,
                    highlightStroke: datasets[i].stroke,
                    data: values
                });
            }

            if (!hasChartData || !source.labels || source.labels.length === 0) {
                canvas.style.display = 'none';
                if (empty) {
                    empty.style.display = 'block';
                }
                return;
            }

            if (empty) {
                empty.style.display = 'none';
            }
            if (fallback) {
                fallback.style.display = 'none';
            }
            canvas.style.display = 'block';

            if (charts[canvasId] && typeof charts[canvasId].destroy === 'function') {
                charts[canvasId].destroy();
            }

            try {
                charts[canvasId] = new Chart(canvas.getContext('2d')).Bar({
                    labels: shortLabels(source.labels),
                    datasets: chartDatasets
                }, {
                    responsive: true,
                    maintainAspectRatio: false,
                    barShowStroke: false,
                    scaleShowGridLines: true,
                    scaleGridLineColor: 'rgba(0,0,0,.06)',
                    scaleFontSize: 10,
                    tooltipTemplate: '<%if (label){%><%=label%>: <%}%><%= value %>',
                    multiTooltipTemplate: '<%= datasetLabel %>: <%= value %>'
                });
            } catch (error) {
                showChartFallback(canvas, empty, fallback);
            }
        }

        function activateToolbar(toolbar, activeButton) {
            var buttons = toolbar.getElementsByTagName('button');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].className = buttons[i].className.replace(' btn-primary', ' btn-default').replace(' active', '');
            }
            activeButton.className = activeButton.className.replace(' btn-default', ' btn-primary') + ' active';
        }

        function bindToolbar(chartId, source) {
            var toolbar = document.querySelector('[data-chart-toolbar="' + chartId + '"]');
            if (!toolbar) {
                return;
            }
            var buttons = toolbar.getElementsByTagName('button');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].onclick = function () {
                    activateToolbar(toolbar, this);
                    renderDepartmentChart(chartId, source, this.getAttribute('data-mode') || 'total');
                };
            }
        }

        renderDepartmentChart('opdDepartmentChart', opdData, 'total');
        renderDepartmentChart('ipdDepartmentChart', ipdData, 'total');
        bindToolbar('opdDepartmentChart', opdData);
        bindToolbar('ipdDepartmentChart', ipdData);
    })();
</script>
