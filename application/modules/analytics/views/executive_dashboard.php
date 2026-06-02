<?php
$snapshot = isset($snapshot) ? $snapshot : array();
$month_summary = isset($month_summary) ? $month_summary : array();
$department_performance = isset($department_performance) ? $department_performance : array();
$revenue_analytics = isset($revenue_analytics) ? $revenue_analytics : array();
$patient_trends = isset($patient_trends) ? $patient_trends : array();
$bed_analytics = isset($bed_analytics) ? $bed_analytics : array();
$panchakarma = isset($panchakarma) ? $panchakarma : array();

if (!function_exists('exec_dash_number')) {
    function exec_dash_number($value)
    {
        return number_format((float)$value);
    }
}

if (!function_exists('exec_dash_money')) {
    function exec_dash_money($value)
    {
        return 'Rs ' . number_format((float)$value, 2);
    }
}
?>

<style>
    .executive-dashboard .section-title {
        margin: 18px 0 12px;
        font-weight: 600;
        color: #263238;
    }
    .executive-dashboard .info-box {
        min-height: 92px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
    }
    .executive-dashboard .info-box-icon {
        height: 92px;
        line-height: 92px;
    }
    .executive-dashboard .info-box-content {
        padding-top: 14px;
    }
    .executive-dashboard .info-box-text {
        white-space: normal;
        font-weight: 600;
    }
    .executive-dashboard .chart-box {
        min-height: 330px;
    }
    .executive-dashboard .chart-canvas {
        width: 100%;
        min-height: 240px;
    }
    .executive-dashboard .placeholder-value {
        font-size: 22px;
        font-weight: 700;
        color: #7a8793;
    }
</style>

