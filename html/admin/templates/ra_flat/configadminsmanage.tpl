<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header card-header">
                <h4 class="title">Add New Admin</h4> 
            </div>
            <div class="content">
                {$infobox}
                <div class="container-fluid">
                    <form method="post" action="configadmins.php?action=save&amp;id={$id}">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td width="20%" class="fieldlabel">Administrator Role</td><td class="fieldarea"><select class="form-control" name="roleid">
                                            {$roleoption}
                                        </select></td></tr>
                                <tr><td class="fieldlabel">First Name</td><td class="fieldarea"><input class="form-control" type="text" name="firstname" size="30" value="{$data.firstname}"></td></tr>
                                <tr><td class="fieldlabel">Last Name</td><td class="fieldarea"><input class="form-control" type="text" name="lastname" size="30" value="{$data.lastname}"></td></tr>
                                <tr><td class="fieldlabel">Email Address</td><td class="fieldarea"><input class="form-control"  type="text" name="email" size="50" value="{$data.email }"></td></tr>
                                <tr><td class="fieldlabel">Username</td><td class="fieldarea"><input class="form-control" type="text" name="username" size="25" value="{$data.username }"></td></tr>
                                <tr><td class="fieldlabel">Password</td><td class="fieldarea"><input class="form-control" type="password" name="password" size="20"></td></tr>
                                <tr><td class="fieldlabel">Confirm Password</td><td class="fieldarea"><input class="form-control" type="password" name="password2" size="20"></td></tr>
                                <tr><td class="fieldlabel">Assigned Departments</td><td class="fieldarea"><label><input type="checkbox" name="deptids[]" value="1"> Provisioning</label> <label><input type="checkbox" name="ticketnotify[]" value="1"> Enable Ticket Notifications</label><br></td></tr>
                                <tr><td class="fieldlabel">Support Ticket Signature</td><td class="fieldarea"><textarea class="form-control" name="signature" cols="80" rows="4">{$data.signature }</textarea></td></tr>
                                <tr><td class="fieldlabel">Private Notes</td><td class="fieldarea"><textarea class="form-control"  name="notes" cols="80" rows="4">{$data.notes}</textarea></td></tr>
                                <tr><td class="fieldlabel">Template</td><td class="fieldarea"><select class="form-control" name="template">
                                            {$templateoptions}
                                        </select></td></tr>
                                <tr><td class="fieldlabel">Language</td><td class="fieldarea">
                                        <select class="form-control" name="language">
                                            {$languageoption}
                                        </select></td></tr>
                                <tr><td class="fieldlabel">Disable</td><td class="fieldarea"><label><input type="checkbox" name="disabled"> Tick this box to deactivate this account and prevent login (you cannot disable your own account or the only admin)</label></td></tr>
                            </tbody></table>

                        <p>Please confirm your admin password to add or make changes to administrator account details.</p>

                        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr><td width="20%" class="fieldlabel">Confirm Password</td>
                                    <td class="fieldarea"><input class="form-control" type="password" name="confirmpassword" size="20"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div class="card-footer text-center">
                            <input type="submit" value="Save Changes" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>