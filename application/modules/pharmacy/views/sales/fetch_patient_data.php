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
                </tr>
            </table>
        </div>
    </div>
    <?php
    $div = '';
    if (!empty($dates)) {
        $i = 0;
        foreach ($dates as $row) {
            $div .= '<div class="hide_div well col-md-6"  id="' . $treat_ids[$i] . '" style="display:none;"><h4>Diagnosis: ' . $diagnosis[$i] . '</h4>';
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
    }
    echo $div;
    ?>

    <?php
} else {
    echo '<span class="error">No Data found</span>';
}
?>