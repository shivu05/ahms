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
                        <div class="col-md-2">
                            <label class="radio-inline">
                                <input type="radio" name="type" id="opd" value="opd"> OPD
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" id="ipd" value="ipd"> IPD
                            </label>
                        </div>
                        <div class="col-md-2">
                            <div class="typeahead__container">
                                <div class="typeahead__field input-group">
                                    <div class="typeahead__query">
                                        <input class="js-typeahead-opd form-control" placeholder="Enter OPD number" name="q" id="q" autocomplete="off">
                                    </div>
                                    <div class="typeahead__button">
                                        <button type="button" class="btn" id="typeahead_reset" style="height: 34px !important;">
                                            <span class="typeahead__search-icon" style="padding-top: 5px !important;"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#typeahead_reset').on('click', function () {
            $('#q').val('');
        });
        $.typeahead({
            input: '.js-typeahead-opd',
            minLength: 1,
            order: "desc",
            group: {
                template: "{{group}} "
            },
            href: base_url + "pharmacy/sales/get_opd_details/ {{group | slugify}} / {{display | slugify}} / ",
            source: {
                "IPD": {
                    ajax: {
                        url: base_url + "pharmacy/sales/get_opd_details",
                        path: "data",
                        data: {
                            type: 'ipd'
                        }
                    }
                }
            },
            dropdownFilter: "OPD",
            emptyTemplate: 'No result found',
            callback: {
                onInit: function (node) {
                    console.log('Typeahead Initiated on ' + node.selector);
                },
                onClickAfter: function (node, a, item, event) {
                    var type = $("input[name='type']:checked").val();
                    event.preventDefault();
                    //console.log(item.display);
                    if (item.display) {
                        $.ajax({
                            url: base_url + "pharmacy/sales/fetch_patient_details",
                            type: 'POST',
                            data: {type: type, 'opd': item.display},
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                            }
                        });
                    }
                    $('#result-container').text('');
                }
            }
        });
    });
</script>