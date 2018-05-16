<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Automation Settings");
$aInt->title = $aInt->lang("automation", "title");
$aInt->sidebar = "config";
$aInt->icon = "autosettings";
if ($sub == "save") {
    check_token("RA.admin.default");
    update_query("ra_config", array("value" => $autosuspend), array("setting" => "AutoSuspension"));
    update_query("ra_config", array("value" => $days), array("setting" => "AutoSuspensionDays"));
    update_query("ra_config", array("value" => $createinvoicedays), array("setting" => "CreateInvoiceDaysBefore"));
    update_query("ra_config", array("value" => $createdomaininvoicedays), array("setting" => "CreateDomainInvoiceDaysBefore"));
    update_query("ra_config", array("value" => $invoicesendreminder), array("setting" => "SendReminder"));
    update_query("ra_config", array("value" => $invoicesendreminderdays), array("setting" => "SendInvoiceReminderDays"));
    update_query("ra_config", array("value" => $updatestatusauto), array("setting" => "UpdateStatsAuto"));
    update_query("ra_config", array("value" => $closeinactivetickets), array("setting" => "CloseInactiveTickets"));
    update_query("ra_config", array("value" => $autotermination), array("setting" => "AutoTermination"));
    update_query("ra_config", array("value" => $autoterminationdays), array("setting" => "AutoTerminationDays"));
    update_query("ra_config", array("value" => $addlatefeedays), array("setting" => "AddLateFeeDays"));
    update_query("ra_config", array("value" => $invoicefirstoverduereminder), array("setting" => "SendFirstOverdueInvoiceReminder"));
    update_query("ra_config", array("value" => $invoicesecondoverduereminder), array("setting" => "SendSecondOverdueInvoiceReminder"));
    update_query("ra_config", array("value" => $invoicethirdoverduereminder), array("setting" => "SendThirdOverdueInvoiceReminder"));
    update_query("ra_config", array("value" => $autocancellationrequests), array("setting" => "AutoCancellationRequests"));
    update_query("ra_config", array("value" => $ccprocessdaysbefore), array("setting" => "CCProcessDaysBefore"));
    update_query("ra_config", array("value" => $ccattemptonlyonce), array("setting" => "CCAttemptOnlyOnce"));
    update_query("ra_config", array("value" => $ccretryeveryweekfor), array("setting" => "CCRetryEveryWeekFor"));
    update_query("ra_config", array("value" => $ccdaysendexpirynotices), array("setting" => "CCDaySendExpiryNotices"));
    update_query("ra_config", array("value" => $donotremovecconexpiry), array("setting" => "CCDoNotRemoveOnExpiry"));
    update_query("ra_config", array("value" => $currencyautoupdateexchangerates), array("setting" => "CurrencyAutoUpdateExchangeRates"));
    update_query("ra_config", array("value" => $currencyautoupdateproductprices), array("setting" => "CurrencyAutoUpdateProductPrices"));
    update_query("ra_config", array("value" => $overagebillingmethod), array("setting" => "OverageBillingMethod"));
    update_query("ra_config", array("value" => $invoicegenmonthly), array("setting" => "CreateInvoiceDaysBeforeMonthly"));
    update_query("ra_config", array("value" => $invoicegenquarterly), array("setting" => "CreateInvoiceDaysBeforeQuarterly"));
    update_query("ra_config", array("value" => $invoicegensemiannually), array("setting" => "CreateInvoiceDaysBeforeSemiAnnually"));
    update_query("ra_config", array("value" => $invoicegenannually), array("setting" => "CreateInvoiceDaysBeforeAnnually"));
    update_query("ra_config", array("value" => $invoicegenbiennially), array("setting" => "CreateInvoiceDaysBeforeBiennially"));
    update_query("ra_config", array("value" => $invoicegentriennially), array("setting" => "CreateInvoiceDaysBeforeTriennially"));
    update_query("ra_config", array("value" => $autoclientstatuschange), array("setting" => "AutoClientStatusChange"));
    $renewalstring = "";
    foreach ($renewals as $renewal) {
        $renewalstring .= "" . $renewal . ",";
    }

    update_query("ra_config", array("value" => $renewalstring), array("setting" => "DomainRenewalNotices"));
    redir("success=true");
    exit();
}



if ($success) {
    infoBox($aInt->lang("automation", "changesuccess"), $aInt->lang("automation", "changesuccessinfo"));
}

$result = select_query_i("ra_config", "", "");
while ($data = mysqli_fetch_array($result)) {
    $setting = $data['setting'];
    $value = $data['value'];
    $CONFIG[$setting] = $value;
}

$aInt->assign('infobox', $infobox);
$aInt->assign('CONFIG', $CONFIG);
$aInt->assign('PHP_SELF', $PHP_SELF);
$aInt->template = "configauto";
$aInt->jscode = $jscode;
$aInt->display();
?>
