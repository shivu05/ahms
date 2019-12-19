<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <h5>Stock entry: <a class="pull-right btn btn-primary" href="<?php echo base_url('product-list'); ?>"><i class="fa fa-eye"></i> View stock</a></h5>
            </div>
            <div class="row tile-body">
                <form class="form-horizontal col-md-12" method="POST" name="add_stock" id="add_stock" action="<?php echo base_url('save-stock'); ?>">
                    <div class="row">
                        <div class="col-lg-2">
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
                                <input type="text" name="bill_no" id="bill_no" class="form-control required" placeholder="Bill number"/>
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
                                <input type="text" name="reference_no" id="reference_no" class="form-control required" placeholder="Reference No"/>
                            </div>
                        </div>
                        <div class="col-lg-2">
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
                    </div>
                    <hr/>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <div class="card mb-3 border-primary">
                            <div class="card-body" id="rows_block">
                                <div id="add_0">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputsupplier">Product:</label>
                                                <select class="form-control required chosen-select product" name="products[]" id="product">
                                                    <option value="">Choose Product</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputsupplier">Batch:</label>
                                                <select class="form-control required chosen-select batch" name="batchs[]" id="batch">
                                                    <option value="">Choose Batch</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputsupplier">P. Aty:</label>
                                                <input type="text" name="p_qty[]" id="p_qty" placeholder="Purchase Qty" onblur="getVal()" class="form-control required" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputsupplier">F. Qty:</label>
                                                <input type="text" name="f_qty[]" id="p_rate" placeholder="Purchase rate" onblur="getVal()" class="form-control required" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputdiscount">MRP:</label>
                                                <input type="text" name="mrp[]" id="mrp" placeholder="MRP" onblur="getVal()" class="form-control required" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputdiscount">Purchase rate:</label>
                                                <input type="text" name="p_rate[]" id="p_rate" placeholder="Purchase rate" onblur="getVal()" class="form-control required" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputdiscount">Discount:</label>
                                                <input type="text" name="discount[]" id="discount" placeholder="Discount" onblur="getVal()" class="form-control required" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="InputVAT">VAT:</label>
                                                <input type="text" name="vat[]" id="vat" placeholder="VAT" onblur="getVal()" class="form-control required" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="InputVAT">B-Type:</label>
                                                <select class="form-control" name="btype[]" id="btype"><option>Select B-Type</option>
                                                    <option value="MRP ON P-QTY">MRP ON P-QTY</option>
                                                    <option value="MRP ON P-QTY+F-QTY">MRP ON P-QTY+F-QTY</option>
                                                    <option value="TRP ON P-QTY">TRP ON P-QTY</option>
                                                    <option value="TRP ON P-QTY+F-QTY">TRP ON P-QTY+F-QTY</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="Inputtotal">Total:</label>
                                                <input type="text" name="total[]" id="total" placeholder="Total" class="form-control required" />
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                </div>
                                <hr/>
                                <div id="addr1"></div>
                            </div>
                        </div>
                    </div>
                    <a id="add_row" class="btn btn-primary pull-left" style="color:#FFF">Add Row</a><a  style="color:#FFF" id='delete_row' class="pull-right btn btn-warning">Delete Row</a>
                </form>
            </div>
            <div class="tile-footer">
                <button class="btn btn-primary" name="submit" id="submit" type="submit"><i class="fa fa-save"></i> Add stock</button>
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
        var validator = $('#add_stock').validate();
        $('.tile-footer').on('click','#submit', function () {
            if ($('#add_stock').valid()) {
                $('#add_stock').submit();
            }
        });
        product_option = '';
        batch_option = '';
        $('#submit').prop('disabled',true);
        $('#supplier').on('change', function () {
            $('#product').html('<option value="">Choose product</option>');
            product_option = '<option>Choose product</option>';
            batch_option = '<option>Choose Batch</option>';
            var supplier_id = $(this).val();
            if(!supplier_id){
                $('#submit').prop('disabled',true);
            }else{
                $('#submit').prop('disabled',false);
            }
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
                        $('.product').html(option);
                        product_option = option;
                    }
                    if (response.batch.length > 0) {
                        var option_batch = '<option value="">Choose batch</option>';
                        $.each(response.batch, function (item) {
                            option_batch += '<option value="' + response.batch[item].product_batch + '">' + response.batch[item].product_batch + '</option>';
                        });
                        $('.batch').html(option_batch);
                        batch_option = option_batch;
                    }
                    $('.product').trigger("chosen:updated");
                    $('.batch').trigger("chosen:updated");
                },
                error: function (error) {
                    console.log(error);
                }

            });
        });
        
        var add_i=1;
        var row_content = '<div class="row">'+
            '<div class="col-lg-3">'+'<div class="form-group"><label for="Inputsupplier">Product:</label>'+
            '<select class="form-control required chosen-select product" name="products[]" id="product">'+
            '<option value="">Choose Product</option>'+
            '</select> '+
            '</div></div>'+
            '<div class="col-lg-3">'+
            '<div class="form-group">'+
            '<label for="Inputsupplier">Batch:</label>'+
            '<select class="form-control required chosen-select batch" name="batchs[]" id="batch">'+
            '<option value="">Choose Batch</option>'+
            '</select> '+
            '</div></div>'+
            '<div class="col-lg-2">'+
            '<div class="form-group">'+
            '<label for="Inputsupplier">Purchase Quantity:</label>'+
            '<input type="text" name="p_qty[]" id="p_qty" placeholder="Purchase Qty" onblur="getVal()" class="form-control required" />'+
            '</div></div>'+
            '<div class="col-lg-2">'+
            '<div class="form-group">'+
            '<label for="Inputsupplier">F quantity:</label>'+
            '<input type="text" name="f_qty[]" id="f_qty" placeholder="F. Qty" onblur="getVal()" class="form-control required" />'+
            '</div></div>'+
            '<div class="col-lg-2">'+
            '<div class="form-group">'+
            '<label for="Inputdiscount">MRP:</label>'+
            '<input type="text" name="mrp[]" id="mrp" placeholder="MRP" onblur="getVal()" class="form-control required" />'+
            '</div></div>'+
            '<div class="col-lg-3">'+
            '<div class="form-group">'+
            '<label for="Inputdiscount">Discount:</label>'+
            '<input type="text" name="discount[]" id="discount" placeholder="Discount" onblur="getVal()" class="form-control required" />'+
            '</div></div>'+
            '<div class="col-lg-3">'+
            '<div class="form-group">'+
            '<label for="InputVAT">VAT:</label>'+
            '<input type="text" name="vat[]" id="vat" placeholder="VAT" onblur="getVal()" class="form-control required" />'+
            '</div></div>'+
            '<div class="col-lg-2">'+
            '<div class="form-group">'+
            '<label for="Inputtotal">Total:</label>'+
            '<input type="text" name="total[]" id="total" placeholder="Total" class="form-control required" />'+
            '</div></div></div><hr/>';
        $("#add_row").click(function(){
            $('#addr'+add_i).html(row_content);
            $('.product option').each(function() {
                if(!$(this).is(':selected')){
                    $('.product').html(product_option);
                }
            });
            
            $('.batch').html(batch_option);
            $('#rows_block').append('<div id="addr'+(add_i+1)+'"></div>');
            add_i++; 
        });
        $("#delete_row").click(function(){
            if(add_i>1){
                $("#addr"+(add_i-1)).html('');
                add_i--;
            }
        });
        
    });//document ready ends
    
    function getVal() {
        var a = $('#p_qty').val();
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
</script>