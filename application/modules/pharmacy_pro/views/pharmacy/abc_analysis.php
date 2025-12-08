<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bar-chart"></i>
            ABC Analysis
            <small>Medicine Sales Performance Analysis</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy/reports'); ?>">Reports</a></li>
            <li class="active">ABC Analysis</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">ABC Analysis Report</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" onclick="window.print()">
                                <i class="fa fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            <strong>ABC Analysis:</strong> Classifies medicines based on sales value and frequency.
                            <ul>
                                <li><strong>Class A:</strong> High-value medicines (70% of sales)</li>
                                <li><strong>Class B:</strong> Medium-value medicines (20% of sales)</li>
                                <li><strong>Class C:</strong> Low-value medicines (10% of sales)</li>
                            </ul>
                        </div>
                        
                        <?php if (isset($abc_analysis) && !empty($abc_analysis)): ?>
                            <!-- Summary Cards -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box bg-red">
                                        <span class="info-box-icon"><i class="fa fa-star"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Class A Items</span>
                                            <span class="info-box-number">
                                                <?php echo count(array_filter($abc_analysis, function($item) { return $item->abc_class == 'A'; })); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-star-half-o"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Class B Items</span>
                                            <span class="info-box-number">
                                                <?php echo count(array_filter($abc_analysis, function($item) { return $item->abc_class == 'B'; })); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-star-o"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Class C Items</span>
                                            <span class="info-box-number">
                                                <?php echo count(array_filter($abc_analysis, function($item) { return $item->abc_class == 'C'; })); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="abcTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Category</th>
                                            <th>Total Quantity Sold</th>
                                            <th>Total Sales Value</th>
                                            <th>Sales Frequency</th>
                                            <th>ABC Class</th>
                                            <th>Contribution %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($abc_analysis as $item): ?>
                                            <tr>
                                                <td><?php echo $item->medicine_name; ?></td>
                                                <td><?php echo $item->category_name; ?></td>
                                                <td><?php echo $item->total_quantity; ?></td>
                                                <td>₹<?php echo number_format($item->total_value, 2); ?></td>
                                                <td><?php echo $item->sales_frequency; ?></td>
                                                <td>
                                                    <?php 
                                                    $class_color = '';
                                                    switch($item->abc_class) {
                                                        case 'A': $class_color = 'danger'; break;
                                                        case 'B': $class_color = 'warning'; break;
                                                        case 'C': $class_color = 'success'; break;
                                                    }
                                                    ?>
                                                    <span class="label label-<?php echo $class_color; ?>">
                                                        Class <?php echo $item->abc_class; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo number_format($item->contribution_percent, 2); ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-info-circle"></i> 
                                No sales data available for ABC analysis. Please import the database schema and make some sales to generate this report.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#abcTable').DataTable({
        "order": [[3, "desc"]], // Order by total sales value
        "pageLength": 25
    });
});
</script>
