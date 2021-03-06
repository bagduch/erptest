<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Mass Mail", false);
$aInt->title = $aInt->lang("sendmessage", "sendmessagetitle");
$aInt->sidebar = "clients";
$aInt->icon = "massmail";
ob_start();
$massmailquery = $query = $safeStoredQuery = $queryMadeFromEmailType = $token = null;
$userInput_massmailquery = $ra->get_req_var("massmailquery");
$queryMgr = new RA_Token_Query("Admin.Massmail");
if (!$queryMgr->isValidTokenFormat($userInput_massmailquery)) {
    $userInput_massmailquery = null;
}
if ($action == "preview") {
    check_token("RA.admin.default");
    $email_preview = true;
    delete_query("ra_templates_mail", array("name" => "Mass Mail Template"));
    insert_query("ra_templates_mail", array("type" => $type, "name" => "Mass Mail Template", "subject" => html_entity_decode($subject), "message" => html_entity_decode($messagetxt), "fromname" => "", "fromemail" => "", "copyto" => ""));

    if ($massmail && $safeStoredQuery = $queryMgr->getQuery($queryMgr->getTokenValue())) {
        $massmailquery = $safeStoredQuery;
        $result = full_query_i($massmailquery);
        $totalemails = mysqli_num_rows($result);
        $totalsteps = ceil($totalemails / $massmailamount);
        $esttotaltime = ($totalsteps - ($step + 1)) * $massmailinterval;
        $result = full_query_i($massmailquery . " LIMIT 0,1");

        while ($data = mysqli_fetch_array($result)) {
            sendMessage("Mass Mail Template", $data['id'], "", true, $_SESSION['massmail']['attachments']);
        }
    } else {
        if ($multiple) {
            sendMessage("Mass Mail Template", $selectedclients[0], "", true, $_SESSION['massmail']['attachments']);
        } else {
            sendMessage("Mass Mail Template", $id, "", true, $_SESSION['massmail']['attachments']);
        }
    }

    exit();
}
if ($action == "send") {
    check_token("RA.admin.default");
    if (!$step) {
        if (!$message) {
            infoBox($aInt->lang("sendmessage", "validationerrortitle"), $aInt->lang("sendmessage", "validationerrormsg"));
        }
        if (!$subject) {
            infoBox($aInt->lang("sendmessage", "validationerrortitle"), $aInt->lang("sendmessage", "validationerrorsub"));
        }
        if (!$fromemail) {
            infoBox($aInt->lang("sendmessage", "validationerrortitle"), $aInt->lang("sendmessage", "validationerroremail"));
        }
        if (!$fromname) {
            infoBox($aInt->lang("sendmessage", "validationerrortitle"), $aInt->lang("sendmessage", "validationerrorname"));
        }
    }
    if ($infobox) {
        $showform = true;
    } else {
        $done = false;

        if ($type == "addon") {
            $type = "product";
        }


        if ($save == "on") {
            insert_query("ra_templates_mail", array("type" => $type, "name" => $savename, "subject" => html_entity_decode($subject), "message" => html_entity_decode($message), "fromname" => html_entity_decode($fromname), "fromemail" => $fromemail, "copyto" => $cc, "custom" => "1"));
            echo "<p>" . $aInt->lang("sendmessage", "msgsavedsuccess") . "</p>";
        }


        if (!$step) {
            delete_query("ra_templates_mail", array("name" => "Mass Mail Template"));
            insert_query("ra_templates_mail", array("type" => $type, "name" => "Mass Mail Template", "subject" => html_entity_decode($subject), "message" => html_entity_decode($message), "fromname" => html_entity_decode($fromname), "fromemail" => $fromemail, "copyto" => $cc));
            $_SESSION['massmail']['massmailamount'] = $massmailamount;
            $_SESSION['massmail']['massmailinterval'] = $massmailinterval;
            $_SESSION['massmail']['attachments'] = array();

            if (is_array($_FILES['attachments'])) {
                foreach ($_FILES['attachments']['name'] as $num => $displayname) {

                    if (empty($_FILES['attachments']['name']) || empty($_FILES['attachments']['name'][$num])) {
                        continue;
                    }


                    if (!isFileNameSafe($_FILES['attachments']['name'][$num])) {
                        $aInt->gracefulExit("Invalid upload filename.  Valid filenames contain only alpha-numeric, dot, hyphen and underscore characters.");
                        exit();
                    }

                    $filename = preg_replace("/[^a-zA-Z0-9-_. ]/", "", $displayname);

                    if ($filename) {
                        mt_srand(time());
                        $rand = mt_rand(100000, 999999);
                        $filename = "attach" . $rand . "_" . $filename;
                        move_uploaded_file($_FILES['attachments']['tmp_name'][$num], $attachments_dir . $filename);
                        $_SESSION['massmail']['attachments'][] = $filename;
                        continue;
                    }
                }
            }

            $step = 0;
        }

        $mail_attachments = array();

        if (isset($_SESSION['massmail']['attachments'])) {
            foreach ($_SESSION['massmail']['attachments'] as $filename) {
                $mail_attachments[$attachments_dir . $filename] = $filename;
            }
        }


        if ($massmail && $safeStoredQuery = $queryMgr->getQuery($queryMgr->getTokenValue())) {
            $massmailquery = $safeStoredQuery;

            if ($emailoptout || RA_Session::get("massmailemailoptout")) {
                RA_Session::set("massmailemailoptout", true);
                $massmailquery .= " AND ra_user.emailoptout = '0'";
            }

            $sentids = $_SESSION['massmail']['sentids'];
            $massmailamount = (int) $_SESSION['massmail']['massmailamount'];
            $massmailinterval = (int) $_SESSION['massmail']['massmailinterval'];

            if (!$massmailamount) {
                $massmailamount = 25;
            }


            if (!$massmailinterval) {
                $massmailinterval = 30;
            }

            $result = full_query_i($massmailquery);
            $totalemails = mysqli_num_rows($result);
            $totalsteps = ceil($totalemails / $massmailamount);
            $esttotaltime = ($totalsteps - ($step + 1)) * $massmailinterval;
            infoBox($aInt->lang("sendmessage", "massmailqueue"), $totalemails . $aInt->lang("sendmessage", "massmailspart1") . ($step + 1) . $aInt->lang("sendmessage", "massmailspart2") . $totalsteps . $aInt->lang("sendmessage", "massmailspart3") . $esttotaltime . $aInt->lang("sendmessage", "massmailspart4"));
            echo $infobox;
            $result = full_query_i($massmailquery . " LIMIT " . (int) $step * $massmailamount . "," . (int) $massmailamount);
            ob_start();

            while ($data = mysqli_fetch_array($result)) {
                if ($sendforeach || (!$sendforeach && !in_array($data['userid'], $sentids))) {
                    sendMessage("Mass Mail Template", $data['id'], "", true, $mail_attachments);
                    $sentids[] = $data['userid'];
                }

                echo "<li>" . $aInt->lang("sendmessage", "skippedduplicate") . $data['userid'] . "<br>";
            }

            $_SESSION['massmail']['sentids'] = $sentids;
            $content = ob_get_contents();
            ob_end_clean();
            echo "<ul>" . str_replace(array("<p>", "</p>"), array("<li>", "</li>"), $content) . "</ul>";
            $totalsent = $step * $massmailamount + $massmailamount;

            if ($totalemails <= $totalsent) {
                $done = true;
            } else {
                $massmaillink = "sendmessage.php?action=send&sendforeach=" . $sendforeach . "&massmail=1&step=" . ($step + 1) . generate_token("link");
                echo "<p><a href=\"" . $massmaillink . "\">" . $aInt->lang("sendmessage", "forcenextbatch") . ("</a></p><meta http-equiv=\"refresh\" content=\"30;url=" . $massmaillink . "\">");
            }
        } else {
            if ($multiple) {
                foreach ($selectedclients as $selectedclient) {
                    $skipemail = false;

                    if ($emailoptout) {
                        if ($type == "general") {
                            $skipemail = get_query_val("ra_user", "emailoptout", array("id" => $selectedclient));
                        } elseif ($type == "product") {
                            $skipemail = get_query_val("tblcustomerservices", "emailoptout", array("tblcustomerservices.id" => $selectedclient), "", "", "", "ra_user ON ra_user.id=tblcustomerservices.userid");
                        } elseif ($type == "domain") {
                            $skipemail = get_query_val("tbldomains", "emailoptout", array("tbldomains.id" => $selectedclient), "", "", "", "ra_user ON ra_user.id=tbldomains.userid");
                        } elseif ($type == "affiliate") {
                            $skipemail = get_query_val("ra_partners", "emailoptout", array("ra_partners.id" => $selectedclient), "", "", "", "ra_user ON ra_user.id=ra_partners.clientid");
                        }
                    }


                    if ($skipemail) {
                        echo "<p>Email Skipped for ID " . $selectedclient . " due to Marketing Email Opt-Out</p>";
                    } else {
                        sendMessage("Mass Mail Template", $selectedclient, "", true, $mail_attachments);
                    }

                    $done = true;
                }
            } else {
                sendMessage("Mass Mail Template", $id, "", true, $mail_attachments);
                $done = true;
            }
        }


        if ($done) {
            echo "<p><b>" . $aInt->lang("sendmessage", "sendingcompleted") . "</b></p>";
            delete_query("ra_templates_mail", array("name" => "Mass Mail Template"));
            foreach ($_SESSION['massmail']['attachments'] as $filename) {
                deleteFile($attachments_dir, $filename);
            }

            unset($_SESSION['massmail']);
        }
    }
} else {
    $showform = true;
}
if ($showform) {
    if (!$infobox) {
        unset($_SESSION['massmail']);
    }

    $todata = array();
    $query = "";

    if (!$type) {
        $type = "general";
    }
    $queryMadeFromEmailType = "";

    if ($type == "massmail") {
        $clientstatus = db_build_in_array($clientstatus);
        $clientgroup = db_build_in_array($clientgroup);
        $clientlanguage = db_build_in_array($clientlanguage, true);
        $productids = db_build_in_array($productids);
        $productstatus = db_build_in_array($productstatus);
        $server = db_build_in_array($server);
        $addonids = db_build_in_array($addonids);
        $addonstatus = db_build_in_array($addonstatus);
        $servicestatus = db_build_in_array($servicestatus);

        if ($emailtype == "General") {
            $type = "general";
            $query = "SELECT id,id AS userid,ra_user.firstname,ra_user.lastname,ra_user.email FROM ra_user WHERE id!=''";

            if ($clientstatus) {
                $query .= " AND ra_user.status IN (" . $clientstatus . ")";
            }

            if ($clientgroup) {
                $query .= " AND ra_user.groupid IN (" . $clientgroup . ")";
            }

            if ($clientlanguage) {
                $query .= " AND ra_user.language IN (" . $clientlanguage . ")";
            }

            if (is_array($customfield)) {
                foreach ($customfield as $k => $v) {
                    if ($v) {
                        if ($v == "cfon") {
                            $v = "on";
                        }
                        if ($v == "cfoff") {
                            $query .= " AND ((SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE fieldid='" . db_escape_string($k) . "' AND relid=ra_user.id LIMIT 1)='' OR (SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE fieldid='" . db_escape_string($k) . "' AND relid=ra_user.id LIMIT 1) IS NULL)";
                            continue;
                        }
                        $query .= " AND (SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE fieldid='" . db_escape_string($k) . "' AND relid=ra_user.id LIMIT 1)='" . db_escape_string($v) . "'";
                        continue;
                    }
                }
            }
        } elseif ($emailtype == "Product/Service") {
            $type = "product";
            $query = "SELECT tblcustomerservices.id,tblcustomerservices.userid,tblcustomerservices.domain,ra_user.firstname,ra_user.lastname,ra_user.email FROM tblcustomerservices INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid WHERE tblcustomerservices.id!=''";

            if ($productids) {
                $query .= " AND ra_catalog.id IN (" . $productids . ")";
            }

            if ($productstatus) {
                $query .= " AND tblcustomerservices.servicestatus IN (" . $productstatus . ")";
            }

            if ($server) {
                $query .= " AND tblcustomerservices.server IN (" . $server . ")";
            }

            if ($clientstatus) {
                $query .= " AND ra_user.status IN (" . $clientstatus . ")";
            }


            if ($clientgroup) {
                $query .= " AND ra_user.groupid IN (" . $clientgroup . ")";
            }


            if ($clientlanguage) {
                $query .= " AND ra_user.language IN (" . $clientlanguage . ")";
            }


            if (is_array($customfield)) {
                foreach ($customfield as $k => $v) {

                    if ($v) {
                        $query .= " AND (SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE fieldid='" . db_escape_string($k) . "' AND relid=ra_user.id LIMIT 1)='" . db_escape_string($v) . "'";
                        continue;
                    }
                }
            }
        } elseif ($emailtype == "Addon") {
            $type = "addon";
            $query = "SELECT tblcustomerservices.id,tblcustomerservices.userid,tblcustomerservices.domain,ra_user.firstname,ra_user.lastname,ra_user.email FROM tblcustomerservices INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid INNER JOIN ra_catalog_user_sales_addons ON ra_catalog_user_sales_addons.hostingid = tblcustomerservices.id WHERE ra_catalog_user_sales_addons.id!=''";

            if ($addonids) {
                $query .= " AND ra_catalog_user_sales_addons.addonid IN (" . $addonids . ")";
            }


            if ($addonstatus) {
                $query .= " AND ra_catalog_user_sales_addons.status IN (" . $addonstatus . ")";
            }


            if ($clientstatus) {
                $query .= " AND ra_user.status IN (" . $clientstatus . ")";
            }


            if ($clientgroup) {
                $query .= " AND ra_user.groupid IN (" . $clientgroup . ")";
            }


            if ($clientlanguage) {
                $query .= " AND ra_user.language IN (" . $clientlanguage . ")";
            }


            if (is_array($customfield)) {
                foreach ($customfield as $k => $v) {

                    if ($v) {
                        $query .= " AND (SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE fieldid='" . db_escape_string($k) . "' AND relid=ra_user.id LIMIT 1)='" . db_escape_string($v) . "'";
                        continue;
                    }
                }
            }
        } elseif ($emailtype == "Domain") {
            $type = "domain";
            $query = "SELECT tbldomains.id,tbldomains.userid,tbldomains.domain,ra_user.firstname,ra_user.lastname,ra_user.email FROM tbldomains INNER JOIN ra_user ON ra_user.id=tbldomains.userid WHERE tbldomains.id!=''";

            if ($servicestatus) {
                $query .= " AND tbldomains.status IN (" . $servicestatus . ")";
            }


            if ($clientstatus) {
                $query .= " AND ra_user.status IN (" . $clientstatus . ")";
            }


            if ($clientgroup) {
                $query .= " AND ra_user.groupid IN (" . $clientgroup . ")";
            }


            if ($clientlanguage) {
                $query .= " AND ra_user.language IN (" . $clientlanguage . ")";
            }


            if (is_array($customfield)) {
                foreach ($customfield as $k => $v) {

                    if ($v) {
                        $query .= " AND (SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE fieldid='" . db_escape_string($k) . "' AND relid=ra_user.id LIMIT 1)='" . db_escape_string($v) . "'";
                        continue;
                    }
                }
            }
        }

        $queryMadeFromEmailType = $query;
    }


    if ($queryMadeFromEmailType || $userInput_massmailquery) {
        if ($queryMadeFromEmailType) {
            $massmailquery = $queryMadeFromEmailType;
        } else {
            if (!$queryMadeFromEmailType && $queryMgr->isValidTokenFormat($userInput_massmailquery)) {
                $massmailquery = $queryMgr->getQuery($userInput_massmailquery);
            } else {
                $massmailquery = "";
            }
        }

        $useridsdone = array();
        $result = full_query_i($massmailquery);

        while ($data = mysqli_fetch_array($result)) {
            if ($sendforeach || (!$sendforeach && !in_array($data['userid'], $useridsdone))) {
                $temptodata = "" . $data['firstname'] . " " . $data['lastname'];

                if ($data['domain']) {
                    $temptodata .= " - " . $data['domain'];
                }

                $temptodata .= " &lt;" . $data['email'] . "&gt;";
                $todata[] = $temptodata;
                $useridsdone[] = $data['userid'];
            }
        }
    } else {
        if ($multiple) {
            if ($type == "general") {
                foreach ($selectedclients as $id) {
                    $result = select_query_i("ra_user", "", array("id" => $id));
                    $data = mysqli_fetch_array($result);
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " &lt;" . $data['email'] . "&gt;";
                }
            } elseif ($type == "product") {
                foreach ($selectedclients as $id) {
                    $result = select_query_i("tblcustomerservices", "ra_user.firstname,ra_user.lastname,ra_user.email,tblcustomerservices.domain", array("tblcustomerservices.id" => $id), "", "", "", "ra_user ON ra_user.id=tblcustomerservices.userid");
                    $data = mysqli_fetch_array($result);
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " - " . $data['domain'] . " &lt;" . $data['email'] . "&gt;";
                }
            } elseif ($type == "domain") {
                foreach ($selectedclients as $id) {
                    $result = select_query_i("tbldomains", "ra_user.firstname,ra_user.lastname,ra_user.email,tbldomains.domain", array("tbldomains.id" => $id), "", "", "", "ra_user ON ra_user.id=tbldomains.userid");
                    $data = mysqli_fetch_array($result);
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " - " . $data['domain'] . " &lt;" . $data['email'] . "&gt;";
                }
            } elseif ($type == "affiliate") {
                foreach ($selectedclients as $id) {
                    $result = select_query_i("ra_partners", "ra_user.firstname,ra_user.lastname,ra_user.email", array("ra_partners.id" => $id), "", "", "", "ra_user ON ra_user.id=ra_partners.clientid");
                    $data = mysqli_fetch_array($result);
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " - " . $data['domain'] . " &lt;" . $data['email'] . "&gt;";
                }
            }
        } else {
            if ($resend) {
                $result = select_query_i("ra_user_mail", "", array("id" => $emailid));
                $data = mysqli_fetch_array($result);
                $id = $data['userid'];
                $subject = $data['subject'];
                $message = $data['message'];
                $message = str_replace("<p><a href=\"" . $CONFIG['Domain'] . "\" target=\"_blank\"><img src=\"" . $CONFIG['LogoURL'] . "\" alt=\"" . $CONFIG['CompanyName'] . "\" border=\"0\"></a></p>", "", $message);
                $message = str_replace("<p><a href=\"" . $CONFIG['Domain'] . "\" target=\"_blank\"><img src=\"" . $CONFIG['LogoURL'] . "\" alt=\"" . $CONFIG['CompanyName'] . "\" border=\"0\" /></a></p>", "", $message);
                $message = str_replace(html_entity_decode($CONFIG['EmailGlobalHeader']), "", $message);
                $message = str_replace(html_entity_decode($CONFIG['EmailGlobalFooter']), "", $message);
                $styleend = strpos($message, "</style>") + 8;
                $message = trim(substr($message, $styleend));
                $type = "general";
            }


            if ($type == "general") {
                $result = select_query_i("ra_user", "", array("id" => $id));
                $data = mysqli_fetch_array($result);

                if ($data['email']) {
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " &lt;" . $data['email'] . "&gt;";
                }
            } elseif ($type == "product") {
                $query = "SELECT ra_user.id,ra_user.firstname,ra_user.lastname,ra_user.email,tblcustomerservices.domain FROM tblcustomerservices INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE tblcustomerservices.id='" . mysqli_real_escape_string($id) . "'";
                $result = full_query_i($query);
                $data = mysqli_fetch_array($result);

                if ($data['email']) {
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " - " . $data['domain'] . " &lt;" . $data['email'] . "&gt;";
                }
            } elseif ($type == "domain") {
                $query = "SELECT ra_user.id,ra_user.firstname,ra_user.lastname,ra_user.email,tbldomains.domain FROM tbldomains INNER JOIN ra_user ON ra_user.id=tbldomains.userid WHERE tbldomains.id='" . mysqli_real_escape_string($id) . "'";
                $result = full_query_i($query);
                $data = mysqli_fetch_array($result);

                if ($data['email']) {
                    $todata[] = "" . $data['firstname'] . " " . $data['lastname'] . " - " . $data['domain'] . " &lt;" . $data['email'] . "&gt;";
                }
            }
        }
    }


    if (!$todata) {
        infoBox($aInt->lang("sendmessage", "noreceiptients"), $aInt->lang("sendmessage", "noreceiptientsdesc"));
    }

    echo $infobox;

    if ($sub == "loadmessage") {
        $result = select_query_i("ra_templates_sms", "", array("name" => $messagename));
        $data = mysqli_fetch_array($result);

        if (!$data['id']) {
            $result = select_query_i("ra_templates_sms", "", array("name" => $messagename));
            $data = mysqli_fetch_array($result);
        }

        $subject = $data['subject'];
        $message = $data['message'];
        $fromname = $data['fromname'];
        $fromemail = $data['fromemail'];
        $plaintext = $data['plaintext'];

        if ($plaintext) {
            $message = nl2br($message);
        }
    }

    echo "<script langauge=\"javascript\">
frmmessage.subject.select();
</script>

    <textarea name=\"message\" id=\"email_msg1\" rows=\"25\" style=\"width: 100%\"
        class=\"tinymce\">";
    echo $message;
    echo "</textarea>

    <br />

    <table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\"
        cellpadding=\"3\">
        <tr>
            <td width=\"140\" class=\"fieldlabel\">";
    echo $aInt->lang("support", "attachments");
    echo "</td>
            <td class=\"fieldarea\"><div style=\"float: right;\">
                    <input type=\"button\"
                        value=\"";
    echo $aInt->lang("emailtpls", "rteditor");
    echo "\"
                        class=\"btn\" onclick=\"toggleEditor()\" />
                </div>
                <input type=\"file\" name=\"attachments[]\" style=\"width: 60%;\" /> <a
                href=\"#\" id=\"addfileupload\"><img src=\"images/icons/add.png\"
                    align=\"absmiddle\" border=\"0\" /> ";
    echo $aInt->lang("support", "addmore");
    echo "</a><br />
            <div id=\"fileuploads\"></div></td>
        </tr>
";

    if ($massmailquery || $multiple) {
        echo "<tr>
            <td class=\"fieldlabel\">";
        echo $aInt->lang("sendmessage", "marketingemail");
        echo "</td>
            <td class=\"fieldarea\"><label><input type=\"checkbox\" id=\"emailoptout\"
                    name=\"emailoptout\"> ";
        echo $aInt->lang("sendmessage", "dontsendemailunsubscribe");
        echo "</label></td>
        </tr>
";
    }


    if (checkPermission("Create/Edit Email Templates", true)) {
        echo "<tr>
            <td class=\"fieldlabel\">";
        echo $aInt->lang("sendmessage", "savemesasge");
        echo "</td>
            <td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"save\"> ";
        echo $aInt->lang("sendmessage", "entersavename");
        echo ":</label>
                <input type=\"text\" name=\"savename\" size=\"30\"></td>
        </tr>";
    }


    if ($massmailquery) {
        echo "<tr>
            <td class=\"fieldlabel\">";
        echo $aInt->lang("sendmessage", "massmailsettings");
        echo "</td>
            <td class=\"fieldarea\">";
        echo $aInt->lang("sendmessage", "massmailsetting1");
        echo " <input
                type=\"text\" name=\"massmailamount\" size=\"5\" value=\"25\" /> ";
        echo $aInt->lang("sendmessage", "massmailsetting2");
        echo " <input
                type=\"text\" name=\"massmailinterval\" size=\"5\" value=\"30\" /> ";
        echo $aInt->lang("sendmessage", "massmailsetting3");
        echo "</td>
        </tr>";
    }

    echo "</table>

    <p align=\"center\">
        <input type=\"button\"
            value=\"";
    echo $aInt->lang("sendmessage", "preview");
    echo "\"
            onclick=\"previewMsg()\" class=\"btn\" /> <input type=\"submit\"
            value=\"";
    echo $aInt->lang("global", "sendmessage");
    echo " &raquo;\"
            class=\"btn-primary\" />
    </p>

</form>

";
    $aInt->richTextEditor();
    echo "<div id=\"emailoptoutinfo\">";
    infoBox($aInt->lang("sendmessage", "marketingemail"), $aInt->lang("sendmessage", "marketingemaildesc"));
    echo $infobox;
    echo "</div>";
    $i = 1;
    include "mergefields.php";
    echo "
<form method=\"post\" action=\"";
    echo $_SERVER['PHP_SELF'];
    echo "\">
    <input type=\"hidden\" name=\"sub\" value=\"loadmessage\"> <input
        type=\"hidden\" name=\"type\" value=\"";
    echo $type;
    echo "\">
";

    if ($massmailquery) {
        if ($queryMgr->isValidTokenFormat($massmailquery)) {
            $queryToStore = $queryMgr->getQuery($massmailquery);
        } else {
            $queryToStore = $massmailquery;
        }

        $token = $queryMgr->generateToken();
        $queryMgr->setQuery($token, $queryToStore);
        echo "<input type=\"hidden\" name=\"massmailquery\" value=\"" . $token . "\">";

        if ($sendforeach) {
            echo "<input type=\"hidden\" name=\"sendforeach\" value=\"" . $sendforeach . "\">";
        }
    } else {
        if ($multiple) {
            echo "<input type=\"hidden\" name=\"multiple\" value=\"true\">";
            foreach ($selectedclients as $selectedclient) {
                echo "<input type=\"hidden\" name=\"selectedclients[]\" value=\"" . $selectedclient . "\">";
            }
        } else {
            echo "<input type=\"hidden\" name=\"id\" value=\"" . $id . "\">";
        }
    }

    echo "<div class=\"contentbox\">
        <b>";
    echo $aInt->lang("sendmessage", "loadsavedmsg");
    echo ":</b> ";
    echo "<s";
    echo "elect
            name=\"messagename\"><option value=\"\">";
    echo $aInt->lang("sendmessage", "choose");
    echo "...";
    $query = "SELECT * FROM ra_templates_sms";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $messid = $data['id'];
        $messagename = $data['name'];
        echo "<option value='" . $messagename . "' style=\"background-color:#ffffff\">" . $messagename . "</option>";
    }


    if ($type != "general") {
        $result = select_query_i("ra_templates_mail", "", array("type" => $type, "language" => ""), "custom` ASC,`name", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $messid = $data['id'];
            $messagename = $data['name'];
            echo "<option";

            if ($custom == "") {
                echo " style=\"background-color:#efefef\"";
            }

            echo ">" . $messagename . "</option>";
        }
    }

    echo "</select> <input type=\"submit\"
            value=\"";
    echo $aInt->lang("home", "load");
    echo "\">
    </div>
