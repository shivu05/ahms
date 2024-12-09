<style type="text/css">
    #footer{
        margin:0px 0px 0px 0px; 
        padding:5px 0px; 
        position:absolute; 
        bottom:0px; 
        position:fixed; 
        background:#fff;
        text-align:center !important; 
        width:100%; 
        color:#333333; 
        font-size:11px;
        z-index:999;
        border-top: 1px solid #e8e8e8;
        box-shadow: 3px 0px 11px rgb(150, 150, 150) !important;
    }

</style>
<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
</script>
<footer class="main-footer" id="footer">
    <div class="containers">
        <div class="pull-left hidden-xs" style="margin-left: 15px !important">
            <?php $clientIp = getClientIpAddress(); ?>
            <span class="pull-right text-info">Your IP address : <?= htmlspecialchars(@$clientIp); ?></span>
            <?php /*if ($_SERVER['REQUEST_SCHEME'] == 'https') { ?>
                <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=M2aqKksIVkB3SLxKB93DbZs9NdJf8Q6EcjBPOpys606nTeqV3RLBERrnXeqU"></script></span>
            <?php } */?>
        </div>
        <div class="pull-right hidden-xs" style="margin-right: 15px !important">
            v 1.0
        </div>
        <strong style="margin-top: 1%;"> AYUSH -Copyright © <?= date('Y') ?> by <a href="http://ayushsoftwares.com/" target="_blank">AYUSH Softwares</a></strong>    
    </div>
</footer>