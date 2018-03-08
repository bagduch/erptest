 <div class="row">
  <div class="box fgtpsw">
    <p class="text-danger bg-danger text-alert {if !$incorrect} displaynone{/if}" id="login-error"><strong><span aria-hidden="true" class="icon icon-ban"></span> {$LANG.warning}</strong><br/>{$LANG.loginincorrect}</p>         
    <div class="content-wrap">
      <h3>{$LANG.pwreset}</h3>
      {if $success}
      <p class="text-success bg-success text-alert"><span aria-hidden="true" class="icon icon-paper-plane"></span> {$LANG.pwresetvalidationsent}</p><p><small>{$LANG.pwresetvalidationcheckemail}</small></p>
          <p class="rtncl"><a href="/clientarea.php">Return to Client Login Page</a></p>
      {else}
      {if $errormessage}
      <p><small>{$LANG.pwresetdesc}</small></p>      
      <p class="text-danger bg-danger text-alert">{$errormessage}</p>
      {else}
      <p><small>{$LANG.pwresetdesc}</small></p>
      {/if}
      <form method="post" action="pwreset.php"  name="frmpwreset">
        <input type="hidden" name="action" value="reset" />
        <div class="row">
         <div class="col-md-12">
          <p><input type="submit" class="btn btn-primary btn-lg btn-block" value="{$LANG.pwresetsubmit}" /></p>
          <p class="rtncl"><a href="/clientarea.php">Return to Client Login Page</a></p>
        </div>
        </div>
      </form>
    </div>
    {/if} 
  </div>
  </div>
  <div class="content-sm">
    <div class="row">   
      <div class="col-md-4 col-md-offset-4">
