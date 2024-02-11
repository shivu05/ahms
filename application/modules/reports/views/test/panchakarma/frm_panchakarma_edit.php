<form id="panchakarma_update_form" class="panchakarma_update_form" method="POST">
    <table class="table table-borderless" id="panchakarma_table">
        <thead>
            <tr>
                <th style="width: 30% !important;">Procedure</th>
                <th style="width: 30% !important;">Sub procedure</th>
                <th style="width: 20% !important;">Start date</th>
                <th style="width: 20% !important;">End date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($result)) {
                foreach ($result as $row) {
                    ?>
                    <tr>
                        <td>
                            <input type="hidden" name="pid" id="pid" value="<?= $pid ?>"/>
                            <input type="hidden" name="id[]" id="id" value="<?= $row['id'] ?>"/>
                            <select class="form-control chosen-select pancha_procedure pancha_input" name="pancha_procedure[]" id="pancha_procedure_1">
                                <option value="">Choose procedure</option>
                                <?php
                                if (!empty($pancha_procedures)) {
                                    foreach ($pancha_procedures as $proc) {
                                        $is_selected = ($row['treatment'] == $proc['proc_name']) ? 'selected="selected"' : '';
                                        echo "<option $is_selected value='" . $proc['proc_name'] . "'>" . $proc['proc_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control pancha_sub_procedure chosen-select pancha_input" name="pancha_sub_procedure[]" id="pancha_sub_procedure">
                                <option value="">Choose sub procedure</option>
                                <option value="<?= $row['procedure'] ?>" selected><?= $row['procedure'] ?></option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control date_picker pancha_input" name="pancha_proc_start_date[]" id="pancha_proc_start_date" value="<?= $row['date'] ?>"/></td>
                        <td><input type="text" class="form-control date_picker pancha_input" name="pancha_proc_end_date[]" pancha_proc_end_date value="<?= $row['proc_end_date'] ?>"/></td>
                    </tr>
                    <?php
                }
            }// end of main if
            ?>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', '.pancha_procedure', function () {
            var panchaprocedure = $(this).val();
            var dom = $(this);
            $.ajax({
                url: base_url + 'master/panchakarma/fetch_sub_procedures',
                data: {'proc_name': panchaprocedure},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    var sub_proc_options = '<option value="">choose sub procedure</option>';
                    if (response.status) {
                        var sub_proc = response.data;
                        $.each(sub_proc, function (i) {
                            sub_proc_options += '<option value="' + sub_proc[i].sub_proc_name + '">' + sub_proc[i].sub_proc_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.pancha_sub_procedure').html(sub_proc_options);
                        dom.closest('td').next('td').find('.pancha_sub_procedure').trigger("chosen:updated");
                    }
                },
                error: function (xhr, errorType, exception) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>