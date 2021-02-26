<?php
//pma($result_set);
?>
<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Laboratory reference:</h3>
                <!--<button class="btn btn-sm btn-primary pull-right" id="add_main_pancha"><i class="fa fa-plus"></i> Add main procedure</button>
                <button class="btn btn-sm btn-success pull-right" style="margin-right: 1%;" id="add_sub_pancha"><i class="fa fa-plus"></i> Add sub procedure</button>-->
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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
<div class="modal fade" id="test_modal_box" tabindex="-1" role="dialog" aria-labelledby="default_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="default_modal_label">Update Lab reference: <span id="pat_opd" class="text-warning"></span> </h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <form action="" name="test_form" id="test_form" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="control-label">Test range:</label>
                            <input type="hidden" name="inv_id" id="inv_id"/>
                            <input class="form-control" type="text" placeholder="Test range" name="test_range" id="test_range" required="required" autocomplete="off">
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
    $(document).ready(function () {
        $('#lab_info_table').dataTable();
        $('#lab_info_table').on('click', '.edit_lab', function () {
            var inv_id = $(this).data('inv_id');
            var range = $(this).closest('td').prev('.lab_test_range').text();
            console.log(range);
            $('#test_form #inv_id').val(inv_id);
            $('#test_form #test_range').val(range);
            $('#test_modal_box').modal('show');
        });
        $('#test_modal_box').on('click', '#btn-ok', function () {
            var form_data = $('#test_form').serializeArray();
            $.ajax({
                url: base_url + '',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    console.log(res);
                    window.location.reload();
                }
            });
        });
    });
</script>