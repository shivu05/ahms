<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-archive"></i> Archived Physiotherapy report:</h3>
                <a class="btn btn-sm btn-success pull-right" href="<?php echo base_url('archive/TestReports') ?>"><i class="fa fa-backward"></i> Back to main</a>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('physiotherapy_treatments') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').validate();
        $('#search_form').on('click', '#search', function () {
            $('#patient_statistics').html('');
            var form_data = $('#search_form').serializeArray();
            if ($('#search_form').valid()) {
                $.ajax({
                    url: base_url + 'archive/test/fetch_physiotherapy_records',
                    type: 'POST',
                    data: form_data,
                    success: function (response) {
                        $('#patient_statistics').html(response);
                        $('#physic_grid').dataTable({
                            ordering: false
                        });
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });
    });
</script>