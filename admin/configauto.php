<?php

/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Automation Settings");
$aInt->title = $aInt->lang("automation", "title");
$aInt->sidebar = "config";
$aInt->icon = "autosettings";
$menuselect = "$('#menu').multilevelpushmenu('expand','System');";
if ($sub == "save") {
    check_token("RA.admin.default");
    update_query("tblconfiguration", array("value" => $autosuspend), array("setting" => "AutoSuspension"));
    update_query("tblconfiguration", array("value" => $days), array("setting" => "AutoSuspensionDays"));
    update_query("tblconfiguration", array("value" => $createinvoicedays), array("setting" => "CreateInvoiceDaysBefore"));
    update_query("tblconfiguration", array("value" => $createdomaininvoicedays), array("setting" => "CreateDomainInvoiceDaysBefore"));
    update_query("tblconfiguration", array("value" => $invoicesendreminder), array("setting" => "SendReminder"));
    update_query("tblconfiguration", array("value" => $invoicesendreminderdays), array("setting" => "SendInvoiceReminderDays"));
    update_query("tblconfiguration", array("value" => $updatestatusauto), array("setting" => "UpdateStatsAuto"));
    update_query("tblconfiguration", array("value" => $closeinactivetickets), array("setting" => "CloseInactiveTickets"));
    update_query("tblconfiguration", array("value" => $autotermination), array("setting" => "AutoTermination"));
    update_query("tblconfiguration", array("value" => $autoterminationdays), array("setting" => "AutoTerminationDays"));
    update_query("tblconfiguration", array("value" => $autounsuspend), array("setting" => "AutoUnsuspend"));
    update_query("tblconfiguration", array("value" => $addlatefeedays), array("setting" => "AddLateFeeDays"));
    update_query("tblconfiguration", array("value" => $invoicefirstoverduereminder), array("setting" => "SendFirstOverdueInvoiceReminder"));
    update_query("tblconfiguration", array("value" => $invoicesecondoverduereminder), array("setting" => "SendSecondOverdueInvoiceReminder"));
    update_query("tblconfiguration", array("value" => $invoicethirdoverduereminder), array("setting" => "SendThirdOverdueInvoiceReminder"));
    update_query("tblconfiguration", array("value" => $autocancellationrequests), array("setting" => "AutoCancellationRequests"));
    update_query("tblconfiguration", array("value" => $ccprocessdaysbefore), array("setting" => "CCProcessDaysBefore"));
    update_query("tblconfiguration", array("value" => $ccattemptonlyonce), array("setting" => "CCAttemptOnlyOnce"));
    update_query("tblconfiguration", array("value" => $ccretryeveryweekfor), array("setting" => "CCRetryEveryWeekFor"));
    update_query("tblconfiguration", array("value" => $ccdaysendexpirynotices), array("setting" => "CCDaySendExpiryNotices"));
    update_query("tblconfiguration", array("value" => $donotremovecconexpiry), array("setting" => "CCDoNotRemoveOnExpiry"));
    update_query("tblconfiguration", array("value" => $currencyautoupdateexchangerates), array("setting" => "CurrencyAutoUpdateExchangeRates"));
    update_query("tblconfiguration", array("value" => $currencyautoupdateproductprices), array("setting" => "CurrencyAutoUpdateProductPrices"));
    update_query("tblconfiguration", array("value" => $overagebillingmethod), array("setting" => "OverageBillingMethod"));
    update_query("tblconfiguration", array("value" => $invoicegenmonthly), array("setting" => "CreateInvoiceDaysBeforeMonthly"));
    update_query("tblconfiguration", array("value" => $invoicegenquarterly), array("setting" => "CreateInvoiceDaysBeforeQuarterly"));
    update_query("tblconfiguration", array("value" => $invoicegensemiannually), array("setting" => "CreateInvoiceDaysBeforeSemiAnnually"));
    update_query("tblconfiguration", array("value" => $invoicegenannually), array("setting" => "CreateInvoiceDaysBeforeAnnually"));
    update_query("tblconfiguration", array("value" => $invoicegenbiennially), array("setting" => "CreateInvoiceDaysBeforeBiennially"));
    update_query("tblconfiguration", array("value" => $invoicegentriennially), array("setting" => "CreateInvoiceDaysBeforeTriennially"));
    update_query("tblconfiguration", array("value" => $autoclientstatuschange), array("setting" => "AutoClientStatusChange"));
    $renewalstring = "";
    foreach ($renewals as $renewal) {
        $renewalstring .= "" . $renewal . ",";
    }

    update_query("tblconfiguration", array("value" => $renewalstring), array("setting" => "DomainRenewalNotices"));
    redir("success=true");
    exit();
}



if ($success) {
    infoBox($aInt->lang("automation", "changesuccess"), $aInt->lang("automation", "changesuccessinfo"));
    echo $infobox;
}



$aInt->assign('CONFIG', $CONFIG);
$aInt->assign('PHP_SELF', $PHP_SELF);
$aInt->template = "configauto";
$aInt->jscode = $jscode;
$aInt->jquerycode .= $menuselect;
$aInt->display();
?>
