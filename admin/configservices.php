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

    if ($tax == "on") {
        $tax = "1";
    }

    // $overagesenabled = ($overagesenabled ? "1," . $overageunitsdisk . "," . $overageunitsbw : "");
    if ($_POST['welcomeemail'] == 0) {
        $welcomeemail = "NULL";
    } else {
        $welcomeemail = $_POST['welcomeemail'];
    }
    $table = "tblservices";
    $array = array(
        "type" => $_POST['type'],
        "gid" => $_POST['gid'],
        "name" => $_POST['name'],
        'contract' => $_POST['contract'] == "on" ? 1 : 0,
        'etf' => $_POST['etf'],
        'term' => $_POST['term'],
        "description" => html_entity_decode($_POST['description']),
        "hidden" => $_POST['hidden'],
        "welcomeemail" => $welcomeemail,
        "paytype" => $_POST['paytype'],
        "servertype" => $_POST['servertype'],
        "recurringcycles" => $_POST['recurringcycles'],
        "autoterminatedays" => $_POST['autoterminatedays'],
        "autoterminateemail" => $_POST['autoterminateemail'],
        "tax" => $tax,
        'affiliateonetime' => $_POST['affiliateonetime'],
        "affiliatepaytype" => $_POST['affiliatepaytype'],
        "affiliatepayamount" => $_POST['affiliatepayamount'],
    );


