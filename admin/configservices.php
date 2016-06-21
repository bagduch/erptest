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
function printProductDownlads($downloads) {
    if (!is_array($downloads)) {
        $downloads = array();
    }

    echo "<ul class=\"jqueryFileTree\">";
    foreach ($downloads as $downloadid) {
        $result = select_query_i("tbldownloads", "", array("id" => $downloadid));
        $data = mysqli_fetch_array($result);
        $downid = $data['id'];
        $downtitle = $data['title'];
        $downfilename = $data['location'];
        $ext = end(explode(".", $downfilename));
        echo "<li class=\"file ext_" . $ext . "\"><a href=\"#\" class=\"removedownload\" rel=\"" . $downid . "\">" . $downtitle . "</a></li>";
    }

    echo "</ul>";
}

function buildCategoriesList($level, $parentlevel) {
    global $categorieslist;
    global $categories;

    $result = select_query_i("tbldownloadcats", "", array("parentid" => $level), "name", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $parentid = $data['parentid'];
        $category = $data['name'];
        $categorieslist .= "<option value=\"" . $id . "\">";
        $i = 1;

        while ($i <= $parentlevel) {
            $categorieslist .= "- ";
            ++$i;
        }

        $categorieslist .= "" . $category . "</option>";
        buildCategoriesList($id, $parentlevel + 1);
    }
}

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Products/Services");
$aInt->title = $aInt->lang("services", "title");
$aInt->sidebar = "config";
$aInt->icon = "configservices";
$aInt->requiredFiles(array("modulefunctions", "gatewayfunctions"));

if ($action == "getdownloads") {
    check_token("RA.admin.default");

    if (!checkPermission("Edit Products/Services", true)) {
        exit("Access Denied");
    }

    $dir = $_POST['dir'];
    $dir = preg_replace("/[^0-9]/", "", $dir);
    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
    $result = select_query_i("tbldownloadcats", "", array("parentid" => $dir), "name", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $catid = $data['id'];
        $catname = $data['name'];
        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"dir" . $catid . "/\">" . $catname . "</a></li>";
    }

    $result = select_query_i("tbldownloads", "", array("category" => $dir), "title", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $downid = $data['id'];
        $downtitle = $data['title'];
        $downfilename = $data['location'];
        $ext = end(explode(".", $downfilename));
        echo "<li class=\"file ext_" . $ext . "\"><a href=\"#\" rel=\"" . $downid . "\">" . $downtitle . "</a></li>";
    }

    echo "</ul>";
    exit();
}


if ($action == "managedownloads") {
    check_token("RA.admin.default");

    if (!checkPermission("Edit Products/Services", true)) {
        exit("Access Denied");
    }

    $result = select_query_i("tblservices", "downloads", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $downloads = $data['downloads'];
    $downloads = unserialize($downloads);

    if (!is_array($downloads)) {
        $downloads = array();
    }


    if ($adddl) {
        if (!in_array($adddl, $downloads)) {
            $downloads[] = $adddl;
        }
    }


    if ($remdl) {
        foreach ($downloads as $key => $downloadid) {

            if ($downloadid == $remdl) {
                unset($downloads[$key]);
                continue;
            }
        }
    }

    update_query("tblservices", array("downloads" => serialize($downloads)), array("id" => $id));
    printProductDownlads($downloads);
    exit();
}


if ($action == "quickupload") {
    check_token("RA.admin.default");

    if (!checkPermission("Edit Products/Services", true)) {
        exit("Access Denied");
    }

    $categorieslist = "";
    buildCategoriesList(0, 0);
    echo "<form method=\"post\" action=\"configservices.php?action=uploadfile&id=" . $id . "\" id=\"quickuploadfrm\" enctype=\"multipart/form-data\">
" . generate_token("form") . "
<table width=\"100%\">
<tr><td width=\"80\">Category:</td><td><select name=\"catid\" style=\"width:95%;\">" . $categorieslist . "</select></td></tr>
<tr><td>Title:</td><td><input type=\"text\" name=\"title\" style=\"width:95%;\" /></td></tr>
<tr><td>Description:</td><td><input type=\"text\" name=\"description\" style=\"width:95%;\" /></td></tr>
<tr><td>Choose File:</td><td><input type=\"file\" name=\"uploadfile\" style=\"width:95%;\" /></td></tr>
</table>
</form>";
    exit();
}


if ($action == "uploadfile") {
    check_token("RA.admin.default");

    if (!checkPermission("Edit Products/Services", true)) {
        exit("Access Denied");
    }


    if (!isFileNameSafe($_FILES['uploadfile']['name'])) {
        $aInt->gracefulExit("Invalid upload filename.  Valid filenames contain only alpha-numeric, dot, hyphen and underscore characters.");
        exit();
    }

    $filename = $_FILES['uploadfile']['name'];

    if (!$filename) {
        redir("action=edit&id=" . $id . "&tab=7");
    }

    move_uploaded_file($_FILES['uploadfile']['tmp_name'], $downloads_dir . $filename);
    $adddl = insert_query("tbldownloads", array("category" => $catid, "type" => "zip", "title" => $title, "description" => html_entity_decode($description), "location" => $filename, "clientsonly" => "on", "productdownload" => "on"));
    logActivity("Added New Product Download - " . $title);
    $result = select_query_i("tblservices", "downloads", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $downloads = $data['downloads'];
    $downloads = unserialize($downloads);

    if (!is_array($downloads)) {
        $downloads = array();
    }

    $downloads[] = $adddl;
    update_query("tblservices", array("downloads" => serialize($downloads)), array("id" => $id));
    redir("action=edit&id=" . $id . "&tab=7");
}


if ($action == "adddownloadcat") {
    check_token("RA.admin.default");

    if (!checkPermission("Edit Products/Services", true)) {
        exit("Access Denied");
    }

    $categorieslist = "";
    buildCategoriesList(0, 0);
    echo "<form method=\"post\" action=\"configservices.php?action=createdownloadcat&id=" . $id . "\" id=\"adddownloadcatfrm\" enctype=\"multipart/form-data\">
" . generate_token("form") . "
<table width=\"100%\">
<tr><td width=\"80\">Category:</td><td><select name=\"catid\" style=\"width:95%;\">" . $categorieslist . "</select></td></tr>
<tr><td>Name:</td><td><input type=\"text\" name=\"title\" style=\"width:95%;\" /></td></tr>
<tr><td>Description:</td><td><input type=\"text\" name=\"description\" style=\"width:95%;\" /></td></tr>
</table>
</form>";
    exit();
}


if ($action == "createdownloadcat") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");
    insert_query("tbldownloadcats", array("parentid" => $catid, "name" => $title, "description" => html_entity_decode($description), "hidden" => ""));
    logActivity("Added New Download Category - " . $title);
    redir("action=edit&id=" . $id . "&tab=7");
    redir("action=edit&id=" . $id . "&tab=7");
}


if ($action == "add") {
    check_token("RA.admin.default");
    checkPermission("Create New Products/Services");
    $pid = insert_query("tblservices", array("type" => $type, "gid" => $gid, "name" => $productname, "paytype" => "free"));
    redir("action=edit&id=" . $pid);
}


if ($action == "save") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");
    $savefreedomainpaymentterms = ($freedomainpaymentterms ? implode(",", $freedomainpaymentterms) : "");


    if ($tax == "on") {
        $tax = "1";
    }

    $overagesenabled = ($overagesenabled ? "1," . $overageunitsdisk . "," . $overageunitsbw : "");
    $table = "tblservices";
    $array = array(
        "type" => $type,
        "gid" => $gid,
        "name" => $name,
        "description" => html_entity_decode($description),
        "hidden" => $hidden,
        "welcomeemail" => $welcomeemail,
        "proratabilling" => $proratabilling,
        "proratadate" => $proratadate,
        "proratachargenextmonth" => $proratachargenextmonth,
        "paytype" => $paytype,
        "subdomain" => $subdomain,
        "autosetup" => $autosetup,
        "servertype" => $servertype,
        "servergroup" => $servergroup,
        "recurringcycles" => $recurringcycles,
        "autoterminatedays" => $autoterminatedays,
        "autoterminateemail" => $autoterminateemail,
        "tax" => $tax,
        "affiliatepaytype" => $affiliatepaytype,
        "affiliatepayamount" => $affiliatepayamount,
        "affiliateonetime" => $affiliateonetime,
        "order" => $order,
        "retired" => $retired
    );
    $counter = 1;

    while ($counter <= 24) {
        $array["configoption" . $counter] = trim($packageconfigoption[$counter]);
        $counter += 1;
    }

    $where = array("id" => $id);
    update_query($table, $array, $where);
    foreach ($_POST['currency'] as $currency_id => $pricing) {
        update_query("tblpricing", $pricing, array("type" => "product", "currency" => $currency_id, "relid" => $id));
    }


    if ($customfieldname) {
        foreach ($customfieldname as $fid => $value) {
            update_query("tblcustomfields", array("fieldname" => $value, "fieldtype" => $customfieldtype[$fid], "description" => $customfielddesc[$fid], "fieldoptions" => $customfieldoptions[$fid], "regexpr" => html_entity_decode($customfieldregexpr[$fid]), "adminonly" => $customadminonly[$fid], "required" => $customrequired[$fid], "showorder" => $customshoworder[$fid], "showinvoice" => $customshowinvoice[$fid], "sortorder" => $customsortorder[$fid]), array("id" => $fid));
        }
    }

//here
    if ($addfieldname) {
        insert_query("tblcustomfields", array("type" => "product", "relid" => $id, "fieldname" => $addfieldname, "fieldtype" => $addfieldtype, "description" => $addcustomfielddesc, "fieldoptions" => $addfieldoptions, "regexpr" => html_entity_decode($addregexpr), "adminonly" => $addadminonly, "required" => $addrequired, "showorder" => $addshoworder, "showinvoice" => $addshowinvoice, "sortorder" => $addsortorder));
    }

    delete_query("tblserviceconfiglinks", array("pid" => $id));

    if ($configoptionlinks) {
        foreach ($configoptionlinks as $gid) {
            insert_query("tblserviceconfiglinks", array("gid" => $gid, "pid" => $id));
        }
    }

    RebuildModuleHookCache();
    run_hook("ProductEdit", array_merge(array("pid" => $id), $array));
    run_hook("AdminProductConfigFieldsSave", array("pid" => $id));
    redir("action=edit&id=" . $id . ($tab ? "&tab=" . $tab : "") . "&success=true");
}


