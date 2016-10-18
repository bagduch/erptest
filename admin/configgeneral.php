<?php

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure General Settings", false);
$aInt->title = $aInt->lang("general", "title");
$aInt->sidebar = "config";
$aInt->icon = "config";
$aInt->helplink = "General Settings";
$aInt->requiredFiles(array("clientfunctions", "configgeneral"));

if ($action == "addwhitelistip") {
    check_token("RA.admin.default");
    $whitelistedips = $ra->get_config("WhitelistedIPs");
    $whitelistedips = unserialize($whitelistedips);
    $whitelistedips[] = array("ip" => $ipaddress, "note" => $notes);
    $ra->set_config("WhitelistedIPs", serialize($whitelistedips));
    delete_query("tblbannedips", array("ip" => $ipaddress));
    exit();
}


if ($action == "deletewhitelistip") {
    check_token("RA.admin.default");
    $removeip = explode(" - ", $removeip);
    $whitelistedips = $ra->get_config("WhitelistedIPs");
    $whitelistedips = unserialize($whitelistedips);
    foreach ($whitelistedips as $k => $v) {

        if ($v['ip'] == $removeip[0]) {
            unset($whitelistedips[$k]);
            continue;
        }
    }

    $ra->set_config("WhitelistedIPs", serialize($whitelistedips));
    update_query("tblconfiguration", array("value" => serialize($whitelistedips)), array("setting" => "WhitelistedIPs"));
    exit();
}


if ($action == "addapiip") {
    check_token("RA.admin.default");
    $whitelistedips = $ra->get_config("APIAllowedIPs");
    $whitelistedips = unserialize($whitelistedips);
    $whitelistedips[] = array("ip" => $ipaddress, "note" => $notes);
    $ra->set_config("APIAllowedIPs", serialize($whitelistedips));
    exit();
}


if ($action == "deleteapiip") {
    check_token("RA.admin.default");
    $removeip = explode(" - ", $removeip);
    $whitelistedips = $ra->get_config("APIAllowedIPs");
    $whitelistedips = unserialize($whitelistedips);
    foreach ($whitelistedips as $k => $v) {

        if ($v['ip'] == $removeip[0]) {
            unset($whitelistedips[$k]);
            continue;
        }
    }

    $ra->set_config("APIAllowedIPs", serialize($whitelistedips));
    exit();
}


