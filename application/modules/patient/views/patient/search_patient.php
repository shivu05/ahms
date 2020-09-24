<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> Search patient:</h3>
            </div>
            <div class="box-body">
                <form name="patient_info_form" id="patient_info_form" method="POST">
                    <div class="row">

                        <div class="col-md-4">
                            <input type="text" name="opd_id" id="opd_id" placeholder="OPD " class="form-control" required="required"/>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn btn-primary" id="search_opd"><i class="icon-search"></i> Search</a>
                        </div>

                    </div>
                    <div id="patient_data"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#patient_info_form').on('click', '#search_opd', function () {
            $("#patient_data").html("");
            var opd = $('#opd_id').val();
            var ipd = $('#ipd_id').val();
            if ((opd.length == 0 || opd == "")) {
                $('#message').html("<span class='alert alert-error'>Please enter OPD </span>");
                return;
            }
            var dataToSend = {'opd_id': opd, 'ipd_id': ipd};
            $.ajax({
                type: 'POST',
                url: base_url + "patient/fetch_patient_info/",
                data: dataToSend,
                dataType: 'html',
                success: function (data) {
                    $('#message').html("");
                    $("#patient_data").html(data).show();

                },
                error: function (data) {
                    console.log("error");
                    console.log(data);
                }
            });
        });
    });
</script>