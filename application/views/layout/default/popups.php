<!-- Modal -->
<div class="modal fade" id="default_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="default_modal_label">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="default_modal_body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-primary fade" id="primary_modal_box">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Primary Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-outline pull-right" type="button">Close</button>                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.example-modal -->

<div class="modal modal-info fade" id="info_modal_box">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Info Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-outline pull-right" type="button">Close</button>                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.example-modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="warning_modal_box">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.modal -->
<!-- /.example-modal -->


<div class="modal modal-success fade" id="success_modal_box">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Success Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-outline pull-right" type="button">Close</button>                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.example-modal -->
<div class="modal modal-danger fade" id="danger_modal_box">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Danger Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-outline pull-right" type="button">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.example-modal -->
<!-- /.example-modal -->
<div class="modal modal-default fade" id="clear_modal_box">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">                
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.example-modal -->



<!-- Popup for Gap report -->
<div class="modal fade" id="skills_gap_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header panel_heading orange_bg" style="border-radius: 6px 6px 0px 0px;">
                <button aria-label="Close" data-dismiss="modal" class="close close_wrapper" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title orange_text_bold white"><?= $this->lang->line("OUT_DATE_PROF_TITLE") ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="alert gray_bg alert-dismissible alert_pad" style="float: none; text-align: left;margin: 0px auto;">
                        <button type="button" class="close close_wrapper" data-dismiss="alert" aria-hidden="true">×</button>

                        <div class="text-left" id="message_box">
                            <?= $this->lang->line("OUT_DATE_PROF_MSG") ?>
                        </div>        
                    </div>
                    <label><?= $this->lang->line("OUT_DATE_PROF_DATE") ?>:</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" id="gapdatepicker" data-provide="datepicker" data-date-end-date="0d" onkeydown="return false;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" id="skills_gap_export"><?= $this->lang->line("POP_UP_EXPORT") ?></button>
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button"><?= $this->lang->line("POP_UP_CANCEL") ?></button>                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Popup for Gap report ends-->
<div class="modal fade" id="export_skills_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header panel_heading orange_bg" style="border-radius: 6px 6px 0px 0px;">
                <button aria-label="Close" data-dismiss="modal" class="close close_wrapper" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title orange_text_bold white"><?= $this->lang->line("EMPTY_PROF_TITLE") ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group"><?= $this->lang->line("EMPTY_PROF_MSG") ?></div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default empty_skills_export_manager" type="button" id="indirect" value="yes"><?= $this->lang->line("POP_UP_YES") ?></button>
                <button data-dismiss="modal" class="btn btn-default pull-right empty_skills_export_manager" id="direct" type="button" value="No"><?= $this->lang->line("POP_UP_NO") ?></button>                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Popup for preferred language-->
<div class="modal fade" id="preferred_language">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header panel_heading orange_bg" style="border-radius: 6px 6px 0px 0px;">
                <button aria-label="Close" data-dismiss="modal" class="close close_wrapper" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title orange_text_bold white">Default Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Popup for help menu-->
<div class="modal fade" id="help_menu_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header panel_heading orange_bg" style="border-radius: 6px 6px 0px 0px;">
                <button aria-label="Close" data-dismiss="modal" class="close close_wrapper" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title orange_text_bold white"><?= $this->lang->line('MENU_HELP') ?></h4>
            </div>
            <div class="modal-body">
                <p><?= $this->lang->line('HELP_MENU_POPUP_MSG') ?></p>
            </div>
            <div class="modal-footer">
                <button if id="help_menu_file_show" data-dismiss="modal" class="btn btn-default pull-right" type="button">
                    <?= $this->lang->line('POP_UP_OK') ?>
                </button>                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- arushi -->
<div class="modal fade" id="help_menu_popup_2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header panel_heading orange_bg" style="border-radius: 6px 6px 0px 0px;">
                <button aria-label="Close" data-dismiss="modal" class="close close_wrapper" type="button">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title orange_text_bold white"><?= $this->lang->line('MENU_HELP') ?></h4>
            </div>
            <div class="modal-body">
                <p><?= $this->lang->line('HELP_MENU_POPUP_MSG') ?></p>
            </div>
            <div class="modal-footer">
                <button if id="help_menu_file_show_2" data-dismiss="modal" class="btn btn-default pull-right" type="button">
                    <?= $this->lang->line('POP_UP_OK') ?>
                </button>                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>