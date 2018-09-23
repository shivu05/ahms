<div class="error-page">
    <h2 class="headline text-yellow"><?=(isset($status_code)?$status_code:'')?></h2>
    <div class="error-content">
        <h3><i class="fa fa-info-circle text-yellow"></i>&nbsp; <?=(isset($title)?$title:'')?>!</h3>
        <p><?=(isset($message)?$message:'')?></p>        
    </div>
    <!-- /.error-content -->
</div>