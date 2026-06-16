<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i> Death register:</h3>
            </div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('reports/ipd/export_death_register_pdf'); ?>">
                    <div class="form-group col-md-2 col-sm-12">
                        <input class="form-control date_picker" type="text" placeholder="From date" name="start_date" id="start_date" autocomplete="off" required="required">
                    </div>
                    <div class="form-group col-md-2 col-sm-12">
                        <input class="form-control date_picker" type="text" placeholder="To date" name="end_date" id="end_date" required="required" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <select name="department" id="department" class="form-control" required="required">
                            <option value="">Select Department</option>
                            <?php
                            if ($is_admin) {
                                echo '<option value="1">Central IPD</option>';
                            }
                            if (!empty($dept_list)) {
                                foreach ($dept_list as $dept) {
                                    echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12 align-self-end">
                        <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                    <li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="death_register_table" width="100%"></table>
                </div>
                <div id="death_statistics" class="col-md-12"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var deathRegisterTable = '';
        var columns = [{
                title: "Sl No",
                data: function(item, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                title: "Register No",
                data: function(item) {
                    return item.death_register_no;
                }
            },
            {
                title: "UHID",
                data: function(item) {
                    return item.uhid || '';
                }
            },
            {
                title: "OPD/IPD",
                data: function(item) {
                    return item.opd_no + ' / ' + item.ipd_no;
                }
            },
            {
                title: "Name",
                data: function(item) {
                    return item.patient_name;
                }
            },
            {
                title: "Age/Sex",
                data: function(item) {
                    return item.age + ' / ' + item.gender;
                }
            },
            {
                title: "Department",
                data: function(item) {
                    return (item.department || '').replace(/_/g, ' ');
                }
            },
            {
                title: "Ward/Bed",
                data: function(item) {
                    return (item.ward_no || '') + ' / ' + (item.bed_no || '');
                }
            },
            {
                title: "Death",
                data: function(item) {
                    return item.death_date + ' ' + item.death_time;
                }
            },
            {
                title: "Certifying Doctor",
                data: function(item) {
                    return item.certifying_doctor;
                }
            },
            {
                title: "Immediate Cause",
                data: function(item) {
                    return item.immediate_cause;
                }
            },
            {
                title: "MCCD",
                data: function(item) {
                    return item.mccd_form4_issued == 1 ? 'Yes' : 'No';
                }
            },
            {
                title: "MLC",
                data: function(item) {
                    return item.mlc_case == 1 ? 'Yes' : 'No';
                }
            }
        ];

        $('#search_form').on('click', '#search', function() {
            showDeathRegister();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function() {
            $('#search_form').submit();
        });

        $('#search_form #export').on('click', '#export_to_xls', function(e) {
            e.preventDefault();
            $('.loading-box').css('display', 'block');
            $.ajax({
                url: base_url + 'reports/ipd/export_death_register_excel',
                type: 'POST',
                dataType: 'json',
                data: $('#search_form').serializeArray(),
                success: function(data) {
                    $('.loading-box').css('display', 'none');
                    if (data.op === 'ok') {
                        download(data.file, data.file_name, 'application/octet-stream');
                    } else {
                        $.notify({
                            title: "Death register:",
                            message: data.message || "No records found",
                            icon: 'fa fa-info'
                        }, {
                            type: "warning"
                        });
                    }
                }
            });
        });

        function showDeathRegister() {
            deathRegisterTable = $('#death_register_table').DataTable({
                'columns': columns,
                "bDestroy": true,
                language: {
                    sZeroRecords: "<div class='no_records'>No records found</div>",
                    sEmptyTable: "<div class='no_records'>No records found</div>",
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
                    'url': base_url + 'reports/ipd/get_death_register_list',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': function(d) {
                        return $.extend({}, d, {
                            "search_form": $('#search_form').serializeArray()
                        });
                    }
                },
                info: true,
                sScrollX: true
            });
            showStatistics();
        }

        function showStatistics() {
            $.ajax({
                url: base_url + 'reports/ipd/get_death_register_statistics',
                type: 'POST',
                dataType: 'json',
                data: {
                    search_form: $('#search_form').serializeArray()
                },
                success: function(response) {
                    var stats = response.statistics || [];
                    var total = 0;
                    var maleTotal = 0;
                    var femaleTotal = 0;
                    var table = "<hr/><h4>DEATH REGISTER STATISTICS:</h4><hr>";
                    table += "<table width='50%' class='table table-bordered dataTable' style='margin-left:auto;margin-right:auto;'>";
                    table += "<thead><tr><th><center>Department</center></th><th><center>Total</center></th><th><center>Male</center></th><th><center>Female</center></th></tr></thead><tbody>";
                    $.each(stats, function(i) {
                        table += "<tr>";
                        table += "<td>" + (stats[i].department || '').replace(/_/g, ' ') + "</td>";
                        table += "<td style='text-align:right;'>" + stats[i].Total + "</td>";
                        table += "<td style='text-align:right;'>" + stats[i].Male + "</td>";
                        table += "<td style='text-align:right;'>" + stats[i].Female + "</td>";
                        table += "</tr>";
                        total += parseInt(stats[i].Total || 0);
                        maleTotal += parseInt(stats[i].Male || 0);
                        femaleTotal += parseInt(stats[i].Female || 0);
                    });
                    table += "<tr><td align='right'><b>Total No:</b></td><td style='text-align:right;' class='alert-info'><b>" + total + "</b></td><td style='text-align:right;'><b>" + maleTotal + "</b></td><td style='text-align:right;'><b>" + femaleTotal + "</b></td></tr>";
                    table += "</tbody></table>";
                    $('#death_statistics').html(table);
                }
            });
        }
    });
</script>
