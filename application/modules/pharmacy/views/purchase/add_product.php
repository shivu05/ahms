<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <h5>Add new product: <a class="pull-right btn btn-primary" href="<?php echo base_url('product-list'); ?>"><i class="fa fa-eye"></i> View products</a></h5>
            </div>
            <div class="row tile-body">
                <form class="form-horizontal col-md-12" method="POST" name="add_product" id="add_product" action="<?php echo base_url('save-product'); ?>">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="Inputsupplier">Supplier:</label>
                                <select class="form-control required chosen-select" name="supplier" id="supplier">
                                    <option value="">Choose supplier</option>
                                    <?php
                                    if (!empty($suppliers)) {
                                        foreach ($suppliers as $supplier) {
                                            echo '<option value="' . $supplier['id'] . '">' . $supplier['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product ID:</label>
                                <input type="text" name="product_unique_id" id="product_unique_id" class="form-control" value="<?php echo $this->uuid->v4(); ?>" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product name:</label>
                                <select class="form-control required chosen-select" name="product_id" id="product_id">
                                    <option value="">Choose product</option>
                                    <?php
                                    if (!empty($products)) {
                                        foreach ($products as $product) {
                                            echo '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="batchHelp">Batch Number:</label>
                                <input class="form-control required" id="batch_no" name="batch_no" type="text" aria-describedby="batchHelp" placeholder="Enter batch number">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="mfrHelp">MFR: </label>
                                <select class="form-control required chosen-select" name="mfr" id="mfr">
                                    <option value="">Choose MFR</option>
                                    <?php
                                    if (!empty($mfgs)) {
                                        foreach ($mfgs as $mfg) {
                                            echo '<option value="' . $mfg['name'] . '">' . $mfg['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="groupHelp">Packaging details:</label>
                                <input type="text" name="packing" id="packing" class="form-control required" placeholder="Enter package details" />
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="groupHelp">Product category:</label>
                                <select class="form-control required chosen-select" name="category" id="category">
                                    <option value="">Choose Category</option>
                                    <?php
                                    if (!empty($categories)) {
                                        foreach ($categories as $category) {
                                            echo '<option value="' . $category['name'] . '">' . $category['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="groupHelp">Product Group:</label>
                                <select class="form-control required chosen-select" name="group" id="group">
                                    <option value="">Choose Group</option>
                                    <?php
                                    if (!empty($groups)) {
                                        foreach ($groups as $group) {
                                            echo '<option value="' . $group['name'] . '">' . $group['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="vatHelp">VAT:</label>
                                <input type="text" name="vat" id="vat" class="form-control required numbers-only" aria-describedby="vatHelp" placeholder="Enter VAT" value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="purchase_rateHelp">Purchase rate:</label>
                                <input type="text" name="purchase_rate" id="purchase_rate" class="form-control required numbers-only" aria-describedby="purchase_rateHelp" placeholder="Enter purchase rate" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="mrpHelp">MRP:</label>
                                <input type="text" name="mrp" id="mrp" class="form-control required numbers-only" aria-describedby="mrpHelp" placeholder="Enter MRP" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="sale_rateHelp">Selling price:</label>
                                <input type="text" name="sale_rate" id="sale_rate" class="form-control required numbers-only" aria-describedby="sale_rateHelp" placeholder="Enter sale rate" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="mrpHelp">Discount:</label>
                                <input type="text" name="discount" id="discount" class="form-control required numbers-only" aria-describedby="discountHelp" placeholder="Enter Discount" value="0" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="manufacturing_dateHelp">Manufacturing date:</label>
                                <input type="text" name="manufacturing_date" id="manufacturing_date" class="form-control required date_picker" aria-describedby="manufacturing_dateHelp" placeholder="Enter manufacturing date" />
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="packingHelp">Expiry date:</label>
                                <input type="text" name="expiry_date" id="expiry_date" class="form-control required date_picker" aria-describedby="packingHelp" placeholder="Enter expiry date" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="weightHelp">Weight:</label>
                                <input type="text" name="weight" id="weight" class="form-control required" aria-describedby="weightHelp" placeholder="Enter Weight" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="order_levelHelp">Order level:</label>
                                <input type="text" name="order_level" id="order_level" class="form-control required numbers-only" aria-describedby="order_levelHelp" placeholder="Enter Order level" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="reorder_pointHelp">Re-order point:</label>
                                <input type="text" name="reorder_point" id="reorder_point" class="form-control required numbers-only" aria-describedby="reorder_pointHelp" placeholder="Enter Re-order point" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tile-footer">
                <button class="btn btn-primary" name="submit" id="submit" type="submit"><i class="fa fa-save"></i> Save product</button>
                <button class="btn btn-danger" type="button"><i class="fa fa-refresh"></i> Reset</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });

    $(document).ready(function () {
        var validator = $('#add_product').validate();
        $('#submit').on('click', function () {
            if ($('#add_product').valid()) {
                var form_data = $('#add_product').serializeArray();
                $.ajax({
                    url: base_url + 'save-product',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (response) {
                        if (response.status) {
                            $('#add_product')[0].reset();
                            validator.resetForm();
                            $('.chosen-select').val('').trigger('chosen:updated');
                            $('#product_unique_id').val(response.uuid);
                            $.notify({
                                title: "Product information:",
                                message: "Added successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                        } else {
                            $.notify({
                                title: "Product information:",
                                message: "Failed to add please try again.",
                                icon: 'fa fa-times'
                            }, {
                                type: "danger"
                            });
                        }
                    }
                });
            }
        });
        
        $('#mrp').on('change', function () {
            $('#sale_rate').val($(this).val());
        });
    });
</script>