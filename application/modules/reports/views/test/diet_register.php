<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Diet register:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <hr/>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div
            </div>
            
            <div id="patient_statistics" class="col-md-12"></div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
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
                title: "Bed",
                data: function (item) {
                    return item.BedNo;
                }
            },
            {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "DOA",
                data: function (item) {
                    return item.DoAdmission;
                }
            },
            {
                title: "DOD",
                data: function (item) {
                    return item.DoDischarge;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Morning <i data-toggle='tooltip' data-placement='left' title='Bread/Biscuit/Tea' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return 'Yes';
                }
            },
            {
                title: "Afternoon <i data-toggle='tooltip' data-placement='left' title='Chapathi Rice' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return 'Yes';
                }
            },
            {
                title: "Night <i data-toggle='tooltip' data-placement='left' title='Chapathi Rice' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return 'Yes';
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
                    'url': base_url + 'reports/Test/get_diet_register_data',
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