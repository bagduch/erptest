<div style="float:left;width:100%;">
    <h1></h1>
    <h2>Duplicate Service</h2>

    <form method="post" action="configservices.php?action=duplicatenow">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody>
                <tr>
                    <td width="150" class="fieldlabel">Existing Service</td>
                    <td class="fieldarea">
                        <select name="existingservice">
                            {foreach from=$service item=servicevalue key=gid}
                                <option value="{$gid}">{$servicevalue.gname} - {$servicevalue.prodname}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">New Service Name</td>
                    <td class="fieldarea">
                        <input type="text" name="newservicename" size="50">
                    </td>
                </tr>
            </tbody>
        </table>
        <p align="center"><input type="submit" value="Continue >>" class="button"></p>
    </form>
</div>