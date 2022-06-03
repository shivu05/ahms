<div class="row">
    <div class="box box-primary">
        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-archive"></i> Archived Nursing Indent Report: </h3>
            <a class="btn btn-sm btn-success pull-right" href="<?php echo base_url('archive/TestReports') ?>"><i class="fa fa-backward"></i> Back to main</a>
        </div>
        <!-- /widget-header -->
        <div class="box-body">
            <?php echo $top_form; ?>
            <hr/>
            <div id="nursing_data"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            $('#nursing_data').html("");
            var form_data = $('#search_form').serializeArray();
            $('#loader_div').show();
            $.ajax({
                type: "POST",
                url: base_url + 'archive/nursing/get_nursing_indent_report',
                data: form_data,
                dataType: 'html',
                success: function (response) {
                    $('#nursing_data').html(response);
                    $('#loader_div').hide();
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