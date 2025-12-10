<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cogs"></i>
            Pharmacy Settings
            <small>Configuration & Master Data</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>

    <section class="content">
        <!-- Categories Management -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicine Categories</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addCategoryModal">
                                <i class="fa fa-plus"></i> Add Category
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($categories) && !empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?php echo $category->category_name; ?></td>
                                                <td><?php echo $category->description; ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary">Edit</button>
                                                    <button class="btn btn-xs btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <em>Import database schema to manage categories</em>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manufacturers Management -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manufacturers</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addManufacturerModal">
                                <i class="fa fa-plus"></i> Add Manufacturer
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Manufacturer Name</th>
                                        <th>Country</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($manufacturers) && !empty($manufacturers)): ?>
                                        <?php foreach ($manufacturers as $manufacturer): ?>
                                            <tr>
                                                <td><?php echo isset($manufacturer['manufacturer_name']) ? $manufacturer['manufacturer_name'] : $manufacturer->company_name; ?></td>
                                                <td><?php echo $manufacturer->country; ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary">Edit</button>
                                                    <button class="btn btn-xs btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <em>Import database schema to manage manufacturers</em>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers & Units -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Suppliers</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addSupplierModal">
                                <i class="fa fa-plus"></i> Add Supplier
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Contact</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($suppliers) && !empty($suppliers)): ?>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <tr>
                                                <td><?php echo $supplier->supplier_name; ?></td>
                                                <td><?php echo $supplier->contact_person . ' - ' . $supplier->phone; ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary">Edit</button>
                                                    <button class="btn btn-xs btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <em>Import database schema to manage suppliers</em>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Units Management -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicine Units</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addUnitModal">
                                <i class="fa fa-plus"></i> Add Unit
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Unit Name</th>
                                        <th>Symbol</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($units) && !empty($units)): ?>
                                        <?php foreach ($units as $unit): ?>
                                            <tr>
                                                <td><?php echo $unit->unit_name; ?></td>
                                                <td><?php echo $unit->symbol; ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary">Edit</button>
                                                    <button class="btn btn-xs btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <em>Import database schema to manage units</em>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">System Configuration</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>General Settings</h4>
                                <div class="form-group">
                                    <label>Default Tax Rate (%)</label>
                                    <input type="number" class="form-control" value="18" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Default Discount (%)</label>
                                    <input type="number" class="form-control" value="0" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Low Stock Alert Days</label>
                                    <input type="number" class="form-control" value="30">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Business Information</h4>
                                <div class="form-group">
                                    <label>Pharmacy Name</label>
                                    <input type="text" class="form-control" value="AHMS Pharmacy">
                                </div>
                                <div class="form-group">
                                    <label>Drug License Number</label>
                                    <input type="text" class="form-control" placeholder="Enter license number">
                                </div>
                                <div class="form-group">
                                    <label>Pharmacist Name</label>
                                    <input type="text" class="form-control" placeholder="Enter pharmacist name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success">
                                    <i class="fa fa-save"></i> Save Settings
                                </button>
                                <a href="<?php echo site_url('pharmacy_pro/pharmacy/backup_data'); ?>" class="btn btn-info">
                                    <i class="fa fa-download"></i> Backup Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Initialize any settings functionality here
    console.log('Settings page loaded');
});
</script>
