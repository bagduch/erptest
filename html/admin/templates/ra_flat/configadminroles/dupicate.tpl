<section class="content">
    <p><strong>Duplicate Role Group</strong></p>
    <form method="post" action="configadminroles.php?action=duplicaterole">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody>
                <tr>
                    <td width="15%" class="fieldlabel">Existing Group Name</td>
                    <td class="fieldarea">
                        {$existinggrouphtml}
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">New Group Name</td>
                    <td class="fieldarea"><input class='form-control' type="text" name="newname" size="40" value=""></td>
                </tr>
            </tbody>
        </table>
        <p align="center"><input type="submit" value="Continue >>" class="button"></p>
    </form>
</section>