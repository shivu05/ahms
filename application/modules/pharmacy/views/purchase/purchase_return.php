<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">

            </div>
            <div class="row tile-body">
                <div class="purchase_return col-lg-12">
                    <div class="card">
                        <h4 class="card-header">Product list:</h4>
                        <div class="card-body">
                            <table id="table_purchase_return" class="table table-bordered dataTable" width="100%"></table>
                        </div>
                        <div class="card-footer text-muted"></div>
                    </div>
                </div><!-- purchase_return -->
                <div class="clearfix"></div>
                <div class="col-lg-12" style="margin-top: 1%;">
                    <h4 class="mb-3 line-head" id="type-blockquotes">Add products to return:</h4>
                </div>
                <div class="col-lg-3">
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
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="Inputsupplier">Bill No.:</label>
                        <select class="form-control required chosen-select" name="bill_no" id="bill_no">
                            <option value="">Choose Bill No</option>
                            <?php
                            if (!empty($bill_nos)) {
                                foreach ($bill_nos as $billno) {
                                    echo '<option value="' . $billno['billno'] . '">' . $billno['billno'] . '</option>';
                                }
                            }
                            ?>
                        </select> 
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="Inputsupplier">Date:</label>
                        <input class="form-control required date_picker" type="text" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" />
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="Inputsupplier">Reference No.:</label>
                        <select class="form-control required chosen-select" name="reference_no" id="reference_no">
                            <option value="">Choose Reference No</option>
                            <?php
                            if (!empty($refnos)) {
                                foreach ($refnos as $refno) {
                                    echo '<option value="' . $refno['refno'] . '">' . $refno['refno'] . '</option>';
                                }
                            }
                            ?>
                        </select> 
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="Inputsupplier">Price:</label>
                        <input class="form-control required" type="text" name="price" id="price" placeholder="Price" />
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="Inputsupplier">Opening balance:</label>
                        <input class="form-control required" type="text" name="op_bal" id="op_bal" placeholder="Opening balance" />
                    </div>
                </div>
                <hr/>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div class="card mb-3 border-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="Inputsupplier">Product:</label>
                                        <select class="form-control required chosen-select" name="product" id="product">
                                            <option value="">Choose Product</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="Inputsupplier">Batch:</label>
                                        <select class="form-control required chosen-select" name="batch" id="batch">
                                            <option value="">Choose Batch</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="Inputsupplier">Quantity:</label>
                                        <input type="text" name="qty" id="qty" placeholder="QTY" onblur="getVal()" class="form-control required" />
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="Inputsupplier">Purchase rate:</label>
                                        <input type="text" name="p_rate" id="p_rate" placeholder="Purchase rate" onblur="getVal()" class="form-control required" />
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="Inputdiscount">MRP:</label>
                                        <input type="text" name="mrp" id="mrp" placeholder="MRP" onblur="getVal()" class="form-control required" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="Inputdiscount">Discount:</label>
                                        <input type="text" name="discount" id="discount" placeholder="Discount" onblur="getVal()" class="form-control required" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="InputVAT">VAT:</label>
                                        <input type="text" name="vat" id="vat" placeholder="VAT" onblur="getVal()" class="form-control required" />
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="Inputtotal">Total:</label>
                                        <input type="text" name="total" id="total" placeholder="Total" class="form-control required" />
                                    </div>
                                </div>
                            </div>
                            <button class="card-link btn btn-primary" type="button" id="add_product_to_list" name="add_product_to_list"><i class="fa fa-plus" ></i> Add product</button>
                        </div><!-- row -->
                    </div>
                </div>
            </div>
            <hr/>
        </div>
    </div>
</div>
<script type="text/javascript">
    var columns = [
        {
            title: "Sl. No",
            class: "sl_no",
            data: function (item) {
                return item.serial_number;
            }
        },
        {
            title: "Prodcut",
            class: "bill_no",
            data: function (item) {
                return item.product_name;
            }
        },
        {
            title: "Bill No.",
            class: "bill_no",
            data: function (item) {
                return item.billno;
            }
        },
        {
            title: "Date",
            data: function (item) {
                return item.date;
            }
        },
        {
            title: "Reference number",
            data: function (item) {
                return item.refno;
            }
        },
        {
            title: "MRP",
            data: function (item) {
                return item.mrp;
            }
        },
        {
            title: "VAT",
            data: function (item) {
                return item.vat;
            }
        },
        {
            title: "Discount",
            data: function (item) {
                return item.discount;
            }
        }
    ];
    $(document).ready(function () {
        load_purchase_return_data();
        $('#supplier').on('change', function () {
            $('#product').html('<option value="">Choose product</option>');
            var supplier_id = $(this).val();
            $.ajax({
                url: base_url + 'pharmacy/purchase/get_product_list_by_supplier',
                type: 'POST',
                dataType: 'json',
                data: {supplier_id: supplier_id},
                success: function (response) {
                    console.log(response);
                    if (response.products_list.length > 0) {
                        var option = '<option value="">Choose product</option>';
                        $.each(response.products_list, function (item) {
                            option += '<option value="' + response.products_list[item].product_id + '">' + response.products_list[item].name + '</option>';
                        });
                        $('#product').html(option);
                    }
                    if (response.batch.length > 0) {
                        var option_batch = '<option value="">Choose batch</option>';
                        $.each(response.batch, function (item) {
                            option_batch += '<option value="' + response.batch[item].batch + '">' + response.batch[item].batch + '</option>';
                        });
                        $('#batch').html(option_batch);
                    }

                },
                error: function (error) {
                    console.log(error);
                }

            });
        });

        $('#batch').on('change', function () {
            var batch = $(this).val();
            var product_id = $('#product').val();
            $.ajax({
                url: base_url + 'pharmacy/purchase/get_product_details',
                type: 'POST',
                dataType: 'json',
                data: {product_id: product_id, batch: batch},
                success: function (response) {
                    $('#mrp').val(response.product_info[0].mrp);
                    $('#p_rate').val(response.product_info[0].p_rate);
                    $('#p_rate').val(response.product_info[0].p_rate);
                    $('#discount').val(response.product_info[0].discount);
                    $('#vat').val(response.product_info[0].vat);
                    console.log(response);

                },
                error: function (error) {
                    console.log(error);
                }

            });
        });

    });

    function getVal() {
        var a = $('#qty').val();
        var b = $('#p_rate').val();
        var c = a * b;
        $('#mrp').val(c);
        var d = $('#discount').val();
        var e = (c * d) / 100;
        var f = c - e;
        $('#total').val(f);
        if ($('#vat').val() !== "") {
            var g = $('#vat').val();
            var i = (c * g) / 100;
            var h = Number($('#total').val()) + Number(i);
            $('#total').val(h);
        }
    }

    var patient_table = '';
    function load_purchase_return_data() {
        patient_table = $('#table_purchase_return').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            language: {
                sZeroRecords: "<div class='no_records'>No products found</div>",
                sEmptyTable: "<div class='no_records'>No products found</div>",
                sProcessing: "<div class='no_records'>Loading</div>",
            },
            'searching': false,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': base_url + 'pharmacy/purchase/get_products_tobe_returned',
                'type': 'POST',
                'dataType': 'json'
            },
            order: [[0, 'desc']],
            info: true,
            sScrollX: true
        });
    }
</script>