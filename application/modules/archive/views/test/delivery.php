<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-archive"></i> Archived Delivery report:</h3>
                <a class="btn btn-sm btn-success pull-right" href="<?php echo base_url('archive/TestReports') ?>"><i class="fa fa-backward"></i> Back to main</a>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <hr/>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .num{
        width: 40px !important;
    }
    .name{
        width: 80px !important;
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
                class: "num",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "OPD",
                class: "num",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "IPD",
                class: "num",
                data: function (item) {
                    return item.IpNo;
                }
            },
            {
                title: "Name",
                class: "name",
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
                title: "Delivery date",
                data: function (item) {
                    return item.babyBirthDate;
                }
            },
            {
                title: "Baby weight",
                data: function (item) {
                    return item.babyWeight;
                }
            },
            {
                title: "Baby Gender",
                data: function (item) {
                    return item.babygender;
                }
            },
            {
                title: "Delivery type",
                data: function (item) {
                    return item.deliverytype;
                }
            },
            {
                title: "DOA",
                data: function (item) {
                    return item.DoAdmission;
                }
            },
            {
                title: "Doctor",
                class: "name",
                data: function (item) {
                    return item.treatby;
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
                "drawCallback": function (settings) {

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
                    'url': base_url + 'archive/Test/get_birth_register_data',
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
                scrollX: true
            });
        }
    });
</script>