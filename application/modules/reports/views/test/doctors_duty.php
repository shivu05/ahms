<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-stethoscope"></i> Doctors duty register:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('doctorsduty') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        $('#search_form').on('click', '#search', function () {
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + '/reports/Test/fetch_doctorsduty',
                type: 'POST',
                data: form_data,
                dataType: 'html',
                success: function (response) {
                    $('#patient_statistics').html(response);
                }
            });
        });
    });
</script>