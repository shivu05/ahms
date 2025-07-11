<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Panchakarma procedure count :</h3>
                <a class="btn btn-primary pull-right btn-sm" id="pancha_stats" href="<?= base_url('reports/panchakarma')?>">Panchakarma statistics</a>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            show_patients();
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        function show_patients() {
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'reports/Test/get_panchakarma_procedure_count',
                type: 'POST',
                data: form_data,
                success: function (response) {
                    $('#patient_details').html(response);
                }
            });
        }
    });
</script>