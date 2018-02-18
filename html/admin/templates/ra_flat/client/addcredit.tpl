
<div class="card">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="clientscredits.php?userid={$userid}&amp;sub=add">
                <input type="hidden" name="token" value="{$token}">
                <div class="header card-header-text">
                    <h4 class="title">
                        <p>Client: <b>{$name}</b> (Balance: {$creditbalance})</p>
                        <p><b>Add Credit</b></p>
                    </h4>
                </div>



                <div class="content">

                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr><td width="15%" class="fieldlabel">Date</td><td class="fieldarea"><input class="form-control" type="text" name="date" size="12" value="19/09/2017"></td></tr>
                            <tr><td class="fieldlabel">Description</td><td class="fieldarea"><textarea class="form-control" name="description" cols="75" rows="4"></textarea></td></tr>
                            <tr><td class="fieldlabel">Amount</td><td class="fieldarea"><input class="form-control" type="text" name="amount" size="15" value="0.00"></td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="footer">
                    <p align="center"> 
                        <input type="submit" value="Save Changes" class="btn">
                        <a href="clientscredits.php?userid={$userid}" class="btn btn-danger">Cancel</a>
                    </p>
                </div>


            </form>
        </div>
    </div>
</div>