<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon fa fa-male fa-1x"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Male</span>
                <span class="info-box-number"><?php echo $gender_count[0]['males']; ?></span>
            </div>
            <!--/.info-box-content -->
        </div>
        <!--/.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon fa fa-female"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Female</span>
                <span class="info-box-number"><?php echo $gender_count[0]['females']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon fa fa-user-plus"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number"><?php echo $gender_count[0]['total']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Department wise patients</h3></div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title"></h3>
                    <?php
                    $table = '';
                    $graph = array();
                    if (!empty($dept_wise_data)) {
                        $total = 0;
                        $maletotal = 0;
                        $femaletotal = 0;
                        $table = "<div class='table-responsive'><table class='table table-bordered'>";
                        $table .= "<thead><tr style='background-color:#009688;color:#FFFFFF'><th><center>Department</center></th><th><center>Old</center></th><th><center>New</center></th><th><center>Total</center></th><th><center>Male</center></th><th><center>Female</center></th></tr></thead>";
                        $table .= "<tbody>";
                        foreach ($dept_wise_data as $pdata) {
                            $table .= "<tr>";
                            $table .= "<td>" . ucfirst(strtolower(str_replace('_', ' ', $pdata['department']))) . "</td>";
                            $table .= "<td style='text-align: right;'>" . $pdata['OLD'] . "</td>";
                            $table .= "<td style='text-align: right;'>" . $pdata['NEW'] . "</td>";
                            $table .= "<td style='text-align: right;'>" . $pdata['Total'] . "</td>";
                            $table .= "<td style='text-align: right;'>" . $pdata['Male'] . "</td>";
                            $table .= "<td style='text-align: right;'>" . $pdata['Female'] . "</td>";
                            $table .= "</tr>";
                            $total = $total + $pdata['Total'];
                            $maletotal = $maletotal + $pdata['Male'];
                            $femaletotal = $femaletotal + $pdata['Female'];
                            $dept = restructure_string(preg_replace('/&/', '', $pdata['department']));
                            array_push($graph, array($dept => $pdata['Total']));
                        }
                        $table .= "<tr class='alert-info' style='text-align: right;'><td colspan=3 align=right>Total:</td><td style='text-align: right;'>" . $total . "</td><td>" . $maletotal . "</td><td>" . $femaletotal . "</td></tr>";
                        $table .= "</tbody>";
                        $table .= "</table></div>";
                    }
                    echo $table;
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var graph = '<?php echo json_encode($graph); ?>';
    graph = $.parseJSON(graph);

    var lineChartData = {
        labels: ["Aatyayikachikitsa", "Balaroga", "KayaChikitsa", "Panchakarma", "Prasooti & Striroga", "Shalakya Tantra", "Shalya Tantra", "Swasthavritta"],
        datasets: [
            {
                fillColor: "rgba(51,204,255,0.5)",
                strokeColor: "rgba(51,204,255,1)",
                pointColor: "rgba(51,204,255,1)",
                pointStrokeColor: "#fff",
                data: [
                    parseInt(graph[0].aatyayikachikitsa),
                    parseInt(graph[1].balaroga),
                    parseInt(graph[2].kayachikitsa),
                    parseInt(graph[3].panchakarma),
                    parseInt(graph[4].prasooti__striroga),
                    parseInt(graph[5].shalakya_tantra),
                    parseInt(graph[6].shalya_tantra),
                    parseInt(graph[7].swasthavritta)
                ]
            }
        ]

    };
    var ctxl = $("#lineChartDemo").get(0).getContext("2d");
    var lineChart = new Chart(ctxl).Bar(lineChartData);
</script>