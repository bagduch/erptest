{literal}
    <script type="text/javascript">
        $(function () {
            $("#replyform{/literal}{$smartyvalues.ticketid}{$adminid}{literal}").sisyphus();
        });
    </script>
{/literal}
<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <div class="form-inline">
                # - {$smartyvalues.tid} {$smartyvalues.subject} 
                <select class="form-control" name="ticketstatus" id="ticketstatus">
                    {$statuseshtml}
                </select>
                <a href="#" onclick="$('#ticketstatus').val('Closed');
                        $('#ticketstatus').trigger('change');
                        return false"></a> 
                <span class="ticketheader">Assigned To</span>

                <select class="form-control" id="flagto" name="flagto">
                    <option value="0">None</option>
                    {foreach from=$smartyvalues.staff item=data}
                        <option value="{$data.id}"  {if $smartyvalues.flag eq $data.id}SELECTED{/if}>{$data.name}</option>
                    {/foreach}
                </select>
            </div>
        </h3>
        <div class="ticketlastreply">Last Reply: {$smartyvalues.lastreply} </div>
    </div>
    <div class="box-body">
        <div class="col-md-8">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#reply" aria-controls="reply" role="tab" data-toggle="tab">Add Reply</a></li>
                    <li role="presentation"><a href="#tag" aria-controls="tag" role="tab" data-toggle="tab">Add Tag</a></li>
                    <li role="presentation"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">Add Note</a></li>
                    <li role="presentation"><a href="#otickets" aria-controls="otickets" role="tab" data-toggle="tab">Other Tickets</a></li>
                    <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">Log</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="reply">
                        <form id="replyform{$smartyvalues.ticketid}{$adminid}" method="post" action="{$PHP_SELF}?action=viewticket&amp;id={$smartyvalues.ticketid}" enctype="multipart/form-data" name="replyfrm" id="replyfrm">
                            <textarea class="form-control" name="message" id="replymessage" rows="14" style="width:100%;margin:0 0 10px 0;"></textarea>
                            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                                <tbody><tr>
                                        <td width="15%" class="fieldlabel">Tools</td>
                                        <td class="fieldarea"><select name="postaction">
                                                <option value="answered">Set to Answered &amp; Remain in Ticket View</option>
                                                <option value="return">Set to Answered &amp; Return to Ticket List</option>
                                                <option value="close">Close &amp; Return to Ticket List</option>
                                            </select>
                                            <input type="submit" value="Add Response »" name="postreply" class="btn-primary" id="postreplybutton">
                                            <div style="float:right;">
                                                <input type="button" value="Insert Predefined Reply" class="btn" id="insertpredef">
                                            </div>
                                            <div id="prerepliescontainer">
                                                <div class="box">
                                                    <div style="float:right;">
                                                        <input type="text" id="predefq" size="25" value="Search" onfocus="this.value = (this.value == 'Search') ? '' : this.value;" onblur="this.value = (this.value == '') ? 'Search' : this.value;">
                                                    </div>
                                                    <div id="prerepliescontent"></div>
                                                </div>
                                            </div></td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Attachments</td>
                                        <td class="fieldarea"><input type="file" name="attachments[]" size="85">
                                            <a href="#" id="addfileupload"><img src="images/icons/add.png" align="absmiddle" border="0"> Add More</a><br>
                                            <div id="fileuploads"></div></td>
                                    </tr>
                                </tbody></table>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tag">
                        <div class="tag-list">
                            {if $smartyvalues.tags}
                                {foreach from=$smartyvalues.tags key=id item=tag}
                                    <div class="btn-group">
                                        <button disabled class="btn btn-info">{$tag}</button>
                                        <button onclick="deletetag({$id})" class="btn btn-info"><i class="fa fa-fw fa-close"></i></button>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                        <form method="post" action="{$PHP_SELF}?action=viewticket&amp;id={$smartyvalues.ticketid}">
                            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                                <tr>
                                    <td class="fieldlabel">Tag</td>
                                    <td><input class="form-control" type="text" name="tag" value=""></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" value="Add Tag »"class="btn-primary"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="note">
                        <form method="post" action="{$PHP_SELF}?action=viewticket&amp;id={$smartyvalues.ticketid}">
                            <input type="hidden" name="postaction" value="note">
                            <textarea name="message" id="replymessage" rows="14" style="width:100%"></textarea>
                            <br>
                            <img src="images/spacer.gif" height="8" width="1"> <br>
                            <div align="center">
                                <input type="submit" value="Add Note" class="button" name="postreply">
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="otickets">
                        <img src="images/loading.gif" align="top"> Loading...
                    </div>
                    <div role="tabpanel" class="tab-pane" id="clog"> 
                        <img src="images/loading.gif" align="top"> Loading...
                    </div>
                    <div role="tabpanel" class="tab-pane" id="log">
                        <img src="images/loading.gif" align="top"> Loading... 
                    </div>  
                </div>
            </div>
            <input type="hidden" name="id" value="">
            <input type="hidden" name="action" value="split">

            <p align="center"><a href="{$PHP_SELF}?id=" target="_blank">View Printable Version</a></p>
            <input type="hidden" name="splitdeptid" id="splitdeptid">
            <input type="hidden" name="splitsubject" id="splitsubject">
            <input type="hidden" name="splitpriority" id="splitpriority">
            <input type="hidden" name="splitnotifyclient" id="splitnotifyclient">
            <div id="ticketreplies">
                {foreach from=$smartyvalues.replies item=row}
                    <div class="{if $row.admin != NULL}staff{/if}reply">
                        <div class="leftcol">
                            <div class="submitter">
                                <div class="name">{$row.admin}{$row.clientname}</div>
                                <div class="title">{if $row.admin != NULL}client{else}staff{/if}</div>
                            </div>
                            <div class="tools">
                                <div class="editbtnsr{$row.id}">
                                    <input type="button" value="Edit" onclick="editTicket('r{$row.id}')" class="btn btn-xs btn-small btn-default">
                                    <input type="button" value="Delete" onclick="doDeleteReply('{$row.id}')" class="btn btn-xs btn-small btn-danger"></div>
                                <div class="editbtnsr{$row.id}" style="display:none">
                                    <input type="button" value="Save" onclick="editTicketSave('r{$row.id}')" class="btn btn-xs btn-small btn-success">
                                    <input type="button" value="Cancel" onclick="editTicketCancel('r{$row.id}')" class="btn btn-xs btn-small btn-default">
                                </div>

                            </div>
                        </div>
                        <div class="rightcol">
                            <div class="quoteicon">
                                <a href="#" onclick="quoteTicket('', '{$row.id}')"><i class="fa fa-comment-o" aria-hidden="true"></i></a> 
                                <input type="checkbox" name="rids[]" value="{$row.id}">
                            </div>
                            <div class="postedon">Posted on {$row.friendlydate} {$row.friendlytime }</div>
                            <div class="msgwrap" id="contentr{$row.id}">
                                <div class="message">
                                    {$row.message|strip_tags}
                                </div>
                                <div class="ticketattachmentcontainer">
                                    {foreach from=$row.attachments item=item}
                                        <a href="{$item.dllink}">{$item.filename}</a>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                {/foreach} 
            </div>
        </div>
        <div class="col-md-4">
            <form class="right-form" method="post" action="{$PHP_SELF}?action=viewticket&amp;id={$smartyvalues.ticketid}">

                <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td width="15%" class="fieldlabel">Department</td>
                            <td class="fieldarea">
                                <select class="form-control" name="deptid">
                                    {$departmentshtml}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Subject</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="subject" value="{$smartyvalues.subject}" style="width:80%"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Status</td>
                            <td class="fieldarea"><select class="form-control" name="status">
                                    {$statuseshtml}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">CC Recipients</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="cc" value="{$smartyvalues.cc}" size="40">
                                (Comma Separated)</td>
                        </tr>

                        <tr>
                            <td class="fieldlabel">Flag</td>
                            <td class="fieldarea">
                                <select class="form-control" id="flagto" name="flagto">
                                    <option value="0">None</option>
                                    {foreach from=$smartyvalues.staff item=data}
                                        <option {if $smartyvalues.flag eq $data.id}SELECTED{/if} value="{$data.id}">{$data.name}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Priority</td>
                            <td class="fieldarea">
                                <select class="form-control" name="priority">
                                    <option {if $smartyvalues.priority eq 'High'}SELECTED{/if} value="High">High</option>
                                    <option {if $smartyvalues.priority eq 'Medium'}SELECTED{/if} value="Medium">Medium</option>
                                    <option {if $smartyvalues.priority eq 'Low'}SELECTED{/if} value="Low">Low</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Merge Ticket</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="mergetid" size="10">
                                (# to combine)</td>
                        </tr>
                    </tbody>
                </table>

                <div class="clearfix"></div>
                <div align="center">
                    <input type="submit" value="Save Changes" class="button">
                </div>
                <div id="notes">
                    <h3>Notes:</h3>
                    {if $smartyvalues.notes}
                        {foreach from=$smartyvalues.notes item=note}
                            <div class="staffreply" id="not{$note.id}">
                                <div class="leftcol">
                                    <div class="submitter">
                                        <div class="name">{$note.admin}</div>
                                        <br>
                                    </div>
                                    {if $note.adminid eq $adminid}
                                        <div class="tools">
                                            <div class="editbtnotes{$note.id}">
                                                <input type="button" value="Edit" onclick="editNotes({$note.id})" class="btn btn-xs btn-small btn-default">
                                                <input type="button" value="Delete" onclick="deleteNotes({$note.id})" class="btn btn-xs btn-small btn-danger"></div>
                                            <div class="editbtnotess{$note.id}" style="display:none">
                                                <input type="button" value="Save" onclick="saveNotes({$note.id})" class="btn btn-xs btn-small btn-success">
                                                <input type="button" value="Cancel" onclick="cancelNotes({$note.id})" class="btn btn-xs btn-small btn-default">
                                            </div>

                                        </div>
                                    {/if}
                                </div>
                                <div class="rightcol">

                                    <div class="postedon">Posted on {$note.date}</div>
                                    <div class="msgwrap" id="notes{$note.id}">
                                        <div class="message">{$note.message}</div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        {/foreach}
                    {/if}
                </div>
            </form>

        </div>
    </div>
    {literal}
        <script type="text/javascript">
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // newly activated tab getticketlog
                if (e.target.attributes[1].value == "clog")
                {
                    $.get("", {action: "getclientlog"}).done(function (data) {
                        $("#clog").html(data);
                    });
                }

                if (e.target.attributes[1].value == "otickets")
                {
                    $.get("", {action: "gettickets"}).done(function (data) {
                        $("#otickets").html(data);
                    });

                }
                if (e.target.attributes[1].value == "log")
                {
                    $.get("", {action: "getticketlog"}).done(function (data) {
                        $("#log").html(data);
                    });
                }
                if (e.target.attributes[1].value == "note")
                {
                    $.get("", {action: "getmsg"}).done(function (data) {
                        $("#note").append(data);
                    });
                }
            });

            function insertKBLink(url) {
                $("#replymessage").addToReply(url);
            }
            function showDialog(name) {
                $("#" + name).dialog('open');
            }
        </script>
    {/literal}
</div>
