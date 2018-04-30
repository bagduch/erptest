<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">

                <script type="text/javascript" src="../includes/jscript/jquerylq.js"></script>
                <script type="text/javascript" src="../includes/jscript/jqueryFileTree.js"></script>
                <link href="../includes/jscript/css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen">

                <div class="box-title">
                    <h2>Create Service</h2>
                </div>
                <form method="post" action="configservices.php?action=add" name="packagefrm">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="150" class="fieldlabel">Product Type</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="type">
                                        <option value="residential" {if $data.type eq 'residential'}selected{/if}>Residential</option>
                                        <option value="business" {if $data.type eq 'business'}selected{/if}>Business</option>
                                    </select>


                                </td>
                            </tr>
                            <tr>
                                <td width="150" class="fieldlabel">Product Group</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="gid" >
                                        {foreach from=$groups item=item}
                                            <option value="{$item.id}">{$item.name}</option>
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
