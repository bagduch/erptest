



<script type="text/javascript" src="includes/jscript/statesdropdown.js"></script>

<div class="account-wrap">

{include file="$template/clientareadetailslinks.tpl"}


<div class="titleline"></div>
    <form method="post" action="{$smarty.server.PHP_SELF}?action=details">
        
        <div class="row details">
            <h3 class="page-header"><span aria-hidden="true" class="icon icon-user"></span> {$LANG.clientareanavdetails}</h3>
                {if $successful}
                    <div class="alert alert-success">
                        <p>{$LANG.changessavedsuccessfully}</p>
                    </div>
                {/if}

                {if $errormessage}
                    <div class="alert alert-danger">
                        <p class="bold">{$LANG.clientareaerrors}</p>
                        <ul>
                            {$errormessage}
                        </ul>
                    </div>
                {/if}
            <div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label  for="firstname">{$LANG.clientareafirstname}</label>
                        <input type="text" name="firstname" id="firstname" value="{$clientfirstname}"{if in_array('firstname',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>

                    <div class="form-group">
                        <label  for="email">{$LANG.clientareaemail}</label>
                        <input type="text" name="email" id="email" value="{$clientemail}"{if in_array('email',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>
                </div>
                <div class="col-lg-4">
                    
                    <div class="form-group">
                        <label  for="lastname">{$LANG.clientarealastname}</label>
                        <input type="text" name="lastname" id="lastname" value="{$clientlastname}"{if in_array('lastname',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>

                    <div class="form-group">
                        <label  for="phonenumber">{$LANG.clientareaphonenumber}</label>
                        <input type="text" name="phonenumber" id="phonenumber" value="{$clientphonenumber}"{if in_array('phonenumber',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label  for="companyname">{$LANG.clientareacompanyname}</label>
                        <input type="text" name="companyname" id="companyname" value="{$clientcompanyname}"{if in_array('companyname',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>
                </div>
            </div>
            <div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label  for="address1">{$LANG.clientareaaddress1}</label>
                        <input type="text" name="address1" id="address1" value="{$clientaddress1}"{if in_array('address1',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>

                    <div class="form-group">
                        <label  for="state">{$LANG.clientareastate}</label>
                        <div class="state">
                            <input type="text" name="state" id="state" value="{$clientstate}"{if in_array('state',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                        </div>
                    </div>            



                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label  for="address2">{$LANG.clientareaaddress2}</label>
                        <input type="text" name="address2" id="address2" value="{$clientaddress2}"{if in_array('address2',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>

                    <div class="form-group">
                        <label  for="postcode">{$LANG.clientareapostcode}</label>
                        <input type="text" name="postcode" id="postcode" value="{$clientpostcode}"{if in_array('postcode',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="form-group">
                        <label  for="city">{$LANG.clientareacity}</label>
                        <input type="text" name="city" id="city" value="{$clientcity}"{if in_array('city',$uneditablefields)} disabled="" class="form-control disabled" {else} class="form-control" {/if} />
                    </div>

                    <div class="form-group">
                        <label  for="country">{$LANG.clientareacountry}</label>
                        {$clientcountriesdropdown}
                    </div>

                </div>
            </div>
            <div>
                <div class="col-lg-2">

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label  for="paymentmethod">{$LANG.paymentmethod}</label>
                        <select name="paymentmethod" class="form-control" id="paymentmethod">
                            <option value="none">{$LANG.paymentmethoddefault}</option>
                            {foreach from=$paymentmethods item=method}
                                <option value="{$method.sysname}"{if $method.sysname eq $defaultpaymentmethod} selected="selected"{/if}>{$method.name}</option>
                            {/foreach}
                        </select>
                    </div>

                    {if $emailoptoutenabled}
                        <div class="form-group">
                            <label  for="emailoptout">{$LANG.emailoptout}</label>
                            <input type="checkbox" value="1" name="emailoptout" id="emailoptout" {if $emailoptout} checked{/if} /> {$LANG.emailoptoutdesc}
                        </div>
                    {/if}

                </div>

                <div class="col-lg-4">

                    <div class="form-group">
                        <label  for="billingcontact">{$LANG.defaultbillingcontact}</label>
                        <select name="billingcid" class="form-control" id="billingcontact">
                            <option value="0">{$LANG.usedefaultcontact}</option>
                            {foreach from=$contacts item=contact}
                                <option value="{$contact.id}"{if $contact.id eq $billingcid} selected="selected"{/if}>{$contact.name}</option>
                            {/foreach}
                        </select>
                    </div>


                    {if $customfields}
                        {foreach from=$customfields key=num item=customfield}
                            <div class="form-group">
                                <label  for="customfield{$customfield.id}">{$customfield.name}</label>
                                {$customfield.input} {$customfield.description}
                            </div>
                        {/foreach}
                    {/if}

                </div>

                <div class="col-lg-2">

                </div>
            </div>
            <div class="btn-toolbar" role="toolbar">
                <input class="btn btn-primary-dark btn-sm" type="submit" name="save" value="{$LANG.clientareasavechanges}" />
                <input class="btn btn-primary-outline rst" type="reset" value="{$LANG.cancel}" />
            </div>

        </div>
    </form>
</div>