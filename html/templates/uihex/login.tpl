 <div class="row login">
  <div class="box clgn">
    <div class="content-wrap">
      <img src="/templates/uihex/img/ui-logo-332x80.png">
      <h6>{$LANG.clientlogin}</h6>
      <p class="text-danger bg-danger text-alert {if !$incorrect} displaynone{/if}" id="login-error">{$LANG.loginincorrect}</p>
      <form method="post" action="{$systemurl}dologin.php">
        <div class="form-group">
          <div class="input-group input-group-lg">
            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
            <input class="form-control" name="username" type="text" placeholder="{$LANG.loginemail}">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group input-group-lg">
            <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
            <input class="form-control" name="password" type="password" placeholder="{$LANG.loginpassword}">
          </div>
        </div>
	  	<div class="remember">
          <label class="checkbox-inline rememberme"><input type="checkbox" name="rememberme" id="rememberme" >{$LANG.loginrememberme}</label><a href="pwreset.php" class="forgot" style="float:right;">{$LANG.loginforgotteninstructions}</a>
        </div>
     <div class="row">
         <div class="col-md-12">
          <p><input style="float:left;" type="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="{$LANG.loginbutton}"><input style="float:right;" type="button" onclick="location.href='register.php'" name="reg" class="btn btn-primary-outline btn-lg btn-block" value="{$LANG.signup}"></p>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<div class="content-sm">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
