<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('reports/Ipd/export_to_pdf'); ?>">
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label">From:</label>
                        <input class="form-control date_picker" type="text" placeholder="From date" name="start_date" id="start_date" autocomplete="off" required="required">
                    </div>
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label">To:</label>
                        <input class="form-control date_picker" type="text" placeholder="To date" name="end_date" id="end_date" required="required" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label class="control-label">Department:</label>
                        <select name="department" id="department" class="form-control" required="required">
                            <option value="">Select Department</option>
                            <option value="1">Central OPD</option>
                            <?php
                            if (!empty($dept_list)) {
                                foreach ($dept_list as $dept) {
                                    echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12 align-self-end">
                        <button class="btn btn-primary" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info dropdown-toggle" id="btnGroupDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(36px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a>
                                    <a class="dropdown-item" href="#" id="export_to_xls">.xls</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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

        var columns = [
            {
                title: "IPD",
                class: "ipd_no",
                data: function (item) {
                    return item.IpNo;
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
                title: "Gender",
                data: function (item) {
                    return item.Gender;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "BedNo",
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
                title: "Days",
                data: function (item) {
                    return item.NofDays;
                }
            },
            {
                title: "Doctor",
                data: function (item) {
                    return item.Doctor;
                }
            }
        ];

        function show_patients() {
            var statistics = '';
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
                    'url': base_url + 'reports/Ipd/get_patients_list',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': function (d) {
                        return $.extend({}, d, {
                            "search_form": $('#search_form').serializeArray()
                        });
                    },
                    drawCallback: function (response) {
                        statistics = response.statistics;

                    }
                },
                order: [[0, 'desc']],
                info: true,
                sScrollX: true
            });
            //show_statistics();
        }
    });
</script>