<div style="float:left;width:100%;">
    <h1>Services</h1>
    <p><b>Options:</b>
        <a href="/admin/configservices.php?action=creategroup">Create a New Group</a> | <a href="/admin/configservices.php?action=create">Create a New Service</a> | <a href="/admin/configservices.php?action=duplicate">Duplicate Service</a>
    </p>
    <form method="post" action="configpservices.php?action=updatesort">
        <input type="hidden" name="token" value="a77ffd97d8a752f1165226d85ae274ae6f19f719">
        <div class="tablebg">
            <table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
                <tbody>
                    <tr>
                        <th>Service Name</th>
                        <th>Type</th>
                        <th>Sort Order</th>
                        <th>Pay Type</th>
                        <th>Price</th>
                        <th>Auto Setup</th>
                        <th width="20"></th>
                        <th width="20"></th>
                    </tr>
                    <tr>
                        <td colspan="6" style="background-color:#ffffdd;"><div align="left"><b>Group Name:</b> Broadband </div></td>
                        <td style="background-color:#ffffdd;" align="center"><a href="/admin/configservices.php?action=editgroup&amp;ids=1">
                                <img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td><td style="background-color:#ffffdd;" align="center">
                            <a href="#" onclick="alert('You cannot delete a service group that contains services.');
                                    return false">
                                <img src="images/delete.gif" width="16" height="16" border="0" alt="Delete">
                            </a>
                        </td>
                    </tr>
                    <tr style="text-align:center;">
                        <td>ADSL2</td>
                        <td>Hosting Account</td>
                        <td><input type="text" name="so[3]" value="0" size="5" style="font-size:10px;"></td>
                        <td>Recurring</td>
                        <td>-</td>
                        <td>Off</td>
                        <td><a href="/admin/configservices.php?action=edit&amp;id=3"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td>
                        <td><a href="#" onclick="alert('You cannot delete a service that is in use.  To delete the service, you need to first re-assign or remove the services using it.');
                                return false"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a>
                        </td>
                    </tr>
                    <tr style="text-align:center;">
                        <td>UFB</td>
                        <td>Hosting Account</td>
                        <td><input type="text" name="so[4]" value="0" size="5" style="font-size:10px;"></td>
                        <td>Free</td>
                        <td>-</td>
                        <td>Off</td>
                        <td><a href="/admin/configservices.php?action=edit&amp;id=4"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td>
                        <td><a href="#" onclick="doDelete('4');
                                return false"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><div align="center"><input type="submit" value="Update Sorting" style="font-size:10px;"></div></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>