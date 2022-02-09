<?php
if (!empty($data)) {
    $dates = explode(',', $data['CameOn']);
    $treat_ids = explode(',', $data['treat_id']);
    $diagnosis = explode('##', $data['diagnosis']);
    $treatment = explode('##', $data['treatment']);
    //pma($dates);
    $option = '<option value="">Choose date</option>';
    if (!empty($dates)) {
        $i = 0;
        foreach ($dates as $row) {
            $option .= '<option value="' . $treat_ids[$i] . '">' . $row . '</option>';
            $i++;
        }
    }
    ?>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered">
                <tr class="bg-aqua">
                    <th>Name: <?= $data['name'] . '&nbsp;&nbsp;(' . $data['Age'] . ')' . '&nbsp;&nbsp;(' . $data['gender'] . ')'; ?></th>
                    <th>Place: <?= $data['city']; ?></th>
                </tr>
                <tr>
                    <td>
                        <label for="treatment_date">Select Visited date:</label>
                        <select class="form-control" name="treatment_date" id="treatment_date">
                            <?= $option ?>
                        </select>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row-fluid">
        <?php
        $div = $div_two = '';

        if (!empty($dates)) {
            $i = 0;
            foreach ($dates as $row) {
                $div .= '<div class="hide_div well col-md-4 pl-2"  id="' . $treat_ids[$i] . '" style="display:none;"><h4>Diagnosis: ' . $diagnosis[$i] . '</h4>';
                $meds = explode(',', $treatment[$i]);
                if (!empty($meds)) {
                    $div .= '<table class="table table-bordered" width="50">';
                    $div .= '<caption class="text-info"><h5>Prescribed medicine</h5></caption>';
                    foreach ($meds as $row) {
                        $div .= '<tr><td>' . $row . '</td></tr>';
                    }
                    $div .= '</table>';
                }
                $div .= '</div>';
                $i++;
            }

            $j = 0;

            foreach ($dates as $row) {
                $div_two .= '<div class="hide_div well col-md-6"  id="sec_' . $treat_ids[$j] . '" style="display:none;margin-left:2% !important;">';
                $meds = explode(',', $treatment[$j]);
                if (!empty($meds)) {
                    $div_two .= '<table class="table table-bordered" width="100%">';
                    $div_two .= '<caption class="text-info"><h5>Prescribed medicine</h5></caption>';
                    foreach ($meds as $row) {
                        if (!empty(trim($row)) || strlen(trim($row)) > 0) {
                            if (!empty($products_list)) {
                                $select = '<select class="form-control select2" data-placeholder="Type in medicine"><option value=""></option>';
                                foreach ($products_list as $row) {
                                    $select .= "<option>" . $row['name'] . "</option>";
                                }
                                $select .= "</select>";
                            }
                            $div_two .= '<tr><td>' . $select . '</td>';
                            $div_two .= '<td><input type="number" name="qty[]" id="qty" value="1"/></td></tr>';
                        }
                    }
                    $div_two .= '</table>';
                }
                $div_two .= '</div>';
                $i++;
            }
        }
        echo $div;
        //echo $div_two;
        ?>
    </div>
    <?php
} else {
    echo '<span class="error">No Data found</span>';
}
?>
<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    });
</script>