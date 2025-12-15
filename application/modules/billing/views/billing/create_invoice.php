<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Create New Invoice</h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="<?php echo base_url('billing/create_invoice'); ?>" id="invoice-form">
            
            <!-- Invoice Type and Patient Selection -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice Type <span class="text-danger">*</span></label>
                        <select name="invoice_type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <?php if (isset($invoice_types) && !empty($invoice_types)): ?>
                                <?php foreach ($invoice_types as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Patient/Registration No <span class="text-danger">*</span></label>
                        <input type="text" name="patient_id" class="form-control" 
                               placeholder="Enter Patient ID / OPD No / IPD No" required>
                        <small class="text-muted">Enter the patient registration number</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" name="invoice_date" class="form-control" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>

            <!-- Service Selection -->
            <div class="row">
                <div class="col-md-12">
                    <h4>Services / Items</h4>
                    <div id="service-items">
                        <div class="row service-item mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Service Category <span class="text-danger">*</span></label>
                                    <select name="category_id[]" class="form-control category-select" required>
                                        <option value="">-- Select Category --</option>
                                        <?php if (isset($service_categories) && !empty($service_categories)): ?>
                                            <?php foreach ($service_categories as $category): ?>
                                                <option value="<?php echo $category['category_id']; ?>">
                                                    <?php echo $category['category_name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Service/Item <span class="text-danger">*</span></label>
                                    <select name="service_id[]" class="form-control service-select" required disabled>
                                        <option value="">-- Select Category First --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Qty <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity[]" class="form-control quantity-input" 
                                           value="1" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit Price <span class="text-danger">*</span></label>
                                    <input type="number" name="unit_price[]" class="form-control price-input" 
                                           step="0.01" min="0" required readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Disc(%)</label>
                                    <input type="number" name="discount[]" class="form-control discount-input" 
                                           value="0" min="0" max="100" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>GST(%)</label>
                                    <input type="number" name="gst_rate[]" class="form-control gst-input" 
                                           value="0" min="0" max="100" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block remove-service">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-info btn-sm" id="add-service">
                        <i class="fa fa-plus"></i> Add More Services
                    </button>
                </div>
            </div>

            <hr>

            <!-- Summary -->
            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Subtotal:</th>
                            <td class="text-right">₹<span id="subtotal">0.00</span></td>
                        </tr>
                        <tr>
                            <th>Total Discount:</th>
                            <td class="text-right">- ₹<span id="total-discount">0.00</span></td>
                        </tr>
                        <tr>
                            <th>GST:</th>
                            <td class="text-right">₹<span id="gst">0.00</span></td>
                        </tr>
                        <tr class="success">
                            <th><h4>Grand Total:</h4></th>
                            <td class="text-right"><h4>₹<span id="grand-total">0.00</span></h4></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Create Invoice
                    </button>
                    <a href="<?php echo base_url('billing/invoices'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    
    // Load services when category is selected
    $(document).on('change', '.category-select', function() {
        var $row = $(this).closest('.service-item');
        var categoryId = $(this).val();
        var $serviceSelect = $row.find('.service-select');
        
        if (categoryId) {
            $.ajax({
                url: '<?php echo base_url('billing/get_services_by_category'); ?>',
                method: 'POST',
                data: { category_id: categoryId },
                dataType: 'json',
                success: function(response) {
                    $serviceSelect.html('<option value="">-- Select Service --</option>');
                    if (response.success && response.services.length > 0) {
                        $.each(response.services, function(i, service) {
                            $serviceSelect.append(
                                '<option value="' + service.service_id + '" ' +
                                'data-price="' + service.unit_price + '" ' +
                                'data-gst="' + service.gst_rate + '">' +
                                service.service_name + ' (₹' + service.unit_price + ')' +
                                '</option>'
                            );
                        });
                        $serviceSelect.prop('disabled', false);
                    } else {
                        $serviceSelect.html('<option value="">No services found</option>');
                        $serviceSelect.prop('disabled', true);
                    }
                },
                error: function() {
                    alert('Error loading services');
                }
            });
        } else {
            $serviceSelect.html('<option value="">-- Select Category First --</option>');
            $serviceSelect.prop('disabled', true);
        }
    });
    
    // Update price and GST when service is selected
    $(document).on('change', '.service-select', function() {
        var $row = $(this).closest('.service-item');
        var $option = $(this).find(':selected');
        var price = $option.data('price') || 0;
        var gst = $option.data('gst') || 0;
        
        $row.find('.price-input').val(price);
        $row.find('.gst-input').val(gst);
        calculateTotal();
    });
    
    // Calculate total on quantity, discount change
    $(document).on('input', '.quantity-input, .discount-input', function() {
        calculateTotal();
    });
    
    // Add more services
    $('#add-service').on('click', function() {
        var $firstItem = $('.service-item:first');
        var $newItem = $firstItem.clone();
        
        // Reset all values
        $newItem.find('select').val('').prop('disabled', false);
        $newItem.find('input').val('');
        $newItem.find('.quantity-input').val('1');
        $newItem.find('.discount-input, .gst-input').val('0');
        $newItem.find('.price-input').prop('readonly', true);
        $newItem.find('.service-select').prop('disabled', true).html('<option value="">-- Select Category First --</option>');
        
        $('#service-items').append($newItem);
        calculateTotal();
    });
    
    // Remove service
    $(document).on('click', '.remove-service', function() {
        if ($('.service-item').length > 1) {
            $(this).closest('.service-item').remove();
            calculateTotal();
        } else {
            alert('At least one service item is required');
        }
    });
    
    // Calculate totals
    function calculateTotal() {
        var subtotal = 0;
        var totalDiscount = 0;
        var totalGst = 0;
        
        $('.service-item').each(function() {
            var qty = parseFloat($(this).find('.quantity-input').val()) || 0;
            var price = parseFloat($(this).find('.price-input').val()) || 0;
            var discountPct = parseFloat($(this).find('.discount-input').val()) || 0;
            var gstPct = parseFloat($(this).find('.gst-input').val()) || 0;
            
            var itemTotal = qty * price;
            var itemDiscount = (itemTotal * discountPct) / 100;
            var taxableAmount = itemTotal - itemDiscount;
            var itemGst = (taxableAmount * gstPct) / 100;
            
            subtotal += itemTotal;
            totalDiscount += itemDiscount;
            totalGst += itemGst;
        });
        
        var grandTotal = subtotal - totalDiscount + totalGst;
        
        $('#subtotal').text(subtotal.toFixed(2));
        $('#total-discount').text(totalDiscount.toFixed(2));
        $('#gst').text(totalGst.toFixed(2));
        $('#grand-total').text(grandTotal.toFixed(2));
    }
    
    // Form validation
    $('#invoice-form').on('submit', function(e) {
        var hasValidService = false;
        $('.service-select').each(function() {
            if ($(this).val()) {
                hasValidService = true;
                return false;
            }
        });
        
        if (!hasValidService) {
            e.preventDefault();
            alert('Please select at least one service');
            return false;
        }
    });
    
    // Initial calculation
    calculateTotal();
});
</script>
