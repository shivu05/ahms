<style>
    body
    {
        font: caption;
    }
    body
    {
        color: WindowText;
        background-color: Window;
        border: 2px solid ActiveBorder;
    }
</style>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">System information:</div>
            <div class="panel-body">
                <!-- up time -->
                <div class="panel panel-default">
                    <div class="panel-heading">Uptime:</div>
                    <div class="panel-body">
                        <pre>
                            <?php system("uptime"); ?>
                        </pre>
                    </div>
                </div>
                <!-- System Information -->
                <div class="panel panel-default">
                    <div class="panel-heading">System Information:</div>
                    <div class="panel-body">
                        <pre>
                            <?php system("uname -a"); ?>
                        </pre>
                    </div>
                </div>
                <!-- Memory Usage (MB) -->
                <div class="panel panel-default">
                    <div class="panel-heading">Memory Usage (MB):</div>
                    <div class="panel-body">
                        <pre>
                            <?php system("free -m"); ?>
                        </pre>
                    </div>
                </div>
                <!-- Disk Usage: -->
                <div class="panel panel-default">
                    <div class="panel-heading">Disk Usage:</div>
                    <div class="panel-body">
                        <pre>
                            <?php system("df -h"); ?>
                        </pre>
                    </div>
                </div>
                <!--CPU Information: -->
                <div class="panel panel-default">
                    <div class="panel-heading">CPU Information:</div>
                    <div class="panel-body">
                        <pre>
                            <?php system("cat /proc/cpuinfo | grep \"model name\\|processor\""); ?>
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
