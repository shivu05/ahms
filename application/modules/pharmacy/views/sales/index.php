<style>
    .typeahead__filter-button{
        height: 34px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-shopping-cart text-black"></i> Sales:</h3>
            </div>
            <div class="box-body">
                <form role="form" name="sales_form" id="sales_form" method="POST"> 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kw" id="kw" placeholder="Search for OPD...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" name="search_opd" id="search_opd" type="button">Go!</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12" id="patient_div"></div>
                    </div>
                </form>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#patient_div').on('change', '#treatment_date', function () {
            $('.hide_div').hide();
            $('#' + $(this).val()).show();
        });
        $('#sales_form').on('click', '#search_opd', function () {
            var kw = $('#sales_form #kw').val();
            $.ajax({
                url: base_url + 'fetch_patient_data',
                type: 'POST',
                dataType: 'html',
                data: {'opd': kw},
                success: function (response) {
                    $('#patient_div').html(response);
                },
                error: function (error) {
                    console.log(error)
                }

            });
        });
    });
</script>