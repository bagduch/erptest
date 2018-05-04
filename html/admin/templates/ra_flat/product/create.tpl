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
                <form method="post" action="configproducts.php?action=add" name="packagefrm">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="150" class="fieldlabel">Product Type</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="type">
                                        <option value="addon" {if $data.type eq "addon"}Selected{/if}>Addon</option>
                                        <option  value="individual" {if $data.type eq "individual"}Selected{/if}>Individual</option>
                                        {*          <option value="wholesell" {if $data.type eq 'wholesell'}selected{/if}>Whole Sell</option>
                                        <option value="product" {if $data.type eq 'server'}selected{/if}>Product</option>
                                        <option value="addon" {if $data.type eq 'addon'}selected{/if}>Addon</option>
                                        <option value="other" {if $data.type eq 'other'}selected{/if}>{$langs.other}</option>*}
                                    </select>
                                    <input type="hidden" name="gid" value="{$groupsid}">
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