if ($action == "save") {
    check_token("RA.admin.default");

    if ($companyname) {
        unset($_SESSION['Language']);
        unset($_SESSION['Template']);
        unset($_SESSION['OrderFormTemplate']);
        releaseSession();
        $affiliatebonusdeposit = number_format($affiliatebonusdeposit, 2, ".", "");
        $affiliatepayout = number_format($affiliatepayout, 2, ".", "");

        if (!$language) {
            $language = "chinese";
        }


        if (!$template) {
            $template = "default";
        }

        $acceptedcardtypes = ($acceptedcctypes ? implode(",", $acceptedcctypes) : "");
        $clientsprofoptional = ($clientsprofoptional ? implode(",", $clientsprofoptional) : "");
        $clientsprofuneditable = ($clientsprofuneditable ? implode(",", $clientsprofuneditable) : "");

        if (($tcpdffont != "helvetica" && $tcpdffont != "freesans") && $tcpdffontcustom) {
            $tcpdffont = $tcpdffontcustom;
        }

        $addfundsminimum = format_as_currency($addfundsminimum);
        $addfundsmaximum = format_as_currency($addfundsmaximum);
        $addfundsmaximumbalance = format_as_currency($addfundsmaximumbalance);
        $latefeeminimum = format_as_currency($latefeeminimum);
        $bulkchecktldsstring = ($bulkchecktlds ? implode(",", $bulkchecktlds) : "");

        if (!$ra->get_config("CCNeverStore") && $ccneverstore) {
            update_query("tblclients", array("cardtype" => "", "cardlastfour" => "", "cardnum" => "", "expdate" => "", "startdate" => "", "issuenumber" => "", "gatewayid" => ""), "");
        }

        $domain = cleanSystemURL($domain);
        $systemurl = cleanSystemURL($systemurl);
        $systemsslurl = cleanSystemURL($systemsslurl, true, true);

        if (current(parse_url(cleanSystemURL($systemurl))) == current(parse_url(cleanSystemURL($systemsslurl)))) {
            $systemsslurl = "";
        }

        $save_arr = array("CompanyName" => html_entity_decode($companyname), "Email" => $email, "Domain" => $domain, "LogoURL" => $logourl, "SystemURL" => $systemurl, "SystemSSLURL" => $systemsslurl, "Template" => $template, "MaintenanceModeURL" => $maintenancemodeurl, "ClientDateFormat" => $clientdateformat, "FreeDomainAutoRenewRequiresProduct" => $freedomainautorenewrequiresproduct, "DomainToDoListEntries" => $domaintodolistentries, "AllowIDNDomains" => $allowidndomains, "BulkCheckTLDs" => $bulkchecktldsstring, "DomainSyncEnabled" => $domainsyncenabled, "DomainSyncNextDueDate" => $domainsyncnextduedate, "DomainSyncNextDueDateDays" => $domainsyncnextduedatedays, "DomainSyncNotifyOnly" => $domainsyncnotifyonly, "DefaultNameserver1" => $ns1, "DefaultNameserver2" => $ns2, "DefaultNameserver3" => $ns3, "DefaultNameserver4" => $ns4, "DefaultNameserver5" => $ns5, "RegistrarAdminFirstName" => $domfirstname, "RegistrarAdminLastName" => $domlastname, "RegistrarAdminCompanyName" => $domcompanyname, "RegistrarAdminEmailAddress" => $domemail, "RegistrarAdminAddress1" => $domaddress1, "RegistrarAdminAddress2" => $domaddress2, "RegistrarAdminCity" => $domcity, "RegistrarAdminStateProvince" => $domstate, "RegistrarAdminPostalCode" => $dompostcode, "RegistrarAdminCountry" => $domcountry, "RegistrarAdminPhone" => $domphone, "RegistrarAdminUseClientDetails" => $domuseclientsdetails, "SMTPHost" => $smtphost, "SMTPUsername" => $smtpusername, "SMTPPassword" => encrypt(html_entity_decode($smtppassword)),
            "SMTPPort" => $smtpport, "SMTPSSL" => $smtpssl, "EmailGlobalHeader" => $emailglobalheader, "EmailGlobalFooter" => $emailglobalfooter, "BCCMessages" => $bccmessages, "ContactFormTo" => $contactformto, "ShowClientOnlyDepts" => $showclientonlydepts, "TicketFeedback" => $ticketfeedback, "TicketMask" => $ticketmask, "AttachmentThumbnails" => $attachmentthumbnails, "DownloadsIncludeProductLinked" => $dlinclproductdl, "CancelInvoiceOnCancellation" => $cancelinvoiceoncancel, "TCPDFFont" => $tcpdffont, "AddFundsEnabled" => $addfundsenabled, "AddFundsMinimum" => $addfundsminimum, "AddFundsMaximum" => $addfundsmaximum, "AddFundsMaximumBalance" => $addfundsmaximumbalance, "LateFeeMinimum" => $latefeeminimum, "AffiliateEnabled" => $affiliateenabled, "AffiliateEarningPercent" => $affiliateearningpercent, "AffiliateDepartment" => $affiliatedepartment, "AffiliateBonusDeposit" => $affiliatebonusdeposit, "AffiliatePayout" => $affiliatepayout, "AffiliatesDelayCommission" => $affiliatesdelaycommission, "AffiliateLinks" => $affiliatelinks, "CaptchaType" => $captchatype, "ReCAPTCHAPrivateKey" => $recaptchaprivatekey, "ReCAPTCHAPublicKey" => $recaptchapublickey, "AdminForceSSL" => $adminforcessl, "DisableAdminPWReset" => $disableadminpwreset, "CCNeverStore" => $ccneverstore, "TwitterUsername" => $twitterusername, "AnnouncementsTweet" => $announcementstweet, "AnnouncementsFBRecommend" => $announcementsfbrecommend, "AnnouncementsFBComments" => $announcementsfbcomments, "GooglePlus1" => $googleplus1, "ClientsProfileOptionalFields" => $clientsprofoptional, "ClientsProfileUneditableFields" => $clientsprofuneditable, "DefaultToClientArea" => $defaulttoclientarea, "AllowClientsEmailOptOut" => $allowclientsemailoptout, "BannedSubdomainPrefixes" => $bannedsubdomainprefixes, "DisplayErrors" => $displayerrors, "SQLErrorReporting" => $sqlerrorreporting);

        foreach ($save_arr as $k => $v) {
            $ra->set_config($k, trim($v));
        }

        update_query("tblconfiguration", array("value" => $activitylimit), array("setting" => "ActivityLimit"));
        update_query("tblconfiguration", array("value" => $numrecords), array("setting" => "NumRecordstoDisplay"));
        update_query("tblconfiguration", array("value" => $language), array("setting" => "Language"));
        update_query("tblconfiguration", array("value" => $dateformat), array("setting" => "DateFormat"));
        update_query("tblconfiguration", array("value" => $allowuserlanguage), array("setting" => "AllowLanguageChange"));
        update_query("tblconfiguration", array("value" => $enabletos), array("setting" => "EnableTOSAccept"));
        update_query("tblconfiguration", array("value" => $tos), array("setting" => "TermsOfService"));
        update_query("tblconfiguration", array("value" => $orderform), array("setting" => "OrderForm"));
        update_query("tblconfiguration", array("value" => $allowregister), array("setting" => "AllowRegister"));
        update_query("tblconfiguration", array("value" => $allowtransfer), array("setting" => "AllowTransfer"));
        update_query("tblconfiguration", array("value" => $allowowndomain), array("setting" => "AllowOwnDomain"));
        update_query("tblconfiguration", array("value" => $mailtype), array("setting" => "MailType"));
        update_query("tblconfiguration", array("value" => $invoicepayto), array("setting" => "InvoicePayTo"));
        update_query("tblconfiguration", array("value" => $mailpiping), array("setting" => "MailPipingEnabled"));
        update_query("tblconfiguration", array("value" => $presales), array("setting" => "PreSalesQuestions"));
        update_query("tblconfiguration", array("value" => $showcancel), array("setting" => "ShowCancellationButton"));
        update_query("tblconfiguration", array("value" => $affreport), array("setting" => "SendAffiliateReportMonthly"));
        update_query("tblconfiguration", array("value" => $signature), array("setting" => "Signature"));
        update_query("tblconfiguration", array("value" => $allowcustomerchangeinvoicegateway), array("setting" => "AllowCustomerChangeInvoiceGateway"));
        update_query("tblconfiguration", array("value" => $sendemailnotificationonuserdetailschange), array("setting" => "SendEmailNotificationonUserDetailsChange"));
        update_query("tblconfiguration", array("value" => $invalidloginsbanlength), array("setting" => "InvalidLoginBanLength"));
        update_query("tblconfiguration", array("value" => $charset), array("setting" => "Charset"));
        update_query("tblconfiguration", array("value" => $runscriptoncheckout), array("setting" => "RunScriptonCheckOut"));
        update_query("tblconfiguration", array("value" => $allowedfiletypes), array("setting" => "TicketAllowedFileTypes"));
        update_query("tblconfiguration", array("value" => $orderformdefault), array("setting" => "OrderOption"));
        update_query("tblconfiguration", array("value" => $orderformtemplate), array("setting" => "OrderFormTemplate"));
        update_query("tblconfiguration", array("value" => $allowdomainstwice), array("setting" => "AllowDomainsTwice"));
        update_query("tblconfiguration", array("value" => $defaultcountry), array("setting" => "DefaultCountry"));
        update_query("tblconfiguration", array("value" => $captchasetting), array("setting" => "CaptchaSetting"));
        update_query("tblconfiguration", array("value" => $autoredirecttoinvoice), array("setting" => "AutoRedirectoInvoice"));
        update_query("tblconfiguration", array("value" => $enablepdfinvoices), array("setting" => "EnablePDFInvoices"));
        update_query("tblconfiguration", array("value" => $supportticketorder), array("setting" => "SupportTicketOrder"));
        update_query("tblconfiguration", array("value" => $invoicesubscriptionpayments), array("setting" => "InvoiceSubscriptionPayments"));
        update_query("tblconfiguration", array("value" => $invoiceincrement), array("setting" => "InvoiceIncrement"));
        update_query("tblconfiguration", array("value" => $continuousinvoicegeneration), array("setting" => "ContinuousInvoiceGeneration"));
        update_query("tblconfiguration", array("value" => html_entity_decode($systememailsfromname)), array("setting" => "SystemEmailsFromName"));
        update_query("tblconfiguration", array("value" => $systememailsfromemail), array("setting" => "SystemEmailsFromEmail"));
        update_query("tblconfiguration", array("value" => $allowclientregister), array("setting" => "AllowClientRegister"));
        update_query("tblconfiguration", array("value" => $productmonthlypricingbreakdown), array("setting" => "ProductMonthlyPricingBreakdown"));
        update_query("tblconfiguration", array("value" => $bulkdomainsearchenabled), array("setting" => "BulkDomainSearchEnabled"));
        update_query("tblconfiguration", array("value" => $creditondowngrade), array("setting" => "CreditOnDowngrade"));
        update_query("tblconfiguration", array("value" => $acceptedcardtypes), array("setting" => "AcceptedCardTypes"));
        update_query("tblconfiguration", array("value" => $invoicelatefeeamount), array("setting" => "InvoiceLateFeeAmount"));
        update_query("tblconfiguration", array("value" => $latefeetype), array("setting" => "LateFeeType"));
        update_query("tblconfiguration", array("value" => $sequentialinvoicenumbering), array("setting" => "SequentialInvoiceNumbering"));
        update_query("tblconfiguration", array("value" => $sequentialinvoicenumberformat), array("setting" => "SequentialInvoiceNumberFormat"));
        update_query("tblconfiguration", array("value" => $sequentialinvoicenumbervalue), array("setting" => "SequentialInvoiceNumberValue"));
        update_query("tblconfiguration", array("value" => $supportmodule), array("setting" => "SupportModule"));
        update_query("tblconfiguration", array("value" => $orderdaysgrace), array("setting" => "OrderDaysGrace"));
        update_query("tblconfiguration", array("value" => $autorenewdomainsonpayment), array("setting" => "AutoRenewDomainsonPayment"));
        update_query("tblconfiguration", array("value" => $domainautorenewdefault), array("setting" => "DomainAutoRenewDefault"));
        update_query("tblconfiguration", array("value" => $supportticketkbsuggestions), array("setting" => "SupportTicketKBSuggestions"));
        update_query("tblconfiguration", array("value" => $seofriendlyurls), array("setting" => "SEOFriendlyUrls"));
        update_query("tblconfiguration", array("value" => $showccissuestart), array("setting" => "ShowCCIssueStart"));
        update_query("tblconfiguration", array("value" => $emailcss), array("setting" => "EmailCSS"));
        update_query("tblconfiguration", array("value" => $clientdropdownformat), array("setting" => "ClientDropdownFormat"));
        update_query("tblconfiguration", array("value" => $ticketratingenabled), array("setting" => "TicketRatingEnabled"));
        update_query("tblconfiguration", array("value" => $requireloginforclienttickets), array("setting" => "RequireLoginforClientTickets"));
        update_query("tblconfiguration", array("value" => $shownotesfieldoncheckout), array("setting" => "ShowNotesFieldonCheckout"));
        update_query("tblconfiguration", array("value" => $networkissuesrequirelogin), array("setting" => "NetworkIssuesRequireLogin"));
        update_query("tblconfiguration", array("value" => $requiredpwstrength), array("setting" => "RequiredPWStrength"));
        update_query("tblconfiguration", array("value" => $maintenancemode), array("setting" => "MaintenanceMode"));
        update_query("tblconfiguration", array("value" => $maintenancemodemessage), array("setting" => "MaintenanceModeMessage"));
        update_query("tblconfiguration", array("value" => $skipfraudforexisting), array("setting" => "SkipFraudForExisting"));
        update_query("tblconfiguration", array("value" => $contactformdept), array("setting" => "ContactFormDept"));
        update_query("tblconfiguration", array("value" => $disablesessionipcheck), array("setting" => "DisableSessionIPCheck"));
        update_query("tblconfiguration", array("value" => $disablesupportticketreplyemailslogging), array("setting" => "DisableSupportTicketReplyEmailsLogging"));
        update_query("tblconfiguration", array("value" => $ccallowcustomerdelete), array("setting" => "CCAllowCustomerDelete"));
        update_query("tblconfiguration", array("value" => $noinvoicemeailonorder), array("setting" => "NoInvoiceEmailOnOrder"));
        update_query("tblconfiguration", array("value" => $autoprovisionexistingonly), array("setting" => "AutoProvisionExistingOnly"));
        update_query("tblconfiguration", array("value" => $enabledomainrenewalorders), array("setting" => "EnableDomainRenewalOrders"));
        update_query("tblconfiguration", array("value" => $enablemasspay), array("setting" => "EnableMassPay"));
        update_query("tblconfiguration", array("value" => $noautoapplycredit), array("setting" => "NoAutoApplyCredit"));
        update_query("tblconfiguration", array("value" => $clientdisplayformat), array("setting" => "ClientDisplayFormat"));
        update_query("tblconfiguration", array("value" => $generaterandomusername), array("setting" => "GenerateRandomUsername"));
        update_query("tblconfiguration", array("value" => $addfundsrequireorder), array("setting" => "AddFundsRequireOrder"));
        update_query("tblconfiguration", array("value" => $groupsimilarlineitems), array("setting" => "GroupSimilarLineItems"));
        update_query("tblconfiguration", array("value" => $prorataclientsanniversarydate), array("setting" => "ProrataClientsAnniversaryDate"));

        if ($continuousinvoicegeneration == "on" && !$CONFIG['ContinuousInvoiceGeneration']) {
            full_query_i("UPDATE tblcustomerservices SET nextinvoicedate = nextduedate");
            full_query_i("UPDATE tbldomains SET nextinvoicedate = nextduedate");
            full_query_i("UPDATE tblserviceaddons SET nextinvoicedate = nextduedate");
        }


        if (is_numeric($invoicestartnumber)) {
            full_query_i("ALTER TABLE tblinvoices AUTO_INCREMENT = " . (int) $invoicestartnumber);
        }
    }

    global $ra;
    $token_manager = &getTokenManager();

    $token_manager->processAdminHTMLSave($ra);
    redir("success=true&tab=" . $tab);
    exit();
} // end of action==save

