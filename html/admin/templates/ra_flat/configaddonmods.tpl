<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header card-header-text">
                <h4 class="title">Form Elements</h4>
                {$infobox}
            </div>
            <div class="content">

                <p>This is where you can activate and manage addon modules in your ra installation. Older legacy modules will still allow you to activate/deactivate and configure access rights, but will not be able to show any configuration options, version or author information.</p>

                <form method="post" action="configaddonmods.php">
                    <input type="hidden" name="action" value="save">
                    <div class="tablebg">
                        <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                            <tbody>
                                <tr>
                                    <th>Module</th><th width="100">Version</th><th width="100">Author</th><th width="350"></th>
                                </tr>

                                {foreach from = $moduletable key=module  item =row}
                                    <tr>
                                        <td style="background-color:#{$row}};text-align:left;">
                                            <a name="act{$row.module}"></a><a name="{$row.module}"></a>
                                            <b>&nbsp;»{$row.name}</b>
                                            <br>{$row.des}</td>
                                        <td style="background-color:#{$row}};text-align:center;">{$row.version}</td>
                                        <td style="background-color:#{$row}};text-align:center;">{$row.author}</td>
                                        <td style="background-color:#{$row}};text-align:center;">
                                            {if $row.active}
                                                <input type="button" value="Activate" onclick="window.location = 'configaddonmods.php?action=activate&amp;module={$row.module}&amp;{$token}'" class ="btn btn-success"> 
                                            {else}
                                                <input type="button" value="Activate" disabled="disabled" class="btn disabled" /> 
                                            {/if}
                                            {if $row.deactive}
                                                <input type="button" value="Deactivate" onclick="deactivateMod('{$row.module}');
                                                        return false" class="btn btn-danger" />
                                            {else}
                                                <input type="button" value="Deactivate" disabled="disabled" class="btn disabled"> 
                                            {/if}
                                            <input type="button" value="Configure" class="btn {$row.showconfig}" onclick="showConfig('{$row.module}')"></td>
                                    </tr>
                                    <tr id="{$module}config" colspan="4" style="display:none;padding:15px;">
                                        <td>
                                            <table class="table borderless">
                                                {foreach from=$configvalues[$row.module] item=cvalue}
                                                    <tr>
                                                        <td class="fieldlabel">{$cvalue.FriendlyName}</td>
                                                        <td class="fieldarea">{$cvalue.fieldvalue}</td>
                                                    </tr>
                                                {/foreach}
                                                <tr>
                                                    <td width="20%" class="fieldlabel">Access Control</td>
                                                    <td class="fieldarea">Choose the admin role groups to permit access to this module<br />
                                                        {foreach from=$adminrolelist[$module] item=roles}
                                                            <label><input type="checkbox" name="access[{$row.module}][{$roles.id}]" value="1" {$roles.check} />{$roles.name}</label> 
                                                        {/foreach}
                                                    </td>
                                                </tr>
                                            </table>
                                            <div align="center"><input type="submit" name="msave_hdtolls" value="Save Changes" class="btn btn-default"></div>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            {literal}
                <script language="javascript">
                    $(document).ready(function () {
                        var modpass = window.location.hash;
                        if (modpass)
                            $(modpass + "config").show();


                    });
                </script>
            {/literal}
        </div>
    </div>
</div>
