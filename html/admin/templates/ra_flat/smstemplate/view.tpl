
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <form method="post" action="/admin/configesmstemplates.php?action=new">
                    <label>Group:</label>
                    <select name="smsgrp" class="form-control">
                        <option value=""></option>
                        <option value="invoice">Invoice</option>
                        <option value="account">Account</option>
                        <option value="general">General</option>
                    </select>
                    <label>Unique Name:</label> 
                    <br>
                    <input class="form-control" type="text" name="name" size="30"> 
                    <br>
                    <input type="submit" value="Create" class="button">
                </form>
            </div>
            <div class="box-body">

                {foreach from=$temdata key=key item=row}
                    <h2>{$key}</h2>
                    {foreach from=$row item=data}

                        <div class="emailtplstandard">
                            <a href='?action=edit&id={$data.id}'>
                                <img src="images/icons/massmail.png" align="absmiddle" border="0" alt="Edit">
                                {$data.name}
                            </a>
                        </div>

                    {/foreach}
                    <div style="clear:left;"></div>
                {/foreach}

            </div>
        </div>
    </div>
</div> 