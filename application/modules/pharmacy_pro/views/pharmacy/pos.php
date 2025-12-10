<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-shopping-cart"></i>
            Point of Sale (POS)
            <small>Medicine Sales & Billing</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">POS</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Point of Sale System</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-success" onclick="clearSale()">
                                <i class="fa fa-refresh"></i> New Sale
                            </button>
                        </div>
                    </div>
            <div class="box-body">
                <form id="posForm">
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-heading">Customer Information</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>Patient Type</label>
                                        <select class="form-control" name="patient_type" id="patient_type">
                                            <option value="walk_in">Walk-in Customer</option>
                                            <option value="opd">OPD Patient</option>
                                            <option value="ipd">IPD Patient</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" id="patient_search_group" style="display: none;">
                                        <label>Search Patient</label>
                                        <input type="text" class="form-control" id="patient_search" placeholder="Search by OPD/IPD number or name">
                                        <input type="hidden" id="patient_id" name="patient_id">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Patient Name *</label>
                                        <input type="text" class="form-control" name="patient_name" id="patient_name" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="patient_phone" id="patient_phone">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Doctor</label>
                                        <select class="form-control chosen-select" name="doctor_id" id="doctor_id">
                                            <option value="">Select Doctor</option>
                                            <?php if (isset($doctors) && !empty($doctors)): ?>
                                                <?php foreach($doctors as $doctor): ?>
                                                    <option value="<?php echo $doctor['ID']; ?>"><?php echo $doctor['user_name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Prescription ID</label>
                                        <input type="text" class="form-control" name="prescription_id" id="prescription_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sale Type -->
                        <div class="col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Sale Information</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>Sale Type</label>
                                        <select class="form-control" name="sale_type" id="sale_type">
                                            <option value="prescription">Prescription</option>
                                            <option value="otc">Over The Counter</option>
                                            <option value="emergency">Emergency</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Payment Mode</label>
                                        <select class="form-control" name="payment_mode" id="payment_mode">
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="online">Online</option>
                                            <option value="credit">Credit</option>
                                            <option value="insurance">Insurance</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medicine Selection -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4>Medicine Selection</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Search Medicine</label>
                                <input type="text" class="form-control" id="medicine_search" placeholder="Type medicine name to search...">
                            </div>
                            
                            <!-- Selected Medicines Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="sale_items_table">
                                    <thead>
                                        <tr>
                                            <th width="25%">Medicine</th>
                                            <th width="15%">Batch</th>
                                            <th width="10%">Expiry</th>
                                            <th width="8%">MRP</th>
                                            <th width="8%">Qty</th>
                                            <th width="8%">Disc%</th>
                                            <th width="10%">Amount</th>
                                            <th width="8%">GST%</th>
                                            <th width="8%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Billing Summary -->
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Space for additional information -->
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-success">
                                <div class="panel-heading">Bill Summary</div>
                                <div class="panel-body">
                                    <table class="table table-condensed">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-right">₹<span id="subtotal_display">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>Total Discount:</td>
                                            <td class="text-right">₹<span id="total_discount_display">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>Total GST:</td>
                                            <td class="text-right">₹<span id="total_gst_display">0.00</span></td>
                                        </tr>
                                        <tr class="success">
                                            <td><strong>Grand Total:</strong></td>
                                            <td class="text-right"><strong>₹<span id="grand_total_display">0.00</span></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Amount Paid:</td>
                                            <td class="text-right">
                                                <input type="number" class="form-control input-sm text-right" id="paid_amount" name="paid_amount" value="0" step="0.01" onchange="calculateBalance()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Balance:</td>
                                            <td class="text-right">₹<span id="balance_display">0.00</span></td>
                                        </tr>
                                    </table>
                                    
                                    <button type="button" class="btn btn-success btn-block btn-lg" onclick="processSale()">
                                        <i class="fa fa-shopping-cart"></i> Process Sale
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for totals -->
                    <input type="hidden" id="subtotal" name="subtotal" value="0">
                    <input type="hidden" id="discount_amount" name="discount_amount" value="0">
                    <input type="hidden" id="tax_amount" name="tax_amount" value="0">
                    <input type="hidden" id="total_amount" name="total_amount" value="0">
                </form>
            </div>
        </div>
    </div>
</div>
    </section>
</div>

<!-- Medicine Selection Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Medicine Batch</h4>
            </div>
            <div class="modal-body">
                <div id="batch_selection_area">
                    <!-- Batch selection will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var saleItems = [];
var itemCounter = 0;

$(document).ready(function() {
    // Initialize chosen selects
    $('.chosen-select').chosen({width: '100%'});
    
    // Patient type change handler
    $('#patient_type').change(function() {
        if ($(this).val() !== 'walk_in') {
            $('#patient_search_group').show();
            $('#patient_search').attr('required', true);
        } else {
            $('#patient_search_group').hide();
            $('#patient_search').attr('required', false);
            $('#patient_id').val('');
        }
    });
    
    // Medicine search autocomplete
    $('#medicine_search').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '<?php echo site_url("pharmacy_pro/pharmacy/search_medicines"); ?>',
                type: 'GET',
                data: {term: request.term},
                success: function(data) {
                    response(JSON.parse(data));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            selectMedicine(ui.item);
            $(this).val('');
            return false;
        }
    });
    
    // Patient search autocomplete
    $('#patient_search').autocomplete({
        source: function(request, response) {
            var patient_type = $('#patient_type').val();
            $.ajax({
                url: '<?php echo site_url("pharmacy_pro/pharmacy/search_patients"); ?>',
                type: 'GET',
                data: {term: request.term, type: patient_type},
                success: function(data) {
                    response(JSON.parse(data));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $('#patient_id').val(ui.item.id);
            $('#patient_name').val(ui.item.name);
            $('#patient_phone').val(ui.item.phone);
            return false;
        }
    });
});

function selectMedicine(medicine) {
    // Get available batches for the medicine
    $.ajax({
        url: '<?php echo site_url("pharmacy_pro/pharmacy/get_medicine_batch_details"); ?>',
        type: 'POST',
        data: {medicine_id: medicine.id},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success' && data.data.length > 0) {
                showBatchSelection(medicine, data.data);
            } else {
                alert('No available batches for this medicine');
            }
        }
    });
}

