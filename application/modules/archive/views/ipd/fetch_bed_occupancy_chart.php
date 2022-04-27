<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-archive" aria-hidden="true"></i> Archived IPD: Bed occupancy chart</h3>
                <div class="btn-group pull-right" role="group" id="export">
                    <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                            <!--<li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>-->
                            <?php // base_url('archive/Ipd/bed_occupancy_chart_pdf') ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="#">
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label  sr-only">Archived Year:</label>
                        <select name="arch_year" id="arch_year" class="form-control" required="required">
                            <option value="">Select Year</option>
                            <?php
                            if (!empty($arch_years)) {
                                foreach ($arch_years as $row) {
                                    echo "<option value='" . urlencode($row['db_name']) . "'>" . $row['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <span id="arch_year_msg" class="error"></span>
                    </div>
                </form>
                <div id="div_bed_occ_chart"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('change', '#arch_year', function () {
            var arch_year = $(this).val();
            $('#arch_year_msg').html('');
            if (arch_year !== '') {
                $.ajax({
                    url: base_url + 'archive/Ipd/bed_occupancy_chart',
                    type: 'POST',
                    dataType: 'html',
                    data: {arch_year: arch_year},
                    success: function (response) {
                        $('#div_bed_occ_chart').html(response);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            } else {
                $('#div_bed_occ_chart').html('');
                $('#arch_year_msg').html('Please select year');
            }
        });

        $('#export_to_pdf').on('click', function (e) {
            e.preventDefault();
            var arch_year = $('#search_form #arch_year').val();
            window.open(base_url + 'archive/Ipd/bed_occupancy_chart_pdf/' + arch_year);
        });
    });
</script>