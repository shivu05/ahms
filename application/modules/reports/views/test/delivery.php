<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Delivery report:</h3>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <hr />
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('birthregistery') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .num {
        width: 40px !important;
    }

    .name {
        width: 80px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function() {
            show_patients();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function() {
            $('#search_form').submit();
        });

        var columns = [{
                title: "Sl. No",
                class: "num",
                data: function(item) {
                    return item.serial_number;
                }
            },
            {
                title: "OPD",
                class: "num",
                data: function(item) {
                    return item.OpdNo;
                }
            },
            {
                title: "IPD",
                class: "num",
                data: function(item) {
                    return item.IpNo;
                }
            },
            {
                title: "Name",
                class: "name",
                data: function(item) {
                    return item.FName;
                }
            },
            {
                title: "Age",
                data: function(item) {
                    return item.Age;
                }
            },
            {
                title: "Diagnosis",
                data: function(item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Delivery details",
                data: function(item) {
                    return item.deliveryDetail;
                }
            },
            {
                title: "Delivery date",
                data: function(item) {
                    return item.babyBirthDate;
                }
            },
            {
                title: "Baby weight",
                data: function(item) {
                    return item.babyWeight;
                }
            },
            {
                title: "Baby Gender",
                data: function(item) {
                    return item.babygender;
                }
            },
            {
                title: "Delivery type",
                data: function(item) {
                    return item.deliverytype;
                }
            },
            {
                title: "DOA",
                data: function(item) {
                    return item.DoAdmission;
                }
            },
            {
                title: "Doctor",
                class: "name",
                data: function(item) {
                    return item.treatby;
                }
            }


        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action | <input type="checkbox" name="check_all" class="check_all" id="check_all" onclick="toggle(this)"/>',
                data: function(item) {
                    return "<center><input type='checkbox' name='check_del[]' class='check_xray' id='checkbx" + item.ID + "' value='" + item.ID + "'/>" + "</center>";
                }
            });
        }
        var patient_table;

        function show_patients() {
            patient_table = $('#patient_table').DataTable({
                'columns': columns,
                'columnDefs': [{
                    className: "",
                    "targets": [4]
                }],
                "drawCallback": function(settings) {

                },
                "destroy": true,
                language: {
                    sZeroRecords: "<div class='no_records'>No patients found</div>",
                    sEmptyTable: "<div class='no_records'>No patients found</div>",
                    sProcessing: "<div class='no_records'>Loading</div>",
                },
                'searching': true,
                'paging': true,
                'pageLength': 25,
                'lengthChange': true,
                'aLengthMenu': [10, 25, 50, 100],
                'processing': true,
                'serverSide': true,
                'ordering': false,
                'ajax': {
                    'url': base_url + 'reports/Test/get_birth_register_data',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': function(d) {
                        return $.extend({}, d, {
                            "search_form": $('#search_form').serializeArray()
                        });
                    }
                },
                order: [
                    [0, 'desc']
                ],
                info: true,
                scrollX: true
            });
        }

        $('#search_form').on('click', '#btn_delete', function() {

            var $b = $('#test_form input[type=checkbox]');
            num = $b.filter(':checked').length;
            if (num == 0) {
                alert('Please select atleast one records');
            } else {
                if (confirm('Are you sure want to delete?')) {
                    var form_data = $('#test_form').serializeArray();
                    $.ajax({
                        url: base_url + 'reports/Test/delete_records',
                        type: 'POST',
                        data: form_data,
                        dataType: 'json',
                        success: function(res) {
                            alert('Deleted successfully');
                            $('#search_form search').trigger('click');
                            patient_table.clear();
                            patient_table.draw();
                        },
                        error: function(err) {
                            console.log(err)
                        }
                    });
                }
            }

        });
    });

    var clicked = false;

    function toggle(source) {
        //console.log($(".skip_script:checked").length+'check');
        $(".check_xray").prop("checked", !clicked);
        clicked = !clicked;
    }
</script>