<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">

                <script type="text/javascript" src="../includes/jscript/jquerylq.js"></script>
                <script type="text/javascript" src="../includes/jscript/jqueryFileTree.js"></script>
                <link href="../includes/jscript/css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen">

                <div class="box-title">
                    <h2>Create Product</h2>
                </div>
                <form method="post" action="/admin/configservices.php?action=add" name="packagefrm">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="150" class="fieldlabel">Product Type</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="type" onchange="doFieldUpdate()">
                                        <option value="hostingaccount" {if $data.type eq 'hostingaccount'}selected{/if}>{$langs.hostingaccount}</option>
                                        <option value="reselleraccount" {if $data.type eq 'reselleraccount'}selected{/if}>{$langs.reselleraccount}</option>
                                        <option value="server" {if $data.type eq 'server'}selected{/if}>{$langs.server}</option>
                                        <option value="other" {if $data.type eq 'other'}selected{/if}>{$langs.other}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Service Group</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="gid">
                                        {foreach from=$servicegroups item=row  key=gid}
                                            <option value="{$gid}" {if $gid==$data.gid}selected{/if}>{$row}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Product Name</td>
                                <td class="fieldarea"><input class="form-control" type="text" name="productname" size="50"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="btn-container">
                        <input type="submit" value="Continue »" class="btn btn-primary" id="btnContinue">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>