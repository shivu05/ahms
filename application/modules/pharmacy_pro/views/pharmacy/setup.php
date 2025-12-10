<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-database"></i>
            Pharmacy Module Setup
            <small>Database Configuration Check</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">Setup</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Database Tables Status</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info"></i> Setup Instructions</h4>
                            To complete the pharmacy module setup, please follow these steps:
                            <ol>
                                <li>Import the <code>pharmacy_schema.sql</code> file into your database</li>
                                <li>The file is located in your project root directory</li>
                                <li>Use phpMyAdmin, MySQL Workbench, or command line to import</li>
                                <li>After import, refresh this page to verify setup</li>
                            </ol>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50%">Table Name</th>
                                        <th width="20%">Status</th>
                                        <th width="30%">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $table_descriptions = array(
                                        'medicine_categories' => 'Medicine categories (Tablet, Syrup, etc.)',
                                        'medicine_manufacturers' => 'Medicine manufacturers/companies',
                                        'medicine_units' => 'Units of measurement (mg, ml, etc.)',
                                        'medicines' => 'Master medicine data',
                                        'medicine_batches' => 'Medicine batch/lot information',
                                        'medicine_suppliers' => 'Supplier information',
                                        'medicine_sales' => 'Sales transactions',
                                        'medicine_sale_items' => 'Sales line items',
                                        'medicine_purchases' => 'Purchase transactions',
                                        'medicine_purchase_items' => 'Purchase line items',
                                        'purchase_orders' => 'Purchase orders',
                                        'purchase_order_items' => 'Purchase order items',
                                        'prescriptions' => 'Doctor prescriptions',
                                        'prescription_items' => 'Prescription line items',
                                        'medicine_stock_adjustments' => 'Stock adjustment headers',
                                        'medicine_stock_adjustment_items' => 'Stock adjustment items',
                                        'medicine_returns' => 'Return transactions',
                                        'medicine_return_items' => 'Return line items',
                                        'medicine_frequencies' => 'Medicine frequency master',
                                        'daily_stock_summary' => 'Daily stock summary'
                                    );
                                    
                                    foreach ($tables_status as $table => $exists): ?>
                                        <tr>
                                            <td><code><?php echo $table; ?></code></td>
                                            <td>
                                                <?php if ($exists): ?>
                                                    <span class="label label-success">
                                                        <i class="fa fa-check"></i> Exists
                                                    </span>
                                                <?php else: ?>
                                                    <span class="label label-danger">
                                                        <i class="fa fa-times"></i> Missing
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo isset($table_descriptions[$table]) ? $table_descriptions[$table] : 'Pharmacy module table'; ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php 
                        $all_tables_exist = true;
                        foreach ($tables_status as $exists) {
                            if (!$exists) {
                                $all_tables_exist = false;
                                break;
                            }
                        }
                        ?>

                        <?php if ($all_tables_exist): ?>
                            <div class="alert alert-success">
                                <h4><i class="icon fa fa-check"></i> Setup Complete!</h4>
                                All required database tables are present. Your pharmacy module is ready to use.
                                <br><br>
                                <a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>" class="btn btn-success">
                                    <i class="fa fa-arrow-right"></i> Go to Pharmacy Dashboard
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <h4><i class="icon fa fa-warning"></i> Setup Required</h4>
                                Some required tables are missing. Please import the pharmacy_schema.sql file.
                                <br><br>
                                <button type="button" class="btn btn-primary" onclick="location.reload()">
                                    <i class="fa fa-refresh"></i> Check Again
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- SQL Import Instructions -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">SQL Import Instructions</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="display: none;">
                        <h4>Method 1: Using phpMyAdmin</h4>
                        <ol>
                            <li>Open phpMyAdmin in your browser</li>
                            <li>Select your AHMS database</li>
                            <li>Click on the "Import" tab</li>
                            <li>Click "Choose File" and select <code>pharmacy_schema.sql</code></li>
                            <li>Click "Go" to import</li>
                        </ol>

                        <h4>Method 2: Using MySQL Command Line</h4>
                        <pre><code>mysql -u username -p database_name < pharmacy_schema.sql</code></pre>

                        <h4>Method 3: Using MySQL Workbench</h4>
                        <ol>
                            <li>Open MySQL Workbench</li>
                            <li>Connect to your database server</li>
                            <li>Go to Server → Data Import</li>
                            <li>Select "Import from Self-Contained File"</li>
                            <li>Browse and select <code>pharmacy_schema.sql</code></li>
                            <li>Select your target schema</li>
                            <li>Click "Start Import"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Auto-refresh every 30 seconds to check table status
    setTimeout(function() {
        location.reload();
    }, 30000);
});
</script>
