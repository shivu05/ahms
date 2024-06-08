<?php

function show_bed_status($data) {
    if ($data['bedstatus'] == 'Available') {
        return '<i class="fa fa-bed fa-2x text-success" style="cursor: pointer;" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Available"></i>';
    } else {
        return '<i class="fa fa-bed fa-2x disabled text-warning" style="cursor: pointer;" aria-hidden="true" aria-hidden="true" data-toggle="tooltip" data-placement="left" '
                . ' title="Not Available: OPD - ' . $data["OpdNo"] . ' IPD - ' . $data["IpNo"] . '"></i>';
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list-alt"></i> Beds list:</h3></div>
            <div class="box-body">
                <table class="table table-bordered dataTable" id="bed_list_grid">
                    <thead>
                    <th>Sl.No</th>
                    <th>Bed No</th>
                    <th>Ward</th>
                    <th>Department</th>
                    <th>C.OPD</th>
                    <th>C.IPD</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                        <?php
                        $tr = '';
                        if (!empty(($beds_list))) {
                            foreach ($beds_list as $row) {
                                $tr .= '<tr>';
                                $tr .= '<td>' . $row['id'] . '</td>';
                                $tr .= '<td>' . $row['bedno'] . '</td>';
                                $tr .= '<td>' . $row['wardno'] . '</td>';
                                $tr .= '<td>' . $row['department'] . '</td>';
                                $tr .= '<td>' . $row['OpdNo'] . '</td>';
                                $tr .= '<td>' . $row['IpNo'] . '</td>';
                                $tr .= '<td>' . show_bed_status($row) . '</td>';
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#bed_list_grid').DataTable({
            'ordering': false,
            "rowCallback": function (row, data, index) {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                });
            }
        });
    });


</script>