<section class="content executive-dashboard">
    <h3 class="section-title">Hospital Snapshot</h3>
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-user-md"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's OPD</span>
                    <span class="info-box-number"><?php echo exec_dash_number(@$snapshot['today_opd']); ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-hospital-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's IPD</span>
                    <span class="info-box-number"><?php echo exec_dash_number(@$snapshot['today_ipd']); ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-inr"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Revenue</span>
                    <span class="info-box-number"><?php echo exec_dash_money(@$snapshot['today_revenue']); ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-bed"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bed Occupancy %</span>
                    <span class="info-box-number"><?php echo number_format((float)@$snapshot['bed_occupancy'], 2); ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">Current Month Summary</h3>
    <div class="row">
        <?php
        $month_cards = array(
            array('OPD Count', @$month_summary['opd_count'], 'fa-stethoscope', 'bg-aqua'),
            array('IPD Count', @$month_summary['ipd_count'], 'fa-bed', 'bg-green'),
            array('Admissions', @$month_summary['admissions'], 'fa-sign-in', 'bg-blue'),
            array('Discharges', @$month_summary['discharges'], 'fa-sign-out', 'bg-maroon'),
            array('Panchakarma Count', @$month_summary['panchakarma_count'], 'fa-leaf', 'bg-olive'),
            array('Lab Count', @$month_summary['lab_count'], 'fa-flask', 'bg-orange')
        );
        foreach ($month_cards as $card) {
            ?>
            <div class="col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon <?php echo $card[3]; ?>"><i class="fa <?php echo $card[2]; ?>"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><?php echo $card[0]; ?></span>
                        <span class="info-box-number"><?php echo exec_dash_number($card[1]); ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Department Performance</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th class="text-right">OPD Count</th>
                                <th class="text-right">IPD Count</th>
                                <th class="text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($department_performance)) { ?>
                                <?php foreach ($department_performance as $row) { ?>
                                    <tr>
                                        <td><?php echo html_escape($row['department']); ?></td>
                                        <td class="text-right"><?php echo exec_dash_number($row['opd_count']); ?></td>
                                        <td class="text-right"><?php echo exec_dash_number($row['ipd_count']); ?></td>
                                        <td class="text-right"><?php echo exec_dash_money($row['revenue']); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No department data available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="box box-success chart-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Revenue Analytics</h3>
                </div>
                <div class="box-body">
                    <canvas id="executiveRevenueChart" class="chart-canvas"></canvas>
                    <div class="row">
                        <div class="col-xs-4 text-center">
                            <strong><?php echo exec_dash_money(@$revenue_analytics['opd_revenue']); ?></strong><br>
                            <span class="text-muted">OPD</span>
                        </div>
                        <div class="col-xs-4 text-center">
                            <strong><?php echo exec_dash_money(@$revenue_analytics['ipd_revenue']); ?></strong><br>
                            <span class="text-muted">IPD</span>
                        </div>
                        <div class="col-xs-4 text-center">
                            <strong><?php echo exec_dash_money(@$revenue_analytics['pharmacy_revenue']); ?></strong><br>
                            <span class="text-muted">Pharmacy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">Patient Trends</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info chart-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Monthly OPD Trend</h3>
                </div>
                <div class="box-body">
                    <canvas id="executiveOpdTrendChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-warning chart-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Monthly IPD Trend</h3>
                </div>
                <div class="box-body">
                    <canvas id="executiveIpdTrendChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">Bed Analytics</h3>
    <div class="row">
        <?php
        $bed_cards = array(
            array('Total Beds', @$bed_analytics['total_beds'], 'fa-bed', 'bg-blue'),
            array('Occupied Beds', @$bed_analytics['occupied_beds'], 'fa-bed', 'bg-red'),
            array('Available Beds', @$bed_analytics['available_beds'], 'fa-check-circle', 'bg-green'),
            array('Occupancy %', number_format((float)@$bed_analytics['occupancy_percentage'], 2) . '%', 'fa-pie-chart', 'bg-purple')
        );
        foreach ($bed_cards as $card) {
            ?>
            <div class="col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon <?php echo $card[3]; ?>"><i class="fa <?php echo $card[2]; ?>"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><?php echo $card[0]; ?></span>
                        <span class="info-box-number"><?php echo is_numeric($card[1]) ? exec_dash_number($card[1]) : $card[1]; ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Panchakarma Analytics</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-olive"><i class="fa fa-leaf"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Today's Therapies</span>
                                    <span class="info-box-number"><?php echo exec_dash_number(@$panchakarma['today_therapies']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-teal"><i class="fa fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Current Month</span>
                                    <span class="info-box-number"><?php echo exec_dash_number(@$panchakarma['month_therapies']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Top Therapies</th>
                                <th class="text-right">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($panchakarma['top_therapies'])) { ?>
                                <?php foreach ($panchakarma['top_therapies'] as $row) { ?>
                                    <tr>
                                        <td><?php echo html_escape($row['therapy']); ?></td>
                                        <td class="text-right"><?php echo exec_dash_number($row['total']); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No therapy data available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Compliance Section</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4 text-center">
                            <div class="placeholder-value">0</div>
                            <span class="text-muted">ABHA Linked Patients</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="placeholder-value">Pending</div>
                            <span class="text-muted">NCISM Compliance</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="placeholder-value">0</div>
                            <span class="text-muted">Pending Registers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    (function () {
        var revenue = <?php echo json_encode(isset($revenue_analytics['last_12_months']) ? $revenue_analytics['last_12_months'] : array('labels' => array(), 'values' => array())); ?>;
        var opdTrend = <?php echo json_encode(isset($patient_trends['opd']) ? $patient_trends['opd'] : array('labels' => array(), 'values' => array())); ?>;
        var ipdTrend = <?php echo json_encode(isset($patient_trends['ipd']) ? $patient_trends['ipd'] : array('labels' => array(), 'values' => array())); ?>;

        function makeLineChart(id, labels, values, color) {
            var canvas = document.getElementById(id);
            if (!canvas || typeof Chart === 'undefined') {
                return;
            }
            new Chart(canvas.getContext('2d')).Line({
                labels: labels,
                datasets: [{
                    fillColor: color.fill,
                    strokeColor: color.stroke,
                    pointColor: color.stroke,
                    pointStrokeColor: '#fff',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: color.stroke,
                    data: values
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false,
                scaleShowGridLines: true,
                datasetFill: true
            });
        }

        function makeBarChart(id, labels, values) {
            var canvas = document.getElementById(id);
            if (!canvas || typeof Chart === 'undefined') {
                return;
            }
            new Chart(canvas.getContext('2d')).Bar({
                labels: labels,
                datasets: [{
                    fillColor: 'rgba(60,141,188,0.75)',
                    strokeColor: 'rgba(60,141,188,0.9)',
                    highlightFill: 'rgba(60,141,188,0.9)',
                    highlightStroke: 'rgba(60,141,188,1)',
                    data: values
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false,
                scaleShowGridLines: true
            });
        }

        makeBarChart('executiveRevenueChart', revenue.labels || [], revenue.values || []);
        makeLineChart('executiveOpdTrendChart', opdTrend.labels || [], opdTrend.values || [], {
            fill: 'rgba(0,192,239,0.20)',
            stroke: 'rgba(0,192,239,1)'
        });
        makeLineChart('executiveIpdTrendChart', ipdTrend.labels || [], ipdTrend.values || [], {
            fill: 'rgba(243,156,18,0.20)',
            stroke: 'rgba(243,156,18,1)'
        });
    })();
</script>
