<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-title">Doctors list:</div>
            <div class="tile-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-3">
                        <label class="control-label">OPD</label>
                        <input class="form-control" type="text" placeholder="Enter OPD number" name="OpdNo" id="OpdNo" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                    </div>
                    <div class="form-group col-md-4 align-self-end">
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
                        <a href="<?php echo base_url('add-user'); ?>" class="btn btn-secondary" type="button" id="add"><i class="fa fa-fw fa-lg fa-plus-circle"></i> Add user</a>
                    </div>
                </form>
                <div id="user_details">
                    <table class="table table-bordered table-hover table-striped dataTable" id="users_table" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .status{
        width: 50px !important;
        text-align: center;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {

        var columns = [
            {
                title: "Name",
                class: "name",
                data: function (item) {
                    return item.user_name;
                }
            },
            {
                title: "Email",
                class: "email",
                data: function (item) {
                    return item.user_email;
                }
            },
            {
                title: "Mobile",
                class: "mobile",
                data: function (item) {
                    return item.user_mobile;
                }
            },
            {
                title: "Type",
                class: "mobile",
                data: function (item) {
                    return item.role_name;
                }
            },
            {
                title: "Status",
                class: "status",
                data: function (item) {
                    if (item.active == 1) {
                        return '<i class="fa fa-check-square text-success" style="font-size:20px" aria-hidden="true"></i>';
                    } else {
                        return '<i class="fa fa-check-square" aria-hidden="true"></i>';
                    }
                }
            },
            {
                title: "Edit",
                class: "status",
                data: function (item) {
                    return '<i class="fa fa-pencil-square" style="font-size:20px" aria-hidden="true"></i>';
                }
            }
        ];


        var user_table = $('#users_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [2]}
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
                'url': base_url + 'master/users/get_users_list',
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
    });
</script>