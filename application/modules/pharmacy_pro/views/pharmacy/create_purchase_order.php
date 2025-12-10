<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus"></i>
            Create Purchase Order
            <small>Create New Purchase Order</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy/purchases'); ?>">Purchases</a></li>
            <li class="active">Create PO</li>
        </ol>
    </section>

    <section class="content">
        <form id="purchaseOrderForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Purchase Order Details</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier *</label>
                                        <select name="supplier_id" class="form-control" required>
                                            <option value="">Select Supplier</option>
                                            <?php if(isset($suppliers)): ?>
                                                <?php foreach($suppliers as $supplier): ?>
                                                    <option value="<?php echo $supplier->id; ?>"><?php echo $supplier->supplier_name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PO Date *</label>
                                        <input type="date" name="po_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expected Delivery Date</label>
                                        <input type="date" name="expected_delivery_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Terms</label>
                                        <select name="payment_terms" class="form-control">
                                            <option value="Cash">Cash</option>
                                            <option value="Credit-15">Credit - 15 Days</option>
                                            <option value="Credit-30">Credit - 30 Days</option>
                                            <option value="Credit-45">Credit - 45 Days</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Items</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-sm btn-success" id="addItemBtn">
                                    <i class="fa fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th width="30%">Medicine</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Unit Price</th>
                                            <th width="15%">Discount %</th>
                                            <th width="15%">Total</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        <!-- Items will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Summary</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-condensed">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-right">₹<span id="subtotal">0.00</span></td>
                                </tr>
                                <tr>
                                    <td>Tax (GST):</td>
                                    <td class="text-right">₹<span id="tax">0.00</span></td>
                                </tr>
                                <tr class="info">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-right"><strong>₹<span id="grandTotal">0.00</span></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Additional Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Any special instructions or notes..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-body">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-save"></i> Save Purchase Order
                            </button>
                            <a href="<?php echo site_url('pharmacy_pro/pharmacy/purchases'); ?>" class="btn btn-default btn-block">
                                <i class="fa fa-arrow-left"></i> Back to Purchases
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
$(document).ready(function() {
    let itemCounter = 0;
    
    // Add new item row
    $('#addItemBtn').click(function() {
        addItemRow();
    });
    
    function addItemRow() {
        itemCounter++;
        let html = `
            <tr id="item_${itemCounter}">
                <td>
                    <select name="items[${itemCounter}][medicine_id]" class="form-control medicine-select" required>
                        <option value="">Select Medicine</option>
                        <?php if(isset($medicines)): ?>
                            <?php foreach($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->id; ?>"><?php echo $medicine->medicine_name; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemCounter}][quantity]" class="form-control item-qty" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="items[${itemCounter}][unit_price]" class="form-control item-price" step="0.01" min="0" required>
                </td>
                <td>
                    <input type="number" name="items[${itemCounter}][discount_percent]" class="form-control item-discount" step="0.01" min="0" max="100" value="0">
                </td>
                <td>
                    <input type="text" class="form-control item-total" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsTableBody').append(html);
        bindItemEvents();
    }
    
    // Bind events to item rows
    function bindItemEvents() {
        $('.item-qty, .item-price, .item-discount').off('keyup change').on('keyup change', function() {
            calculateItemTotal($(this).closest('tr'));
            calculateGrandTotal();
        });
        
        $('.remove-item').off('click').on('click', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });
    }
    
    // Calculate item total
    function calculateItemTotal(row) {
        let qty = parseFloat(row.find('.item-qty').val()) || 0;
        let price = parseFloat(row.find('.item-price').val()) || 0;
        let discount = parseFloat(row.find('.item-discount').val()) || 0;
        
        let subtotal = qty * price;
        let discountAmount = (subtotal * discount) / 100;
        let total = subtotal - discountAmount;
        
        row.find('.item-total').val(total.toFixed(2));
    }
    
    // Calculate grand total
    function calculateGrandTotal() {
        let subtotal = 0;
        $('.item-total').each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });
        
        let tax = subtotal * 0.18; // 18% GST
        let grandTotal = subtotal + tax;
        
        $('#subtotal').text(subtotal.toFixed(2));
        $('#tax').text(tax.toFixed(2));
        $('#grandTotal').text(grandTotal.toFixed(2));
    }
    
    // Handle form submission
    $('#purchaseOrderForm').on('submit', function(e) {
        e.preventDefault();
        
        if ($('#itemsTableBody tr').length === 0) {
            alert('Please add at least one item to the purchase order.');
            return;
        }
        
        let formData = $(this).serialize();
        let subtotal = $('#subtotal').text();
        let tax = $('#tax').text();
        let total = $('#grandTotal').text();
        
        formData += '&subtotal=' + subtotal + '&tax_amount=' + tax + '&total_amount=' + total;
        
        $.ajax({
            url: '<?php echo site_url("pharmacy_pro/pharmacy/save_purchase_order"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Purchase order created successfully! PO Number: ' + response.po_number);
                    window.location.href = '<?php echo site_url("pharmacy_pro/pharmacy/purchases"); ?>';
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred while creating the purchase order.');
            }
        });
    });
    
    // Add first row on load
    addItemRow();
});
</script>
