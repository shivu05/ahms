<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Panchakarma report:</h3>
                <a href="<?= base_url('panchakarma-complete-report') ?>" target="_blank">
                    <button type="button" class="btn btn-sm btn-primary pull-right">Export full report</button>
                </a>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <form name="test_form" id="test_form" method="POST">
                    <input type="hidden" name="tab" id="tab" value="<?= base64_encode('panchaprocedure') ?>" />
                    <div id="patient_statistics" class="col-md-12"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .patient{
        width: 200px !important;
    }

    .sl_no{
        width: 80px !important;
        text-align: center;
    }
    .opd{
        width: 50px !important;
        text-align: center;
    }
    .place{
        width: 120px !important;
    }
    .department{
        width: 120px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').validate();
        $('#search_form').on('click', '#search', function () {
            $('#patient_statistics').html('');
            var form_data = $('#search_form').serializeArray();
            if ($('#search_form').valid()) {
                $.ajax({
                    url: base_url + 'reports/test/get_panchakarma_report',
                    type: 'POST',
                    data: form_data,
                    success: function (response) {
                        $('#patient_statistics').html(response);
                    },
                    error: function (error) {}
                });
            }
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        $('#search_form').on('click', '#btn_delete', function () {

            var $b = $('#test_form input[type=checkbox]');
            num = $b.filter(':checked').length;
            if (num == 0) {
                alert('Please select atleast one records');
            } else {
                if (confirm('Are you sure want to delete?')) {
                    var form_data = $('#test_form').serializeArray();
                    $.ajax({
                        url: base_url + 'reports/Test/delete_records',
                        type: 'POST',
                        data: form_data,
                        dataType: 'json',
                        success: function (res) {
                            $.notify({
                                title: "Panchakarma:",
                                message: "Deleted successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            }
        });
    });
    var clicked = false;
    function toggle(source) {
        //console.log($(".skip_script:checked").length+'check');
        $(".check_xray").prop("checked", !clicked);
        clicked = !clicked;
    }
</script>