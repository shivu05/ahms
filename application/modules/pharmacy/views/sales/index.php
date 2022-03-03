<style>
    .typeahead__filter-button{
        height: 34px !important;
    }
</style>
<?php
//pma($product_list);
$dropdown_products = '';
if (!empty($product_list)) {
    foreach ($product_list as $row) {
        $dropdown_products .= '<option value="' . $row['st_id'] . '" data-stock_status="' . $row['current_stock'] . '" data-unit-price="' . $row['sale_rate'] . '" data-avail-stock="' . $row['cstock'] . '">' . $row['name'] . '</option>';
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-shopping-cart text-black"></i> Sales:</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form role="form" name="sales_form" id="sales_form" method="POST"> 
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kw" id="kw" placeholder="Search for OPD...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" name="search_opd" id="search_opd" type="button">Go!</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </form>
                </div>
                <br/>
                <div class="row-fluid">
                    <div class="col-md-6" id="patient_div"></div>
                    <div class="col-md-6 bg-gray-light" id="priscription_div">
                        <form id="medicine_form" name="medicine_form" method="POST">
                            <input type="hidden" name="total_bill" id="total_bill" />
                            <button type="button" name="add_sales" id="add_sales" style="margin: 1%;" disabled="disabled" class="btn btn-primary btn-sm add_sales pull-right mt-1">Add sales</button>
                            <br/>
                            <table class="table table-dark dataTable" id="table_sales">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>QTY</th>
                                        <th>Unit price</th>
                                        <th>Sub total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:right">Total:</td>
                                        <td id="total_amt" class="bg-success" style="text-align:right;font-weight: bold;">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var dropdown_products = '<?= $dropdown_products ?>';

        $('#patient_div').on('change', '#treatment_date', function () {
            $('.hide_div').hide();
            $('#sec_' + $(this).val()).show();
            $('#' + $(this).val()).show();
            $('#medicine_form .add_sales').removeAttr('disabled');
            // append_data();
        });
        var rowval = 1;

        $('#medicine_form .add_sales').on('click', function () {
            var tr_products = '<tr>'
                    + '<td><select name="product_id[]" class="form-control select2 product_id" data-rownum="' + rowval + '" id="product_id_' + rowval + '" data-title="Choose product">' + '<option value="">Choose product</option>' + dropdown_products + '</select></td>'
                    + '<td><input type="hidden" name="stock[]" data-rownum="' + rowval + '" id="stock_' + rowval + '" class="stock"/><span class="current_stock_info text-primary"></span></td>'
                    + '<td><input type="text" name="qty[]" data-rownum="' + rowval + '" class="form-control qty" id="qty_' + rowval + '" placeholder="Quantity" /></td>'
                    + '<td><input type="text" name="unit_price[]" data-rownum="' + rowval + '" id="unit_price_' + rowval + '" readonly="readonly" class="form-control unit_price" placeholder="Unit price"/></td>'
                    + '<td><input type="text" name="sub_total[]" data-rownum="' + rowval + '" id="sub_total_' + rowval + '" class="form-control sub_total" readonly="readonly" placeholder="Sub total" /></td>'
                    + '</tr>';
            $('#table_sales').append(tr_products);
            rowval++;

            $('#medicine_form #table_sales .product_id').on('change', function () {
                //alert($(this).data('rownum'));
                var rn = $(this).data('rownum');
                var stock_status = $(this).find('option:selected').data('stock_status');
                var available_stock = $(this).find('option:selected').data('avail-stock');
                var unit_price = $(this).find('option:selected').data('unit-price');
                if (stock_status == 'y') {
                    $(this).parent().next('td').find('.current_stock_info').html(available_stock);
                    $(this).parent().next('td').find('.stock').val(available_stock);
                    $(this).parent().next('td').next('td').next('td').find('.unit_price').val(unit_price);
                    $('#qty_' + rn).attr('disabled', false);
                    $('#qty_' + rn).trigger('change');
                } else {
                    $('#qty_' + rn).val('0').trigger('change')
                    $('#qty_' + rn).attr('disabled', 'disabled');
                }
            });

            $('#medicine_form #table_sales .qty').on('change', function () {
                var rownum = $(this).data('rownum');
                var current_stock = $(this).parent().prev('td').find('#stock_' + rownum).val();
                var unit_price = $('#unit_price_' + rownum).val();
                var required_qty = parseInt($(this).val());
                if (required_qty > parseInt(current_stock)) {
                    alert('Out of stock. Quantity can not be greater than stock');
                    $(this).focus();
                } else {
                    var sub_total = required_qty * parseInt(unit_price);
                    if (!Number.isNaN(sub_total)) {
                        $('#sub_total_' + rownum).val(sub_total);
                        var total = 0;
                        $(".sub_total").each(function () {
                            total += parseInt($(this).val());
                        });
                        $('#table_sales tfoot td#total_amt').html(total);
                    }
                }
            });
        });



        $('#sales_form').on('click', '#search_opd', function () {
            var kw = $('#sales_form #kw').val();
            $.ajax({
                url: base_url + 'fetch_patient_data',
                type: 'POST',
                dataType: 'html',
                data: {'opd': kw},
                success: function (response) {
                    $('#patient_div').html(response);
                    $('.select2').select2();
                },
                error: function (error) {
                    console.log(error)
                }

            });
        });

        function PrintElem(elem) {
            Popup(jQuery(elem).html());
        }

        function Popup(data) {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title></title>');
            mywindow.document.write('<link rel="stylesheet" href="http://www.test.com/style.css" type="text/css" />');
            mywindow.document.write('<style type="text/css">.test { color:red; } </style></head><body>');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.print();
        }



    });
</script>