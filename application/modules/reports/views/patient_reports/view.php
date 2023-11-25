<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Patient Information:</h3></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <form role="form" name="sales_form" id="sales_form" method="POST">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="kw" id="kw" placeholder="Search for OPD...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" name="search_opd" id="search_opd" type="button">Go!</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div>
                        </form>
                    </div>
                </div>
                <br/>
                <div class="row-fluid">
                    <div class="col-md-6" id="patient_div"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sales_form').on('click', '#search_opd', function () {
            var kw = $('#sales_form #kw').val();
            $.ajax({
                url: base_url + 'fetch_patient_data',
                type: 'POST',
                dataType: 'html',
                data: {'opd': kw},
                success: function (response) {
                    $('#patient_div').html(response);
                    $('.select2').select2();
                },
                error: function (error) {
                    console.log(error);
                }

            });
        });
        $('#patient_div').on('change', '#treatment_date', function () {
            var vali = $('#treatment_date').val();
            $.ajax({
                url: base_url + 'fetch-patient-repo',
                type: 'POST',
                dataType: 'json',
                data: {'treatment_date': vali},
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {

                }
            });
        });
    });
</script>