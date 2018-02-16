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
            "SMTPPort" => $smtpport, "SMTPSSL" => $smtpssl, "EmailGlobalHeader" => $emailglobalheader, "EmailGlobalFooter" => $emailglobalfooter, "BCCMessages" => $bccmessages, "ContactFormTo" => $contactformto, "ShowClientOnlyDepts" => $showclientonlydepts, "TicketFeedback" => $ticketfeedback, "TicketMask" => $ticketmask, "AttachmentThumbnails" => $attachmentthumbnails, "DownloadsIncludeProductLinked" => $dlinclproductdl, "CancelInvoiceOnCancellation" => $cancelinvoiceoncancel, "TCPDFFont" => $tcpdffont, "AddFundsEnabled" => $addfundsenabled, "AddFundsMinimum" => $addfundsminimum, "AddFundsMaximum" => $addfundsmaximum, "AddFundsMaximumBalance" => $addfundsmaximumbalance, "LateFeeMinimum" => $latefeeminimum, "AffiliateEnabled" => $affiliateenabled, "AffiliateEarningPercent" => $affiliateearningpercent, "AffiliateDepartment" => $affiliatedepartment, "AffiliateBonusDeposit" => $affiliatebonusdeposit, "AffiliatePayout" => $affiliatepayout, "AffiliatesDelayCommission" => $affiliatesdelaycommission, "AffiliateLinks" => $affiliatelinks, "CaptchaType" => $captchatype, "ReCAPTCHAPrivateKey" => $recaptchaprivatekey, "ReCAPTCHAPublicKey" => $recaptchapublickey, "AdminForceSSL" => $adminforcessl, "DisableAdminPWReset" => $disableadminpwreset, "CCNeverStore" => $ccneverstore, "TwitterUsername" => $twitterusername, "AnnouncementsTweet" => $announcementstweet, "AnnouncementsFBRecommend" => $announcementsfbrecommend, "AnnouncementsFBComments" => $announcementsfbcomments, "GooglePlus1" => $googleplus1, "ClientsProfileOptionalFields" => $clientsprofoptional, "ClientsProfileUneditableFields" => $clientsprofuneditable, "DefaultToClientArea" => $defaulttoclientarea, "AllowClientsEmailOptOut" => $allowclientsemailoptout, "BannedSubdomainPrefixes" => $bannedsubdomainprefixes, "DisplayErrors" => $displayerrors, "SQLErrorReporting" => $sqlerrorreporting, "gst" => $gst,
            "invphone" => $invphone,
            "invfax" => $invfax,
            "invwebsite" => $invwebsite,
            "invaccount" => $invaccount,
            "invname" => $invname,
            "invaddress" => $invaddress,
            "invcompany" => $invcompany,
            "invpobox" => $invpobox,
            "invcity" => $invcity,
            "invpostcode" => $invpostcode,
            "invcountry" => $invcountry
        );
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
    redir("success=true");
    exit();
} // end of action==save
releaseSession();

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


if ($success) {
    infoBox($aInt->lang("general", "changesuccess"), $aInt->lang("general", "changesuccessinfo"));
    $infobox;
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
            $templatearray .= "<option value=\"" . $folder . "\"";

            if ($folder == $ra->get_sys_tpl_name()) {
                $templatearray .= " selected";
            }

            $templatearray .= ">" . ucfirst($folder) . "</option>";
        }
    }

    closedir($dh);
}

include "../includes/countries.php";
$countryarray = getCountriesDropDown($CONFIG['DefaultCountry'], "defaultcountry");
$languagearray = "";
$language = $ra->validateLanguage($ra->get_config("Language"));
foreach ($ra->getValidLanguages() as $lang) {
    $languagearray .= "<option value=\"" . $lang . "\"";

    if ($lang == $language) {
        $languagearray .= " selected=\"selected\"";
    }

    $languagearray .= ">" . ucfirst($lang) . "</option>";
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

    $ordertemplatearray .= "<div style=\"float:left;padding:10px;text-align:center;\"><label><img src=\"" . $thumbnail . "\" width=\"165\" height=\"90\" style=\"border:5px solid #fff;\" /><br /><input type=\"radio\" name=\"orderformtemplate\" value=\"" . $template . "\"";

    if ($template == $CONFIG['OrderFormTemplate']) {
        $ordertemplatearray .= " checked";
    }

    $ordertemplatearray .= "> " . ucfirst($template) . "</label></div>";
}

$currency = getCurrency();

$countrys = getCountriesDropDown($CONFIG['RegistrarAdminCountry'], "domcountry");

$dept_query = select_query_i("tblticketdepartments", "id, name", "");
$deptarray = "";
while ($dept_result = mysqli_fetch_assoc($dept_query)) {
    $selected = "";

    if ($CONFIG['ContactFormDept'] == $dept_result['id']) {
        $selected = " selected";
    }

    $deptarray .= "<option value=\"" . $dept_result['id'] . "\"" . $selected . ">" . $dept_result['name'] . "</option>";
}

$supportfolder = ROOTDIR . "/modules/support/";
$supportarray = "";
if (is_dir($supportfolder)) {
    $dh = opendir($supportfolder);

    while (false !== $folder = readdir($dh)) {
        if ((is_dir($supportfolder . $folder) && $folder != ".") && $folder != "..") {
            $supportarray .= "<option value=\"" . $folder . "\"";

            if ($folder == $CONFIG['SupportModule']) {
                $supportarray .= " selected";
            }

            $supportarray .= ">" . ucfirst($folder) . "</option>";
        }
    }

    closedir($dh);
}

$aInt->assign("infobox", $infobox);
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
$aInt->jquerycode = $jquerycode . $menuselect;
$aInt->jscode = $jscode;
$aInt->display();
?>
