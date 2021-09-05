<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Archived reports:</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8" id="archive_form_elements">
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
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        //pdf click 
        $('#archive_form_elements').on('click', '.pdf_export', function() {
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

                    var form = document.createElement("form");
                    var ele_arch_year = document.createElement("input");
                    var ele_selected_month = document.createElement("input");
                    var ele_selected_dept = document.createElement("input");
                    var ele_report_type = document.createElement("input");

                    form.method = "POST";
                    form.action = base_url + "archive/reports/export_to_pdf";

                    ele_arch_year.value = arch_year;
                    ele_arch_year.name = "arch_year";
                    form.appendChild(ele_arch_year);

                    ele_selected_month.value = selected_month;
                    ele_selected_month.name = "selected_month";
                    form.appendChild(ele_selected_month);

                    ele_selected_dept.value = selected_dept;
                    ele_selected_dept.name = "selected_dept";
                    form.appendChild(ele_selected_dept);

                    ele_report_type.value = report_type;
                    ele_report_type.name = "report_type";
                    form.appendChild(ele_report_type);

                    document.body.appendChild(form);

                    form.submit();
                }
            }
        });
    });
</script>