
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <form method="post" action="/admin/configesmstemplates.php?action=update">
                    <label>Group:</label>
                    <select name="smsgrp" class="form-control">
                        <option value=""></option>
                        <option value="invoice">Invoice</option>
                        <option value="account">Account</option>
                        <option value="general">General</option>
                    </select>
                    <br>
                    <label>Unique Name:</label> 
                    <br>
                    <input class="form-control" type="text" name="name" size="30"> 
                    <br>
                    <label>Message:</label>
                    <br>
                    <textarea class="form-control" name="message"></textarea>
                    <br>
                    <input type="submit" value="Create" class="button">
                </form>
            </div>
            <div class="box-body">



            </div>
        </div>
    </div>
</div>