<section class="content">
    <div class="error-page">
        <h2 class="headline text-yellow" style="margin: 0px; padding: 0px;"> 401</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> <?= $this->lang->line("ERR_UN_AUTH_ACCESS_TIT")?></h3>

            <p style="margin-top: 25px;">
                <?= $this->lang->line("ERR_UN_AUTH_ACCESS_MSG")?><a href="<?=APP_BASE?>">return to home page.</a>
            </p>            
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>