if ($sub == "deletecustomfield") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");
    delete_query("tblcustomfields", array("id" => $fid));
    delete_query("tblcustomfieldsvalues", array("fieldid" => $fid));
    redir("action=edit&id=" . $id . "&tab=" . $tab);
    exit();
}


if ($action == "duplicatenow") {
    check_token("RA.admin.default");
    checkPermission("Create New Products/Services");
    $result = select_query_i("tblservices", "", array("id" => $existingservice));
    $data = mysqli_fetch_array($result);
    $addstr = "";
    foreach ($data as $key => $value) {

        if (is_numeric($key)) {
            if ($key == "0") {
                $value = "";
            }


            if ($key == "3") {
                $value = $newproductname;
            }

            $addstr .= "'" . db_escape_string($value) . "',";
            continue;
        }
    }

    $addstr = substr($addstr, 0, 0 - 1);
    full_query_i("INSERT INTO tblservices VALUES (" . $addstr . ")");
    $newproductid = mysqli_insert_id();
    $result = select_query_i("tblpricing", "", array("type" => "product", "relid" => $existingservice));

    while ($data = mysqli_fetch_array($result)) {
        $addstr = "";
        foreach ($data as $key => $value) {

            if (is_numeric($key)) {
                if ($key == "0") {
                    $value = "";
                }


                if ($key == "3") {
                    $value = $newproductid;
                }

                $addstr .= "'" . db_escape_string($value) . "',";
                continue;
            }
        }

        $addstr = substr($addstr, 0, 0 - 1);
        full_query_i("INSERT INTO tblpricing VALUES (" . $addstr . ")");
    }

    $result2 = select_query_i("tblcustomfields", "", array("type" => "product", "relid" => $existingservice), "id", "ASC");

    while ($data = mysqli_fetch_array($result2)) {
        $addstr = "";
        foreach ($data as $key => $value) {

            if (is_numeric($key)) {
                if ($key == "0") {
                    $value = "";
                }


                if ($key == "2") {
                    $value = $newproductid;
                }

                $addstr .= "'" . db_escape_string($value) . "',";
                continue;
            }
        }

        $addstr = substr($addstr, 0, 0 - 1);
        full_query_i("INSERT INTO tblcustomfields VALUES (" . $addstr . ")");
    }

    redir("action=edit&id=" . $newproductid);
}


if ($sub == "savegroup") {
    check_token("RA.admin.default");
    checkPermission("Manage Product Groups");
    $disabledgateways = array();
    $gateways2 = getGatewaysArray();
    foreach ($gateways2 as $gateway => $gwname) {

        if (!$gateways[$gateway]) {
            $disabledgateways[] = $gateway;
            continue;
        }
    }


    if ($ids) {
        update_query("tblservicegroups", array("name" => $name, "type" => 2, "orderfrmtpl" => $orderfrmtpl, "disabledgateways" => implode(",", $disabledgateways), "hidden" => $hidden), array("id" => $ids));
    } else {
        insert_query("tblservicegroups", array("name" => $name, "type" => 2, "orderfrmtpl" => $orderfrmtpl, "disabledgateways" => implode(",", $disabledgateways), "hidden" => $hidden, "order" => get_query_val("tblservicegroups", "`order`", "", "order", "DESC") + 1));
    }

    redir();
}


if ($sub == "deletegroup") {
    check_token("RA.admin.default");
    checkPermission("Manage Product Groups");
    delete_query("tblservicegroups", array("id" => $id));
    redir();
}


if ($sub == "delete") {
    check_token("RA.admin.default");
    checkPermission("Delete Products/Services");
    run_hook("ProductDelete", array("pid" => $id));
    delete_query("tblservices", array("id" => $id));
    delete_query("tblserviceconfiglinks", array("pid" => $id));
    delete_query("tblcustomfields", array("type" => "product", "relid" => $id));
    full_query_i("DELETE FROM tblcustomfieldsvalues WHERE fieldid NOT IN (SELECT id FROM tblcustomfields)");
    redir();
}


