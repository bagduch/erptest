<html xmlns="http://www.w3.org/1999/xhtml"><head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <title>RA- Login</title>
            <link href="includes/jscript/css/ui.all.css" rel="stylesheet" type="text/css">
            <link href="templates/ra_flat/css/style.css" rel="stylesheet" type="text/css">
            <link href="templates/ra_flat/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
            <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
                <script type="text/javascript" src="../includes/jscript/jquery.js"></script>
                <script type="text/javascript" src="../includes/jscript/jqueryui.js"></script>
                </head>
                <body>
                    <div class="bodybg"></div>
                    <div class="bodybg1"></div>

                    <div id="login_wrapper">
                            <div id="login_container">
                                <div id="logo">
                                    <img src="images/ui-logo-332x80.png">
                                    <h6>Admin Area Login</h6>
                                </div>
                                
                                <div id="login_msg">
                                <strong>{$msgtitle}</strong> <br>{$msg}
                                </div>
                                {if $action ==""}
                                    <form action="dologin.php" method="post" name="frmlogin" id="frmlogin">
                                        <div  class="login-title user"><span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span><input type="text" name="username" size="30" class="login_inputs" placeholder="Username"></div>
                                        <div  class="login-title pass"><span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span><input type="password" name="password" size="30" class="login_inputs" placeholder="Password"></div>
                                        <div  class="login-options">
                                            <div  class="login-remember"><input type="checkbox" name="rememberme" id="rememberme"><label for="rememberme" style="cursor:hand">Remember me until I logout.</label></div>
                                            <div  class="login-forgot"><a href="login.php?action=reset">Forgot your password?</a></div>
                                        </div>
                                                
                                                    <div  class="login-button"><input type="submit" value="Login" class="button"></div>
                                    </form>
                                {else}
                                    <form action="login.php" method="post" name="frmlogin" id="frmlogin">
                                        <input type="hidden" name="action" value="reset">
                                        <input type="hidden" name="sub" value="send">

                                        <div  class="login-title user">
                                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                            <input  class="login_inputs" type="text" name="email"  placeholder="Email Address">
                                        </div>
                                                
                                        <div  class="login-button"><input type="submit" value="Reset Password"  class="button"></div>
                                                        
                                        <div  class="login-return"><a href="login.php">Return to the Login page</a></div>
                                    </form>

                                            {/if}



                                            <div id="extRA_info">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody><tr>
                                                            <td align="left" valign="middle">IP Logged: <strong>{$remote_ip}</strong></td>
                                                            <td align="right" valign="middle">Powered by <strong><a href="#" target="_blank">Robotic Accounting</a></strong></td>
                                                        </tr>
                                                    </tbody></table>
                                            </div>
                                            </div>
                                            </div>
                                    <div align="center"></div>
                                    {literal}
                                        <script type="text/javascript">
                                            $("form input:text:visible:first").focus();
                                        </script>
                                    {/literal}

                                    </body>
                                    </html>