releaseSession();
ob_start();

$jquerycode .= "$(\"#removewhitelistedip\").click(function () {
    var removeip = $('#whitelistedips option:selected;').text();
    $('#whitelistedips option:selected').remove();
    $.post(\"configgeneral.php\", { action: \"deletewhitelistip\", removeip: removeip, token: '" . generate_token("plain") . "'});
    return false;
});
function addwhitelistedip(ipaddress,note) {
    $('#whitelistedips').append('<option>'+ipaddress+' - '+note+'</option>');
    $.post(\"configgeneral.php\", { action: \"addwhitelistip\", ipaddress: ipaddress, notes: note, token: '" . generate_token("plain") . "'});
    $('#addwhitelistip').dialog('close');
    return false;
};
$(\"#removeapiip\").click(function () {
    var removeip = $('#apiallowedips option:selected;').text();
    $('#apiallowedips option:selected').remove();
    $.post(\"configgeneral.php\", { action: \"deleteapiip\", removeip: removeip, token: '" . generate_token("plain") . "'});
    return false;
});
function addapiip(ipaddress,note) {
    $('#apiallowedips').append('<option>'+ipaddress+' - '+note+'</option>');
    $.post(\"configgeneral.php\", { action: \"addapiip\", ipaddress: ipaddress, notes: note, token: '" . generate_token("plain") . "'});
    $('#addapiip').dialog('close');
    return false;
};
";
echo $aInt->jqueryDialog("addwhitelistip", $aInt->lang("general", "addwhitelistedip"), "<table><tr><td>" . $aInt->lang("fields", "ipaddress") . ":</td>" .
        "<td><input type=\"text\" id=\"ipaddress\" size=\"20\" /></td></tr>" .
        "<tr><td>" . $aInt->lang("fields", "reason") . ":</td>" .
        "<td><input type=\"text\" id=\"notes\" size=\"40\" /></td></tr></table>", array(
    $aInt->lang("general", "addip") => "addwhitelistedip($(\"#ipaddress\").val(),$(\"#notes\").val());",
    $aInt->lang("global", "cancel") => ""), "", "350", ""
);

echo $aInt->jqueryDialog("addapiip", $aInt->lang("general", "addwhitelistedip"), "<table><tr><td>" . $aInt->lang("fields", "ipaddress") . ":</td><td><input type=\"text\" id=\"ipaddress2\" size=\"20\" /></td></tr><tr><td>" . $aInt->lang("fields", "notes") . ":</td><td><input type=\"text\" id=\"notes2\" size=\"40\" /></td></tr></table>", array($aInt->lang("general", "addip") => "addapiip($(\"#ipaddress2\").val(),$(\"#notes2\").val());",
    $aInt->lang("global", "cancel") => ""), "", "350", ""
);

if ($success) {
    infoBox($aInt->lang("general", "changesuccess"), $aInt->lang("general", "changesuccessinfo"));
    echo $infobox;
}

$result = select_query_i("tblconfiguration", "", "");

while ($data = mysqli_fetch_array($result)) {
    $setting = $data['setting'];
    $value = $data['value'];
    $CONFIG["" . $setting] = "" . $value;
}


$tplfolder = ROOTDIR . "/templates/";
$templatearray = "";
if (is_dir($tplfolder)) {
    $dh = opendir($tplfolder);

    while (false !== $folder = readdir($dh)) {
        if ((((is_dir($tplfolder . $folder) && $folder != ".") && $folder != "..") && $folder != "orderforms") && $folder != "kayako") {
            $templatearray.= "<option value=\"" . $folder . "\"";

            if ($folder == $ra->get_sys_tpl_name()) {
                $templatearray.= " selected";
            }

            $templatearray.= ">" . ucfirst($folder) . "</option>";
        }
    }

    closedir($dh);
}

include "../includes/countries.php";
$countryarray = getCountriesDropDown($CONFIG['DefaultCountry'], "defaultcountry");
$languagearray = "";
$language = $ra->validateLanguage($ra->get_config("Language"));
foreach ($ra->getValidLanguages() as $lang) {
    $languagearray.= "<option value=\"" . $lang . "\"";

    if ($lang == $language) {
        $languagearray.= " selected=\"selected\"";
    }

    $languagearray.= ">" . ucfirst($lang) . "</option>";
}

$ordertplfolder = ROOTDIR . "/templates/orderforms/";

if (is_dir($ordertplfolder)) {
    $dh = opendir($ordertplfolder);

    while (false !== $folder = readdir($dh)) {
        if (file_exists($ordertplfolder . $folder . "/products.tpl")) {
            $ordertemplates[] = $folder;
        }
    }

    closedir($dh);
}

sort($ordertemplates);
$ordertemplatearray = "";
foreach ($ordertemplates as $template) {
    $thumbnail = "../templates/orderforms/" . $template . "/thumbnail.gif";

    if (!file_exists($thumbnail)) {
        $thumbnail = "images/ordertplpreview.gif";
    }

    $ordertemplatearray.= "<div style=\"float:left;padding:10px;text-align:center;\"><label><img src=\"" . $thumbnail . "\" width=\"165\" height=\"90\" style=\"border:5px solid #fff;\" /><br /><input type=\"radio\" name=\"orderformtemplate\" value=\"" . $template . "\"";

    if ($template == $CONFIG['OrderFormTemplate']) {
        $ordertemplatearray.= " checked";
    }

    $ordertemplatearray.= "> " . ucfirst($template) . "</label></div>";
}

$currency = getCurrency();

echo getCountriesDropDown($CONFIG['RegistrarAdminCountry'], "domcountry");

$dept_query = select_query_i("tblticketdepartments", "id, name", "");
$deptarray = "";
while ($dept_result = mysqli_fetch_assoc($dept_query)) {
    $selected = "";

    if ($CONFIG['ContactFormDept'] == $dept_result['id']) {
        $selected = " selected";
    }

    $deptarray.="<option value=\"" . $dept_result['id'] . "\"" . $selected . ">" . $dept_result['name'] . "</option>";
}

$supportfolder = ROOTDIR . "/modules/support/";
$supportarray = "";
if (is_dir($supportfolder)) {
    $dh = opendir($supportfolder);

    while (false !== $folder = readdir($dh)) {
        if ((is_dir($supportfolder . $folder) && $folder != ".") && $folder != "..") {
            $supportarray.= "<option value=\"" . $folder . "\"";

            if ($folder == $CONFIG['SupportModule']) {
                $supportarray.= " selected";
            }

            $supportarray.= ">" . ucfirst($folder) . "</option>";
        }
    }

    closedir($dh);
}


echo $aInt->lang("general", "continvgeneration");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"continuousinvoicegeneration\"";

if ($CONFIG['ContinuousInvoiceGeneration'] == "on") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "continvgenerationinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "enablepdf");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"enablepdfinvoices\"";

if ($CONFIG['EnablePDFInvoices'] == "on") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "enablepdfinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "enablemasspay");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"enablemasspay\"";

if ($CONFIG['EnableMassPay'] == "on") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "enablemasspayinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "clientsgwchoose");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"allowcustomerchangeinvoicegateway\"";

if ($CONFIG['AllowCustomerChangeInvoiceGateway'] == "on") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "clientsgwchooseinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "groupsimilarlineitems");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"groupsimilarlineitems\"";

if ($CONFIG['GroupSimilarLineItems']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "groupsimilarlineitemsinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "disableautocreditapply");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"noautoapplycredit\"";

if ($CONFIG['NoAutoApplyCredit']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "disableautocreditapplyinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "cancelinvoiceoncancel");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"cancelinvoiceoncancel\"";

if ($CONFIG['CancelInvoiceOnCancellation']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "cancelinvoiceoncancelinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "sequentialpaidnumbering");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"sequentialinvoicenumbering\"";

if ($CONFIG['SequentialInvoiceNumbering'] == "on") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "sequentialpaidnumberinginfo");
echo "</td></label></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "sequentialpaidformat");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"sequentialinvoicenumberformat\" value=\"";
echo $CONFIG['SequentialInvoiceNumberFormat'];
echo "\" size=\"25\"> ";
echo $aInt->lang("general", "sequentialpaidformatinfo");
echo " RA2007-{NUMBER}</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "nextpaidnumber");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"sequentialinvoicenumbervalue\" value=\"";
echo $CONFIG['SequentialInvoiceNumberValue'];
echo "\" size=\"5\"> ";
echo $aInt->lang("general", "nextpaidnumberinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "latefeetype");
echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"latefeetype\" value=\"Percentage\"";

if ($CONFIG['LateFeeType'] == "Percentage") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("affiliates", "percentage");
echo "</label> <label><input type=\"radio\" name=\"latefeetype\" value=\"Fixed Amount\"";

if ($CONFIG['LateFeeType'] == "Fixed Amount") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("affiliates", "fixedamount");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "latefeeamount");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"invoicelatefeeamount\" value=\"";
echo $CONFIG['InvoiceLateFeeAmount'];
echo "\" size=\"8\"> ";
echo $aInt->lang("general", "latefeeamountinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "latefeemin");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"latefeeminimum\" value=\"";
echo $CONFIG['LateFeeMinimum'];
echo "\" size=\"8\"> ";
echo $aInt->lang("general", "latefeemininfo");
echo "</td></tr>
";
$acceptedcctypes = $CONFIG['AcceptedCardTypes'];
$acceptedcctypes = explode(",", $acceptedcctypes);
echo "<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "acceptedcardtype");
echo "</td><td class=\"fieldarea\"><table cellspacing=0 cellpadding=0><tr><td>";
echo "<s";
echo "elect name=\"acceptedcctypes[]\" size=\"5\" multiple>
<option";

if (in_array("Visa", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "visa");
echo "</option>
<option";

if (in_array("MasterCard", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "mastercard");
echo "</option>
<option";

if (in_array("Discover", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "discover");
echo "</option>
<option";

if (in_array("American Express", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "amex");
echo "</option>
<option";

if (in_array("JCB", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "jcb");
echo "</option>
<option";

if (in_array("EnRoute", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "enroute");
echo "</option>
<option";

if (in_array("Diners Club", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "diners");
echo "</option>
<option";

if (in_array("Solo", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "solo");
echo "</option>
<option";

if (in_array("Switch", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "switch");
echo "</option>
<option";

if (in_array("Maestro", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "maestro");
echo "</option>
<option";

if (in_array("Visa Debit", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "visadebit");
echo "</option>
<option";

if (in_array("Visa Electron", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "visaelectron");
echo "</option>
<option";

if (in_array("Laser", $acceptedcctypes)) {
    echo " selected";
}

echo ">";
echo $aInt->lang("general", "laser");
echo "</option>
</select></td><td style=\"padding-left:15px;\">";
echo $aInt->lang("general", "acceptedcardtypeinfo");
echo "</td></tr></table></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "issuestart");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"showccissuestart\"";

if ($CONFIG['ShowCCIssueStart'] == "on") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "issuestartinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "tcpdffont");
echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"tcpdffont\" value=\"helvetica\"";

if ($CONFIG['TCPDFFont'] == "helvetica") {
    echo " checked";
}

echo " /> Helvetica </label><label><input type=\"radio\" name=\"tcpdffont\" value=\"freesans\"";

if ($CONFIG['TCPDFFont'] == "freesans") {
    echo " checked";
}

echo " /> Freesans </label><label> <input type=\"radio\" name=\"tcpdffont\" value=\"custom\"";

if ($CONFIG['TCPDFFont'] != "freesans" && $CONFIG['TCPDFFont'] != "helvetica") {
    $customtcpdffont = true;
}


if ($customtcpdffont) {
    echo " checked";
}

echo " /> Custom</label> <input type=\"text\" name=\"tcpdffontcustom\" size=\"15\" value=\"";

if ($customtcpdffont) {
    echo $CONFIG['TCPDFFont'];
}

$query = "SELECT * FROM tblinvoices ORDER BY id DESC LIMIT 0,1";
$result = full_query_i($query);
$data = mysqli_fetch_array($result);


/// upto here 
if (!$data[0]) {
    echo "0";
} else {
    echo $data[0];
}

echo " (";
echo $aInt->lang("general", "blanknochange");
echo ")</td></tr>
</table>

  </div>
</div>
<!-- Credit -->
<div id=\"tab7box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "enabledisable");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"addfundsenabled\"";

if ($CONFIG['AddFundsEnabled']) {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "enablecredit");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "mincreditdeposit");
echo "</td><td class=\"fieldarea\">";
echo $CONFIG['CurrencySymbol'];
echo "<input type=\"text\" name=\"addfundsminimum\" size=\"10\" value=\"";
echo $CONFIG['AddFundsMinimum'];
echo "\"> ";
echo $aInt->lang("general", "mincreditdepositinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "maxcreditdeposit");
echo "</td><td class=\"fieldarea\">";
echo $CONFIG['CurrencySymbol'];
echo "<input type=\"text\" name=\"addfundsmaximum\" size=\"10\" value=\"";
echo $CONFIG['AddFundsMaximum'];
echo "\"> ";
echo $aInt->lang("general", "maxcreditdepositinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "maxbalance");
echo "</td><td class=\"fieldarea\">";
echo $CONFIG['CurrencySymbol'];
echo "<input type=\"text\" name=\"addfundsmaximumbalance\" size=\"10\" value=\"";
echo $CONFIG['AddFundsMaximumBalance'];
echo "\"> ";
echo $aInt->lang("general", "maxbalanceinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "addfundsrequireorder");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"addfundsrequireorder\"";

if ($CONFIG['AddFundsRequireOrder']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "addfundsrequireorderinfo");
echo "</label></td></tr>
</table>

  </div>
</div>
<!-- Affiliates -->
<div id=\"tab8box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "enabledisable");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"affiliateenabled\"";

if ($CONFIG['AffiliateEnabled'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "enableaff");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "affpercentage");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"affiliateearningpercent\" size=\"10\" value=\"";
echo $CONFIG['AffiliateEarningPercent'];
echo "\"> ";
echo $aInt->lang("general", "affpercentageinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "affbonus");
echo "</td><td class=\"fieldarea\">";
echo $CONFIG['CurrencySymbol'];
echo "<input type=\"text\" name=\"affiliatebonusdeposit\" size=\"10\" value=\"";
echo $CONFIG['AffiliateBonusDeposit'];
echo "\"> ";
echo $aInt->lang("general", "affbonusinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "affpayamount");
echo "</td><td class=\"fieldarea\">";
echo $CONFIG['CurrencySymbol'];
echo "<input type=\"text\" name=\"affiliatepayout\" size=\"10\" value=\"";
echo $CONFIG['AffiliatePayout'];
echo "\"> ";
echo $aInt->lang("general", "affpayamountinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "affcommdelay");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"affiliatesdelaycommission\" size=\"10\" value=\"";
echo $CONFIG['AffiliatesDelayCommission'];
echo "\"> ";
echo $aInt->lang("general", "affcommdelayinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "affdepartment");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"affiliatedepartment\">";
$dept_query = select_query_i("tblticketdepartments", "id,name", "", "order", "ASC");

while ($dept_result = mysqli_fetch_assoc($dept_query)) {
    echo "<option value=\"" . $dept_result['id'] . "\"";

    if ($CONFIG['AffiliateDepartment'] == $dept_result['id']) {
        echo " selected";
    }

    echo ">" . $dept_result['name'] . "</option>";
}

echo "</select> ";
echo $aInt->lang("general", "affdepartmentinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "afflinks");
echo "</td><td class=\"fieldarea\"><textarea name=\"affiliatelinks\" rows=10 style=\"width:100%\">";
echo $CONFIG['AffiliateLinks'];
echo "</textarea><br>";
echo $aInt->lang("general", "afflinksinfo");
echo "<br>";
echo $aInt->lang("general", "afflinksinfo2");
echo "</td></tr>
</table>

  </div>
</div>
<!-- Security -->
<div id=\"tab9box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "captcha");
echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"captchasetting\" value=\"on\"";

if ($CONFIG['CaptchaSetting'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "captchaalwayson");
echo "</label><br /><label><input type=\"radio\" name=\"captchasetting\" value=\"offloggedin\"";

if ($CONFIG['CaptchaSetting'] == "offloggedin") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "captchaoffloggedin");
echo "</label><br /><label><input type=\"radio\" name=\"captchasetting\" value=\"\"";

if ($CONFIG['CaptchaSetting'] == "") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "captchaoff");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "captchatype");
echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"captchatype\" value=\"\" onclick=\"$('.recaptchasetts').hide();\"";

if ($CONFIG['CaptchaType'] == "") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "captchadefault");
echo "</label><br /><label><input type=\"radio\" name=\"captchatype\" value=\"recaptcha\" onclick=\"$('.recaptchasetts').show();\"";

if ($CONFIG['CaptchaType'] == "recaptcha") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "captcharecaptcha");
echo "</label></td></tr>
<tr class=\"recaptchasetts\"";

if ($CONFIG['CaptchaType'] == "") {
    echo " style=\"display:none;\"";
}

echo "><td class=\"fieldlabel\">";
echo $aInt->lang("general", "recaptchaprivatekey");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"recaptchaprivatekey\" size=\"25\" value=\"";
echo $CONFIG['ReCAPTCHAPrivateKey'];
echo "\"> ";
echo $aInt->lang("general", "recaptchakeyinfo");
echo "</td></tr>
<tr class=\"recaptchasetts\"";

if ($CONFIG['CaptchaType'] == "") {
    echo " style=\"display:none;\"";
}

echo "><td class=\"fieldlabel\">";
echo $aInt->lang("general", "recaptchapublickey");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"recaptchapublickey\" size=\"25\" value=\"";
echo $CONFIG['ReCAPTCHAPublicKey'];
echo "\"></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "reqpassstrength");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"requiredpwstrength\" size=\"5\" value=\"";
echo $CONFIG['RequiredPWStrength'];
echo "\"> ";
echo $aInt->lang("general", "reqpassstrengthinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "failedbantime");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"invalidloginsbanlength\" value=\"";
echo $CONFIG['InvalidLoginBanLength'];
echo "\" size=\"5\"> ";
echo $aInt->lang("general", "banminutes");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "whitelistedips");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"whitelistedips[]\" id=\"whitelistedips\" size=\"3\" style=\"min-width:200px;\" multiple>";
$whitelistedips = unserialize($CONFIG['WhitelistedIPs']);
foreach ($whitelistedips as $whitelist) {
    echo "<option value=" . $whitelist['ip'] . ">" . $whitelist['ip'] . " - " . $whitelist['note'] . "</option>";
}

echo "</select> ";
echo $aInt->lang("general", "whitelistedipsinfo");
echo "<br /><a href=\"javascript:;\" onClick=\"showDialog('addwhitelistip')\"><img src=\"images/icons/add.png\" align=\"absmiddle\" border=\"0\" /> ";
echo $aInt->lang("general", "addip");
echo "</a> <a href=\"#\" id=\"removewhitelistedip\"><img src=\"images/icons/delete.png\" align=\"absmiddle\" border=\"0\" /> ";
echo $aInt->lang("general", "removeselected");
echo "</a></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "adminforcessl");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"adminforcessl\"";

if ($CONFIG['AdminForceSSL']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "adminforcesslinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "disableadminpwreset");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"disableadminpwreset\"";

if ($CONFIG['DisableAdminPWReset']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "disableadminpwresetinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "disableccstore");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"ccneverstore\"";

if ($CONFIG['CCNeverStore']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "disableccstoreinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "allowccdelete");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"ccallowcustomerdelete\"";

if ($CONFIG['CCAllowCustomerDelete']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "allowccdeleteinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";

echo $aInt->lang("general", "disablesessionip");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"disablesessionipcheck\"";

if ($CONFIG['DisableSessionIPCheck']) {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "disablesessionipinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "apirestriction");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"apiallowedips[]\" id=\"apiallowedips\" size=\"3\" style=\"min-width:200px;\" multiple>";
$whitelistedips = unserialize($CONFIG['APIAllowedIPs']);
foreach ($whitelistedips as $whitelist) {
    echo "<option value=" . $whitelist['ip'] . ">" . $whitelist['ip'] . " - " . $whitelist['note'] . "</option>";
}

echo "</select> ";
echo $aInt->lang("general", "apirestrictioninfo");
echo "<br /><a href=\"javascript:;\" onClick=\"showDialog('addapiip')\"><img src=\"images/icons/add.png\" align=\"absmiddle\" border=\"0\" /> ";
echo $aInt->lang("general", "addip");
echo "</a> <a href=\"#\" id=\"removeapiip\"><img src=\"images/icons/delete.png\" align=\"absmiddle\" border=\"0\" /> ";
echo $aInt->lang("general", "removeselected");
echo "</a></td></tr>
";
$token_manager = &getTokenManager();

echo $token_manager->generateAdminConfigurationHTMLRows($aInt);
echo "
</table>

  </div>
</div>
<!-- Social -->
<div id=\"tab10box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "twitterint");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"twitterusername\" size=\"20\" value=\"";
echo $CONFIG['TwitterUsername'];
echo "\" /> ";
echo $aInt->lang("general", "twitterintinfo");
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "twitterannouncementstweet");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"announcementstweet\"";

if ($CONFIG['AnnouncementsTweet']) {
    echo " checked";
}

echo " /> ";
echo $aInt->lang("general", "twitterannouncementstweetinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "facebookannouncementsrecommend");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"announcementsfbrecommend\"";

if ($CONFIG['AnnouncementsFBRecommend']) {
    echo " checked";
}

echo " /> ";
echo $aInt->lang("general", "facebookannouncementsrecommendinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "facebookannouncementscomments");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"announcementsfbcomments\"";

if ($CONFIG['AnnouncementsFBComments']) {
    echo " checked";
}

echo " /> ";
echo $aInt->lang("general", "facebookannouncementscommentsinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "googleplus1");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"googleplus1\"";

if ($CONFIG['GooglePlus1']) {
    echo " checked";
}

echo " /> ";
echo $aInt->lang("general", "googleplus1info");
echo "</label></td></tr>
</table>

  </div>
</div>
<!-- Other -->
<div id=\"tab11box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "adminclientformat");
echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"clientdisplayformat\" value=\"1\"";

if ($CONFIG['ClientDisplayFormat'] == "1") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "showfirstlast");
echo "</label><br /><label><input type=\"radio\" name=\"clientdisplayformat\" value=\"2\"";

if ($CONFIG['ClientDisplayFormat'] == "2") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "showcompanyfirstlast");
echo "</label><br /><label><input type=\"radio\" name=\"clientdisplayformat\" value=\"3\"";

if ($CONFIG['ClientDisplayFormat'] == "3") {
    echo " checked";
}

echo "> ";
echo $aInt->lang("general", "showfullcompany");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "clientdropdown");
echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"clientdropdownformat\" value=\"1\"";

if ($CONFIG['ClientDropdownFormat'] == "1") {
    echo " CHECKED";
}

echo "> [";
echo $aInt->lang("fields", "firstname");
echo "] [";
echo $aInt->lang("fields", "lastname");
echo "] ([";
echo $aInt->lang("fields", "companyname");
echo "])</label><br /><label><input type=\"radio\" name=\"clientdropdownformat\" value=\"2\"";

if ($CONFIG['ClientDropdownFormat'] == "2") {
    echo " CHECKED";
}

echo "> [";
echo $aInt->lang("fields", "companyname");
echo "] - [";
echo $aInt->lang("fields", "firstname");
echo "] [";
echo $aInt->lang("fields", "lastname");
echo "]</label><br /><label><input type=\"radio\" name=\"clientdropdownformat\" value=\"3\"";

if ($CONFIG['ClientDropdownFormat'] == "3") {
    echo " CHECKED";
}

echo "> #[";
echo $aInt->lang("fields", "clientid");
echo "] - [";
echo $aInt->lang("fields", "firstname");
echo "] [";
echo $aInt->lang("fields", "lastname");
echo "] - [";
echo $aInt->lang("fields", "companyname");
echo "]</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "disabledropdowninfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "defaulttoclientarea");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"defaulttoclientarea\"";

if ($CONFIG['DefaultToClientArea']) {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "defaulttoclientareainfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "allowclientreg");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"allowclientregister\"";

if ($CONFIG['AllowClientRegister'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "allowclientreginfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "profileoptionalfields");
echo "</td><td class=\"fieldarea\">";
echo $aInt->lang("general", "profileoptionalfieldsinfo");
echo ":<br />
<table width=\"100%\"><tr>
";
$ClientsProfileOptionalFields = explode(",", $CONFIG['ClientsProfileOptionalFields']);
$updatefieldsarray = array("firstname" => $aInt->lang("fields", "firstname"), "lastname" => $aInt->lang("fields", "lastname"), "address1" => $aInt->lang("fields", "address1"), "city" => $aInt->lang("fields", "city"), "state" => $aInt->lang("fields", "state"), "postcode" => $aInt->lang("fields", "postcode"), "phonenumber" => $aInt->lang("fields", "phonenumber"));
$fieldcount = 0;
foreach ($updatefieldsarray as $field => $displayname) {
    echo "<td width=\"25%\"><label><input type=\"checkbox\" name=\"clientsprofoptional[]\" value=\"" . $field . "\"";

    if (in_array($field, $ClientsProfileOptionalFields)) {
        echo " checked";
    }

    echo " /> " . $displayname . "</label></td>";
    ++$fieldcount;

    if ($fieldcount == 4) {
        echo "</tr><tr>";
        $fieldcount = 0;
        continue;
    }
}

echo "</tr></table></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "lockedfields");
echo "</td><td class=\"fieldarea\">";
echo $aInt->lang("general", "lockedfieldsinfo");
echo ":<br />
<table width=\"100%\"><tr>
";
$ClientsProfileUneditableFields = explode(",", $CONFIG['ClientsProfileUneditableFields']);
$updatefieldsarray = array("firstname" => $aInt->lang("fields", "firstname"), "lastname" => $aInt->lang("fields", "lastname"), "companyname" => $aInt->lang("fields", "companyname"), "email" => $aInt->lang("fields", "email"), "address1" => $aInt->lang("fields", "address1"), "address2" => $aInt->lang("fields", "address2"), "city" => $aInt->lang("fields", "city"), "state" => $aInt->lang("fields", "state"), "postcode" => $aInt->lang("fields", "postcode"), "country" => $aInt->lang("fields", "country"), "phonenumber" => $aInt->lang("fields", "phonenumber"));
$fieldcount = 0;
foreach ($updatefieldsarray as $field => $displayname) {
    echo "<td width=\"25%\"><label><input type=\"checkbox\" name=\"clientsprofuneditable[]\" value=\"" . $field . "\"";

    if (in_array($field, $ClientsProfileUneditableFields)) {
        echo " checked";
    }

    echo " /> " . $displayname . "</label></td>";
    ++$fieldcount;

    if ($fieldcount == 4) {
        echo "</tr><tr>";
        $fieldcount = 0;
        continue;
    }
}

echo "</tr></table></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "clientdetailsnotify");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"sendemailnotificationonuserdetailschange\"";

if ($CONFIG['SendEmailNotificationonUserDetailsChange'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "clientdetailsnotifyinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "marketingemailoptout");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"allowclientsemailoptout\"";

if ($CONFIG['AllowClientsEmailOptOut'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "marketingemailoptoutinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "showcancellink");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"showcancel\"";

if ($CONFIG['ShowCancellationButton'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "showcancellinkinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "creditdowngrade");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"creditondowngrade\"";

if ($CONFIG['CreditOnDowngrade'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "creditdowngradeinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "monthlyaffreport");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"affreport\"";

if ($CONFIG['SendAffiliateReportMonthly'] == "on") {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "monthlyaffreportinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "bannedsubdomainprefixes");
echo "</td><td class=\"fieldarea\"><textarea name=\"bannedsubdomainprefixes\" cols=\"100\" rows=\"2\">";
echo $CONFIG['BannedSubdomainPrefixes'];
echo "</textarea></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "displayerrors");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"displayerrors\"";

if ($CONFIG['DisplayErrors']) {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "displayerrorsinfo");
echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("general", "sqldebugmode");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"sqlerrorreporting\"";

if ($CONFIG['SQLErrorReporting']) {
    echo " CHECKED";
}

echo "> ";
echo $aInt->lang("general", "sqldebugmodeinfo");
echo "</label></td></tr>

</table>

  </div>
</div>

<p align=\"center\"><input type=\"submit\" value=\"";
echo $aInt->lang("global", "savechanges");
echo "\" class=\"button\"></p>

<input type=\"hidden\" name=\"tab\" id=\"tab\" value=\"";
echo $_REQUEST['tab'];
echo "\" />

</form>

";
$content = ob_get_contents();
ob_end_clean();


$aInt->assign("PHP_SELF", $PHP_SELF);
$aInt->assign("acceptedcctypes", $acceptedcctypes);
$aInt->assign("supportarray", $supportarray);
$aInt->assign("ordertemplatearray", $ordertemplatearray);
$aInt->assign("deptarray", $deptarray);
$aInt->assign("templatearray", $templatearray);
$aInt->assign("languagearray", $languagearray);
$aInt->assign("countryarray", $countryarray);
$aInt->assign("CONFIG", $CONFIG);
$aInt->template = "configgeneral";
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>
