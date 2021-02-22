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
                                $tr .= '<td>' . $row['lab_test_reference'] . '</td>';
                                $tr .= '<td><i class="fa fa-edit hand_cursor text-primary" data-inv_id="'.$row['lab_inv_id'].'"></i></td>';
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#lab_info_table').dataTable();
    });
</script>