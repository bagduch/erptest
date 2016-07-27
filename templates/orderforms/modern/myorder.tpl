
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript" src="templates/orderforms/{$carttpl}/js/main.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery-ultimate-fancy-form.min.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/bootstrap.min.js"></script>
        <link href="templates/orderforms/{$carttpl}/uff/css/animate.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/jquery-ultimate-fancy-form.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/custom.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container row">
            <h1>UI Order Process</h1>
            <div class="form-container">
                <form class="form form-horizontal component-uff" onkeypress="return event.keyCode != 13;" role="form">
                    <h2 class="text-center">Registration</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div data-step>
                                <h4 data-sb="fadeInLeft">Personal Information</h4>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" data-sb="fadeInRight">First Name</label>
                                    <div class="col-sm-10">
                                        <input data-sb="fadeInLeft" type="text" class="form-control" data-validation-required="#first_name_error_required" name="first_name">
                                        <div class="clearfix"></div>
                                        <div id="first_name_error_required" class="alert alert-danger">
                                            First Name is required
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" data-sb="fadeInRight">Last Name</label>
                                    <div class="col-sm-10">
                                        <input  data-sb="fadeInLeft"
                                                type="text"
                                                class="form-control"
                                                data-validation-required="#last_name_error_required"
                                                name="last_name"
                                                >
                                        <div class="clearfix"></div>
                                        <div id="last_name_error_required" class="alert alert-danger">
                                            Last Name is required
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" data-sb="fadeInRight">Sex</label>
                                    <div class="col-sm-10">
                                        <select name="sex"  data-sb="fadeInLeft" class="form-control">
                                            <option value="1">Personal</option>
                                            <option value="2">Female</option>
                                            <option value="3">Male</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" data-sb="fadeInRight">Profession</label>
                                    <div class="col-sm-10">
                                        <select name="profession" class="form-control" data-sb="fadeInLeft">
                                            <option value="1">Programmer</option>
                                            <option value="2">Front End Developer</option>
                                            <option value="3">jQuery Fan</option>
                                            <option value="4">Not Sure</option>
                                        </select>
                                    </div>
                                </div>
                                <ul class="pager">
                                    <li class="right" data-sb="bounceInLeft"><a class="next" data-step-next>Next &raquo;</a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div data-step>
                                <h4 data-sb="fadeInLeft">Account Information</h4>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" data-sb="rollIn">Email Address</label>
                                    <div class="col-sm-10">
                                        <input  data-sb="bounceInLeft"
                                                type="text"
                                                class="form-control"
                                                data-validation-required="#email_error_required"
                                                data-validation-email="#email_error_invalid"
                                                name="email"
                                                >
                                        <div class="clearfix"></div>

                                        <div id="email_error_required" class="alert alert-danger">
                                            Email is required
                                        </div>
                                        <div id="email_error_invalid" class="alert alert-danger">
                                            Email is invalid
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" data-sb="rollIn">Password</label>
                                    <div class="col-sm-10">
                                        <input data-sb="bounceInLeft"
                                               type="password"
                                               class="form-control"
                                               data-validation-required="#password_error_required"
                                               name="password"
                                               >
                                        <div class="clearfix"></div>
                                        <div id="password_error_required" class="alert alert-danger">
                                            Password is required
                                        </div>
                                    </div>
                                </div>
                                <ul class="pager">
                                    <li class="left" data-sb="bounceInLeft"><a class="prev" data-step-previous>&laquo;  Previous</a></li>
                                    <li class="right" data-sb="bounceInLeft"><a class="next" data-step-finish>Finish &raquo;</a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>