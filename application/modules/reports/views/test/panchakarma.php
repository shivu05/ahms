<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Panchakarma report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
                <div id="patient_statistics" class="col-md-12"></div>
            </div>
        </div>
    </div>
</div>
<style>
    .patient{
        width: 200px !important;
    }

    .sl_no{
        width: 80px !important;
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
        $('#search_form').validate();
        $('#search_form').on('click', '#search', function () {
            $('#patient_statistics').html('');
            var form_data = $('#search_form').serializeArray();
            if ($('#search_form').valid()) {
                $.ajax({
                    url: base_url + 'reports/test/get_panchakarma_report',
                    type: 'POST',
                    data: form_data,
                    success: function (response) {
                        $('#patient_statistics').html(response);
                        $('#panchakarma_table').dataTable();
                    },
                    error: function (error) {}
                });
            }
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        var detailRows = [];
        $('#patient_table tbody').on('click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = patient_table.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                row.child(add_child(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        var columns = [
            {
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
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
                "rowCallback": function (row, data, index) {
                    // row.add(add_child(data)).show();
                    console.log($(this));
                    var table = '<table><tr><th>Procs</th></tr><tr><td>' + data.procedure + '</td></tr></table>'
                    $(this).append('<tr>' + table + '</tr>');
                    console.log(index);
                },
                "createdRow": function (row, data, index) {
                    $('tr', row).append('<tr><table><tr><th>Procs</th></tr><tr><td>' + data.procedure + '</td></tr></table></tr>');
                    console.log('createdRow');
                    console.log(row);
                    console.log(data);
                    console.log(index);
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
                bScrollCollapse: true
            });
            // On each draw, loop over the `detailRows` array and show any child rows
            patient_table.on('draw', function () {
                $.each(detailRows, function (i, id) {
                    $('#' + id + ' td.details-control').trigger('click');
                });
            });
        }
    }
    );
</script>