{include file="$template/pageheader.tpl" title=$LANG.clientareanavsecurity}

{include file="$template/clientareadetailslinks.tpl"}

{if $successful}
<div class="alert alert-success">
    <p>{$LANG.changessavedsuccessfully}</p>
</div>
{/if}

{if $errormessage}
<div class="alert alert-danger">
    <p>{$LANG.clientareaerrors}</p>
    <ul>
        {$errormessage}
    </ul>
</div>
{/if}

{if $twofaavailable}

{if $twofaactivation}

<script>{literal}
    function dialogSubmit() {
        $('div#twofaactivation form').attr('method', 'post');
        $('div#twofaactivation form').attr('action', 'clientarea.php');
        $('div#twofaactivation form').attr('onsubmit', '');
        $('div#twofaactivation form').submit();
        return true;
    }
    {/literal}</script>

    <div id="twofaactivation">
        {$twofaactivation}
    </div>

    <script type="text/javascript">
        $("#twofaactivation input:text:visible:first,#twofaactivation input:password:visible:first").focus();
    </script>

    {else}

    <h2>{$LANG.twofactorauth}</h2>

    <p>{$LANG.twofaactivationintro}</p>

    <form method="post" action="clientarea.php?action=security">
        <input type="hidden" name="2fasetup" value="1" />
        <p>
            {if $twofastatus}
            <input type="submit" value="{$LANG.twofadisableclickhere}" class="btn btn-danger" />
            {else}
            <input type="submit" value="{$LANG.twofaenableclickhere}" class="btn btn-success" />
            {/if}
        </p>
    </form>

    {/if}

    {/if}