</form>
";
    echo $aInt->jqueryDialog("previewwnd", $aInt->lang("sendmessage", "preview"), "<div id=\"previewwndcontent\">" . $aInt->lang("global", "loading") . "</div>", array($aInt->lang("global", "ok") => ""), "450", "700", "");
    $jquerycode .= "$(\"#addfileupload\").click(function () {
    $(\"#fileuploads\").append(\"<input type=\\\"file\\\" name=\\\"attachments[]\\\" style=\\\"width:70%;\\\" /><br />\");
    return false;
});
$(\"#emailoptoutinfo\").hide();
$(\"#emailoptout\").click(function(){
    if (this.checked) {
        $(\"#emailoptoutinfo\").slideDown(\"slow\");
    } else {
        $(\"#emailoptoutinfo\").slideUp(\"slow\");
    }
});";
    $jscode = "function previewMsg() {
    if ($(\"#email_msg1\").tinymce().isHidden()) {
        alert(\"Cannot preview message while the rich-text editor is disabled - please re-enable and then try again\");
    } else {
        $(\"#previewwnd\").dialog(\"open\");
        jQuery.post(\"sendmessage.php\", $(\"#sendmsgfrm\").serialize()+\"&action=preview&messagetxt=\"+$(\"#email_msg1\").html(),
        function(data){
            if (data) {
                jQuery(\"#previewwndcontent\").html(data);}
            else {
                jQuery(\"#previewwndcontent\").html(\"Syntax Error - Please check your email message for invalid template syntax or missing closing tags\");
            }
        });
        return false;
    }
}";
}
$content = ob_get_contents();
ob_end_clean();
$aInt->template = "smstemplate/send";
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>
