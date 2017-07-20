<section class="content">

    {literal}
        <script type="text/javascript">
            function zCheckAll(oForm) {
                var oElems = oForm.elements;
                for (var i = 0; oElems.length > i; i++) {
                    if (oElems[i].type == "checkbox")
                        oElems[i].checked = true;
                }
            }
            function zUncheckAll(oForm) {
                var oElems = oForm.elements;
                for (var i = 0; oElems.length > i; i++) {
                    if (oElems[i].type == "checkbox")
                        oElems[i].checked = false;
                }
            }
        </script>
    {/literal}

    <form method="post" action="{$PHP_SELF}?action=save&amp;id={$id}" name="frmperms">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody>
                <tr>
                    <td width="15%" class="fieldlabel">Name</td><td class="fieldarea">
                        <input class="form-control" type="text" name="name" size="40" value="{$name}"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Permissions</td><td class="fieldarea">
                        {$permissionsfieldhtml}   
                    </td></tr>
                <tr><td class="fieldlabel">Widgets</td><td class="fieldarea">

                        {$widgethtml}

                    </td></tr>
                <tr>
                    <td class="fieldlabel">Report</td>
                    <td class='fieldarea'>
                        {$reporthtml}
                    </td>
                </tr>
                <tr><td class="fieldlabel">Email Messages</td>
                    <td class="fieldarea"><input type="checkbox" name="systememails" value="1" {if $systememails}checked{/if}> System Emails (eg. Cron Notifications, Invalid Login Attempts, etc...)<br>
                        <input type="checkbox" name="accountemails" value="1"  {if $accountemails}checked{/if}> Account Emails (eg. Order Confirmations, Details Changes, Automatic Setup Notifications, etc...)<br>
                        <input type="checkbox" name="supportemails" value="1"  {if $supportemails}checked{/if}> Support Emails (eg. New Ticket &amp; Ticket Reply Notifications)</td></tr>
                <tr>
                    <td></td>
                    <td>  <div align="right"><a href="#" onclick="zCheckAll(frmperms);
                            return false">Check All</a> | <a href="#" onclick="zUncheckAll(frmperms);
                                    return false">Uncheck All</a></div></td>
                </tr>
            </tbody></table>

        <p align="center"><input type="submit" value="Save Changes" class="button"></p>
    </form>











</section>