<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-medkit"></i> Pharmacy OPD report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12 col-lg-12 col-sm-12"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            $('#patient_statistics').html("");
            var form_data = $('#search_form').serializeArray();
            $('#loader_div').show();
            $.ajax({
                type: "POST",
                url: base_url + 'reports/sales/get_pharmacy_report',
                data: form_data,
                dataType: 'html',
                success: function (response) {
                    $('#patient_statistics').html(response);
                    $('#loader_div').hide();
                    $('#opd_pharma_table').dataTable();
                },
                error: function (res) {
                    $('#loader_div').hide();
                }
            });
        });
        $('#search_form').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });
    });
</script>