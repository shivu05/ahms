<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cubes"></i>
            Stock Report
            <small>Current Stock Status</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy/reports'); ?>">Reports</a></li>
            <li class="active">Stock Report</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Current Stock Status</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" onclick="window.print()">
                                <i class="fa fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            This report shows the current stock levels of all medicines in your inventory.
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="stockTable">
                                <thead>
                                    <tr>
                                        <th>Medicine Name</th>
                                        <th>Category</th>
                                        <th>Manufacturer</th>
                                        <th>Total Stock</th>
                                        <th>Reorder Level</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <em>Please import the database schema to view stock data</em>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#stockTable').DataTable({
        "order": [[0, "asc"]],
        "pageLength": 25
    });
});
</script>
