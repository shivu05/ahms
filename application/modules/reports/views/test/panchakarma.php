<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Panchakarma report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
<style>
    .patient{
        width: 200px !important;
    }

    .sl_no{
        width: 40px !important;
        text-align: center;
    }
    .opd{
        width: 50px !important;
        text-align: center;
    }
    .place{
        width: 120px !important;
    }
    .department{
        width: 120px !important;
    }
</style>
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
                class: "sl_no",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "C.OPD",
                class: "opd",
                data: function (item) {
                    return item.opdno;
                }
            },
            {
                title: "Patient",
                class: "patient",
                data: function (item) {
                    return item.name;
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
                title: "Disease",
                data: function (item) {
                    return item.disease;
                }
            },
            {
                title: "Treatment",
                data: function (item) {
                    return item.treatment;
                }
            }
            , {
                title: "Procedure",
                data: function (item) {
                    return item.procedure;
                }
            }
            , {
                title: "Doctor",
                data: function (item) {
                    return item.docname;
                }
            }
            , {
                title: "Date",
                data: function (item) {
                    return item.date;
                }
            }
        ];
        var patient_table;
        function add_child(d) {
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                    '<tr>' +
                    '<td>Proc name:</td>' +
                    '<td>' + d.procedure + '</td>' +
                    '</tr>' +
                    '</table>';
        }
        function show_patients() {
            patient_table = $('#patient_table').DataTable({
                "rowCallback": function (row, data) {
                    // row.add(add_child(data)).show();
                },
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
                    'url': base_url + 'reports/Test/get_panchakarma_report',
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
                sScrollX: "100%",
                sScrollXInner: "150%",
                bScrollCollapse: true,
                responsive: {
                    details: {
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                                return col.hidden ?
                                        '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                        '<td>' + col.procedure + ':' + '</td> ' +
                                        '<td>' + col.date + '</td>' +
                                        '</tr>' :
                                        '';
                            }).join('');

                            return data ?
                                    $('<table/>').append(data) :
                                    false;
                        }
                    }
                }
            });
        }
    }
    );
</script>