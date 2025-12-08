<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cubes"></i>
            Inventory Management
            <small>Stock Management & Tracking</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">Inventory</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicine Stock Information</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addStockModal">
                                <i class="fa fa-plus"></i> Add Stock
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="inventoryTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Medicine Name</th>
                                        <th>Batch Number</th>
                                        <th>Expiry Date</th>
                                        <th>Current Stock</th>
                                        <th>MRP</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
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
    $('#inventoryTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('pharmacy_pro/pharmacy/get_inventory_data'); ?>",
            "type": "POST"
        },
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ]
    });
});
</script>
