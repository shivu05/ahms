<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div
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