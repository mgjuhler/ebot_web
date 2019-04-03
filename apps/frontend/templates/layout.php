<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" type="image/png" href="/favicon.png" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }
        </style>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <script>
            function submitForm(id) {
                $("#" + id).submit();
            }

            var socketIoAddress = "<?php echo sfConfig::get("app_ebot_ip"); ?>:<?php echo sfConfig::get("app_ebot_port"); ?>";
            var socket = null;
            var socketIoLoaded = false;
            var loadingSocketIo = false;
            var callbacks = new Array();
            function initSocketIo(callback) {
                callbacks.push(callback);
                if (loadingSocketIo) {
                    return;
                }
                
                if (socketIoLoaded) {
                    if (typeof callback == "function") {
                        callback(socket);
                    }
                    return;
                }
                
                loadingSocketIo = true;
                $.getScript("http://"+socketIoAddress+"/socket.io/socket.io.js", function(){
                    socket = io.connect("http://"+socketIoAddress);
                    socket.on('connect', function(){ 
                        socketIoLoaded = true;
                        loadingSocketIo = false;
                        if (typeof callback == "function") {
                            callback(socket);
                        }
                        for (var c in callbacks) {
                            callbacks[c](socket);
                        }
                        //callbacks = new Array();
                    });
                });
            }
        </script>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo url_for("homepage"); ?>">LANudlejning 1234 - eBot</a>
                    <div class="nav-collapse collapse">
                        <?php if ($sf_user->isAuthenticated()): ?>
                            <p class="navbar-text pull-right">
                                Logged in as <a href="#" class="navbar-link"><?php echo $sf_user->getGuarduser()->getUsername(); ?></a>
                            </p>
                            <ul class="nav">
                                <li class="active"><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Home"); ?></a></li>
                                <li><a href="/admin.php"><?php echo __("Admin"); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <?php include_component("main", "menu"); ?>

                <div class="span10">
                    <?php if ($sf_user->hasFlash("notification_error")): ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4><?php echo __("Error"); ?> !</h4>
                            <?php echo $sf_user->getFlash("notification_error"); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($sf_user->hasFlash("notification_ok")): ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4><?php echo __("Information"); ?></h4>
                            <?php echo $sf_user->getFlash("notification_ok"); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo $sf_content ?>
                </div>
            </div>

            <!-- Please, don't remove the brand -->
            <footer class="footer">
            </footer>
        </div>
    </body>
</html>
