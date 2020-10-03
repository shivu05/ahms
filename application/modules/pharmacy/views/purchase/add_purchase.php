<?php
$bills_readonly = $supplier_id = $bill_no = $refno = $bill_date = $bill_amt = $opbal = '';
$data_exists = 0;
if (!empty($temp_purchase_data)) {
    $data_exists = 1;
    $bills_readonly = 'readonly="readonly"';
    $supplier_id = $temp_purchase_data['supplier_id'];
    $bill_no = $temp_purchase_data['billno'];
    $refno = $temp_purchase_data['refno'];
    $bill_date = $temp_purchase_data['date'];
    $bill_amt = $temp_purchase_data['price'];
    $opbal = $temp_purchase_data['opbal'];
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus"></i> Add purchase entry:
                </h3>
                <button id="print_purchase" name="print_purchase" type="button" class="btn btn-sm btn-primary pull-right"><i class="fa fa-print"></i> Print</button>
            </div>
            <div class="box-body">
                <form role="form" method="POST" name="add_purchase_form" id="add_purchase_form">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-bold text-black">Billing information:</h6>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="supplier_id">Supplier:</label>
                                <select class="form-control select2 required " name="supplier_id" id="supplier_id" data-placeholder="Choose supplier" <?= $bills_readonly ?>>
                                    <option value="">Choose supplier</option>
                                    <?php
                                    if (!empty($suppliers)) {
                                        foreach ($suppliers as $row) {
                                            $selected = ($row['id'] == $supplier_id) ? 'selected="selected"' : '';
                                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bill_no">Bill No:</label>
                                <input type="text" name="bill_no" id="bill_no" placeholder="Bill No" value="<?= $bill_no ?>" class="form-control required" <?= $bills_readonly ?> />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ref_no">Ref. No:</label>
                                <input type="text" name="ref_no" id="ref_no" placeholder="Ref. No" value="<?= $refno ?>" class="form-control required" <?= $bills_readonly ?>/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bill_date">Billed date:</label>
                                <input type="text" name="bill_date" id="bill_date" placeholder="Bill date" value="<?= $bill_date ?>"  class="date_picker form-control required" <?= $bills_readonly ?>/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bill_amount">Bill amount:</label>
                                <input type="text" name="bill_amount" id="bill_amount" placeholder="Bill amount" value="<?= $bill_amt ?>" class="form-control required numbers-only" <?= $bills_readonly ?> />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="op_bal">Opening balance:</label>
                                <input type="text" name="op_bal" id="op_bal" placeholder="Opening balance" value="<?= $opbal ?>" class="form-control required numbers-only" <?= $bills_readonly ?> />
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-bold text-black">Product information:</h6>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_id">Product:</label>
                                <select class="form-control select2 required" name="product_id" id="product_id" data-placeholder="Choose product">
                                    <option value="">Choose product</option>
                                    <?php
                                    if (!empty($products)) {
                                        foreach ($products as $product) {
                                            echo '<option value="' . $product['product_id'] . '">' . $product['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="batch_id">Batch:</label>
                                <select class="form-control select2 required" name="batch_id" id="batch_id" data-placeholder="Choose batch">
                                    <option value="">Choose batch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="purchase_qty">Purchase quantity:</label>
                                <input type="text" name="purchase_qty" id="purchase_qty" onchange="getTotal();" placeholder="Purchase Qty" class="form-control required numbers-only" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="f_qty">F.Qty:</label>
                                <input type="text" name="f_qty" id="f_qty" placeholder="F.Qty" class="form-control required numbers-only"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mrp">MRP:</label>
                                <input type="text" name="mrp" id="mrp" placeholder="MRP" class="form-control required numbers-only"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="p_rate">Purchase rate:</label>
                                <input type="text" name="p_rate" id="p_rate" placeholder="Purchase rate" class="form-control required numbers-only"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="discount">Discount:</label>
                                <input type="text" name="discount" id="discount" placeholder="Discount" value="0" class="form-control required numbers-only"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="vat">VAT:</label>
                                <input type="text" name="vat" id="vat" placeholder="VAT" value="0" class="form-control required numbers-only"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="btype">B-Type:</label>
                                <select class="form-control select2 required" name="btype" id="btype" data-placeholder="Choose B-Type">
                                    <option value="">Choose B-Type</option>
                                    <option value="p_qty">MRP ON P-QTY</option>
                                    <option value="p_qty_fqty">MRP ON P-QTY+F-QTY</option>
                                    <option value="tpqty">TRP ON P-QTY</option>
                                    <option value="tpqty_fqty">TRP ON P-QTY+F-QTY</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="total">Total:</label>
                                <input type="text" name="total" id="total" placeholder="Total" value="0" class="form-control required numbers-only readonly" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <br/>
                            <button type="button" class="btn btn-primary" name="add_purchase" id="add_purchase"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" class="btn btn-secondary" id="retotal_vals"><i class="fa fa-refresh"></i> Re-total </button>
                            <button type="button" class="btn btn-success" id="final_submit"><i class="fa fa-check"></i> Final submit </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-body">
                <table class="table table-bordered dataTable" id="temp_purchase_table">
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>Product</th>
                            <th>Batch</th>
                            <th>P.Qty</th>
                            <th>F.Qty</th>
                            <th>P.Rate</th>
                            <th>MRP</th>
                            <th>VAT</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //pma($temp_pdata);
                        if (!empty($temp_pdata)) {
                            $i = 0;
                            foreach ($temp_pdata as $row) {
                                ?>
                                <tr>
                                    <td><?= ++$i ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['batch'] ?></td>
                                    <td><?= $row['pty'] ?></td>
                                    <td><?= $row['fty'] ?></td>
                                    <td><?= $row['prate'] ?></td>
                                    <td><?= $row['mrp'] ?></td>
                                    <td><?= $row['vat'] ?></td>
                                    <td><?= $row['discount'] ?></td>
                                    <td><?= $row['total'] ?></td>
                                    <td><i class="fa fa-trash-o text-danger hand_cursor delete_record" data-id="<?= $row['id'] ?>"></i> </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal modal fade" id="custom_confirmation_modal_box" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" name="add_btn" id="btn-ok">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal modal fade" id="print_purchase_bill_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Print purchase details</h4>
            </div>
            <div class="modal-body">
                <form name="print_purchase_form" role="form" id="print_purchase_form" method="POST" 
                      action="<?= base_url('pharmacy/purchase/export_patients_list_pdf') ?>" target="_blank">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billno">Bill No:</label><br/>
                                <select class="form-control select2 required " style="width:100%;" name="billno" id="billno" data-placeholder="Choose bill no">
                                    <option value="">Choose bill no</option>
                                    <?php
                                    if (!empty($bill_nos)) {
                                        foreach ($bill_nos as $row) {
                                            echo '<option value="' . $row['billno'] . '">' . $row['billno'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" name="add_btn" id="btn-print">Print</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>
    .login-dialog .modal-dialog {
        width: 400px;
    }
</style>
<script type="text/javascript">
    var data_exists = '';
    $(document).ready(function () {
        $(".select2").select2();
        var data_exists = '<?= $data_exists ?>';

        $('#temp_purchase_table').dataTable();

        $('#print_purchase').on('click', function () {
            $('#print_purchase_bill_modal').modal('show');
        });

        $('#print_purchase_bill_modal').on('click', '#btn-print', function () {
            $('#print_purchase_form').submit();
        });

        $('#final_submit').on('click', function () {
            if (data_exists == 1) {
                $.ajax({
                    url: base_url + 'pharmacy/purchase/store_purchase_entry',
                    type: 'POST',
                    data: {bill_no: $('#bill_no').val()},
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        if (response.status = true) {
                            BootstrapDialog.show({
                                type: BootstrapDialog.TYPE_SUCCESS,
                                title: 'Purchase entry',
                                message: 'Purchase details added successfully',
                                cssClass: 'login-dialog',
                                buttons: [{
                                        label: 'Ok',
                                        cssClass: 'btn-primary',
                                        action: function (dialog) {
                                            dialog.close();
                                            window.location = base_url + 'add-purchase';
                                        }
                                    }]
                            });
                        } else {
                            BootstrapDialog.show({
                                type: BootstrapDialog.TYPE_DANGER,
                                title: 'Purchase entry',
                                message: 'Failed to add data. Try again',
                                cssClass: 'login-dialog',
                                buttons: [{
                                        label: 'Ok',
                                        cssClass: 'btn-primary',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            });
                        }
                    }

                });
            } else {
                BootstrapDialog.show({
                    type: BootstrapDialog.TYPE_DANGER,
                    title: 'Purchase entry',
                    message: 'No data exists. please add purchased products',
                    cssClass: 'login-dialog',
                    buttons: [{
                            label: 'Ok',
                            cssClass: 'btn-primary',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }]
                });
            }
        });
        var row_id = '';
        $('#temp_purchase_table').on('click', '.delete_record', function () {
            row_id = $(this).data('id');
            $('#custom_confirmation_modal_box').modal('show');
        });
        $('#custom_confirmation_modal_box').on('click', '#btn-ok', function () {
            $.ajax({
                url: base_url + 'pharmacy/purchase/delete_temp_purchase_data',
                type: 'POST',
                data: {id: row_id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.status == true) {
                        window.location = base_url + 'add-purchase';
                    }
                }
            });
        });

        $('#add_purchase_form').validate({
            errorPlacement: function (error, element) {
                if (element.hasClass('select2')) {
                    $(element).parents('.form-group').append(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });
        $('#add_purchase_form').on('click', '#add_purchase', function () {
            if ($('#add_purchase_form').valid()) {
                var form_data = $('#add_purchase_form').serializeArray();
                $.ajax({
                    url: base_url + 'pharmacy/purchase/save_temp_purchase_entry',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 'ok') {
                            window.location = base_url + 'add-purchase';
                        }
                    }
                });
            } else {
                alert('Invalid');
            }
        });

        $('#add_purchase_form').on('change', '#supplier_id', function () {
            var sup_id = $(this).val();
            $.ajax({
                url: base_url + 'pharmacy/purchase/get_product_list_by_supplier',
                type: 'POST',
                data: {supplier_id: sup_id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    var option = '<option value="">Choose product</option>';
                    if (response.products_list.length > 0) {
                        $.each(response.products_list, function (i) {
                            option += '<option value="' + response.products_list[i].product_id + '">' + response.products_list[i].name + '</option>';
                        });
                    }
                    $('#add_purchase_form #product_id').html(option);
                }
            });
        });

        $('#add_purchase_form').on('change', '#product_id', function () {
            var prod_id = $(this).val();
            $.ajax({
                url: base_url + 'pharmacy/purchase/get_product_params',
                type: 'POST',
                data: {product_id: prod_id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    var batch_option = '<option value="">Choose batch</option>';
                    if (response.length > 0) {
                        $.each(response, function (i) {
                            batch_option += '<option>' + response[i].product_batch + '</option>';
                        });
                    }
                    $('#add_purchase_form #batch_id').html(batch_option);
                }
            });
        });

        $('#add_purchase_form').on('change', '#batch_id', function () {
            $.ajax({
                url: base_url + 'pharmacy/purchase/get_product_params',
                type: 'POST',
                data: {
                    supplier_id: $('#supplier_id').val(),
                    product_id: $('#product_id').val(),
                    product_batch: $(this).val()
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#add_purchase_form #mrp').val(response[0].mrp);
                    $('#add_purchase_form #p_rate').val(response[0].purchase_rate);
                    $('#add_purchase_form #vat').val(response[0].vat);
                    $('#add_purchase_form #discount').val(response[0].discount);
                }
            });
        });

        $('#retotal_vals').on('click', function () {
            getTotal();
        });

        if (data_exists == 1) {
            $('#supplier_id').trigger('change');
        }
    });
    function getTotal() {
        var a = $('#purchase_qty').val();
        var b = $('#p_rate').val();
        var c = a * b;
        var d = $('#discount').val();
        var e = Number((c * d)) / 100;
        var f = c - e;
        $('#total').val(f);
        if ($('#vat').val() !== "") {
            var g = $('#vat').val();
            var i = (c * g) / 100;
            var h = Number($('#total').val()) + Number(i);
            $('#total').val(h);
        }
    }
</script>