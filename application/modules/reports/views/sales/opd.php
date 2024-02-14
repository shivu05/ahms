<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-medkit"></i> Pharmacy OPD report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12 col-lg-12 col-sm-12"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="opd_pharma_edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="opd_pharma_modal_label">Update pharmacy details</h5>
            </div>
            <div class="modal-body" id="opd_pharma_modal_body">
                <form action="" name="opd_pharma_form" id="opd_pharma_form" method="POST">
                    <div class="form-group">
                        <label for="prod_name">Product name:</label>
                        <input type="text" name="product" class="form-control required" id="product" required/>
                        <small class="form-text text-muted" id="prod_name"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Product quantity:</label>
                        <input type="hidden" name="id" id="id" />
                        <input class="form-control required" id="qty" name="qty" type="text" aria-describedby="prod_qtyHelp" required>
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            $('#patient_statistics').html("");
            var form_data = $('#search_form').serializeArray();
            $('#loader_div').show();
            $.ajax({
                type: "POST",
                url: base_url + 'reports/sales/get_pharmacy_report',
                data: form_data,
                dataType: 'html',
                success: function (response) {
                    $('#patient_statistics').html(response);
                    $('#loader_div').hide();
                    //$('#opd_pharma_table').dataTable();
                },
                error: function (res) {
                    $('#loader_div').hide();
                }
            });
        });
        $('#search_form').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        $('#patient_statistics').on('click', '#opd_pharma_table .product_edit', function () {
            var id = $(this).data('id');
            var prod_name = $(this).data('prod_name');
            var prod_qty = $(this).data('prod_qty');

            $('#opd_pharma_edit_modal #opd_pharma_form #product').val(prod_name);
            $('#opd_pharma_edit_modal #opd_pharma_form #qty').val(prod_qty);
            $('#opd_pharma_edit_modal #opd_pharma_form #id').val(id);
            $('#opd_pharma_edit_modal').modal('show');
            $('#opd_pharma_edit_modal #opd_pharma_form').validate({
                rules: {
                    product: {
                        required: true
                    },
                    qty: {
                        required: true,
                        number: true
                    }
                }
            });
        });

        $('#opd_pharma_edit_modal').on('click', '#btn-update', function () {
            var form_data = $('#opd_pharma_edit_modal #opd_pharma_form').serializeArray();
            if ($('#opd_pharma_edit_modal #opd_pharma_form').valid()) {
                $.ajax({
                    url: base_url + 'reports/sales/update_pharmacy',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (res) {
                        if (res.status == 'ok') {
                            $.notify({
                                title: "OPD Pharmacy:",
                                message: "Updated successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "OPD Pharmacy:",
                                message: "Failed to update",
                                icon: 'fa fa-cross'
                            }, {
                                type: "error"
                            });
                        }
                        $('#opd_pharma_edit_modal').modal('hide');
                    },
                    error: function (err) {

                    }
                });
            } else {
                return false;
            }

        });

        $('#patient_statistics').on('click', '#opd_pharma_table .product_delete', function () {
            var id = $(this).data('id');
            if (confirm('Are you sure want to delete?')) {
                var form_data = $('#opd_pharma_edit_modal #opd_pharma_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/sales/delete_pharmcy',
                    type: 'POST',
                    data: {'id': id},
                    dataType: 'json',
                    success: function (res) {
                        $.notify({
                            title: "Pharmacy:",
                            message: "Deleted successfully",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                        $('#search_form #search').trigger('click');
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        });


    });
</script>