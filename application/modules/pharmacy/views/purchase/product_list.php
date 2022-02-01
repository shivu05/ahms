<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Product list:</h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-3">
                        <label class="sr-only">Type:</label>
                        <select class="form-control select2" data-placeholder="Choose type" name="type" id="type">
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
                        <label class="sr-only">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                    <li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="btn btn-dark" href="<?php echo base_url('add-product') ?>" type="button" id="add_product" name="add_product" data-backdrop="static" data-keyboard="false"><i class="fa fa-fw fa-lg fa-plus-circle"></i>Add</a>
                    </div>
                </form>
                <div id="user_details">
                    <table class="table table-bordered table-hover table-striped dataTable" id="pharmacy_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .sl_no{
        max-width: 40px !important;
        min-width: 40px !important;
        text-align: center;
    }
    .dec_num{
        max-width: 60px !important;
        min-width: 60px !important;
        text-align: center;
    }
    .date{
        max-width: 100px !important;
        min-width: 100px !important;
    }
    .product_name{
        max-width: 200px !important;
        min-width: 200px !important;
    }
    .supplier_name{
        max-width: 300px !important;
        min-width: 300px !important;
    }
    .mfr{
        max-width: 150px !important;
        min-width: 150px !important;
    }
    .category{
        max-width: 80px !important;
        min-width: 80px !important;
    }
</style>
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
            title: "Product name",
            class: "product_name",
            data: function (item) {
                return item.product_name;
            }
        },
        {
            title: "Batch",
            class: "email",
            data: function (item) {
                return item.product_batch;
            }
        },
        {
            title: "Supplier",
            class: "supplier_name",
            data: function (item) {
                return item.supplier_name;
            }
        },
        {
            title: "Package",
            class: "email",
            data: function (item) {
                return item.packing_name;
            }
        },
        {
            title: "MFR",
            class: "mfr",
            data: function (item) {
                return item.product_mfg;
            }
        },
        {
            title: "Category",
            class: "category",
            data: function (item) {
                return item.product_type;
            }
        },
        {
            title: "Group",
            class: "category",
            data: function (item) {
                return item.product_group;
            }
        },
        {
            title: "VAT",
            class: "dec_num",
            data: function (item) {
                return item.vat;
            }
        },
        {
            title: "P. rate",
            class: "dec_num",
            data: function (item) {
                return item.purchase_rate;
            }
        },
        {
            title: "MRP",
            class: "dec_num",
            data: function (item) {
                return item.mrp;
            }
        },
        {
            title: "S. price",
            class: "dec_num",
            data: function (item) {
                return item.sale_rate;
            }
        },
        {
            title: "Discount",
            class: "email",
            data: function (item) {
                return item.discount;
            }
        },
        {
            title: "Reorder point",
            class: "date",
            data: function (item) {
                return item.reorder_point;
            }
        },
        {
            title: "Weight",
            class: "dec_num",
            data: function (item) {
                return item.weight;
            }
        },
        {
            title: "Mfg date",
            class: "date",
            data: function (item) {
                return item.manifacture_date;
            }
        },
        {
            title: "Expiry date",
            class: "date",
            data: function (item) {
                return item.exp_date;
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

        $('#search_form #export').on('click', '#export_to_xls', function (e) {
            e.preventDefault();
            //$('#search_form').submit();
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'export-product-list',
                type: 'POST',
                dataType: 'json',
                data: {search_form: form_data},
                success: function (data) {
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
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
                'url': base_url + 'pharmacy/purchase/get_product_list',
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