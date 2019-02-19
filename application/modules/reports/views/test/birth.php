<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
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
                title: "Sl. No",
                class: "ipd_no",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "OPD",
                class: "opd_no",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "IPD",
                class: "opd_no",
                data: function (item) {
                    return item.IpNo;
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.FName;
                }
            },
            {
                title: "Age",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Delivery details",
                data: function (item) {
                    return item.deliveryDetail;
                }
            },
            {
                title: "Birth date",
                data: function (item) {
                    return item.babyBirthDate;
                }
            },
            {
                title: "Birth time",
                data: function (item) {
                    return item.birthtime;
                }
            },
            {
                title: "Baby weight",
                data: function (item) {
                    return item.babyWeight;
                }
            },
            {
                title: "Delivery type",
                data: function (item) {
                    return item.deliverytype;
                }
            },
            {
                title: "Doctor",
                data: function (item) {
                    return item.treatby;
                }
            }

        ];

        function show_patients() {
            var patient_table = $('#patient_table').DataTable({
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
                    'url': base_url + 'reports/Test/get_birth_register_data',
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
    });
</script>