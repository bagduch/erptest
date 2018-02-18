

<div class="card">
<div id="tabs">
    <ul>
        <li id="tab0" class="tab">
            <a href="javascript:;">Search/Filter</a>
        </li>
    </ul>
</div>

<div id="tab0box" class="tabbox" style="display: none;">
    <div id="tab_content">

        <form method="post" action="gatewaylog.php">
            <input type="hidden" name="token" value="4a1e669a63d4e6eda227ccd49baae409420dcca8">

            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody><tr><td width="15%" class="fieldlabel">Date Range</td><td class="fieldarea"><input type="text" name="startdate" value="21/06/2017" class="datepick"> &nbsp; to <input type="text" name="enddate" value="21/09/2017" class="datepick"></td><td width="15%" class="fieldlabel">Gateway</td><td class="fieldarea"><select name="filtergateway"><option value="">- Any -</option></select></td></tr>
                    <tr><td class="fieldlabel">Debug Data</td><td class="fieldarea"><input type="text" name="filterdebugdata" size="40" value=""></td><td class="fieldlabel">Result</td><td class="fieldarea"><select name="filterresult"><option value="">- Any -</option></select></td></tr>
                </tbody></table>

            <img src="images/spacer.gif" height="10" width="1"><br>
            <div align="center"><input type="submit" value="Filter" class="button"></div>

        </form>

    </div>
</div>

<br>

<form method="post" action="gatewaylog.php">
    <input type="hidden" name="token" value="4a1e669a63d4e6eda227ccd49baae409420dcca8"><table width="100%" border="0" cellpadding="3" cellspacing="0"><tbody><tr><td width="50%">0 Records Found, Page 1 of 1</td><td width="50%" align="right">Jump to Page: <select name="page" onchange="submit()"><option value="0" selected="">1</option></select> <input type="submit" value="Go" class="btn-small"></td>
            </tr></tbody></table>
</form>

<div class="tablebg">
    <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
        <tbody><tr><th>Date</th><th>Gateway</th><th>Debug Data</th><th>Result</th></tr>
            <tr><td colspan="4">No Records Found</td></tr>
        </tbody></table>
</div>
<div class="right">Jump to Page: <select name="page" onchange="submit()"><option value="0" selected="">1</option></select> <input type="submit" value="Go" class="btn-small"></div><div class="clearfix"></div><p align="center">« Previous Page &nbsp;Next Page »</p>

</div>