function showBatchSelection(medicine, batches) {
    var html = '<h5>' + medicine.medicine_name + ' - ' + medicine.strength + '</h5>';
    html += '<table class="table table-striped">';
    html += '<thead><tr><th>Batch</th><th>Expiry</th><th>MRP</th><th>Stock</th><th>Action</th></tr></thead>';
    html += '<tbody>';
    
    batches.forEach(function(batch) {
        html += '<tr>';
        html += '<td>' + batch.batch_number + '</td>';
        html += '<td>' + batch.expiry_date + '</td>';
        html += '<td>₹' + parseFloat(batch.selling_price).toFixed(2) + '</td>';
        html += '<td>' + batch.current_stock + '</td>';
        html += '<td><button type="button" class="btn btn-primary btn-sm" onclick="addToSale(' + 
                JSON.stringify(medicine).replace(/"/g, '&quot;') + ', ' + 
                JSON.stringify(batch).replace(/"/g, '&quot;') + ')">Add</button></td>';
        html += '</tr>';
    });
    
    html += '</tbody></table>';
    
    $('#batch_selection_area').html(html);
    $('#medicineModal').modal('show');
}

function addToSale(medicine, batch) {
    var existingIndex = -1;
    
    // Check if item already exists
    for (var i = 0; i < saleItems.length; i++) {
        if (saleItems[i].medicine_id == medicine.id && saleItems[i].batch_id == batch.id) {
            existingIndex = i;
            break;
        }
    }
    
    if (existingIndex >= 0) {
        // Increase quantity
        saleItems[existingIndex].quantity++;
        updateSaleItem(existingIndex);
    } else {
        // Add new item
        var item = {
            id: ++itemCounter,
            medicine_id: medicine.id,
            medicine_name: medicine.medicine_name,
            strength: medicine.strength,
            batch_id: batch.id,
            batch_number: batch.batch_number,
            expiry_date: batch.expiry_date,
            mrp: parseFloat(batch.selling_price),
            quantity: 1,
            discount_percentage: 0,
            discount_amount: 0,
            gst_percentage: parseFloat(batch.gst_percentage || 0),
            gst_amount: 0,
            unit_price: parseFloat(batch.selling_price),
            total_amount: parseFloat(batch.selling_price)
        };
        
        saleItems.push(item);
        addSaleItemRow(item);
    }
    
    calculateTotals();
    $('#medicineModal').modal('hide');
}

function addSaleItemRow(item) {
    var row = '<tr id="item_row_' + item.id + '">';
    row += '<td>' + item.medicine_name + ' - ' + item.strength + '</td>';
    row += '<td>' + item.batch_number + '</td>';
    row += '<td>' + item.expiry_date + '</td>';
    row += '<td>₹' + item.mrp.toFixed(2) + '</td>';
    row += '<td><input type="number" class="form-control input-sm" value="' + item.quantity + '" min="1" onchange="updateQuantity(' + item.id + ', this.value)"></td>';
    row += '<td><input type="number" class="form-control input-sm" value="' + item.discount_percentage + '" min="0" max="100" step="0.01" onchange="updateDiscount(' + item.id + ', this.value)"></td>';
    row += '<td class="text-right">₹<span id="amount_' + item.id + '">' + item.total_amount.toFixed(2) + '</span></td>';
    row += '<td>' + item.gst_percentage.toFixed(2) + '%</td>';
    row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeSaleItem(' + item.id + ')"><i class="fa fa-trash"></i></button></td>';
    row += '</tr>';
    
    $('#sale_items_table tbody').append(row);
}

function updateQuantity(itemId, newQuantity) {
    var index = saleItems.findIndex(item => item.id == itemId);
    if (index >= 0) {
        saleItems[index].quantity = parseInt(newQuantity);
        updateSaleItem(index);
        calculateTotals();
    }
}

function updateDiscount(itemId, discountPercentage) {
    var index = saleItems.findIndex(item => item.id == itemId);
    if (index >= 0) {
        saleItems[index].discount_percentage = parseFloat(discountPercentage);
        updateSaleItem(index);
        calculateTotals();
    }
}

function updateSaleItem(index) {
    var item = saleItems[index];
    var subtotal = item.unit_price * item.quantity;
    item.discount_amount = (subtotal * item.discount_percentage) / 100;
    var discounted_amount = subtotal - item.discount_amount;
    item.gst_amount = (discounted_amount * item.gst_percentage) / 100;
    item.total_amount = discounted_amount + item.gst_amount;
    
    $('#amount_' + item.id).text(item.total_amount.toFixed(2));
}

function removeSaleItem(itemId) {
    var index = saleItems.findIndex(item => item.id == itemId);
    if (index >= 0) {
        saleItems.splice(index, 1);
        $('#item_row_' + itemId).remove();
        calculateTotals();
    }
}

function calculateTotals() {
    var subtotal = 0;
    var totalDiscount = 0;
    var totalGST = 0;
    var grandTotal = 0;
    
    saleItems.forEach(function(item) {
        subtotal += item.unit_price * item.quantity;
        totalDiscount += item.discount_amount;
        totalGST += item.gst_amount;
        grandTotal += item.total_amount;
    });
    
    $('#subtotal').val(subtotal.toFixed(2));
    $('#discount_amount').val(totalDiscount.toFixed(2));
    $('#tax_amount').val(totalGST.toFixed(2));
    $('#total_amount').val(grandTotal.toFixed(2));
    
    $('#subtotal_display').text(subtotal.toFixed(2));
    $('#total_discount_display').text(totalDiscount.toFixed(2));
    $('#total_gst_display').text(totalGST.toFixed(2));
    $('#grand_total_display').text(grandTotal.toFixed(2));
    
    calculateBalance();
}

function calculateBalance() {
    var total = parseFloat($('#total_amount').val() || 0);
    var paid = parseFloat($('#paid_amount').val() || 0);
    var balance = total - paid;
    
    $('#balance_display').text(balance.toFixed(2));
}

function processSale() {
    if (saleItems.length === 0) {
        alert('Please add at least one medicine to the sale');
        return;
    }
    
    if (!$('#patient_name').val().trim()) {
        alert('Please enter patient name');
        $('#patient_name').focus();
        return;
    }
    
    // Prepare form data
    var formData = $('#posForm').serialize();
    formData += '&sale_items=' + encodeURIComponent(JSON.stringify(saleItems));
    
    $.ajax({
        url: '<?php echo site_url("pharmacy_pro/pharmacy/process_sale"); ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                alert('Sale completed successfully! Bill Number: ' + data.bill_number);
                // Open print window
                window.open('<?php echo site_url("pharmacy_pro/pharmacy/print_invoice/"); ?>' + data.sale_id, '_blank');
                clearSale();
            } else {
                alert('Error: ' + data.message);
            }
        },
        error: function() {
            alert('An error occurred while processing the sale');
        }
    });
}

function clearSale() {
    saleItems = [];
    itemCounter = 0;
    $('#posForm')[0].reset();
    $('#sale_items_table tbody').empty();
    $('#patient_search_group').hide();
    $('.chosen-select').trigger('chosen:updated');
    calculateTotals();
}
</script>
