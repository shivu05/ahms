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
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="no_of_items_in_packHelp">No.of items in pack:</label>
                                <input type="text" name="no_of_items_in_pack" id="no_of_items_in_pack" class="form-control required numbers-only" aria-describedby="no_of_items_in_packHelp" placeholder="Enter No.of items in pack" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="no_of_items_in_packHelp">Packing type:</label>
                                <select class="form-control" name="pack_type" id="pack_type">
                                    <option value="">Choose package type</option>
                                    <?php
                                    if (!empty($packaging_types)) {
                                        foreach ($packaging_types as $type) {
                                            echo '<option value="' . $type['pakg_name'] . '">' . $type['pakg_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="item_unit_costHelp">Item unit cost:</label>
                                <input type="text" name="item_unit_cost" id="item_unit_cost" class="form-control required numbers-only" aria-describedby="item_unit_costHelp" placeholder="Enter item unit cost" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="no_of_sub_itemsHelp">No.of sub items in item:</label>
                                <input type="text" name="no_of_sub_items" id="no_of_sub_items" class="form-control required numbers-only" aria-describedby="no_of_sub_itemsHelp" placeholder="Enter no.of sub items in item" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="sub_item_pack_typeHelp">Sub item Packing type:</label>
                                <select class="form-control" name="sub_item_pack_type" id="sub_item_pack_type">
                                    <option value="">Choose package type</option>
                                    <?php
                                    if (!empty($packaging_types)) {
                                        foreach ($packaging_types as $type) {
                                            echo '<option value="' . $type['pakg_name'] . '">' . $type['pakg_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="sub_item_unit_costHelp">Sub item unit cost:</label>
                                <input type="text" name="sub_item_unit_cost" id="sub_item_unit_cost" class="form-control required numbers-only" aria-describedby="sub_item_unit_costHelp" placeholder="Enter sub item unit cost" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="no_of_sub_items_in_packHelp">No.of sub items in pack:</label>
                                <input type="text" name="no_of_sub_items_in_pack" id="no_of_sub_items_in_pack" class="form-control required numbers-only" aria-describedby="no_of_sub_items_in_packHelp" placeholder="Enter no.of sub items in pack" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="mrpHelp">Purchase quantity:</label>
                                <input type="text" name="pqty" id="pqty" class="form-control required numbers-only" aria-describedby="pqtyHelp" placeholder="Enter Purchase quantity" />
                            </div>
                        </div>
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
                                <input type="text" name="weight" id="weight" class="form-control required" aria-describedby="weightHelp" placeholder="Enter weight" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="rackHelp">Rack:</label>
                                <input type="text" name="rack" id="rack" class="form-control required numbers-only" aria-describedby="rackHelp" placeholder="Enter Rack" />
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
        $('#no_of_items_in_pack').on('change', function () {
            var mrp = $('#mrp').val();
            var sale_rate = $('#sale_rate').val();
            if (mrp != '' && sale_rate != '' && sale_rate != 0) {
                var unit_price = sale_rate / $(this).val();
                $('#item_unit_cost').val(unit_price.toFixed(2));
            } else {
                alert('please enter MRP and Selling price');
                $("#no_of_items_in_pack").focus();
            }
        });

        $('#no_of_sub_items').on('change', function () {
            var unit_price = $('#item_unit_cost').val();
            if (unit_price != '' && unit_price != 0) {
                var sub_item_unit_cost = unit_price / $(this).val();
                $('#sub_item_unit_cost').val(sub_item_unit_cost.toFixed(2));
                var sub_items_in_pack = $('#no_of_items_in_pack').val() * $('#no_of_sub_items').val();
                $('#no_of_sub_items_in_pack').val(sub_items_in_pack);
            } else {
                alert('Unit price is invalid');
            }
        });
    });
</script>