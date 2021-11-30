<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Stock list:</h3> 
                <a class="pull-right btn btn-sm btn-primary" href="<?php echo base_url('stock_entry'); ?>"><i class="fa fa-plus"></i> Add stock</a>
            </div>
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
                <div class="data_grid">
                    <table class="table table-bordered table-hover table-striped dataTable" id="stock_table" width="100%"></table>
                </div>
            </div>
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
            title: "Supplier",
            class: "sl_no",
            data: function (item) {
                return item.supplier_name;
            }
        },
        {
            title: "Product",
            class: "sl_no",
            data: function (item) {
                return item.product_name;
            }
        },
        {
            title: "Batch No",
            class: "sl_no",
            data: function (item) {
                return item.batchno;
            }
        },
        {
            title: "Stock",
            class: "sl_no",
            data: function (item) {
                return item.cstock;
            }
        },
        {
            title: "Price",
            class: "sl_no",
            data: function (item) {
                return item.price;
            }
        },
        {
            title: "Amount",
            class: "sl_no",
            data: function (item) {
                return item.amount;
            }
        },
        {
            title: "Bill No",
            class: "sl_no",
            data: function (item) {
                return item.billno;
            }
        },
        {
            title: "Purchase type",
            class: "sl_no",
            data: function (item) {
                return item.purchasetype;
            }
        },
        {
            title: "VAT",
            class: "sl_no",
            data: function (item) {
                return item.vat;
            }
        },
        {
            title: "Ref.No",
            class: "sl_no",
            data: function (item) {
                return item.refno;
            }
        },
        {
            title: "Date",
            class: "sl_no",
            data: function (item) {
                return item.date;
            }
        },
        {
            title: "Status",
            class: "sl_no",
            data: function (item) {
                if (item.status == '1')
                    return '<span class="label bg-green"> In stock </span>';
                else
                    return 'Out of stock';
            }
        },
    ];
    $(document).ready(function () {
        populate_table();
    });
    var user_table;
    function populate_table() {
        user_table = $('#stock_table').DataTable({
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
                'url': base_url + 'pharmacy/Stock/get_stock_list',
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