
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <form method="post" action="/admin/configesmstemplates.php?action=update">
                    <label>Group:</label>
                    <select name="smsgrp" class="form-control">
                        <option value=""></option>
                        <option value="invoice" {if $sms.smsgrp eq 'invoice'}selected{/if}>Invoice</option>
                        <option value="account" {if $sms.smsgrp eq 'account'}selected{/if}>Account</option>
                        <option value="general" {if $sms.smsgrp eq 'general'}selected{/if}>General</option>
                    </select>
                    <br>
                    <label>Unique Name:</label> 
                    <br>
                    <input type='hidden' name='id' value="{$sms.id}">
                    <input class="form-control" type="text" name="name" size="30" value="{$sms.name}"> 
                    <br>
                    <label>Message: <span class='leftwords'>255</span></label>
                    <br>
                    <textarea class="form-control" name="message">{$sms.message}</textarea>
                    <br>
                    <input type="submit" value="Update" class="button">
                </form>
            </div>
            <div class="box-body">



            </div>
        </div>
    </div>
</div>
{literal}
    <script>

        total = 255;
        $("textarea[name='message']").keyup(function () {
            value = $(this).val();
            regex = /\s+/gi;
            wordCount = value.trim().replace(regex, ' ').split(' ').length;
            $(".leftwords").html(total - wordCount);

        });
    </script>
{/literal}