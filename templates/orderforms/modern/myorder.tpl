<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery-ultimate-fancy-form.min.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/bootstrap.min.js"></script>
        <link href="templates/fontsawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/animate.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/custom.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container row">
            <h1>UI Order Process</h1>
            <div class="form-container">
                <form class="form form-horizontal step-one" role="form" method="post">
                    <h2 class="text-center">Registration</h2>
                    <div class="row">
                      {$error}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  for="#fname">First Name</label>
                                <input type="text" id="fname" class="form-control" name="rfname" >
                            </div>
                            <div class="form-group">
                                <label for="#fname">Last Name</label>
                                <input type="text" id="fname" class="form-control" name="rfname">
                            </div>
                            <div class="form-group">
                                <label for="#fname">Email</label>
                                <input type="text" id="fname" class="form-control" name="remail">
                            </div>
                            <div class="form-group">
                                <label for="#password">Password</label>
                                <input type="password" id="password" class="form-control" name="rpassword">
                            </div>
                            <div class="form-group">
                                <label for="#password2">Confirm Password</label>
                                <input type="password" id="password2" class="form-control" name="rpassword2">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-default" name="signup" type="submit" value="Sign Up">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  for="#username">Username</label>
                                <input type="text" id="username" class="form-control" name="username">
                            </div>
                            <div class="form-group">
                                <label for="#password">Password</label>
                                <input type="text" id="password" class="form-control" name="password">
                            </div>
                            <div class="remember">
                                <label class="checkbox-inline rememberme"><input type="checkbox" name="rememberme" id="rememberme">Remember Me</label>
                                <a href="pwreset.php" class="forgot" style="float:right;">Request a Password Reset</a>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-default" name="login" type="submit" value="Login">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>