<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="widget-small primary "><i class="icon fa fa-male fa-3x"></i>
            <div class="info">
                <h4>Male</h4>
                <p><b><?php echo $gender_count[0]['males']; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="widget-small info"><i class="icon fa fa-female fa-3x"></i>
            <div class="info">
                <h4>Female</h4>
                <p><b><?php echo $gender_count[0]['females']; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="widget-small warning"><i class="icon fa fa-user-plus fa-3x"></i>
            <div class="info">
                <h4>Total</h4>
                <p><b><?php echo $gender_count[0]['total']; ?></b></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="tile">
            <h3 class="tile-title">Department wise Patients</h3>
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
                    $table .="<tr>";
                    $table .="<td>" . $pdata['department'] . "</td>";
                    $table .="<td style='text-align: right;'>" . $pdata['OLD'] . "</td>";
                    $table .="<td style='text-align: right;'>" . $pdata['NEW'] . "</td>";
                    $table .="<td style='text-align: right;'>" . $pdata['Total'] . "</td>";
                    $table .="<td style='text-align: right;'>" . $pdata['Male'] . "</td>";
                    $table .="<td style='text-align: right;'>" . $pdata['Female'] . "</td>";
                    $table .="</tr>";
                    $total = $total + $pdata['Total'];
                    $maletotal = $maletotal + $pdata['Male'];
                    $femaletotal = $femaletotal + $pdata['Female'];
                    $dept = restructure_string(preg_replace('/&/', '', $pdata['department']));
                    array_push($graph, array($dept => $pdata['Total']));
                }
                $table .="<tr class='alert-info' style='text-align: right;'><td colspan=3 align=right>Total:</td><td style='text-align: right;'>" . $total . "</td><td>".$maletotal."</td><td>".$femaletotal."</td></tr>";
                $table .= "</tbody>";
                $table .= "</table></div>";
            }
            echo $table;
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="tile">
            <div class="embed-responsive embed-responsive-16by9" >
                <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
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
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                data: [parseInt(graph[0].aatyayikachikitsa), parseInt(graph[1].balaroga), parseInt(graph[2].kayachikitsa), parseInt(graph[3].panchakarma),
                    parseInt(graph[4].prasooti_striroga), parseInt(graph[5].shalakya_tantra), parseInt(graph[6].shalya_tantra),
                    parseInt(graph[7].swasthavritta)]
            },
        ]

    }
    var ctxl = $("#lineChartDemo").get(0).getContext("2d");
    var lineChart = new Chart(ctxl).Bar(lineChartData);
</script>