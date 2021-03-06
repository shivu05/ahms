<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">OPD bill : <small>(Bill & Prescription print)</small></h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST">
                    <div class="form-group col-md-3">
                        <label class="control-label sr-only">OPD</label>
                        <input class="form-control" type="text" placeholder="Enter OPD number" name="OpdNo" id="OpdNo" autocomplete="off">
                    </div>
                    <!--<div class="form-group col-md-3">
                        <label class="control-label">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                    </div>-->
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Date</label>
                        <input class="form-control date_picker" type="text" placeholder="Enter date" name="date" id="date" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="#" id="export_to_pdf">OPD bill</a></li>
                                    <li><a class="dropdown-item" href="#" id="export_to_prescription_pdf">OPD Prescription</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable display nowrap" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });
        $('#search_form #export').on('click', '#export_to_xls', function (e) {
            e.preventDefault();
            //$('#search_form').submit();
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'reports/Opd/export_patients_list',
                type: 'POST',
                dataType: 'json',
                data: {search_form: form_data},
                success: function (data) {
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
        });
        $('#patient_table tbody').on('click', 'tr', function () {
            alert()
        });
        var submitForm = function (method) {
            //set form action based on the method passed in the click handler, update/delete
            var formAction = base_url + method;
            //set form action
            $('#search_form').attr('action', formAction);
            $('#search_form').attr('target', '_blank');
            //submit form
            $('#search_form').submit();
        };

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            submitForm('print-opd-bill');
        });

        $('#search_form #export').on('click', '#export_to_prescription_pdf', function () {
            submitForm('print-opd-prescription');
        });
        var columns = [
            {
                title: "OPD",
                class: "opd_no",
                data: function (item) {
                    return '<span class="badge hand_cursor"> ' + item.OpdNo + '</span>';
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.FirstName + ' ' + item.LastName;
                }
            },
            {
                title: "Place",
                data: function (item) {
                    return item.address + ' <br/>' + item.city;
                }
            },
            {
                title: "Occupation",
                data: function (item) {
                    return item.occupation;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Entry date",
                data: function (item) {
                    return item.CameOn;
                }
            }
        ];
        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-treat_id', data.ID);
                $(row).attr('data-opd_id', data.OpdNo);
            },
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>",
            },
            'searching': false,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': base_url + 'patient/treatment/get_all_patients',
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
        $('#patient_table tbody').on('click', '.print_opd_bill', function () {
            var data = $(this).data('treat_id');
            //alert(encodeURIComponent(data));
            //console.log(encodeURIComponent(data));
            if (data) {
                window.open(base_url + 'print-opd-bill/0/' + data + '/00', '_blank');
            }
        });
    });



</script>