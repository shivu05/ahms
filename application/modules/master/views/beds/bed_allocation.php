<style>
    .beds_row{
        display: inline-block;
        padding-right: 2%;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list-alt"></i> Beds allocation:</h3></div>
            <div class="box-body">
                <div class="beds_area" >
                    <?php //pma($beds_list) ?>
                    <?php
                    if (!empty($beds_list)) {
                        $dept = array();
                        $dept_beds = array();
                        foreach ($beds_list as $val) {
                            $dept_beds[$val['department']][] = $val['bedno'];
                        }
                        //pma($dept_beds,1);
                        $html = '';
                        foreach ($dept_beds as $key => $value) {
                            $html .= '<div class="box box-primary">';
                            $html .= '<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-bed"></i> ' . $key . '</h3></div>';
                            $html .= '<div class="box-body">';
                            if (!empty($value)) {
                                foreach ($value as $row) {
                                    if ($row <> 0) {
                                        $html .= '<div class="beds_row" class="checkbox"><label><input type="checkbox" value="' . $row . '"> ' . $row . '</label></div>';
                                    } else {
                                        $html .= 'No beds available';
                                    }
                                }
                            }
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                        echo $html;

//                        foreach ($dept_beds as $key->$value) {
//                            $dom = '<div class="row">';
//                            $dom .= '<div class="col-md-4">';
//                            $dom .= $row['department'];
//                            $dom .= '</div>';
//                            $dom .= '<div class="col-md-8">';
//                            if ($row['bedno'] <> 0):
//                                $dom .= '<div class="beds_row" class="checkbox"><label><input type="checkbox" value="' . $row["id"] . '"> ' . $row["bedno"] . '</label></div>';
//                            //echo '<div class="beds_row"><input class="form-control" type="checkbox" >' . $row["bedno"] . '<br/></div>';
//                            endif;
//                            $dom .= '</div>';
//                            $dom .= '</div>';
//                            echo $dom;
//                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
