<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-pulse"></i> Other Procedures report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('other_procedures_treatments') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="xray_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="xray_modal_label">Update X-Ray details</h5>
            </div>
            <div class="modal-body" id="xray_modal_body">
                <form action="" name="xray_form" id="xray_form" method="POST">
                    <div class="form-group">
                        <label for="filmpartOfXray_size">Part of X-ray:</label>
                        <input class="form-control required" id="partOfXray" name="partOfXray" type="text" aria-describedby="partOfXrayHelp" placeholder="Enter Part of X-Ray">
                        <small class="form-text text-muted" id="partOfXrayHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Film size:</label>
                        <input type="hidden" name="ID" id="ID" />
                        <input class="form-control required" id="filmSize" name="filmSize" type="text" aria-describedby="film_sizeHelp" placeholder="Enter film size">
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').validate();
        var patient_table = '';
        $('#search_form').on('click', '#search', function () {
            $('#patient_statistics').html('');
            var form_data = $('#search_form').serializeArray();
            if ($('#search_form').valid()) {
                $.ajax({
                    url: base_url + 'reports/test/fetch_oherprocedures_records',
                    type: 'POST',
                    data: form_data,
                    success: function (response) {
                        $('#patient_statistics').html(response);
                        patient_table = $('#physic_grid').dataTable({
                            ordering: false
                        });
                        $('#physic_grid tbody').on('click', '.edit', function () {
                            var data = patient_table.row($(this).closest('tr')).data();
                            $('#xray_modal_box #xray_form #ID').val(data.ID);
                            $('#xray_modal_box #xray_form #partOfXray').val(data.partOfXray);
                            $('#xray_modal_box #xray_form #filmSize').val(data.filmSize);
                            $('#xray_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
                        });
                        $('#xray_form').validate({
                            messages: {
                                partOfXray: {required: 'Part of X-ray is empty'},
                                filmSize: {required: 'Film size is empty'}
                            }
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