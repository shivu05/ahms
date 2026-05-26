<?php

function bed_value($data, $key, $default = '') {
    return isset($data[$key]) && $data[$key] !== null && $data[$key] !== '' ? $data[$key] : $default;
}

function bed_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function show_bed_status($data) {
    if ($data['bedstatus'] == 'Available') {
        return '<i class="fa fa-bed fa-2x text-success" style="cursor: pointer;" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Available"></i>';
    } else {
        $opd_no = bed_escape(bed_value($data, 'current_opd_no', bed_value($data, 'OpdNo')));
        $ipd_no = bed_escape(bed_value($data, 'current_ipd_no', bed_value($data, 'IpNo')));
        return '<i class="fa fa-bed fa-2x disabled text-warning" style="cursor: pointer;" aria-hidden="true" aria-hidden="true" data-toggle="tooltip" data-placement="left" '
                . ' title="Not Available: OPD - ' . $opd_no . ' IPD - ' . $ipd_no . '"></i>';
    }
}

function validate_edit($data) {
    if ($data['bedstatus'] == 'Available') {
        return '<i class="fa fa-edit fa-1x text-primary edit_bed_loc" data-dept="' . bed_escape($data['department']) . '" data-bed_id="' . bed_escape($data['id']) . '" '
                . ' data-bed_type="'.bed_escape($data['bed_category']).'" style="cursor: pointer;" aria-hidden="true" data-toggle="tooltip" data-placement="left"></i>';
    } else {
        return '<i class="fa fa-edit fa-1x disabled text-warning" style="cursor: pointer;" aria-hidden="true" aria-hidden="true" data-toggle="tooltip" data-placement="left" '
                . ' title="Please discharge patient to update bed details"></i>';
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list-alt"></i> Beds list:</h3>
        <a href="<?= base_url('refresh-beds')?>" target="_blank" class="btn btn-sm btn-primary pull-right"> Refresh beds</a>
        <a href="<?= base_url('manage-beds/export-pdf')?>" target="_blank" class="btn btn-sm btn-info pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Export PDF</a>
        </div>
            <div class="box-body">
                <table class="table table-bordered dataTable" id="bed_list_grid" width="100%" style="width:100%;">
                    <thead>
                    <th>Sl.No</th>
                    <th>Bed No</th>
                    <th>Bed Code</th>
                    <th>Ward</th>
                    <th>Type</th>
                    <th>Department</th>
                    <th>C.OPD</th>
                    <th>C.IPD</th>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $tr = '';
                        if (!empty(($beds_list))) {
                            foreach ($beds_list as $row) {
                                $opd_no = bed_value($row, 'current_opd_no', bed_value($row, 'OpdNo'));
                                $ipd_no = bed_value($row, 'current_ipd_no', bed_value($row, 'IpNo'));
                                $tr .= '<tr>';
                                $tr .= '<td>' . bed_escape($row['id']) . '</td>';
                                $tr .= '<td>' . bed_escape($row['bedno']) . '</td>';
                                $tr .= '<td>' . bed_escape(bed_value($row, 'bed_short_code')) . '</td>';
                                $tr .= '<td>' . bed_escape($row['wardno']) . '</td>';
                                $tr .= '<td>' . bed_escape($row['bed_category']) . '</td>';
                                $tr .= '<td>' . bed_escape($row['department']) . '</td>';
                                $tr .= '<td>' . bed_escape($opd_no) . '</td>';
                                $tr .= '<td>' . bed_escape($ipd_no) . '</td>';
                                $tr .= '<td>' . bed_escape(bed_value($row, 'patient_name')) . '</td>';
                                $tr .= '<td>' . bed_escape(bed_value($row, 'patient_age')) . '</td>';
                                $tr .= '<td>' . bed_escape(bed_value($row, 'patient_gender')) . '</td>';
                                $tr .= '<td>' . show_bed_status($row) . '</td>';
                                $tr .= '<td align="center">' . validate_edit($row) . '</td>';
                                $tr .= '</tr>';
                            }
                        }
                        echo $tr;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bed_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="bed_modal_label">Update Bed details</h5>
            </div>
            <div class="modal-body" id="bed_modal_body">
                <form action="" name="bed_form" id="bed_form" method="POST">
                    <div class="form-group">
                        <label for="filmpartOfXray_size">Bed No:</label>
                        <input type="text" name="id" id="id" readonly="readonly" class="form-control readonly"/>
                        <small class="form-text text-muted" id="partOfXrayHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Department:</label>
                        <select name="department" id="department" class="form-control" required="required">
                            <option value="">Select Department</option>
                            <?php
                            if (!empty($dept_list)) {
                                foreach ($dept_list as $dept) {
                                    echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bed_category">Bed category:</label>
                        <select name="bed_category" id="bed_category" class="form-control" required="required">
                            <option value="">Select Category</option>
                            <option value="general-male">General-Male</option>
                            <option value="general-female">General-Female</option>
                            <option value="general">General</option>
                            <option value="special">Special</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#bed_list_grid').DataTable({
            'ordering': false,
            'autoWidth': false,
            "rowCallback": function (row, data, index) {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                });
            }
        });

        $('#bed_list_grid').on('click', '.edit_bed_loc', function () {
            $('#bed_form #id').val($(this).data('bed_id'));
            $('#bed_form #department').val($(this).data('dept'));
            $('#bed_form #bed_category').val($(this).data('bed_type'));
            $('#bed_modal_box').modal('show');
        });

        //update-bed
        $('#bed_modal_box').on('click', '#btn-update', function () {
            var form_data = $('#bed_form').serializeArray();
            $.ajax({
                url: base_url + 'update-bed',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    alert(res.msg);
                    location.reload();
                },
                error: function (res) {
                    console.log(res);
                }
            });
        });

    });
</script>