//    $counter = 1;
//
//
//    while ($counter <= 24) {
//        $array["configoption" . $counter] = trim($packageconfigoption[$counter]);
//        $counter += 1;
//    }



    update_query("tblservices", $array, array("id" => $id));


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

    //RebuildModuleHookCache();
    //  run_hook("ProductEdit", array_merge(array("pid" => $id), $array));
    // run_hook("AdminProductConfigFieldsSave", array("pid" => $id));
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
        $id = insert_query("tblservicegroups", array("name" => $name, "type" => 2, "orderfrmtpl" => $orderfrmtpl, "disabledgateways" => implode(",", $disabledgateways), "hidden" => $hidden, "order" => get_query_val("tblservicegroups", "`order`", "", "order", "DESC") + 1));
        foreach ($customefield as $row) {
            insert_query("tblcustomerfieldsgrouplinks", array('cfgid' => $row, 'serviceid' => '', 'servicegid' => $id));
        }
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


    $lang = array(
        'fields' => $aInt->lang("fields", "producttype"),
        'hostingaccount' => $aInt->lang("services", "hostingaccount"),
        'reselleraccount' => $aInt->lang("services", "reselleraccount"),
        'server' => $aInt->lang("services", "dedicatedvpsserver"),
        'other' => $aInt->lang("setup", "other"),
        'servicegroup' => $aInt->lang("services", "servicegroup"),
        'servicename' => $aInt->lang("services", "servicename"),
        'servicedesc' => $aInt->lang("services", "servicedesc"),
        'htmlallowed' => $aInt->lang("services", "htmlallowed"),
        'htmlnewline' => $aInt->lang("services", "htmlnewline"),
        'htmlbold' => $aInt->lang("services", "htmlbold"),
        'htmlitalics' => $aInt->lang("services", "htmlitalics"),
        'welcomeemail' => $aInt->lang("services", "welcomeemail"),
        'none' => $aInt->lang("global", "none"),
        'applytax' => $aInt->lang("services", "applytax"),
        'applytaxdesc' => $aInt->lang("serivce", "applytaxdesc"),
        'hidden' => $aInt->lang("fields", "hidden"),
        'hiddendesc' => $aInt->lang("services", "hiddendesc"),
        'retired' => $aInt->lang("services", "retired"),
        'retireddesc' => $aInt->lang("services", "retireddesc"),
        'contract' => $aInt->lang("services", "contract"),
        'contractdes' => $aInt->lang("services", "contractdes"),
        'etf' => $aInt->lang("services", "etf"),
        'term' => $aInt->lang("services", "term"),
        'paymenttype' => $aInt->lang("services", "paymenttype"),
        'free' => $aInt->lang("billingcycles", "free"),
        'onetime' => $aInt->lang("billingcycles", "onetime"),
        'monthly' => $aInt->lang("billingcycles", "monthly"),
        'quarterly' => $aInt->lang("billingcycles", "quarterly"),
        'semiannually' => $aInt->lang("billingcycles", "semiannually"),
        'annually' => $aInt->lang("billingcycles", "annually"),
        'biennially' => $aInt->lang("billingcycles", "biennially"),
        'disablepaymenttermdesc' => $aInt->lang("services", "disablepaymenttermdesc"),
        'triennially' => $aInt->lang("billingcycles", "triennially"),
        'allowqty' => $aInt->lang("services", "allowqtydesc"),
        'recurringcycleslimit' => $aInt->lang("services", "recurringcycleslimit"),
        'recurringcycleslimitdesc' => $aInt->lang("services", "recurringcycleslimitdesc"),
        'autoterminatefixedterm' => $aInt->lang("services", "autoterminatefixedterm"),
        'autoterminatefixedtermdesc' => $aInt->lang("services", "autoterminatefixedtermdesc"),
        'terminationemail' => $aInt->lang("services", "terminationemail"),
        'chooseemailtplfixedtermend' => $aInt->lang("services", "chooseemailtplfixedtermend"),
        'modulename' => $aInt->lang("services", "modulename"),
        'none' => $aInt->lang("global", "none"),
        'setupfee' => $aInt->lang("fields", "setupfee")
    );
    $result2 = select_query_i('tblservicegroups', '*', "", 'order', 'ASC');
    $servicegroups = array();
    while ($groups = mysqli_fetch_assoc($result2)) {
        $servicegroups[$groups['id']] = $groups['name'];
    }
 
    if ($action == "edit") {
        $result = select_query_i("tblservices", "", array("id" => $id));
        $data = mysqli_fetch_assoc($result);
        $id = $data['id'];
        $type = $data['type'];
        $groupid = $gid = $data['gid'];
        $name = $data['name'];
        $description = $data['description'];
        $hidden = $data['hidden'];
        $welcomeemail = $data['welcomeemail'];
        $paytype = $data['paytype'];
        $allowqty = $data['allowqty'];
        $autosetup = $data['autosetup'];
        $servergroup = $data['servergroup'];
        $stockcontrol = $data['stockcontrol'];
        $contract = $data['contract'];
        $etf = $data['etf'];
        $term = $data['term'];
        $qty = $data['qty'];
        $proratabilling = $data['proratabilling'];
        $proratadate = $data['proratadate'];
        $proratachargenextmonth = $data['proratachargenextmonth'];
        $servertype = $data['servertype'];


        $aInt->assign('data', $data);

        $counter = 1;

        while ($counter <= 24) {
            $packageconfigoption[$counter] = $data["configoption" . $counter];
            $counter += 1;
        }
        // $aInt->assign('services', $data);

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
        $overagesenabled = explode(",", $overagesenabled);
        $downloads = unserialize($downloads);
        $order = $data['order'];


        if ($success) {
            infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"));
        }





        $tabledata = array();
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
            $tabledata[$currency_id] = $data;
        }



        $result = select_query_i("tblemailtemplates", "id,name", array("type" => "product", "language" => ""));
        $autoemail = array();
        while ($data = mysqli_fetch_array($result)) {
            $autoemail[$data['id']] = array(
                'name' => $data['name'],
                'select' => $welcomeemail == $data['id'] ? "selected" : "",
                'termninate' => $autoterminateemail == $data['id'] ? "selected" : ""
            );
        }

        $modulesarray = array();
        $dh = opendir(ROOTDIR . "/modules/service/");



        if (!empty($dh)) {
            while ($file = readdir($dh)) {
                if (is_file(ROOTDIR . ("/modules/service/" . $file . "/" . $file . ".php"))) {

                    $modulesarray [] = array(
                        'name' => $file,
                        'select' => $file == $servertype ? 'selected' : '',
                    );
                }
            }
            closedir($dh);
            sort($modulesarray);
        }

        $aInt->assign("modulesarray", $modulesarray);

        if ($servertype && in_array($servertype, $modulesarray)) {


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
                }
            }

            $configoptionlinks = array();
            $result = select_query_i("tblserviceconfiglinks", "", array("pid" => $id));

            while ($data = mysqli_fetch_array($result)) {
                $configoptionlinks[] = $data['gid'];
            }

            $aInt->assign('tblserviceconfiglinks', $configoptionlinks);

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
        }
        $templatefile = 'services/edit';
    } else {
        if ($action == "create") {
            checkPermission("Create New Products/Services");


            $templatefile = 'services/create';
        } else {
            if ($action == "duplicate") {
                checkPermission("Create New Products/Services");
                $query = "SELECT * FROM tblservicegroups ORDER BY `order` ASC";
                $result = full_query_i($query);
                $service = array();
                while ($data = mysqli_fetch_array($result)) {
                    $gid = $data['id'];
                    $gname = $data['name'];
                    $query2 = "SELECT * FROM tblservices WHERE gid=" . (int) $gid;
                    $result2 = full_query_i($query2);
                    while ($data = mysqli_fetch_array($result2)) {
                        $service [$data['id']] = array(
                            'gname' => $gname,
                            'prodname' => $data['name']
                        );
                    }
                }
                $aInt->assign('service', $service);
                $templatefile = 'services/duplicate';
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
                    $queryone = "SELECT * FROM tblcustomfieldsgroupnames";
                    $result = full_query_i($queryone);
                    $option = mysqli_fetch_array($query);
                    $cdata = array();
                    while ($data = mysqli_fetch_assoc($result)) {
                        $cdata[] = $data;
                    }
                    $ordertemplates = array();
                    $ordertplfolder = ROOTDIR . "/templates/orderforms/";
                    if (is_dir($ordertplfolder)) {
                        $dh = opendir($ordertplfolder);

                        while (false !== $folder = readdir($dh)) {
                            if (file_exists($ordertplfolder . $folder . "/services.tpl")) {
                                $thumbnail = "../templates/orderforms/" . $folder . "/thumbnail.gif";
                                if (!file_exists($thumbnail)) {
                                    $thumbnail = "images/ordertplpreview.gif";
                                }
                                $ordertemplates[] = array(
                                    'template' => $folder,
                                    'thumb' => $thumbnail,
                                    'checked' => $template == $orderfrmtpl ? "checked" : ""
                                );
                            }
                        }

                        closedir($dh);
                    }


                    $avaiablegateways = array();
                    $gateways = getGatewaysArray();
                    foreach ($gateways as $gateway => $name) {
                        $avaiablegateway [] = array(
                            'value' => $gateway,
                            'name' => $name,
                            'check' => !in_array($gateway, $disabledgateways) ? " checked" : ""
                        );
                    }
                    $aInt->assign('cdata', $cdata);
                    $aInt->assign('ordertemplates', $ordertemplates);
                    $aInt->assign('avaiablegateway', $avaiablegateway);
                    $templatefile = 'services/creategroup';
                }
            }
        }
    }

    $aInt->assign('autoemail', $autoemail);
    $aInt->assign('servicegroups', $servicegroups);
    $aInt->assign('langs', $lang);
    $aInt->assign('infobox', $infobox);
    $aInt->assign('tabledata', $tabledata);
    //  echo print_r($servicegroups);
    if (isset($templatefile) && $templatefile != "") {
        $aInt->template = $templatefile;
    }
}

$content = ob_get_contents();
ob_end_clean();

$aInt->content = $content;


$aInt->display();
?>
