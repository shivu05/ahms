<?php
$category_options = "<option value=''>Choose category</option>";
if (!empty($categories)) {
    foreach ($categories as $cat) {
        $category_options .= "<option value='" . $cat['lab_cat_id'] . "'>" . $cat['lab_cat_name'] . '</option>';
    }
}
?>
<style>
    td[data-result-val="N"] {
        color: red;
    }

    td[data-result-val="Y"] {
        color: green;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Laboratory reference:</h3>

                <button class="btn btn-sm btn-success pull-right" style="margin-right: 1%;" id="add_lab_inv"><i
                        class="fa fa-plus"></i> Add investigations</button>
                <button class="btn btn-sm btn-info pull-right" style="margin-right: 1%;" id="add_lab_test"><i
                        class="fa fa-plus"></i> Add test</button>
                <button class="btn btn-sm btn-primary pull-right" style="margin-right: 1%;" id="add_lab_cat"><i
                        class="fa fa-plus"></i> Add category</button>
            </div>
            <div class="box-body">
                <table class="table table-bordered" id="lab_info_table">
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>Category</th>
                            <th>Test name</th>
                            <th>Investigation</th>
                            <th>Test reference</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //pma($result_set,1);
                        if (!empty($result_set)) {
                            $i = 0;
                            $tr = '';
                            foreach ($result_set as $row) {
                                $i++;
                                $tr .= '<tr>';
                                $tr .= '<td>' . $i . '</td>';
                                $tr .= '<td>' . $row['lab_cat_name'] . '</td>';
                                $tr .= '<td>' . $row['lab_test_name'] . '</td>';
                                $tr .= '<td>' . $row['lab_inv_name'] . '</td>';
                                $tr .= '<td class="lab_test_range">' . $row['lab_test_reference'] . '</td>';
                                $tr .= '<td data-result-val="' . $row['inv_color'] . '" class="lab_test_status">' . $row['test_status'] . '</td>';
                                $tr .= '<td><i class="fa fa-edit hand_cursor text-primary edit_lab" data-inv_id="' . $row['lab_inv_id'] . '"></i></td>';
                                $tr .= '</tr>';
                            }
                            echo $tr;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="test_modal_box" tabindex="-1" role="dialog" aria-labelledby="default_modal_label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="default_modal_label">Update Lab reference: <span id="pat_opd"
                        class="text-warning"></span> </h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <form action="" name="test_form" id="test_form" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="control-label">Test range:</label>
                            <input type="hidden" name="inv_id" id="inv_id" />
                            <input class="form-control" type="text" placeholder="Test range" name="test_range"
                                id="test_range" required="required" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="control-label">Test status:</label>
                            <select class="form-control" name='test_status' id="test_status" required='required'>
                                <option value='ACTIVE'>ACTIVE</option>
                                <option value="INACTIVE">INACTIVE</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lab_cat_modal_box" tabindex="-1" role="dialog" aria-labelledby="lab_cat_modal_box"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="lab_cat_modal_box_label"></h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <form action="" name="lab_cat_form" id="lab_cat_form" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="control-label">Lab main category:</label>
                            <input class="form-control" type="text" placeholder="Category" name="lab_cat" id="lab_cat"
                                required="required" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lab_test_modal_box" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="lab_test_modal_box_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="lab_test_modal_box_label"></h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <form action="" name="lab_test_form" id="lab_test_form" method="POST">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12">
                            <label class="control-label">Lab main category:</label>
                            <select name="lab_cat" id="lab_cat" class="form-control required" required>
                                <?= $category_options ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <label class="control-label">Enter no.of tests:</label>
                            <input type="number" name="no_of_tests" class="form-control" id="no_of_tests" value="0"
                                onchange="return_text_boxes(this.value, '#test_inputs', 'lab_test')" />
                            <button type="button" class="btn btn-info btn-sm pull-left" style="margin-top:2px;"
                                id="load"><i class="fa fa-refresh"></i> load test</button>
                        </div>
                        <div class="form-group col-md-12 col-sm-12" id="test_inputs">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="lab_investigations_modal_box" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="lab_test_modal_box_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="lab_investigations_modal_box_label"></h4>
            </div>
            <div class="modal-body" id="lab_investigations_modal_box_body">
                <form action="" name="lab_invest_form" id="lab_invest_form" method="POST">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12">
                            <label class="control-label">Lab main category:</label>
                            <select name="lab_cat" id="lab_cat" class="form-control required" required>
                                <?= $category_options ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <label class="control-label">Lab test:</label>
                            <select name="lab_test" id="lab_test" class="form-control required" required>
                                <option value="">Choose one</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <label class="control-label">Enter no.of investigations:</label>
                            <input type="number" name="no_of_invs" class="form-control" id="no_of_invs" value="0"
                                onchange="return_text_boxes(this.value, '#inv_inputs', 'lab_invs')" />
                            <button type="button" class="btn btn-info btn-sm pull-left" style="margin-top:2px;"
                                id="load"><i class="fa fa-refresh"></i> load investigations</button>
                        </div>
                        <div class="form-group col-md-12 col-sm-12" id="inv_inputs">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function return_text_boxes(n, dom, attr_name) {
        $(dom).html('');
        var inputs = '';
        for (var i = 0; i < n; i++) {
            inputs += '<input type="text" placeholder="' + attr_name + '-' + (i + 1) + '" name="' + attr_name + '_' + i + '" id="' + attr_name + '_' + i + '" class="form-control"><br/>';
        }
        $(dom).html(inputs);
    }
    $(document).ready(function () {

        $('#lab_info_table').dataTable();
        $('#lab_info_table').on('click', '.edit_lab', function () {
            var inv_id = $(this).data('inv_id');
            var range = $(this).closest('td').prev('td').prev('.lab_test_range').text();
            var status = $(this).closest('td').prev('.lab_test_status').text();
            $('#test_form #inv_id').val(inv_id);
            $('#test_form #test_range').val(range);
            $('#test_form #test_status').val(status).trigger('change');
            $('#test_modal_box').modal({ backdrop: 'static', keyboard: false }, 'show');
        });
        $('#test_modal_box').on('click', '#btn-ok', function () {
            var form_data = $('#test_form').serializeArray();
            $.ajax({
                url: base_url + 'master/laboratory/update_lab_reference',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    window.location.reload();
                }
            });
        });

        $('#add_lab_cat').on('click', function () {
            $('#lab_cat_modal_box #lab_cat_modal_box_label').html('Add lab category');
            $('#lab_cat_modal_box').modal('show');
        });

        $('#add_lab_test').on('click', function () {
            $('#lab_test_modal_box #lab_test_modal_box_label').html('Add lab tests');
            $('#lab_test_modal_box').modal('show');
        });

        $('#add_lab_inv').on('click', function () {
            $('#lab_investigations_modal_box #lab_investigations_modal_box_label').html('Add lab investigations');
            $('#lab_investigations_modal_box').modal('show');
        });
        $('#lab_investigations_modal_box #lab_invest_form').on('change', '#lab_cat', function () {
            var lab_cat = $(this).val();
            $.ajax({
                url: base_url + 'master/laboratory/fetch_lab_tests_by_category',
                type: 'POST',
                dataType: 'json',
                data: { 'lab_cat': lab_cat },
                success: function (res) {
                    console.log(res)
                    $('#lab_investigations_modal_box #lab_invest_form #lab_test').html(res.data);
                    //   $('#lab_investigations_modal_box').modal('hide');
                }
            });
        });

        $('#lab_cat_modal_box').on('click', '#btn-ok', function () {
            var form_data = $('#lab_cat_form').serializeArray();
            $.ajax({
                url: base_url + 'master/laboratory/add_lab_category',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#lab_cat_modal_box').modal('hide');
                    window.location.reload();
                }
            });
        });

        $('#lab_test_modal_box').on('click', '#btn-ok', function () {
            var form_data = $('#lab_test_form').serializeArray();
            $.ajax({
                url: base_url + 'master/laboratory/add_lab_tests',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#lab_test_modal_box').modal('hide');
                    $.notify({
                        title: res.title,
                        message: res.msg,
                        icon: 'fa fa-check',
                    }, {
                        type: res.type,
                    });
                    window.setTimeout(function () {
                        location.reload()
                    }, 3000);
                }
            });
        });

        $('#lab_investigations_modal_box').on('click', '#btn-ok', function () {
            var form_data = $('#lab_invest_form').serializeArray();
            $.ajax({
                url: base_url + 'master/laboratory/add_lab_investigations',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#lab_investigations_modal_box').modal('hide');
                    $.notify({
                        title: res.title,
                        message: res.msg,
                        icon: 'fa fa-check'
                    }, {
                        type: res.type,
                    });
                    window.setTimeout(function () {
                        location.reload()
                    }, 3000);
                }
            });
        });
    });
</script>