<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i> Stock Management:</h3>
                <button class="btn btn-sm btn-primary pull-right" onclick="add_batch()">Add Stock</button>
            </div>
            <div class="box-body">
                <div class="">
                    <table id="table" class="table table-striped table-bordered dataTable" cellspacing="0" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var save_method;
    var table;

    $(document).ready(function() {

        var is_admin = '<?= $is_admin ?>';

        var columns = [{
                title: "Sl. No",
                class: "ipd_no",
                data: function(item) {
                    return item.id;
                }
            },
            {
                title: "Medicine",
                class: "medicine_id",
                data: function(item) {
                    return item.name;
                }
            },
            {
                title: "Batch Number",
                class: "batch_number",
                data: function(item) {
                    return item.batch_number;
                }
            },
            {
                title: "Expiry Date",
                class: "expiry_date",
                data: function(item) {
                    return item.expiry_date;
                }
            },
            {
                title: "Quantity Received",
                class: "quantity_received",
                data: function(item) {
                    return item.quantity_received;
                }
            },
            {
                title: "Quantity In Stock",
                class: "quantity_instock",
                data: function(item) {
                    return item.quantity_instock;
                }
            },
            {
                title: "Supplier",
                class: "supplier_id",
                data: function(item) {
                    return item.supplier_name;
                }
            },
            {
                title: "Storage Location",
                class: "storage_location",
                data: function(item) {
                    return item.storage_location;
                }
            },
            {
                title: "Date Received",
                class: "date_received",
                data: function(item) {
                    return item.date_received;
                }
            }
        ];

        table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "columns": columns,
            "columnDefs": [{
                "targets": 'ipd_no',
                "orderable": false
            }],
            'searching': true,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ordering': false,
            "ajax": {
                "url": "<?php echo site_url('pharmacy/medicine_batches/fetch_batches'); ?>",
                "type": "POST"
            },
            order: [
                [0, 'asc']
            ],
            info: true,
            sScrollX: true

        });
    });

    function add_batch() {
        save_method = 'add';
        $('#form')[0].reset();
        $('#modal_form').modal('show');
        $('.modal-title').text('Add Batch');
    }

    function edit_batch(id) {
        save_method = 'update';
        $('#form')[0].reset();

        $.ajax({
            url: "<?php echo site_url('pharmacy/medicine_batches/edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="medicine_id"]').val(data.medicine_id);
                $('[name="batch_number"]').val(data.batch_number);
                $('[name="expiry_date"]').val(data.expiry_date);
                $('[name="quantity_received"]').val(data.quantity_received);
                $('[name="quantity_instock"]').val(data.quantity_instock);
                $('[name="supplier_id"]').val(data.supplier_id);
                $('[name="storage_location"]').val(data.storage_location);
                $('[name="date_received"]').val(data.date_received);
                $('#modal_form').modal('show');
                $('.modal-title').text('Edit Batch');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error getting data from ajax');
            }
        });
    }

    function save() {
        var url;
        if (save_method == 'add') {
            url = "<?php echo site_url('pharmacy/medicine_batches/add') ?>";
        } else {
            url = "<?php echo site_url('pharmacy/medicine_batches/update') ?>";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#modal_form').modal('hide');
                    table.ajax.reload();
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / updating data');
            }
        });
    }

    function delete_batch(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "<?php echo site_url('pharmacy/medicine_batches/delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#modal_form').modal('hide');
                    table.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }
</script>

<div class="modal fade" id="modal_form" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Batch Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier ID</label>
                            <div class="col-md-9">
                                <select name="supplier_id" class="form-control select2" style="width: 100%;">
                                    <option value="">Select Supplier</option>
                                    <?php if (!empty($suppliers)) : ?>
                                        <?php foreach ($suppliers as $supplier) : ?>
                                            <option value="<?= $supplier['id'] ?>"><?= $supplier['supplier_name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Medicine ID</label>
                            <div class="col-md-9">
                                <select name="medicine_id" class="form-control select2" style="width: 100%;">
                                    <option value="">Select Medicine</option>
                                    <?php if (!empty($medicines)) : ?>
                                        <?php foreach ($medicines as $medicine) : ?>
                                            <option value="<?= $medicine['id'] ?>"><?= $medicine['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Batch Number</label>
                            <div class="col-md-9">
                                <input name="batch_number" placeholder="Batch Number" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expiry Date</label>
                            <div class="col-md-9">
                                <input name="expiry_date" placeholder="Expiry Date" class="form-control" type="date">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity Received</label>
                            <div class="col-md-9">
                                <input name="quantity_received" placeholder="Quantity Received" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity In Stock</label>
                            <div class="col-md-9">
                                <input name="quantity_instock" placeholder="Quantity In Stock" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Storage Location</label>
                            <div class="col-md-9">
                                <input name="storage_location" placeholder="Storage Location" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date Received</label>
                            <div class="col-md-9">
                                <input name="date_received" placeholder="Date Received" class="form-control" type="date">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>