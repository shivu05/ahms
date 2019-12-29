<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">X-Ray report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('xrayregistery') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            show_patients();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        var columns = [
            {
                title: "#",
                class: "ipd_no",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "C.OPD",
                class: "opd_no",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "D.OPD",
                class: "opd_no",
                data: function (item) {
                    return item.deptOpdNo;
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.FirstName + ' ' + item.LastName;
                }
            },
            {
                title: "Age",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                data: function (item) {
                    return item.gender;
                }
            },
            {
                title: "Place",
                data: function (item) {
                    return item.address;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            /*{
             title: "X-Ray No",
             data: function (item) {
             return item.xrayNo;
             }
             },*/
            {
                title: "Part of X-Ray",
                data: function (item) {
                    return item.partOfXray;
                }
            },
            {
                title: "Film size",
                data: function (item) {
                    return item.filmSize;
                }
            },
            {
                title: "Ref. Date",
                data: function (item) {
                    return item.refDate;
                }
            },
            {
                title: "X-Ray Date",
                data: function (item) {
                    return item.xrayDate;
                }
            },
            {
                title: "Action",
                data: function (item) {
                    return "<center><input type='checkbox' name='check_del[]' class='check_xray' id='checkbx" + item.ID + "' value='" + item.ID + "'/></center>";
                }
            }
        ];
        var patient_table;
        function show_patients() {
            patient_table = $('#patient_table').DataTable({
                'columns': columns,
                'columnDefs': [
                    {className: "", "targets": [4]}
                ],
                "bDestroy": true,
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
                'ajax': {
                    'url': base_url + 'reports/Test/get_xray_patients_list',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': function (d) {
                        return $.extend({}, d, {
                            "search_form": $('#search_form').serializeArray()
                        });
                    },
                    drawCallback: function (response) {}
                },
                order: [[0, 'desc']],
                info: true,
                sScrollX: true
            });
        }

        $('#search_form').on('click', '#btn_delete', function () {

            var $b = $('#test_form input[type=checkbox]');
            num = $b.filter(':checked').length;
            if (num == 0) {
                alert('Please select atleast one records');
            } else {
                var form_data = $('#test_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/delete_records',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (res) {
                        alert('Deleted successfully');
                        $('#search_form search').trigger('click');
                        patient_table.clear();
                        patient_table.draw();
                    },
                    error: function (err) {
                        console.log(err)
                    }
                })
            }
        });
    });
</script>