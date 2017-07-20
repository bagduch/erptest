

<div class="account-wrap">

    {include file="$template/clientareadetailslinks.tpl"}

   
    <div class="titleline"></div>
    <form  method="post" action="{$smarty.server.PHP_SELF}?action=changepw">
        <div class="row details">

        {include file="$template/pageheader.tpl" title=$LANG.clientareanavchangepw}

             {if $successful}
            <div class="alert alert-success">
                <p>{$LANG.changessavedsuccessfully}</p>
            </div>
            {/if}

            {if $errormessage}
            <div class="alert alert-danger">
                <p class="bold">{$errormessage}</p>
            </div>
            {/if}
        <div>
        <div class="col-lg-offset-4 col-lg-4">
        <div class="form-group">
        <label for="existingpw">{$LANG.existingpassword}</label>
        <input type="password" class="form-control" name="existingpw" id="existingpw" />
        </div>
        </div>
        <div class="col-lg-offset-4 col-lg-4">
        <div class="form-group">
        <label for="password">{$LANG.newpassword}</label>
        <input type="password" name="newpw" class="form-control" id="password" />
        </div>
        </div>
        <div class="col-lg-offset-4 col-lg-4">          
        <div class="form-group">
        <label for="confirmpw">{$LANG.confirmnewpassword}</label>
        <input type="password" class="form-control" name="confirmpw" id="confirmpw" />
        </div>
        </div>
        <div class="col-lg-offset-4 col-lg-4">
        <div class="form-group" style="padding-top:15px;">
        {include file="$template/pwstrength.tpl"}
        </div>
        </div>
        </div>
        <div class="btn-toolbar " role="toolbar">    
        <input class="btn btn-primary-dark btn-sm" type="submit" name="submit" value="{$LANG.clientareasavechanges}" />  
        <input class="btn btn-primary-outline" type="reset" value="{$LANG.cancel}" />
        </div> 
        </div>   
    </form>


</div>  