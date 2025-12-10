<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-file-text"></i>
            Prescription Management
            <small>Doctor Prescriptions</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">Prescriptions</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Prescription List</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addPrescriptionModal">
                                <i class="fa fa-plus"></i> Add Prescription
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="prescriptionsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Prescription No</th>
                                        <th>Date</th>
                                        <th>Patient Name</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <em>Please import the database schema to view prescriptions</em>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-3">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-file-text"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Prescriptions</span>
                        <span class="info-box-number">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending</span>
                        <span class="info-box-number">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dispensed</span>
                        <span class="info-box-number">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today</span>
                        <span class="info-box-number">0</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#prescriptionsTable').DataTable({
        "processing": true,
        "serverSide": false,
        "order": [[1, "desc"]]
    });
});
</script>