if ($sub == "moveup") {
    check_token("RA.admin.default");
    checkPermission("Manage Product Groups");
    $result = select_query_i("tblservicegroups", "", array("`order`" => $order));
    $data = mysqli_fetch_array($result);
    $premid = $data['id'];
    $order1 = $order - 1;
    update_query("tblservicegroups", array("order" => $order), array("`order`" => $order1));
    update_query("tblservicegroups", array("order" => $order1), array("id" => $premid));
    redir();
}


if ($sub == "movedown") {
    check_token("RA.admin.default");
    checkPermission("Manage Product Groups");
    $result = select_query_i("tblservicegroups", "", array("`order`" => $order));
    $data = mysqli_fetch_array($result);
    $premid = $data['id'];
    $order1 = $order + 1;
    update_query("tblservicegroups", array("order" => $order), array("`order`" => $order1));
    update_query("tblservicegroups", array("order" => $order1), array("id" => $premid));
    redir();
}


if ($action == "updatesort") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");
    foreach ($so as $pid => $sort) {
        update_query("tblservices", array("order" => $sort), array("id" => $pid));
    }

    redir();
}

ob_start();

if ($action == "") {
    $result = select_query_i("tblservicegroups", "COUNT(*)", "");
    $data = mysqli_fetch_array($result);
    $num_rows = $data[0];
    $result = select_query_i("tblservices", "COUNT(*)", "");
    $data = mysqli_fetch_array($result);
    $num_rows2 = $data[0];
    $aInt->deleteJSConfirm("doDelete", "services", "deleteserviceconfirm", "?sub=delete&id=");
    $aInt->deleteJSConfirm("doGroupDelete", "services", "deletegroupconfirm", "?sub=deletegroup&id=");
    echo "<p><b>";
    echo $aInt->lang("addons", "options");
    echo ":</b><a href=\"";
    echo $PHP_SELF;
    echo "?action=creategroup\">";
    echo $aInt->lang("services", "createnewgroup");
    echo "</a> | ";

    if ($num_rows == "0") {
        echo "<font color=#cccccc>" . $aInt->lang("services", "createnewservice") . "</font>";
    } else {
        echo "<a href=\"";
        echo $PHP_SELF;
        echo "?action=create\">";
        echo $aInt->lang("services", "createnewservice");
        echo "</a>";
    }

    echo " | ";

    if ($num_rows2 == "0") {
        echo "<font color=#cccccc>" . $aInt->lang("services", "duplicateservice") . "</font>";
    } else {
        echo "<a href=\"";
        echo $PHP_SELF;
        echo "?action=duplicate\">";
        echo $aInt->lang("services", "duplicateservice");
        echo "</a>";
    }

    echo "</p>
<form method=\"post\" action=\"configpservices.php?action=updatesort\">
<div class=\"tablebg\">
<table class=\"datatable\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
<tr><th>";
    echo $aInt->lang("services", "servicename");
    echo "</th><th>";
    echo $aInt->lang("fields", "type");
    echo "</th><th>";
    echo $aInt->lang("services", "sortorder");
    echo "</th><th>";
    echo $aInt->lang("services", "paytype");
    echo "</th><th>";
    echo $aInt->lang("services", "price");
    echo "</th><th>";
    echo $aInt->lang("services", "autosetup");
    echo "</th><th width=\"20\"></th><th width=\"20\"></th></tr>";
    $result = select_query_i("tblservicegroups", "", "", "order", "DESC");
    $data = mysqli_fetch_array($result);
    $lastorder = $data['order'];
    $result2 = select_query_i("tblservicegroups", "", "", "order", "ASC");
    $k = 0;

    while ($data = mysqli_fetch_array($result2)) {
        ++$k;
        $groupid = $data['id'];
        update_query("tblservicegroups", array("order" => $k), array("id" => $groupid));
        $name = $data['name'];
        $hidden = $data['hidden'];
        $order = $data['order'];
        $result = select_query_i("tblservices", "COUNT(*)", array("gid" => $groupid));
        $data = mysqli_fetch_array($result);
        $num_rows = $data[0];

        if (0 < $num_rows) {
            $deletelink = "alert('" . $aInt->lang("services", "deletegrouperror", 1) . "')";
        } else {
            $deletelink = "doGroupDelete('" . $groupid . "')";
        }

        echo "<tr><td colspan=\"6\" style=\"background-color:#ffffdd;\"><div align=\"left\"><b>" . $aInt->lang("fields", "groupname") . (":</b> " . $name . " ");

        if ($hidden == "on") {
            echo "(Hidden) ";
        }


        if ($order != "1") {
            echo "<a href=\"" . $PHP_SELF . "?sub=moveup&order=" . $order . generate_token("link") . "\"><img src=\"images/moveup.gif\" border=\"0\" align=\"absmiddle\" alt=\"" . $aInt->lang("services", "navmoveup") . "\"></a>";
        }


        if ($order != $lastorder) {
            echo "<a href=\"" . $PHP_SELF . "?sub=movedown&order=" . $order . generate_token("link") . "\"><img src=\"images/movedown.gif\" border=\"0\" align=\"absmiddle\" alt=\"" . $aInt->lang("services", "navmovedown") . "\"></a>";
        }

        echo "</div></td><td style=\"background-color:#ffffdd;\" align=center><a href=\"" . $PHP_SELF . "?action=editgroup&ids=" . $groupid . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "edit") . ("\"></td><td  style=\"background-color:#ffffdd;\" align=center><a href=\"#\" onClick=\"" . $deletelink . ";return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"") . $aInt->lang("global", "delete") . "\"></a></td></tr>";
        $result = select_query_i("tblservices", "id,type,name,paytype,autosetup,proratabilling,servertype,hidden,`order`,(SELECT COUNT(*) FROM tblcustomerservices WHERE tblcustomerservices.packageid=tblservices.id) AS usagecount", array("gid" => $groupid), "order` ASC,`name", "ASC");

        $i = 0;

        while ($data = mysqli_fetch_array($result)) {

            $id = $data['id'];
            $type = $data['type'];
            $name = $data['name'];
            $paytype = $data['paytype'];
            $autosetup = $data['autosetup'];
            $proratabilling = $data['proratabilling'];
            $servertype = ucfirst($data['servertype']);
            $hidden = $data['hidden'];
            $sortorder = $data['order'];
            $num_rows = $data['usagecount'];

            if (0 < $num_rows) {
                $deletelink = "alert('" . $aInt->lang("services", "deleteserviceerror", 1) . "')";
            } else {
                $deletelink = "doDelete('" . $id . "')";
            }


            if ($autosetup == "on") {
                $autosetup = $aInt->lang("services", "asetupafteracceptpendingorder");
            } else {
                if ($autosetup == "order") {
                    $autosetup = $aInt->lang("services", "asetupinstantlyafterorder");
                } else {
                    if ($autosetup == "payment") {
                        $autosetup = $aInt->lang("services", "asetupafterpay");
                    } else {
                        if ($autosetup == "") {
                            $autosetup = $aInt->lang("services", "off");
                        }
                    }
                }
            }


            if ($paytype == "free") {
                $paymenttype = $aInt->lang("billingcycles", "free");
            } else {
                if ($paytype == "onetime") {
                    $paymenttype = $aInt->lang("billingcycles", "onetime");
                } else {
                    $paymenttype = $aInt->lang("status", "recurring");
                }
            }


            if ($proratabilling) {
                $paymenttype .= " (" . $aInt->lang("services", "proratabilling") . ")";
            }


            if ($type == "hostingaccount") {
                $producttype = $aInt->lang("services", "hostingaccount");
            } else {
                if ($type == "reselleraccount") {
                    $producttype = $aInt->lang("services", "reselleraccount");
                } else {
                    if ($type == "server") {
                        $producttype = $aInt->lang("services", "dedicatedvpsserver");
                    } else {
                        $producttype = $aInt->lang("services", "otherproductservice");
                    }
                }
            }


            if ($servertype) {
                $producttype .= " (" . $servertype . ")";
            }


            if ($stockcontrol) {
                $qtystock = $qty;
            } else {
                $qtystock = "-";
            }


            if ($hidden) {
                $name .= " (Hidden)";
                $hidden = " style=\"background-color:#efefef;\"";
            }

            echo "<tr style=\"text-align:center;\"><td" . $hidden . (">" . $name . "</td><td") . $hidden . ((">" . $producttype . "</td><td><input type=\"text\" name=\"so[" . $id . "]") . "\" value=\"" . $sortorder . "\" size=\"5\" style=\"font-size:10px;\" /></td><td") . $hidden . (">" . $paymenttype . "</td><td") . $hidden . (">" . $qtystock . "</td><td") . $hidden . (">" . $autosetup . "</td><td") . $hidden . ("><a href=\"" . $PHP_SELF . "?action=edit&id=" . $id . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"") . $aInt->lang("global", "edit") . "\"></a></td><td" . $hidden . ("><a href=\"#\" onClick=\"" . $deletelink . ";return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"") . $aInt->lang("global", "delete") . "\"></a></td></tr>";
            ++$i;
        }


        if ($i == "0") {
            echo "<tr><td colspan=10 align=center>" . $aInt->lang("services", "noproductsingroupsetup") . "</td></tr>";
        } else {
            echo "<tr><td></td><td></td><td><div align=\"center\"><input type=\"submit\" value=\"" . $aInt->lang("services", "updatesort") . "\" style=\"font-size:10px;\" /></div></td><td></td><td></td><td></td><td></td><td></td></tr>";
        }

        $i = 0;
    }


    if ($k == "0") {
        echo "<tr><td colspan=10 align=center>" . $aInt->lang("services", "nogroupssetup") . "</td></tr>";
    }

    echo "</table>
</div>
</form>

";
} else {
    if ($action == "edit") {
        $result = select_query_i("tblservices", "", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $id = $data['id'];
        $type = $data['type'];
        $groupid = $gid = $data['gid'];
        $name = $data['name'];
        $description = $data['description'];
        $showdomainops = $data['showdomainoptions'];
        $hidden = $data['hidden'];
        $welcomeemail = $data['welcomeemail'];
        $paytype = $data['paytype'];
        $allowqty = $data['allowqty'];
        $subdomain = $data['subdomain'];
        $autosetup = $data['autosetup'];
        $servergroup = $data['servergroup'];
        $stockcontrol = $data['stockcontrol'];
        $qty = $data['qty'];
        $proratabilling = $data['proratabilling'];
        $proratadate = $data['proratadate'];
        $proratachargenextmonth = $data['proratachargenextmonth'];
        $servertype = $data['servertype'];

        $counter = 1;

        while ($counter <= 24) {
            $packageconfigoption[$counter] = $data["configoption" . $counter];
            $counter += 1;
        }

        $freedomainpaymentterms = $data['freedomainpaymentterms'];
        $freedomaintlds = $data['freedomaintlds'];
        $recurringcycles = $data['recurringcycles'];
        $autoterminatedays = $data['autoterminatedays'];
        $autoterminateemail = $data['autoterminateemail'];
        $tax = $data['tax'];
        $configoptionsupgrade = $data['configoptionsupgrade'];
        $billingcycleupgrade = $data['billingcycleupgrade'];
        $overagesenabled = $data['overagesenabled'];
        $overagesdisklimit = $data['overagesdisklimit'];
        $overagesbwlimit = $data['overagesbwlimit'];
        $overagesdiskprice = $data['overagesdiskprice'];
        $overagesbwprice = $data['overagesbwprice'];
        $affiliatepayamount = $data['affiliatepayamount'];
        $affiliatepaytype = $data['affiliatepaytype'];
        $affiliateonetime = $data['affiliateonetime'];
        $downloads = $data['downloads'];
        $retired = $data['retired'];
        $freedomainpaymentterms = explode(",", $freedomainpaymentterms);
        $freedomaintlds = explode(",", $freedomaintlds);
        $overagesenabled = explode(",", $overagesenabled);
        $downloads = unserialize($downloads);
        $order = $data['order'];
        echo "<script type=\"text/javascript\" src=\"../includes/jscript/jquerylq.js\"></script>
<script type=\"text/javascript\" src=\"../includes/jscript/jqueryFileTree.js\"></script>
<link href=\"../includes/jscript/css/jqueryFileTree.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />

<h2>Edit Product</h2>
<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "?action=save&id=" . $id;
        echo "\" name=\"packagefrm\">";
        $jscode = "function deletecustomfield(id) {
if (confirm(\"Are you sure you want to delete this field and ALL DATA associated with it?\")) {
window.location='" . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $id . "&tab=3&sub=deletecustomfield&fid='+id+'" . generate_token("link") . "';
}}
function deleteoption(id) {
if (confirm(\"Are you sure you want to delete this product configuration?\")) {
window.location='" . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $id . "&tab=4&sub=deleteoption&confid='+id+'" . generate_token("link") . "';
}}";
        $jquerycode = "$('#productdownloadsbrowser').fileTree({ root: '0', script: 'configservices.php?action=getdownloads" . generate_token("link") . "', folderEvent: 'click', expandSpeed: 1, collapseSpeed: 1 }, function(file) {
    $.post(\"configservices.php?action=managedownloads&id=" . $id . generate_token("link") . "&adddl=\"+file, function(data) {
        $(\"#productdownloadslist\").html(data);
    });
});
$(\".removedownload\").livequery(\"click\", function(event) {
    var dlid = $(this).attr(\"rel\");
    $.post(\"configservices.php?action=managedownloads&id=" . $id . generate_token("link") . "&remdl=\"+dlid, function(data) {
        $(\"#productdownloadslist\").html(data);
    });
});
$(\"#showquickupload\").click(
    function() {
        $(\"#quickupload\").dialog(\"open\");
        $(\"#quickupload\").load(\"configservices.php?action=quickupload&id=" . $id . generate_token("link") . "\");
        return false;
    }
);
$(\"#showadddownloadcat\").click(
    function() {
        $(\"#adddownloadcat\").dialog(\"open\");
        $(\"#adddownloadcat\").load(\"configservices.php?action=adddownloadcat&id=" . $id . generate_token("link") . "\");
        return false;
    }
);
";

        if ($success) {
            infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"));
        }

        echo $infobox;
        echo $aInt->Tabs(array($aInt->lang("services", "tabsdetails"), $aInt->lang("global", "pricing"), $aInt->lang("services", "tabsmodulesettings"), $aInt->lang("setup", "configoptions"), $aInt->lang("setup", "other")));
        echo "
<div id=\"tab0box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("fields", "producttype");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"type\" onChange=\"doFieldUpdate()\"><option value=\"hostingaccount\"";

        if ($type == "hostingaccount") {
            echo " SELECTED";
        }

        echo ">";
        echo $aInt->lang("services", "hostingaccount");
        echo "<option value=\"reselleraccount\"";

        if ($type == "reselleraccount") {
            echo " SELECTED";
        }

        echo ">";
        echo $aInt->lang("services", "reselleraccount");
        echo "<option value=\"server\"";

        if ($type == "server") {
            echo " SELECTED";
        }

        echo ">";
        echo $aInt->lang("services", "dedicatedvpsserver");
        echo "<option value=\"other\"";

        if ($type == "other") {
            echo " SELECTED";
        }

        echo ">";
        echo $aInt->lang("setup", "other");
        echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "servicegroup");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"gid\">";
        $result = select_query_i("tblservicegroups", "", "", "order", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $select_gid = $data['id'];
            $select_name = $data['name'];
            echo "<option value=\"" . $select_gid . "\"";

            if ($select_gid == $groupid) {
                echo " selected";
            }

            echo ">" . $select_name . "</option>";
        }

        echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "productname");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" size=\"40\" name=\"name\" value=\"";
        echo $name;
        echo "\"></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "servicedesc");
        echo "</td><td class=\"fieldarea\"><table cellsapcing=0 cellpadding=0><tr><td><textarea name=\"description\" cols=60 rows=5>";
        echo $description;
        echo "</textarea></td><td>";
        echo $aInt->lang("services", "htmlallowed");
        echo "<br>&lt;br /&gt; ";
        echo $aInt->lang("services", "htmlnewline");
        echo "<br>&lt;strong&gt;";
        echo $aInt->lang("services", "htmlbold");
        echo "&lt;/strong&gt; <b>";
        echo $aInt->lang("services", "htmlbold");
        echo "</b><br>&lt;em&gt;";
        echo $aInt->lang("services", "htmlitalics");
        echo "&lt;/em&gt; <i>";
        echo $aInt->lang("services", "htmlitalics");
        echo "</i></td></tr></table></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "welcomeemail");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"welcomeemail\"><option value=\"0\">";
        echo $aInt->lang("global", "none");
        echo "</option>";
        $emails = array("Hosting Account Welcome Email", "Reseller Account Welcome Email", "Dedicated/VPS Server Welcome Email", "Other Product/Service Welcome Email");
        foreach ($emails as $email) {
            $result = select_query_i("tblemailtemplates", "id,name", array("type" => "product", "name" => $email, "language" => ""));

            while ($data = mysqli_fetch_array($result)) {
                $mid = $data['id'];
                $name = $data['name'];
                echo "<option value=\"" . $mid . "\"";

                if ($welcomeemail == $mid) {
                    echo " selected";
                }

                echo ">" . $name . "</option>";
            }
        }

        $result = select_query_i("tblemailtemplates", "id,name", array("type" => "product", "custom" => "1", "language" => ""), "name", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $mid = $data['id'];
            $name = $data['name'];
            echo "<option value=\"" . $mid . "\"";

            if ($welcomeemail == $mid) {
                echo " selected";
            }

            echo ">" . $name . "</option>";
        }

        echo "</select></td></tr>

<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "applytax");
        echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"tax\"";

        if ($tax == "1") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("services", "applytaxdesc");
        echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("fields", "hidden");
        echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"hidden\"";

        if ($hidden == "on") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("services", "hiddendesc");
        echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "retired");
        echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"retired\" value=\"1\"";

        if ($retired) {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("services", "retireddesc");
        echo "</label></td></tr>
</table>

  </div>
</div>
<div id=\"tab1box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "paymenttype");
        echo "</td><td class=\"fieldarea\"><label><input type=\"radio\" name=\"paytype\" value=\"free\"";

        if ($paytype == "free") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("billingcycles", "free");
        echo "</label> <label><input type=\"radio\" name=\"paytype\" value=\"onetime\"";

        if ($paytype == "onetime") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("billingcycles", "onetime");
        echo "</label> <label><input type=\"radio\" name=\"paytype\" value=\"recurring\"";

        if ($paytype == "recurring") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("global", "recurring");
        echo "</label></td></tr>
<tr><td colspan=\"2\" align=\"center\"><br>
<table cellspacing=\"1\" bgcolor=\"#cccccc\">
<tr bgcolor=\"#efefef\" style=\"text-align:center;font-weight:bold\"><td width=80>";
        echo $aInt->lang("currencies", "currency");
        echo "</td><td width=80></td><td width=120>";
        echo $aInt->lang("billingcycles", "onetime");
        echo "/";
        echo $aInt->lang("billingcycles", "monthly");
        echo "</td><td width=90>";
        echo $aInt->lang("billingcycles", "quarterly");
        echo "</td><td width=100>";
        echo $aInt->lang("billingcycles", "semiannually");
        echo "</td><td width=90>";
        echo $aInt->lang("billingcycles", "annually");
        echo "</td><td width=90>";
        echo $aInt->lang("billingcycles", "biennially");
        echo "</td><td width=90>";
        echo $aInt->lang("billingcycles", "triennially");
        echo "</td></tr>
";
        $result = select_query_i("tblcurrencies", "id,code", "", "code", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $currency_id = $data['id'];
            $currency_code = $data['code'];
            $result2 = select_query_i("tblpricing", "", array("type" => "product", "currency" => $currency_id, "relid" => $id));
            $data = mysqli_fetch_array($result2);
            $pricing_id = $data['id'];

            if (!$pricing_id) {
                insert_query("tblpricing", array("type" => "product", "currency" => $currency_id, "relid" => $id));
                $result2 = select_query_i("tblpricing", "", array("type" => "product", "currency" => $currency_id, "relid" => $id));
                $data = mysqli_fetch_array($result2);
            }

            $msetupfee = $data['msetupfee'];
            $qsetupfee = $data['qsetupfee'];
            $ssetupfee = $data['ssetupfee'];
            $asetupfee = $data['asetupfee'];
            $bsetupfee = $data['bsetupfee'];
            $tsetupfee = $data['tsetupfee'];
            $monthly = $data['monthly'];
            $quarterly = $data['quarterly'];
            $semiannually = $data['semiannually'];
            $annually = $data['annually'];
            $biennially = $data['biennially'];
            $triennially = $data['triennially'];
            echo "<tr bgcolor=\"#ffffff\" style=\"text-align:center\"><td rowspan=\"2\" bgcolor=\"#efefef\"><b>" . $currency_code . "</b></td><td>" . $aInt->lang("fields", "setupfee") . ((((((("</td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[msetupfee]\" size=\"10\" value=\"" . $msetupfee . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[qsetupfee]\" size=\"10\" value=\"" . $qsetupfee . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[ssetupfee]\" size=\"10\" value=\"" . $ssetupfee . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[asetupfee]\" size=\"10\" value=\"" . $asetupfee . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[bsetupfee]\" size=\"10\" value=\"" . $bsetupfee . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[tsetupfee]\" size=\"10\" value=\"" . $tsetupfee . "\"></td></tr><tr bgcolor=\"#ffffff\" style=\"text-align:center\"><td>") . $aInt->lang("fields", "price") . ((((((("</td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[monthly]\" size=\"10\" value=\"" . $monthly . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[quarterly]\" size=\"10\" value=\"" . $quarterly . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[semiannually]\" size=\"10\" value=\"" . $semiannually . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[annually]\" size=\"10\" value=\"" . $annually . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[biennially]\" size=\"10\" value=\"" . $biennially . "\"></td><td><input type=\"text\" name=\"currency[" . $currency_id . "]") . "[triennially]\" size=\"10\" value=\"" . $triennially . "\"></td></tr>");
        }

        echo "</table><br>
(";
        echo $aInt->lang("services", "disablepaymenttermdesc");
        echo ")<br /><br />
</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "allowqty");
        echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"allowqty\" value=\"1\"";

        if ($allowqty) {
            echo " checked";
        }

        echo " /> ";
        echo $aInt->lang("services", "allowqtydesc");
        echo "</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "recurringcycleslimit");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"recurringcycles\" value=\"";
        echo $recurringcycles;
        echo "\" size=\"7\" /> ";
        echo $aInt->lang("services", "recurringcycleslimitdesc");
        echo "</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "autoterminatefixedterm");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"autoterminatedays\" value=\"";
        echo $autoterminatedays;
        echo "\" size=\"7\" /> ";
        echo $aInt->lang("services", "autoterminatefixedtermdesc");
        echo "</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "terminationemail");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"autoterminateemail\"><option value=\"0\">";
        echo $aInt->lang("global", "none");
        echo "</option>";
        $result = select_query_i("tblemailtemplates", "id,name", array("type" => "product", "language" => ""));

        while ($data = mysqli_fetch_array($result)) {
            $mid = $data['id'];
            $name = $data['name'];
            echo "<option value=\"" . $mid . "\"";

            if ($autoterminateemail == $mid) {
                echo " selected";
            }

            echo ">" . $name . "</option>";
        }

        echo "</select> ";
        echo $aInt->lang("services", "chooseemailtplfixedtermend");
        echo "</td></tr>
</table>

  </div>
</div>
<div id=\"tab2box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\" width=150>";
        echo $aInt->lang("services", "modulename");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"servertype\" onChange=\"submit()\"><option value=\"\">";
        echo $aInt->lang("global", "none");
        $modulesarray = array();
        $dh = opendir(ROOTDIR . "/modules/servers/");

        foreach(readdir($dh) as $file) {
            if (is_file(ROOTDIR . ("/modules/servers/" . $file . "/" . $file . ".php"))) {
                $modulesarray[] = $file;
            }
        }

        closedir($dh);
        sort($modulesarray);
        foreach ($modulesarray as $module) {
            echo "<option value=\"" . $module . "\"";

            if ($module == $servertype) {
                echo " selected";
            }

            echo ">" . ucfirst($module) . "</option>";
        }

        echo "</select></td></tr>
";

        if ($servertype) {
            echo "<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("services", "servergroup");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"servergroup\"><option value=\"0\">";
            echo $aInt->lang("global", "none");
            echo "</option>";
            $result2 = select_query_i("tblservergroups", "", "", "name", "ASC");

            while ($data2 = mysqli_fetch_array($result2)) {
                $groupid = $data2['id'];
                $groupname = $data2['name'];
                echo "<option value=\"" . $groupid . "\"";

                if ($groupid == $servergroup) {
                    echo " selected";
                }

                echo ">" . $groupname . "</option>";
            }

            echo "</select>";
            echo "</td></tr>
";
        }

        echo "</table>

<br>

";

        if ($servertype && in_array($servertype, $modulesarray)) {
            echo "
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\"><tr>
";

            if (!isValidforPath($servertype)) {
                exit("Invalid Server Module Name");
            }

            include "../modules/servers/" . $servertype . "/" . $servertype . ".php";

            if (function_exists($servertype . "_ConfigOptions")) {
                $configarray = call_user_func($servertype . "_ConfigOptions");
                $i = 0;
                foreach ($configarray as $key => $values) {
                    ++$i;

                    if (!$values['FriendlyName']) {
                        $values['FriendlyName'] = $key;
                    }

                    $values['Name'] = "packageconfigoption[" . $i . "]";
                    $values['Value'] = $packageconfigoption[$i];
                    echo "<td class=\"fieldlabel\">" . $values['FriendlyName'] . "</td><td class=\"fieldarea\">" . moduleConfigFieldOutput($values) . "</td>";

                    if ($i % 2) {
                        continue;
                    }

                    echo "</tr><tr>";
                }
            }

            echo "</tr></table>

<br>

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=20><input type=\"radio\" name=\"autosetup\" value=\"order\"";

            if ($autosetup == "order") {
                echo " CHECKED";
            }

            echo "></td><td class=\"fieldarea\">";
            echo $aInt->lang("services", "asetupinstantlyafterorderdesc");
            echo "</td></tr>
<tr><td><input type=\"radio\" name=\"autosetup\" value=\"payment\"";

            if ($autosetup == "payment") {
                echo " CHECKED";
            }

            echo "></td><td class=\"fieldarea\">";
            echo $aInt->lang("services", "asetupafterpaydesc");
            echo "</td></tr>
<tr><td><input type=\"radio\" name=\"autosetup\" value=\"on\"";

            if ($autosetup == "on") {
                echo " CHECKED";
            }

            echo "></td><td class=\"fieldarea\">";
            echo $aInt->lang("services", "asetupmadesc");
            echo "</td></tr>
<tr><td><input type=\"radio\" name=\"autosetup\" value=\"\"";

            if ($autosetup == "") {
                echo " CHECKED";
            }

            echo "></td><td class=\"fieldarea\">";
            echo $aInt->lang("services", "noautosetupdesc");
            echo "</td></tr>
</table>

";
        }

        echo "
  </div>
</div>

<div id=\"tab3box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"150\" class=\"fieldlabel\">";
        echo $aInt->lang("services", "assignedoptiongroups");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"configoptionlinks[]\" size=\"8\" style=\"width:90%\" multiple>
";
        $configoptionlinks = array();
        $result = select_query_i("tblserviceconfiglinks", "", array("pid" => $id));

        while ($data = mysqli_fetch_array($result)) {
            $configoptionlinks[] = $data['gid'];
        }

        $result = select_query_i("tblserviceconfiggroups", "", "", "name", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $confgroupid = $data['id'];
            $name = $data['name'];
            $description = $data['description'];
            echo "<option value=\"" . $confgroupid . "\"";

            if (in_array($confgroupid, $configoptionlinks)) {
                echo " selected";
            }

            echo ">" . $name . " - " . $description . "</option>";
        }

        echo "</select></td></tr>
</table>

  </div>
</div>

<div id=\"tab4box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "tabsfreedomain");
        echo "</td><td class=\"fieldarea\"><input type=\"radio\" name=\"freedomain\" value=\"\"";

        if (!$freedomain) {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("global", "none");
        echo "<br /><input type=\"radio\" name=\"freedomain\" value=\"once\"";

        if ($freedomain == "once") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("services", "freedomainrenewnormal");
        echo "<br /><input type=\"radio\" name=\"freedomain\" value=\"on\"";

        if ($freedomain == "on") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("services", "freedomainfreerenew");
        echo "</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "freedomainpayterms");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"freedomainpaymentterms[]\" size=\"6\" multiple>
<option value=\"onetime\"";

        if (in_array("onetime", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "onetime");
        echo "</option>
<option value=\"monthly\"";

        if (in_array("monthly", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "monthly");
        echo "</option>
<option value=\"quarterly\"";

        if (in_array("quarterly", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "quarterly");
        echo "</option>
<option value=\"semiannually\"";

        if (in_array("semiannually", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "semiannually");
        echo "</option>
<option value=\"annually\"";

        if (in_array("annually", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "annually");
        echo "</option>
<option value=\"biennially\"";

        if (in_array("biennially", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "biennially");
        echo "</option>
<option value=\"triennially\"";

        if (in_array("triennially", $freedomainpaymentterms)) {
            echo " selected";
        }

        echo ">";
        echo $aInt->lang("billingcycles", "triennially");
        echo "</option>
</select><br>";
        echo $aInt->lang("services", "selectfreedomainpayterms");
        echo "</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "selectfreedomainpayterms");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"freedomaintlds[]\" size=\"5\" multiple>";
        $query = "SELECT DISTINCT extension FROM tbldomainpricing ORDER BY `order` ASC";
        $result = full_query_i($query);

        while ($data = mysqli_fetch_array($result)) {
            $extension = $data['extension'];
            echo "<option";

            if (in_array($extension, $freedomaintlds)) {
                echo " selected";
            }

            echo ">" . $extension;
        }

        echo "</select><br>";
        echo $aInt->lang("services", "usectrlclickpayterms");
        echo "</td></tr>
</table>

  </div>
</div>
<div id=\"tab5box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
";
        $producteditfieldsarray = run_hook("AdminProductConfigFields", array("pid" => $id));

        if (is_array($producteditfieldsarray)) {
            foreach ($producteditfieldsarray as $pv) {
                foreach ($pv as $k => $v) {
                    echo "<tr><td class=\"fieldlabel\">" . $k . "</td><td class=\"fieldarea\">" . $v . "</td></tr>";
                }
            }
        }

        echo "<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "customaffiliatepayout");
        echo "</td><td class=\"fieldarea\"><input type=\"radio\" name=\"affiliatepaytype\" value=\"\"";

        if ($affiliatepaytype == "") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("affiliates", "usedefault");
        echo " <input type=\"radio\" name=\"affiliatepaytype\" value=\"percentage\"";

        if ($affiliatepaytype == "percentage") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("affiliates", "percentage");
        echo " <input type=\"radio\" name=\"affiliatepaytype\" value=\"fixed\"";

        if ($affiliatepaytype == "fixed") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("affiliates", "fixedamount");
        echo " <input type=\"radio\" name=\"affiliatepaytype\" value=\"none\"";

        if ($affiliatepaytype == "none") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("affiliates", "nocommission");
        echo "</td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("affiliates", "affiliatepayamount");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"affiliatepayamount\" value=\"";
        echo $affiliatepayamount;
        echo "\" size=\"10\"> <input type=\"checkbox\" name=\"affiliateonetime\"";

        if ($affiliateonetime == "on") {
            echo " checked";
        }

        echo "> ";
        echo $aInt->lang("affiliates", "onetimepayout");
        echo "</td></tr>

<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "associateddownloads");
        echo "</td><td class=\"fieldarea\">";
        echo $aInt->lang("services", "associateddownloadsdesc");
        echo "<br />
<table align=\"center\"><tr><td valign=\"top\">
<div align=\"center\">";
        echo "<s";
        echo "trong>";
        echo $aInt->lang("services", "availablefiles");
        echo "</strong></div>
<div id=\"productdownloadsbrowser\" style=\"width: 250px;height: 200px;border-top: solid 1px #BBB;border-left: solid 1px #BBB;border-bottom: solid 1px #FFF;border-right: solid 1px #FFF;background: #FFF;overflow: scroll;padding: 5px;\"></div>
</td><td><></td><td valign=\"top\">
<div align=\"center\">";
        echo "<s";
        echo "trong>";
        echo $aInt->lang("services", "selectedfiles");
        echo "</strong></div>
<div id=\"productdownloadslist\" style=\"width: 250px;height: 200px;border-top: solid 1px #BBB;border-left: solid 1px #BBB;border-bottom: solid 1px #FFF;border-right: solid 1px #FFF;background: #FFF;overflow: scroll;padding: 5px;\">";
        printProductDownlads($downloads);
        echo "</div>
</td></tr></table>
<div align=\"center\"><input type=\"button\" value=\"";
        echo $aInt->lang("services", "addcategory");
        echo "\" class=\"button\" id=\"showadddownloadcat\" /> <input type=\"button\" value=\"";
        echo $aInt->lang("services", "quickupload");
        echo "\" class=\"button\" id=\"showquickupload\" /></div>
</td></tr>

</table>

  </div>
</div>
<div id=\"tab8box\" class=\"tabbox\">
  <div id=\"tab_content\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "directscartlink");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" size=\"100\" value=\"";
        echo $CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL'];
        echo "/cart.php?a=add&pid=";
        echo $id;
        echo "\" readonly></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "directscarttpllink");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" size=\"100\" value=\"";
        echo $CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL'];
        echo "/cart.php?a=add&pid=";
        echo $id;
        echo "&carttpl=cart\" readonly></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "directscartdomlink");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" size=\"100\" value=\"";
        echo $CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL'];
        echo "/cart.php?a=add&pid=";
        echo $id;
        echo "&sld=ra&tld=.com\" readonly></td></tr>
<tr><td class=\"fieldlabel\">";
        echo $aInt->lang("services", "servicegcartlink");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" size=\"100\" value=\"";
        echo $CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL'];
        echo "/cart.php?gid=";
        echo $gid;
        echo "\" readonly></td></tr>
</table>

  </div>
</div>

<p align=\"center\"><input type=\"submit\" value=\"Save Changes\" class=\"button\"> <input type=\"button\" value=\"";
        echo $aInt->lang("services", "backtoservicelist");
        echo "\" onClick=\"window.location='configservices.php'\" class=\"button\"></p>

<input type=\"hidden\" name=\"tab\" id=\"tab\" value=\"";
        echo $_REQUEST['tab'];
        echo "\" />

</form>

";
        echo $aInt->jqueryDialog("quickupload", "Quick File Upload", "Loading...", array("Save" => "$('#quickuploadfrm').submit();
", "Cancel" => ""));
        echo $aInt->jqueryDialog("adddownloadcat", "Add Category", "Loading...", array("Save" => "$('#adddownloadcatfrm').submit();
", "Cancel" => ""));
    } else {
        if ($action == "create") {
            checkPermission("Create New Products/Services");
            echo "
<h2>Add New Product</h2>

<form method=\"post\" action=\"";
            echo $PHP_SELF;
            echo "?action=add\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=150 class=\"fieldlabel\">";
            echo $aInt->lang("fields", "producttype");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"type\"><option value=\"hostingaccount\"";

            if ($type == "hostingaccount") {
                echo " SELECTED";
            }

            echo ">";
            echo $aInt->lang("services", "hostingaccount");
            echo "<option value=\"reselleraccount\"";

            if ($type == "reselleraccount") {
                echo " SELECTED";
            }

            echo ">";
            echo $aInt->lang("services", "reselleraccount");
            echo "<option value=\"server\"";

            if ($type == "server") {
                echo " SELECTED";
            }

            echo ">";
            echo $aInt->lang("services", "dedicatedvpsserver");
            echo "<option value=\"other\"";

            if ($type == "other") {
                echo " SELECTED";
            }

            echo ">";
            echo $aInt->lang("services", "otherproductservice");
            echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("services", "productgroup");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"gid\">";
            $query2 = "SELECT * FROM tblservicegroups ORDER BY `order` ASC";
            $result2 = full_query_i($query2);

            while ($data = mysqli_fetch_array($result2)) {
                $gid = $data['id'];
                $gname = $data['name'];
                echo "<option value=\"" . $gid . "\"";

                if ($gid == $groupid) {
                    echo " SELECTED";
                }

                echo ">" . $gname;
            }

            echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("services", "productname");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"productname\" size=\"50\"></td></tr>
</table>
<P ALIGN=\"center\"><input type=\"submit\" value=\"";
            echo $aInt->lang("global", "continue");
            echo " >>\" class=\"button\"></P>
</form>

";
        } else {
            if ($action == "duplicate") {
                checkPermission("Create New Products/Services");
                echo "
<h2>";
                echo $aInt->lang("services", "duplicateservice");
                echo "</h2>

<form method=\"post\" action=\"";
                echo $PHP_SELF;
                echo "?action=duplicatenow\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=150 class=\"fieldlabel\">";
                echo $aInt->lang("services", "existingservice");
                echo "</td><td class=\"fieldarea\">";
                echo "<s";
                echo "elect name=\"existingservice\">";
                $query = "SELECT * FROM tblservicegroups ORDER BY `order` ASC";
                $result = full_query_i($query);

                while ($data = mysqli_fetch_array($result)) {
                    $gid = $data['id'];
                    $gname = $data['name'];
                    $query2 = "SELECT * FROM tblservices WHERE gid=" . (int) $gid;
                    $result2 = full_query_i($query2);

                    while ($data = mysqli_fetch_array($result2)) {
                        $pid = $data['id'];
                        $prodname = $data['name'];
                        echo "<option value=\"" . $pid . "\">" . $gname . " - " . $prodname;
                    }
                }

                echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
                echo $aInt->lang("services", "newservicename");
                echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"newservicename\" size=\"50\"></td></tr>
</table>
<P ALIGN=\"center\"><input type=\"submit\" value=\"";
                echo $aInt->lang("global", "continue");
                echo " >>\" class=\"button\"></P>
</form>

";
            } else {
                if ($action == "creategroup" || $action == "editgroup") {
                    checkPermission("Manage Product Groups");
                    $result = select_query_i("tblservicegroups", "", array("id" => $ids));
                    $data = mysqli_fetch_array($result);
                    $ids = $data['id'];
                    $name = $data['name'];
                    $orderfrmtpl = $data['orderfrmtpl'];
                    $disabledgateways = $data['disabledgateways'];
                    $hidden = $data['hidden'];
                    $disabledgateways = explode(",", $disabledgateways);
                    echo "
<h2>";
                    echo $aInt->lang("services", ($action == "creategroup" ? "creategroup" : "editgroup"));
                    echo "</h2>

<form method=\"post\" action=\"";
                    echo $PHP_SELF;
                    echo "?sub=savegroup&ids=";
                    echo $ids;
                    echo "\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"25%\" class=\"fieldlabel\">";
                    echo $aInt->lang("services", "servicegroupname");
                    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"name\" size=\"40\" value=\"";
                    echo $name;
                    echo "\"></td></tr>
<tr><td class=\"fieldlabel\"><br></td><td class=\"fieldarea\"></td></tr>
<tr><td class=\"fieldlabel\">";
                    echo $aInt->lang("services", "orderfrmtpl");
                    echo "</td><td class=\"fieldarea\">
<div><label><input type=\"radio\" name=\"orderfrmtpl\" value=\"\"";

                    if (!$orderfrmtpl) {
                        echo " checked";
                    }

                    echo " /> Use Default</label></div>
<div class=\"clear\"></div>
";
                    $ordertemplates = array();
                    $ordertplfolder = ROOTDIR . "/templates/orderforms/";

                    if (is_dir($ordertplfolder)) {
                        $dh = opendir($ordertplfolder);

                        while (false !== $folder = readdir($dh)) {
                            if (file_exists($ordertplfolder . $folder . "/services.tpl")) {
                                $ordertemplates[] = $folder;
                            }
                        }

                        closedir($dh);
                    }

                    sort($ordertemplates);
                    foreach ($ordertemplates as $template) {
                        $thumbnail = "../templates/orderforms/" . $template . "/thumbnail.gif";

                        if (!file_exists($thumbnail)) {
                            $thumbnail = "images/ordertplpreview.gif";
                        }

                        echo "<div style=\"float:left;padding:10px;text-align:center;\"><label><img src=\"" . $thumbnail . "\" width=\"165\" height=\"90\" style=\"border:5px solid #fff;\" /><br /><input type=\"radio\" name=\"orderfrmtpl\" value=\"" . $template . "\"";

                        if ($template == $orderfrmtpl) {
                            echo " checked";
                        }

                        echo "> " . ucfirst($template) . "</label></div>";
                    }

                    echo "</td></tr>
<tr><td class=\"fieldlabel\"><br></td><td class=\"fieldarea\"></td></tr>
<tr><td class=\"fieldlabel\">";
                    echo $aInt->lang("services", "availablepgways");
                    echo "</td><td class=\"fieldarea\">";
                    $gateways = getGatewaysArray();
                    foreach ($gateways as $gateway => $name) {
                        echo "<label><input type=\"checkbox\" name=\"gateways[" . $gateway . "]\"" . (!in_array($gateway, $disabledgateways) ? " checked" : "") . " /> " . $name . "</label><br />";
                    }

                    echo "</td></tr>
<tr><td class=\"fieldlabel\"><br></td><td class=\"fieldarea\"></td></tr>
<tr><td class=\"fieldlabel\">";
                    echo $aInt->lang("fields", "hidden");
                    echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"hidden\"";

                    if ($hidden == "on") {
                        echo " checked";
                    }

                    echo "> ";
                    echo $aInt->lang("services", "hiddengroupdesc");
                    echo "</label></td></tr>
";

                    if ($ids) {
                        echo "<tr><td class=\"fieldlabel\"><br></td><td class=\"fieldarea\"></td></tr>
<tr><td class=\"fieldlabel\">";
                        echo $aInt->lang("services", "directcartlink");
                        echo "</td><td class=\"fieldarea\"><input type=\"text\" size=\"100\" value=\"";
                        echo $CONFIG['SystemURL'];
                        echo "/cart.php?gid=";
                        echo ltrim($ids, 0);
                        echo "\" readonly></td></tr>
";
                    }

                    echo "</table>
<p align=\"center\"><input type=\"submit\" value=\"";
                    echo $aInt->lang("global", "savechanges");
                    echo "\" class=\"btn btn-primary\" /> <input type=\"button\" value=\"";
                    echo $aInt->lang("global", "cancelchanges");
                    echo "\" onclick=\"window.location='configservices.php'\" class=\"btn\" /></p>
</form>

";
                }
            }
        }
    }
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>
