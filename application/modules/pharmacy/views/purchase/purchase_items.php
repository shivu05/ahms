<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php //pma($pt_items); ?>
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Purchase master data list:</h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-3">
                        <label class="control-label sr-only">Type:</label>
                        <select class="form-control" name="type" id="type">
                            <option value="">Choose type</option>
                            <?php
                            if (!empty($pt_items)) {
                                foreach ($pt_items as $items) {
                                    echo '<option value="' . $items['pt_desc'] . '">' . $items['pt_desc'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label  sr-only">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name" autocomplete="off">
                    </div>
                    <div class="form-group col-6 align-self-end">
                        <button class="btn btn-primary" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <button class="btn btn-danger" type="reset" id="reset"><i class="fa fa-fw fa-lg fa-refresh"></i>Reset</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                </ul>
                            </div>
                        </div>
                        <button class="btn btn-dark" type="button" id="add_product" name="add_product" data-backdrop="static" data-keyboard="false"><i class="fa fa-fw fa-lg fa-plus-circle"></i>Add</button>
                    </div>
                </form>
                <div id="user_details">
                    <table class="table table-bordered table-hover table-striped dataTable" id="pharmacy_table" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="product_modal_box" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Purchase master data</h4>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="POST" name="add_product_form" id="add_product_form" action="<?php echo base_url('add-purchase-type'); ?>">
                            <div class="form-group">
                                <label for="add_type">Type:</label>
                                <select class="form-control required select2" data-placeholder="Choose type" name="add_type" id="add_type" style="width: 100%">
                                    <option value="">Choose type</option>
                                    <?php
                                    if (!empty($pt_items)) {
                                        foreach ($pt_items as $items) {
                                            echo '<option value="' . $items['pt_desc'] . '">' . $items['pt_desc'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="add_name">Name:</label>
                                <input class="form-control required" name="add_name" type="text" aria-describedby="add_name" placeholder="Enter name" id="add_name"/>
                            </div>
                            <div class="form-group" id="mfg_code" style="display: none;">
                                <label for="extrainfo">MFG Code:</label>
                                <input class="form-control required" name="extrainfo" type="text" aria-describedby="extrainfo" placeholder="Enter details" id="extrainfo"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" name="add_btn" id="add_btn"><i class="fa fa-add"></i> Add</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    var columns = [
        {
            title: "Sl. No",
            class: "name",
            data: function (item) {
                return item.serial_number;
            }
        },
        {
            title: "Type",
            class: "name",
            data: function (item) {
                return item.type;
            }
        },
        {
            title: "Name",
            class: "email",
            data: function (item) {
                return item.name;
            }
        },
        {
            title: "Code",
            class: "email",
            data: function (item) {
                return item.extrainfo;
            }
        },
        {
            title: "Action",
            class: "email",
            data: function (item) {
                return "<center><i class='fa fa-edit hand_cursor edit_xray' data-id='" + item.ID + "'></i>" + "</center>";
            }
        }
    ];
    $(document).ready(function () {
        populate_table();
        $('#search').on('click', function () {
            user_table.draw();
        });

        $('#reset').on('click', function () {
            user_table.draw();
        });

        $('#add_product').on('click', function () {
            $('#add_product_form')[0].reset();
            validator.resetForm();
            $('#product_modal_box').modal('show');
        });
        validator = $('#add_product_form').validate({
            messages: {
                add_type: {
                    required: 'Type is required'
                },
                add_name: {
                    required: 'Name is required'
                }
            }
        });

        $('#add_product_form').on('change', '#add_type', function () {
            if ($('#add_product_form #add_type').val() == 'mfg') {
                $('#add_product_form #mfg_code').show();
            } else {
                $('#add_product_form #mfg_code').hide();
            }
        });
        $('#add_btn').on('click', function () {

            var form_data = $('#add_product_form').serializeArray();
            if ($('#add_product_form').valid()) {
                $.ajax({
                    url: base_url + 'add-purchase-type',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        $('#product_modal_box').modal('hide');
                        if (response.status) {
                            $.notify({
                                title: "Purchase master:",
                                message: "Added successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                        } else {
                            $.notify({
                                title: "Purchase master:",
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
    });
    var user_table;
    function populate_table() {
        user_table = $('#pharmacy_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [2]}
            ],
            language: {
                sZeroRecords: "<div class='no_records'>No data found</div>",
                sEmptyTable: "<div class='no_records'>No data found</div>",
                sProcessing: "<div class='no_records'>Loading</div>",
            },
            'searching': false,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ordering': false,
            'ajax': {
                'url': base_url + 'pharmacy/purchase/get_purchase_master_items_list',
                'type': 'POST',
                'dataType': 'json',
                'data': function (d) {
                    return $.extend({}, d, {
                        "search_form": $('#search_form').serializeArray()
                    });
                }
            },
            order: [[0, 'desc']],
            info: true,
            sScrollX: true
        });
    }
</script>