<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-info-circle"></i>
            Drug Information
            <small>Medicine Database & Information</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">Drug Info</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Drug Information</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Search Medicine</label>
                                    <input type="text" id="medicineSearch" class="form-control" placeholder="Enter medicine name to search...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-primary" id="searchBtn">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drug Information Display -->
        <div class="row" id="drugInfoSection" style="display: none;">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicine Information</h3>
                    </div>
                    <div class="box-body" id="drugInfoContent">
                        <!-- Drug information will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reference -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Reference</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-blue"><i class="fa fa-medkit"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Medicines</span>
                                        <span class="info-box-number">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green"><i class="fa fa-tags"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Categories</span>
                                        <span class="info-box-number">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-industry"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Manufacturers</span>
                                        <span class="info-box-number">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Rx Required</span>
                                        <span class="info-box-number">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Note:</strong> Please import the database schema to access complete drug information database.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Medicine search functionality
    $('#searchBtn').click(function() {
        let medicineName = $('#medicineSearch').val().trim();
        
        if (!medicineName) {
            alert('Please enter a medicine name to search');
            return;
        }
        
        $.ajax({
            url: '<?php echo site_url("pharmacy_pro/pharmacy/get_drug_info"); ?>',
            type: 'POST',
            data: { medicine_name: medicineName },
            dataType: 'json',
            beforeSend: function() {
                $('#searchBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Searching...');
            },
            success: function(response) {
                if (response && response.success) {
                    displayDrugInfo(response.data);
                    $('#drugInfoSection').show();
                } else {
                    $('#drugInfoContent').html('<div class="alert alert-danger">Medicine not found in database.</div>');
                    $('#drugInfoSection').show();
                }
            },
            error: function() {
                $('#drugInfoContent').html('<div class="alert alert-warning">Database schema not imported. Please import pharmacy_schema.sql first.</div>');
                $('#drugInfoSection').show();
            },
            complete: function() {
                $('#searchBtn').prop('disabled', false).html('<i class="fa fa-search"></i> Search');
            }
        });
    });
    
    // Enter key search
    $('#medicineSearch').keypress(function(e) {
        if (e.which == 13) {
            $('#searchBtn').click();
        }
    });
    
    function displayDrugInfo(data) {
        let html = `
            <div class="row">
                <div class="col-md-6">
                    <h4>${data.medicine_name}</h4>
                    <table class="table table-bordered">
                        <tr><th>Generic Name:</th><td>${data.generic_name || 'N/A'}</td></tr>
                        <tr><th>Category:</th><td>${data.category_name || 'N/A'}</td></tr>
                        <tr><th>Manufacturer:</th><td>${data.manufacturer_name || 'N/A'}</td></tr>
                        <tr><th>Medicine Type:</th><td>${data.medicine_type || 'N/A'}</td></tr>
                        <tr><th>Strength:</th><td>${data.strength || 'N/A'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Additional Information</h4>
                    <table class="table table-bordered">
                        <tr><th>Composition:</th><td>${data.composition || 'N/A'}</td></tr>
                        <tr><th>Therapeutic Class:</th><td>${data.therapeutic_class || 'N/A'}</td></tr>
                        <tr><th>Prescription Required:</th><td>${data.prescription_required == 1 ? 'Yes' : 'No'}</td></tr>
                        <tr><th>Current Stock:</th><td>${data.current_stock || '0'}</td></tr>
                        <tr><th>Reorder Level:</th><td>${data.reorder_level || 'Not Set'}</td></tr>
                    </table>
                </div>
            </div>
        `;
        
        $('#drugInfoContent').html(html);
    }
});
</script>
