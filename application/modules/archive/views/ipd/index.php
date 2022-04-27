<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-archive" aria-hidden="true"></i> Archived IPD report:</h3></div>
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
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Ward",
                data: function (item) {
                    return item.WardNo;
                }
            }, {
                title: "Bed",
                data: function (item) {
                    return item.BedNo;
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
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            }
        ];
        var patient_table = '';
        function show_patients() {
            var statistics = '';
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
                'ordering': false,
                'ajax': {
                    'url': base_url + 'archive/Ipd/get_patients_list',
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
            show_statistics();
        }

        function show_statistics() {
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'archive/Ipd/get_statistics',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (response) {
                    stats = response.statistics;
                    console.log(stats);
                    var total = 0;
                    var male_total = 0;
                    var female_total = 0;
                    var table = "<hr/><h4>IPD STATISTICS:</h4><hr>";
                    table += "<table width='50%' class='table table-bordered dataTable' style='margin-left: auto;margin-right: auto;'>";
                    table += "<thead><tr><th width='30%'><center>Department</center></th><th><center>Total</center></th><th><center>Male</center></th><th><center>Female</center></th>"
                            + "</tr></thead>";
                    table += "<tbody>";
                    $.each(stats, function (i) {
                        table += "<tr>";
                        table += "<td>" + stats[i].department + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].Total + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].Male + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].Female + "</td>";
                        table += "</tr>";
                        total = parseInt(total) + parseInt(stats[i].Total);
                        male_total = parseInt(male_total) + parseInt(stats[i].Male);
                        female_total = parseInt(female_total) + parseInt(stats[i].Female);
                    });
                    table += "<tr><td align=right><b>Total No:</b></td><td style='text-align: right;' class='alert-info'><b>" + total + "</b></td><td style='text-align: right;'><b>" + male_total + "</b></td><td style='text-align: right;'><b>" + female_total + "</b></td><td></td><td></td></tr>";
                    table += "</tbody>";
                    table += "</table>";
                    //console.log(table);
                    //alert('Hi');
                    $('#patient_statistics').html(table);
                },
                error: function (err) {
                    console.log(err.responseText);
                }
            });

        }
    });
</script>