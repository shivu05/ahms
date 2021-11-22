<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Patient reference:</h3></div>
            <div class="box-body">
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('oldtable') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
<style>
    .ipd_no{
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });
        var columns = [
            {
                title: "#",
                class: "",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "First Name",
                class: "ipd_no",
                data: function (item) {
                    return item.FirstName;
                }
            },
            {
                title: "Last Name",
                class: "ipd_no",
                data: function (item) {
                    return item.LastName;
                }
            },
            {
                title: "Age",
                class: "ipd_no",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                class: "ipd_no",
                data: function (item) {
                    return item.gender;
                }
            },
            {
                title: "Occupation",
                class: "ipd_no",
                data: function (item) {
                    return item.occupation;
                }
            },
            {
                title: "Address",
                class: "ipd_no",
                data: function (item) {
                    return item.address;
                }
            },
            {
                title: "City",
                class: "ipd_no",
                data: function (item) {
                    return item.city;
                }
            },
            {
                title: "Diagnosis",
                class: "ipd_no",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Complaintes",
                class: "ipd_no",
                data: function (item) {
                    return item.complaints;
                }
            },
            {
                title: "Department",
                class: "ipd_no",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Sub dept",
                class: "ipd_no",
                data: function (item) {
                    return item.sub_dept;
                }
            },
            {
                title: "Procedures",
                class: "ipd_no",
                data: function (item) {
                    return item.procedures;
                }
            },
            {
                title: "Treatment",
                class: "ipd_no",
                data: function (item) {
                    return item.Trtment;
                }
            },
            {
                title: "Medicines",
                class: "ipd_no",
                data: function (item) {
                    return item.medicines;
                }
            }
        ];

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            "bDestroy": true,
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>"
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
                'url': base_url + 'get_data',
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
    });
</script>