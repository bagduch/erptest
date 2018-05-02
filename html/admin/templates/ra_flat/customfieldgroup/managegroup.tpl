
{literal}
    <script type="text/javascript">
        var datepickerformat = "dd/mm/yy";
        function manageconfigoptions(id) {
            window.open('configcustomfieldsgroup.php?manageoptions=true&cid=' + id, 'configoptions', 'width=900,height=500,scrollbars=yes');
        }
        function addconfigoption() {
            window.open('configcustomfieldsgroup.php?manageoptions=true&gid=', 'configoptions', 'width=800,height=500,scrollbars=yes');
        }
        function doDelete(id, opid) {
            if (confirm("Are you sure you want to delete this configurable option?")) {
                window.location = 'configcustomfieldsgroup.php?action=deleteoption&id=' + id + '&opid=' + opid + '&token=1ba6aebbf4014e5e7b53c3611ecb1c9d209df956';
            }
        }
    </script>
{/literal}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header card-header">
                <h4 class="title">Create a New Custom Field Group</h4>
            </div>
            <div class="content">
                <form id="managefrm" method="post" action="configcustomfieldsgroup.php?action=save&amp;id={$id}" name="managefrm">

                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">Name</td>
                                <td class="fieldarea">
                                    <input class="form-control" type="text" name="name" size="40" value="{$name}">
                                </td>
                            </tr>

                            <tr>
                                <td class="fieldlabel">Assigned Products</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="productlinks[]" size="8" style="width:90%" multiple>
                                        {foreach from=$productlinks key=productid item=row}
                                            <option value="{$row.data.id}" {$row.check}>{$row.data.name}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {foreach key=num item=data from=$datas}
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td width="100" class="fieldlabel">Field Name</td>
                                    <td class="fieldarea">
                                        <table width="98%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td><input class="form-control" type="text" name="fieldname[{$num}]" size="30" value="{$data.fieldname}"></td>
                                                    <td align="right">Display Order <input type="text" name="sortorder[{$num}]" size="5" value="{$data.sortorder}"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Field Type</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="fieldtype[{$num}]">
                                            <option {if $data.fieldtype eq 'text'}selected{/if} value="text">Text Box</option>
                                            <option {if $data.fieldtype eq 'link'}selected{/if} value="link">Link/URL</option>
                                            <option {if $data.fieldtype eq 'date'}selected{/if} value="date">Date</option>
                                            <option {if $data.fieldtype eq 'more'}selected{/if} value="more">Click More</option>
                                            <option {if $data.fieldtype eq 'password'}selected{/if} value="password">Password</option>
                                            <option {if $data.fieldtype eq 'dropdown'}selected{/if} value="dropdown">Drop Down</option>
                                            <option {if $data.fieldtype eq 'tickbox'}selected{/if} value="tickbox">Tick Box</option>
                                            <option {if $data.fieldtype eq 'textarea'}selected{/if} value="textarea">Text Area</option>
                                        </select>
                                        {if $data.children }
                                            {foreach from=$data.children item=childrendata}
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkfield{$childrendata.cfid}">{$childrendata.fieldname}</button>
                                                    <button class="btn btn-primary"><span class="fa fa-times"></span></button>
                                                    <div id="linkfield{$childrendata.cfid}" class="modal fade" tabindex="-2" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Link Field</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table">
                                                                        <tr>
                                                                            <td class="fieldlabel">
                                                                                Field Name
                                                                            </td>
                                                                            <td class="fieldarea">
                                                                                <input type="text" class="form-control" value="{$childrendata.fieldname}" name="updatelinkfieldname[{$childrendata.cfid}]">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fieldlabel">
                                                                                Field Type
                                                                            </td>
                                                                            <td class="fieldarea">
                                                                                <select class="form-control" name="updatelinkfieldtype[{$childrendata.cfid}]">
                                                                                    <option {if $childrendata.fieldtype eq "text"}selected{/if} value="text">Text Box</option>
                                                                                    <option {if $childrendata.fieldtype eq "link"}selected{/if} value="link">Link/URL</option>
                                                                                    <option {if $childrendata.fieldtype eq "password"}selected{/if} value="password">Password</option>
                                                                                    <option {if $childrendata.fieldtype eq "dropdown"}selected{/if} value="dropdown">Drop Down</option>
                                                                                    <option {if $childrendata.fieldtype eq "date"}selected{/if} value="date">Date</option>
                                                                                    <option {if $childrendata.fieldtype eq "more"}selected{/if} value="more">Click More</option>
                                                                                    <option {if $childrendata.fieldtype eq "tickbox"}selected{/if} value="tickbox">Tick Box</option>
                                                                                    <option {if $childrendata.fieldtype eq "textarea"}selected{/if} value="textarea">Text Area</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                    <input type="checkbox" {if $childrendata.required eq 1}checked{/if} name="updatelinkrequired[{$childrendata.cfid}]"> Required Field

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <input type="submit" value="Save Changes" class="button btn-info updatebutton">
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->

                                                </div>
                                            {/foreach}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Description</td>
                                    <td class="fieldarea">
                                        <input class="form-control" type="text" name="description[{$num}]" size="60" value='{$data.description}'> The explanation to show users
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Validation</td>
                                    <td class="fieldarea">
                                        <input class="form-control" type="text" name="regexpr[{$num}]" size="60" value='{$data.regexpr}'> Regular Expression Validation String
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Select Options</td>
                                    <td class="fieldarea">
                                        <input class="form-control" type="text" name="fieldoptions[{$num}]" size="60" value='{$data.fieldoptions}'> For Dropdowns Only - Comma Seperated List</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"></td>
                                    <td class="fieldarea">
                                        <input type="checkbox" name="adminonly[{$num}]" {if $data.adminonly}checked{/if}> Admin Only
                                        <input type="checkbox" name="required[{$num}]" {if $data.required}checked{/if}> Required Field
                                        <input type="checkbox" name="showorder[{$num}]" {if $data.showorder}checked{/if}> Show on Order Form
                                        <input type="checkbox" name="showinvoice[{$num}]" {if $data.showinvoice}checked{/if}> Show on Invoice
                                        <div class="btn btn-danger" data-toggle="modal" data-target="#myModal" class="deletefield">Delete</div>
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Are you sure you want delete this custom field</h4>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" value="{$num}" name="deletefieldid">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary deletethatfield">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    {/foreach}

                    <div class="head card-header">
                        <h4 class="title">Add New Custom Field</h4>
                    </div>
                    <table class="table borderless" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody><tr><td width="100" class="fieldlabel">Field Name</td><td class="fieldarea"><table class="borderless" width="98%" cellspacing="0" cellpadding="0"><tbody><tr><td><input class="form-control" type="text" name="addfieldname" size="30"></td><td align="right">Display Order <input type="text" name="addsortorder" size="5" value="0"></td></tr></tbody></table></td></tr>
                            <tr>
                                <td class="fieldlabel">Field Type</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="addfieldtype">
                                        <option value="text">Text Box</option>
                                        <option value="link">Link/URL</option>
                                        <option value="password">Password</option>
                                        <option value="dropdown">Drop Down</option>
                                        <option value="date">Date</option>
                                        <option value="more">Click More</option>
                                        <option value="tickbox">Tick Box</option>
                                        <option value="textarea">Text Area</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td class="fieldlabel">Description</td><td class="fieldarea"><input class="form-control" type="text" name="adddescription" size="60"> The explanation to show users</td></tr>
                            <tr><td class="fieldlabel">Validation</td><td class="fieldarea"><input class="form-control" type="text" name="addregexpr" size="60"> Regular Expression Validation String</td></tr>
                            <tr><td class="fieldlabel">Select Options</td><td class="fieldarea"><input class="form-control" type="text" name="addfieldoptions" size="60"> For Dropdowns Only - Comma Seperated List</td></tr>
                            <tr><td class="fieldlabel"></td><td class="fieldarea"><input  type="checkbox" name="addadminonly"> Admin Only <input type="checkbox" name="addrequired"> Required Field <input type="checkbox" name="addshoworder"> Show on Order Form <input type="checkbox" name="addshowinvoice"> Show on Invoice</td></tr>
                        </tbody>
                    </table>
                    <div id="linkfield" class="modal fade" tabindex="-2" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Link Field</h4>
                                </div>
                                <div class="modal-body">
                                    <table>
                                        <tr>
                                            <td class="fieldlabel">
                                                Field Name
                                            </td>
                                            <td class="fieldarea">
                                                <input type="text" class="form-control" id="linkfieldname" name="linkfield">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fieldlabel">
                                                Field Type
                                            </td>
                                            <td class="fieldarea">
                                                <select class="form-control" id="linkfieldtype" name="linkfieldtype">
                                                    <option value="text">Text Box</option>
                                                    <option value="link">Link/URL</option>
                                                    <option value="password">Password</option>
                                                    <option value="dropdown">Drop Down</option>
                                                    <option value="date">Date</option>
                                                    <option value="more">Click More</option>
                                                    <option value="tickbox">Tick Box</option>
                                                    <option value="textarea">Text Area</option>
                                                </select>
                                            </td>
                                        </tr>

                                    </table>
                                    <input type="checkbox" id="linkrequired" name="linkrequired"> Required Field

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" value="Save Changes" class="button btn-info submitbutton">
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <p style="clear:both" align="center"><input type="submit" value="Save Changes" class="btn btn-success">
                        <input type="button" value="Back to Groups List" onclick="window.location = 'configproductoptions.php'" class="btn btn-danger">
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
{literal}
    <script type="text/javascript">

        $(document).ready(function () {



            $("select").change(function () {
                if ($(this).val() == "more")
                {
                    $(this).parent().append("<div class='btn btn-default addlinkbutton'>Add A More Field</div>");
                } else {
                    $(this).parent().find("#addlinkbutton").remove();
                }
            });
            i = 0;
            $(".submitbutton").click(function (e) {
                e.preventDefault();
                $mastercontainer = $(this).parent().parent();
                $('#linkfield').modal('hide');
                name = $mastercontainer.find("input[name='linkfield']").val();
                type = $mastercontainer.find("select[name='linkfieldtype']").val();
                required = $mastercontainer.find("input[name='linkrequired']").val();
                $(".linkactive").parent().append('<div class="btn-group">\n\
      <button class="btn btn-primary">' + name + '</button>\n\
        <input type="hidden" name="addlinkfieldname[' + i + ']" value="' + name + '">\n\
        <input type="hidden" name="addlinkfieldtype[' + i + ']" value="' + type + '">\n\
          <input type="hidden" name="addlinkrequired[' + i + ']" value="' + required + '">\n\
      <button class="btn btn-primary"><span class="fa fa-times"></span></button>\n\
            </div>');
                i++;
            });

            $(document).on('click', ".addlinkbutton", function () {
                $(this).addClass('linkactive');
                $('#linkfield').modal('show');
            });


            fieldname = "";
            typename = "";
            required = "";

            $('#linkfield').on("show.bs.modal", function (event) {

                //var button = $(event.relatedTarget).attr('class');
                //console.log(button);


            });


            $(".deletethatfield").click(function () {
                id = $(this).parent().find("input").val();
                $(this).parent().append("<input type=hidden value='" + id + "' name='deletefield'>");
                $("#managefrm").submit();
            });
        });
    </script>
{/literal}
