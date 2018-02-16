<div class="card">
    <div class="content">

        <div class="box-header with-border">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_overview" data-toggle="tab">Overview</a></li>
                    <li><a href="#tab_order" data-toggle="tab">Order</a></li>
                    <li><a href="#tab_account" data-toggle="tab">Product/Serivce</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_overview">
                        <table class="datatable table">
                            <tr>
                                <th>Create Date</th>
                                <th>Notes</th>
                                <th>Create Admin</th>
                                <th>Assign To</th>
                                <th>Due Date</th>
                                <th>Update Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            {foreach from=$tabledata item=row}
                                <tr class="itemrow_{$row[10]}">
                                    <td>{$row[1]}</td>
                                    <td>{$row[2]}</td>
                                    <td>{$row[3]}</td>
                                    <td>{$row[4]}</td>
                                    <td>{$row[5]}</td>
                                    <td>{$row[6]}</td>
                                    <td>{$row[7]}</td>
                                    <td>{$row[8]}{$row[9]}</td>
                                </tr>
                            {/foreach}
                        </table>
                    </div>
                    <div class="tab-pane " id="tab_order">
                        <table class="datatable table">
                            <tr>
                                <th>Create Date</th>
                                <th>Notes</th>
                                <th>Create Admin</th>
                                <th>Assign To</th>
                                <th>Due Date</th>
                                <th>Update Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            {foreach from=$tabledata item=row}
                                {if $row[0] eq 'order'}
                                    <tr class="itemrow_{$row[10]}">
                                        <td>{$row[1]}</td>
                                        <td>{$row[2]}</td>
                                        <td>{$row[3]}</td>
                                        <td>{$row[4]}</td>
                                        <td>{$row[5]}</td>
                                        <td>{$row[6]}</td>
                                        <td>{$row[7]}</td>
                                        <td>{$row[8]}{$row[9]}</td>
                                    </tr>
                                {/if}
                            {/foreach}
                        </table>
                    </div>
                    <div class="tab-pane " id="tab_account">
                        <table class="datatable table">
                            <tr>
                                <th>Create Date</th>
                                <th>Notes</th>
                                <th>Create Admin</th>
                                <th>Assign To</th>
                                <th>Due Date</th>
                                <th>Update Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            {foreach from=$tabledata item=row}
                                {if $row[0] eq 'account'}
                                    <tr class="itemrow_{$row[10]}">
                                        <td>{$row[1]}</td>
                                        <td>{$row[2]}</td>
                                        <td>{$row[3]}</td>
                                        <td>{$row[4]}</td>
                                        <td>{$row[5]}</td>
                                        <td>{$row[6]}</td>
                                        <td>{$row[7]}</td>
                                        <td>{$row[8]}{$row[9]}</td>
                                    </tr>
                                {/if}
                            {/foreach}
                        </table>
                    </div>

                </div>
            </div>
            {if $id}
                <form method="post" action="{$PHP_SELF}?userid={$userid}amp;sub=save&amp;id={$id}&quot;">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr><td class="fieldarea"><textarea class="form-control" name="note" rows="6">{$notesdata.note}</textarea></td></tr>
                            <tr><td><input name="duetime" class="datepick form-control" type="text" value="{$notesdata.duedate}"></td></tr>
                            <tr>
                                <td>
                                    {$select}
                                </td>
                            </tr>
                            <tr><td width="60"><label>Task Done:<input type="checkbox" class="checkbox" name="sticky" value="{$notesdata.sticky}" {if $notesdata.sticky}checked{/if}></label></td></tr>
                            <tr><td><input type="submit" value="Save" changes="" class="button"></td></tr>
                        </tbody>
                    </table>
                </form>
            {/if}
        </div>
    </div>
</div>
{literal}
    <script type="text/javascript">
        $(".editnotes").click(function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            if ($(this).hasClass('edit'))
            {
                $(".edit" + $tr.attr('class')).remove();
                $(this).removeClass("edit");
            } else {
                id = $tr.attr('class').split("_");

                createDate = $tr.children('td:first').text();
                notes = $tr.children('td:nth-child(2)').text();
                assign = $tr.children('td:nth-child(4)').text();
                due = $tr.children('td:nth-child(5)').text();
                assignlist = {/literal}{$adminlist|@json_encode}{literal};
                assignselect = "<select name='assignto' class='form-control'>";
                $.each(assignlist, function (index, elemet) {
                    if (elemet == assign)
                    {
                        select = "SELECTED";
                    } else {
                        select = "";
                    }
                    assignselect += "<option value='" + index + "' " + select + ">" + elemet + "</option>";
                });
                assignselect += "</select>";
                duedate = "<input type='text' class='datepick form-control' name='duedate' value='" + due + "'>";
                $("<tr class='edit" + $tr.attr('class') + "'><td><input type='hidden' value='" + id[1] + "' name='id'></td><td><textarea class='form-control' name='notes'>" + notes + "</textarea></td><td></td><td>" + assignselect + "</td><td>" + duedate + "</td><td></td><td></td><td><button class='updatedetail'>Update</button></td></tr>").insertAfter($tr);
                $(this).addClass("edit");
                $('.datepick').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                });
                $(".updatedetail").click(function () {
                    id = $(this).closest('tr').find('input[name="id"]').val();
                    noteschange = $(this).closest('tr').find('textarea').val();
                    assignchange = $(this).closest('tr').find('select').val();
                    duechange = $(this).closest('tr').find('input[name="duedate"]').val();
                    $.ajax({
                        url: "/admin/clientsnotes.php",
                        method: "post",
                        data: {
                            "update": 1,
                            "notes": noteschange,
                            "assign": assignchange,
                            "duedate": duechange,
                            "id": id
                        }}).done(function () {
                        location.reload();
                    });
                });
            }
        });

    </script>
{/literal}