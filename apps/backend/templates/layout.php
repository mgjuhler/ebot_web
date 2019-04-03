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
        function submitForm (id) {
            $("#"+id).submit();
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
                <a class="brand" href="<?php echo url_for("homepage"); ?>">LANudlejning - eBot</a>
                <div class="nav-collapse collapse">
                    <?php if ($sf_user->isAuthenticated()): ?>
                        <p class="navbar-text pull-right">
                            Logged in as <a href="#" class="navbar-link"><?php echo $sf_user->getGuarduser()->getUsername(); ?></a> | <a href="<?php echo url_for('sf_guard_signout') ?>" class="navbar-link">Logout</a>
                        </p>
                        <ul class="nav">
                            <li class="active"><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Administration"); ?></a></li>
                            <li><a href="/index.php"><?php echo __("Back to site"); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php if ($sf_user->isAuthenticated()): ?>
                <?php include_component("main", "menu"); ?>
                <div class="span10">
                    <?php if ($sf_user->hasFlash("notification_error")): ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4><?php echo __("Error:"); ?></h4>
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

                    <div class="alert alert-error" id="websocket_support" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4><?php echo __("Warning!"); ?></h4>
                        <p><?php echo __("Your Browser does not support WebSocket Connections. Without WebSockets you are not abled to control and manage Matches at the eBot Webpanel!"); ?><br>
                            <?php echo __("Update your Browser to the latest version. To check supported Browsers, visit:"); ?> <a href="http://caniuse.com/websockets" target="_blank">http://caniuse.com/websockets</a></p>
                        </div>

                        <?php echo $sf_content ?>
                        <?php else: ?>
                            <div class="span12">
                                <?php echo $sf_content ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($sf_user->isAuthenticated()): ?>
                    <!-- Please, don't remove the brand -->
                    <footer class="footer">
                    </footer>
                <?php endif; ?>
            </div>

        </body>
        </html>
