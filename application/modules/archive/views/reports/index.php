<link rel="stylesheet" href="<?php echo base_url('/assets/plugins/jstree-3.3.12/dist/themes/default/style.min.css'); ?>" />
<script src="<?php echo base_url('/assets/plugins/jstree-3.3.12/dist/jstree.min.js') ?>"></script>
<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Archived reports:</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-7" id="archive_form_elements">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Archived Year:</label>
                            <select class="form-control select2" style="width:25%;" id="archived_year" name="archived_year">
                                <option value="">Choose year</option>
                                <?php
                                if (!empty($archived_data)) {
                                    foreach ($archived_data as $row) {
                                        echo '<option value="' . $row['db_name'] . '">' . $row['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <hr />
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-gray">
                                    <th>Sl.No</th>
                                    <th>Report</th>
                                    <th>Department</th>
                                    <th>Criteria</th>
                                    <th>Export</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td class="text-bold">OPD</td>
                                    <td>
                                        <select class="form-control select2" style="width: 100%;">
                                            <option>Choose department</option>
                                            <?php
                                            if (!empty($dept_list)) {
                                                echo '<option value="1">CENTRAL</option>';
                                                foreach ($dept_list as $row) {
                                                    echo '<option value="' . $row['dept_unique_code'] . '">' . $row['department'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" style="width:100%">
                                            <option>Choose</option>
                                            <option value="year">Full Year</option>
                                            <?php
                                            if (!empty($months_list)) {
                                                foreach ($months_list as $row) {
                                                    echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style="text-align: center;">
                                        <i class="fa fa-2x fa-file-pdf-o text-primary pdf_export" data-report="opd" style="cursor:pointer" aria-hidden="true"></i>
                                        <i class="fa fa-2x fa-file-excel-o text-secondary excel_export" aria-hidden="true" style="cursor:pointer"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td class="text-bold">IPD</td>
                                    <td>
                                        <select class="form-control select2" style="width: 100%;">
                                            <option>Choose department</option>
                                            <?php
                                            if (!empty($dept_list)) {
                                                foreach ($dept_list as $row) {
                                                    echo '<option value="' . $row['dept_unique_code'] . '">' . $row['department'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2 selected_month" style="width:100%" name="selected_month" id="selected_month">
                                            <option value="">Choose</option>
                                            <option value="year">Full Year</option>
                                            <?php
                                            if (!empty($months_list)) {
                                                foreach ($months_list as $row) {
                                                    echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style="text-align: center;">
                                        <i class="fa fa-2x fa-file-pdf-o text-primary pdf_export" data-report="ipd" style="cursor:pointer" aria-hidden="true"></i>
                                        <i class="fa fa-2x fa-file-excel-o text-secondary excel_export" aria-hidden="true" style="cursor:pointer"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <div id="jstree_demo_div">
                            <?php listFolderFiles('public'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $('#jstree_demo_div').jstree({
                "core": {
                    "themes": {
                        "url": true,
                        "icons": true
                    },
                    "check_callback": function (operation, node, node_parent, node_position, more) {
                        if (operation === 'delete_node') {
                            if (confirm('sure') == true) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return true;
                        }
                    }
                },
                "types": {

                },
                "plugins": [
                    "contextmenu", "search", "state", "types", "wholerow", "html_data", "themes", "ui"
                ],
                contextmenu: {
                    'select_node': true,
                    'items': function (node) {
                        var tmp = $.jstree.defaults.contextmenu.items();
                        tmp.ccp = false;
                        tmp.create = false;
                        tmp.rename = false;
                        tmp.remove = false;
                        if (node.children.length == 0) {
                            /*tmp.remove = {"label": "Delete", "action": function (data) {
                                    $('#jstree_demo_div').jstree(true).delete_node(node);
                                }};*/
                            tmp.custom_action = {"label": "Download", "action": function (data) {
                                    var inst = $.jstree.reference(data.reference), obj = inst.get_node(data.reference);
                                    //console.log(data.reference[0]);
                                    window.open(data.reference[0].href);
                                }};
                        } else {
                            tmp.remove = false;
                        }
                        return tmp;
                    }
                }
            }).bind("delete_node.jstree", function (e, data) {
                //console.log(data.instance._model.data);
                var path = '';
                var ordered = Object.keys(data.instance._model.data).sort().reduce(
                        (obj, key) => {
                    obj[key] = data.instance._model.data[key];
                    return obj;
                }, {});
                $.each(ordered, function (k, v) {
                    if (k != '#') {
                        path += '/' + v.text;
                        console.log('key', v);
                    }
                });
                console.log(path);
                $.ajax({
                    url: base_url + "archive/reports/tree_operations",
                    type: 'POST',
                    data: {
                        'file_path': path
                    },
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (data) {
                        console.log(data)
                    }
                });
            });
        });

        $('#archive_form_elements #export').on('click', '.excel_export', function (e) {
            e.preventDefault();
            var arch_year = $('#archive_form_elements #archived_year').val();
            if (arch_year === "") {
                alert('Archvied year can not be empty');
            } else {
                $('.loading-box').css('display', 'block');
                var form_data = $('#search_form').serializeArray();
                $.ajax({
                    url: base_url + 'patient/patient/export_patients_list',
                    type: 'POST',
                    dataType: 'json',
                    data: {search_form: form_data},
                    success: function (data) {
                        $('.loading-box').css('display', 'none');
                        download(data.file, data.file_name, 'application/octet-stream');
                    }
                });
            }
        });

        //pdf click 
        $('#archive_form_elements').on('click', '.pdf_export', function () {
            var arch_year = $('#archive_form_elements #archived_year').val();
            if (arch_year === "") {
                alert('Archvied year can not be empty');
            } else {
                var report_type = $(this).data('report');

                var selected_month = $(this).parent('td').prev('td').find('select').val();
                var selected_dept = $(this).parent('td').prev('td').prev('td').find('select').val();

                if (selected_month !== "" && selected_dept !== "") {
                    var post_data = {
                        'arch_year': arch_year,
                        'selected_month': selected_month,
                        'selected_dept': selected_dept,
                        'report_type': report_type
                    };
                    $.ajax({
                        type: 'post',
                        url: base_url + "archive/reports/export_to_pdf",
                        data: post_data,
                        beforeSend: function (xhr) {
                            $('#ajxloading').show();
                        },
                        success: function (res) {
                            alert('File downloaded sucessfully');
                            $('#ajxloading').hide();
                            window.location.reload();
                        },
                        error: function () {
                            $('#ajxloading').hide();
                            //window.location.reload();
                        }
                    });
                }
            }
        });
    });
</script>