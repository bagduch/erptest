<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="content">
                <form method="post" action="{$PHP_SELF}?sub=save">
                    <p><b>Automatic Module Functions</b></p>
                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Enable Suspension</td>
                                <td class="fieldarea">
                                    <label><input type="checkbox" name="autosuspend" {if $CONFIG.AutoSuspension eq 'on'}checked{/if}> Tick this box to enable automatic suspension</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Suspend Days</td>
                                <td class="fieldarea">
                                    <input type="text" name="days" value="{$CONFIG.AutoSuspensionDays}" size="3" > Enter the number of days after the due payment date you want to wait before suspending the account
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Enable Unsuspension</td>
                                <td class="fieldarea">
                                    <label><input type="checkbox" name="autounsuspend" {if $CONFIG.Autounsuspend eq 'on'}checked{/if}> Tick this box to enable automatic unsuspension on payment</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Enable Termination</td>
                                <td class="fieldarea">
                                    <label><input type="checkbox" name="autotermination" {if $CONFIG.AutoTermination eq 'on'}checked{/if}> Tick this box to enable automatic termination</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Termination Days</td>
                                <td class="fieldarea">
                                    <input type="text" name="autoterminationdays" value="{$CONFIG.AutoTerminationDays}" size="3"> Enter the number of days after the due payment date you want to wait before terminating the account
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><b>Billing Settings</b></p>
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Invoice Generation</td>
                                <td class="fieldarea">
                                    <input type="text" name="createinvoicedays" value="{$CONFIG.CreateInvoiceDaysBefore}" size="3"> Enter the default number of days before the due payment date to generate invoices (<a href="#" onclick="showadvinvoice();
                                    return false">Advanced Settings</a>)
                                    <div id="advinvoicesettings" align="center" style="display:none;">
                                        <br>
                                        <b>Per Billing Cycle Settings</b><br>
                                        This allows you to specify for certain cycles to generate further or less in advance of the due date than the default specified above:<br>
                                        <table width="650" cellspacing="1" bgcolor="#cccccc">
                                            <tbody>
                                                <tr bgcolor="#efefef" style="text-align:center;font-weight:bold">
                                                    <td>Monthly</td>
                                                    <td>Quarterly</td>
                                                    <td>Semi-Annually</td>
                                                    <td>Annually</td>
                                                    <td>Biennially</td>
                                                    <td>Triennially</td>
                                                </tr>
                                                <tr bgcolor="#ffffff">
                                                    <td><input type="text" name="invoicegenmonthly" size="10" value="{$CONFIG.CreateInvoiceDaysBeforeMonthly}"></td>
                                                    <td><input type="text" name="invoicegenquarterly" size="10" value="{$CONFIG.CreateInvoiceDaysBeforeQuarterly}"></td>
                                                    <td><input type="text" name="invoicegensemiannually" size="10" value="{$CONFIG.CreateInvoiceDaysBeforeSemiAnnually}"></td>
                                                    <td><input type="text" name="invoicegenannually" size="10" value="{$CONFIG.CreateInvoiceDaysBeforeAnnually}"></td>
                                                    <td><input type="text" name="invoicegenbiennially" size="10" value="{$CONFIG.CreateInvoiceDaysBeforeBiennially}"></td>
                                                    <td><input type="text" name="invoicegentriennially" size="10" value="{$CONFIG.CreateInvoiceDaysBeforeTriennially}"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        (Leave blank to use default setting for a cycle)
                                        <br><br>
                                        <b></b><br>
                                        :<br>
                                        <input type="text" name="createdomaininvoicedays" value="{$CONFIG.CreateDomainInvoiceDaysBefore}" size="3"> (Leave blank to use default setting)<br><br>
                                    </div>
                                </td></tr>
                            <tr>
                                <td class="fieldlabel">Payment Reminder Emails</td>
                                <td class="fieldarea"><label><input type="checkbox" name="invoicesendreminder" {if $CONFIG.SendReminder}checked{/if}> Tick to activate overdue subscription reminders and invoice payment reminders</label></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Invoice Unpaid Reminder</td>
                                <td class="fieldarea"><input type="text" name="invoicesendreminderdays" value="{$CONFIG.SendInvoiceReminderDays}" size="3"> Enter the number of days before the invoice due date you would like to send a reminder (0 to disable)</td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">First Overdue Reminder</td>
                                <td class="fieldarea"><input type="text" name="invoicefirstoverduereminder" value="{$CONFIG.SendFirstOverdueInvoiceReminder}" size="3"> Enter the number of days after the invoice due date you would like to send the first overdue notice (0 to disable)</td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Second Overdue Reminder</td>
                                <td class="fieldarea"><input type="text" name="invoicesecondoverduereminder" value="{$CONFIG.SendSecondOverdueInvoiceReminder}" size="3"> Enter the number of days after the invoice due date you would like to send the second overdue notice (0 to disable)</td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Third Overdue Reminder</td>
                                <td class="fieldarea"><input type="text" name="invoicethirdoverduereminder" value="{$CONFIG.SendThirdOverdueInvoiceReminder}" size="3"> Enter the number of days after the invoice due date you would like to send the third (final) overdue notice (0 to disable)</td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Add Late Fee Days</td>
                                <td class="fieldarea"><input type="text" name="addlatefeedays" value="{$CONFIG.AddLateFeeDays}" size="5"> Enter the number of days after the due payment date you want to add the late fee</td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Overage Billing Charges</td>
                                <td class="fieldarea"><label><input type="radio" name="overagebillingmethod" value="1" {if $CONFIG.OverageBillingMethod eq 1}checked{/if}> Calculate &amp; invoice on the last day of the month independantly from the related product</label>
                                    <br><label><input type="radio" name="overagebillingmethod" value="2" {if $CONFIG.OverageBillingMethod eq 2}checked{/if}> Calculate on the last day of the month but include on the next invoice to generate for the client</label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><b>Credit Card Charging Settings</b></p>
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Process Days Before Due</td>
                                <td class="fieldarea">
                                    <input type="text" name="ccprocessdaysbefore" value="{$CONFIG.CCProcessDaysBefore}" size="3"> Enter the number of days before the due payment date you want to attempt to capture the payment
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Attempt Only Once</td>
                                <td class="fieldarea"><label><input type="checkbox" name="ccattemptonlyonce" {if $CONFIG.CCAttemptOnlyOnce eq 'on'}checked{/if}> Tick this box to only attempt the payment automatically once and if it fails, don't attempt it again</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Retry Every Week For</td>
                                <td class="fieldarea">
                                    <input type="text" name="ccretryeveryweekfor" value="{$CONFIG.CCRetryEveryWeekFor}" size="3"> Enter the number of weeks to retry failed CC processing attempts for weekly
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">CC Expiry Notices Date</td>
                                <td class="fieldarea">
                                    <input type="text" name="ccdaysendexpirynotices" value="{$CONFIG.CCDaySendExpiryNotices}" size="3"> Enter the day of the month that you want to send credit card expiry notices for the upcoming month
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Do Not Remove CC on Expiry</td>
                                <td class="fieldarea">
                                    <label><input type="checkbox" name="donotremovecconexpiry" {if $CONFIG.CCDoNotRemoveOnExpiry eq 'on'}checked{/if}> Tick this box to not remove credit card details when the expiry date passes</label>
                                </td>
                            </tr>
                        </tbody></table>
                    <p><b></b></p><p><b><b>Support Ticket Settings</b></b></p><b>
                        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">Close Inactive Tickets</td>
                                    <td class="fieldarea">
                                        <input type="text" name="closeinactivetickets" value="0" size="3"> Time (in hours) of inactivity after which ticket is closed (0 to disable)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p><b>Miscellaneous</b></p>
                        Tick this box to update automatically when the cron runs<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">Cancellation Requests</td>
                                    <td class="fieldarea">
                                        <label><input type="checkbox" name="autocancellationrequests"  {if $CONFIG.AutoCancellationRequests eq 'on'}checked{/if}> Tick this box to automatically terminate accounts with cancellation requests when due</label>
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel"></td></tr>
                                <tr>
                                    <td class="fieldlabel">Client Status Update</td>
                                    <td class="fieldarea">
                                        <label><input type="radio" name="autoclientstatuschange" value="1" {if $CONFIG.AutoClientStatusChange eq 1}checked{/if}> Disabled - never auto change client status</label> 
                                        <br>
                                        <label><input type="radio" name="autoclientstatuschange" value="2" {if $CONFIG.AutoClientStatusChange eq 2}checked{/if}>Change client status based on active/inactive products</label>
                                        <br>
                                        <label><input type="radio" name="autoclientstatuschange" value="3" {if $CONFIG.AutoClientStatusChange eq 3}checked{/if}> Change client status based on active/inactive products and not logged in for longer than 3 months</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p align="center"><input type="submit" value="Save Changes" class="btn">
                    </b>
                </form>
            </div>
        </div>
    </div>
</div>