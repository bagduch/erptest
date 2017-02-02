<?php

/**
 *
 * @ RA
 *
 * */
define("ADMINAREA", true);
require "../init.php";
include_once '../includes/servicefunctions.php';
include_once '../includes/orderfunctions.php';
$aInt = new RA_Admin("View Products/Services");
$aInt->sidebar = "config";
$aInt->icon = "configservices";
$aInt->requiredFiles(array("modulefunctions", "gatewayfunctions"));
$menuselect = "$('#menu').multilevelpushmenu('expand','Services');";

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
        'individual' => $_POST['isale'] == "on" ? 1 : 0,
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
        "revenuecode" => $_POST['rcode'],
        'affiliateonetime' => $_POST['affiliateonetime'],
        "affiliatepaytype" => $_POST['affiliatepaytype'],
        "affiliatepayamount" => $_POST['affiliatepayamount'],
    );

    update_query("tblservices", $array, array("id" => $id));
    foreach ($_POST['currency'] as $currency_id => $pricing) {
        update_query("tblpricing", $pricing, array("currency" => $currency_id, "relid" => $id));
    }
    if ($customfieldname) {
        foreach ($customfieldname as $fid => $value) {
            update_query("tblcustomfields", array("fieldname" => $value, "fieldtype" => $customfieldtype[$fid], "description" => $customfielddesc[$fid], "fieldoptions" => $customfieldoptions[$fid], "regexpr" => html_entity_decode($customfieldregexpr[$fid]), "adminonly" => $customadminonly[$fid], "required" => $customrequired[$fid], "showorder" => $customshoworder[$fid], "showinvoice" => $customshowinvoice[$fid], "sortorder" => $customsortorder[$fid]), array("cfid" => $fid));
        }
    }

    if (isset($_POST['linkasscoiateservice'])) {
        delete_query("tblservicetoservice", array("children_id" => $id));
        foreach ($_POST['linkasscoiateservice'] as $key => $value) {
            if ($value == "on") {
                insert_query("tblservicetoservice", array("parent_id" => $key, "children_id" => $id));
            }
        }
    }
    // error_log($query, 3, "/tmp/php_errors.log");
    delete_query("tblcustomfieldsgrouplinks", array("serviceid" => $id));
    if ($_POST['configoptionlinks']) {
        foreach ($_POST['configoptionlinks'] as $row) {
            insert_query("tblcustomfieldsgrouplinks", array("cfgid" => $row, "serviceid" => $id));
        }
    }

//here
    if ($addfieldname) {
        insert_query("tblcustomfields", array("type" => "product", "fieldname" => $addfieldname, "fieldtype" => $addfieldtype, "description" => $addcustomfielddesc, "fieldoptions" => $addfieldoptions, "regexpr" => html_entity_decode($addregexpr), "adminonly" => $addadminonly, "required" => $addrequired, "showorder" => $addshoworder, "showinvoice" => $addshowinvoice, "sortorder" => $addsortorder));
    }

    delete_query("tblserviceconfiglinks", array("pid" => $id));

    delete_query("tbladdontoservice", array("serviceid" => $id));
    if (!empty($_POST['addons'])) {
        foreach ($_POST['addons'] as $row) {
            insert_query("tbladdontoservice", array("serviceid" => $id, "addonid" => $row));
        }
    }

    // delete_query("tblcustomfieldsgrouplinks", $where);

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
        update_query("tblservicegroups", array("name" => $name, "type" => $type, "orderfrmtpl" => $orderfrmtpl, "disabledgateways" => implode(",", $disabledgateways), "hidden" => $hidden), array("id" => $ids));
        delete_query("tblcustomfieldsgrouplinks", array('servicegid' => $ids));


        if ($customefield) {
            foreach ($customefield as $row) {

                insert_query("tblcustomfieldsgrouplinks", array('cfgid' => $row, 'serviceid' => "NULL", 'servicegid' => $ids));
            }
        }
    } else {
        $id = insert_query("tblservicegroups", array("name" => $name, "type" => $type, "orderfrmtpl" => $orderfrmtpl, "disabledgateways" => implode(",", $disabledgateways), "hidden" => $hidden, "order" => get_query_val("tblservicegroups", "`order`", "", "order", "DESC") + 1));
        foreach ($customefield as $row) {
            insert_query("tblcustomfieldsgrouplinks", array('cfgid' => $row, 'serviceid' => "NULL", 'servicegid' => $ids));
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



if ($action == "") {
    $result = select_query_i("tblservicegroups", "COUNT(*)", "");
    $data = mysqli_fetch_array($result);
    $num_rows = $data[0];
    $result = select_query_i("tblservices", "COUNT(*)", "");
    $data = mysqli_fetch_array($result);
    $num_rows2 = $data[0];
    $js = $aInt->deleteJSConfirm("doDelete", "services", "deleteserviceconfirm", "?sub=delete&id=");
    //  $js .=$aInt->deleteJSConfirm("doGroupDelete", "services", "deletegroupconfirm", "?sub=deletegroup&id=");

    $lang = array(
        "options" => $aInt->lang("addons", "options"),
        "createnewgroup" => $aInt->lang("services", "createnewgroup"),
        "createnewservice" => $aInt->lang("services", "createnewservice"),
        "duplicateservice" => $aInt->lang("services", "duplicateservice"),
        "type" => $aInt->lang("fields", "type"),
        "servicename" => $aInt->lang("services", "servicename"),
        "sortorder" => $aInt->lang("services", "sortorder"),
        "paytype" => $aInt->lang("services", "paytype"),
        "price" => $aInt->lang("services", "price"),
        "autosetup" => $aInt->lang("services", "autosetup"),
        "deletegrouperror" => $aInt->lang("services", "deletegrouperror", 1),
        "groupname" => $aInt->lang("fields", "groupname"),
        "navmoveup" => $aInt->lang("services", "navmoveup"),
        "navmovedown" => $aInt->lang("services", "navmovedown"),
        "edit" => $aInt->lang("global", "edit"),
        "delete" => $aInt->lang("global", "delete"),
        "deleteserviceerror" => $aInt->lang("services", "deleteserviceerror", 1),
        "asetupafteracceptpendingorder" => $aInt->lang("services", "asetupafteracceptpendingorder"),
        "asetupinstantlyafterorder" => $aInt->lang("services", "asetupinstantlyafterorder"),
        "asetupafterpay" => $aInt->lang("services", "asetupafterpay"),
        "off" => $aInt->lang("services", "off"),
        "free" => $aInt->lang("billingcycles", "free"),
        "onetime" => $aInt->lang("billingcycles", "onetime"),
        "recurring" => $aInt->lang("status", "recurring"),
        "proratabilling" => $aInt->lang("services", "proratabilling"),
        "hostingaccount" => $aInt->lang("services", "hostingaccount"),
        "reselleraccount" => $aInt->lang("services", "reselleraccount"),
        "dedicatedvpsserver" => $aInt->lang("services", "dedicatedvpsserver"),
        "otherproductservice" => $aInt->lang("services", "otherproductservice"),
        "noproductsingroupsetup" => $aInt->lang("services", "noproductsingroupsetup"),
        "updatesort" => $aInt->lang("services", "updatesort"),
        "nogroupssetup" => $aInt->lang("services", "nogroupssetup")
    );
    $servicegroup = array();
    $result = select_query_i("tblservicegroups", "", "", "order", "DESC");
    while ($groups = mysqli_fetch_array($result)) {
        // error_log(print_r($groups, 1), 3, "/tmp/php_errors.log");
        $servicegroup[$groups['id']]['group'] = $groups;

        $result2 = select_query_i("tblservices", "COUNT(*)", array("gid" => $groups['id']));
        $data = mysqli_fetch_array($result2);
        $num_rows = $data[0];
        if (0 < $num_rows) {
            $servicegroup[$groups['id']]['group']['deletelink'] = "alert('" . $aInt->lang("services", "deletegrouperror", 1) . "')";
        } else {
            $servicegroup[$groups['id']]['group']['deletelink'] = "doGroupDelete('" . $groups['id'] . "')";
        }

        $query2 = select_query_i("tblservices", "", array("gid" => $groups['id']), "order", "ASC");
        while ($services = mysqli_fetch_array($query2)) {
            $servicegroup[$groups['id']]['service'][$services['id']] = $services;
            $result2 = select_query_i("tblcustomerservices", "COUNT(*)", array("packageid" => $services['id']));
            $data2 = mysqli_fetch_array($result2);
            $num_rows2 = $data2[0];
            if (0 < $num_rows2) {
                $servicegroup[$groups['id']]['service'][$services['id']]['deletelink'] = "alert('" . $aInt->lang("services", "deletegrouperror", 1) . "')";
            } else {
                $servicegroup[$groups['id']]['service'][$services['id']]['deletelink'] = "doDelete('" . $services['id'] . "')";
            }
        }
    }
    $lastorder = $data['order'];
    $aInt->title = "Services";
    // $result2 = select_query_i("tblservicegroups", "", "", "order", "ASC");
    // error_log(print_r($servicegroup, 1), 3, "/tmp/php_errors.log");
    $aInt->assign('token', generate_token());
    $aInt->assign('servicegroup', $servicegroup);
    $templatefile = 'services/view';
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
        // get all service data
        $currecy = getCurrency();
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
        $individual = $data['individual'];
        $term = $data['term'];
        $qty = $data['qty'];
        $proratabilling = $data['proratabilling'];
        $proratadate = $data['proratadate'];
        $proratachargenextmonth = $data['proratachargenextmonth'];
        $servertype = $data['servertype'];
        $configserice = getCustomeFieldGroup($id);
        $aInt->assign('configservice', $configserice);
        //  $aInt->assign('data', $data);
        $counter = 1;
        while ($counter <= 24) {
            $packageconfigoption[$counter] = $data["configoption" . $counter];
            $counter += 1;
        }
        $aInt->assign('services', $data);

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
        }
        $query = "SELECT * FROM `tblservicetoservice` WHERE `parent_id` = " . $id;

        $result = full_query_i($query);
        $asscoiateid = array();
        while ($data = mysqli_fetch_assoc($result)) {
            $asscoiateid[] = $data['children_id'];
        }
        $query2 = "select * from tblgrouptogroup";

        $asscoproduct = array();
        $query = "SELECT tblservices.*,tblservicegroups.name as groupname FROM tblservices 
            LEFT JOIN tblservicegroups ON tblservices.gid = tblservicegroups.id 
            where tblservices.id!=" . $id;
        $result = full_query_i($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $asscoproduct[$data['groupname']][$data['id']] = $data;


            if (in_array($data['id'], $asscoiateid)) {
                $asscoproduct[$data['groupname']][$data['id']]['check'] = "checked";
            } else {
                $asscoproduct[$data['groupname']][$data['id']]['check'] = "";
            }
        }

        $aInt->title = "Edit Service #" . $name;
        $aInt->assign('asscoproduct', $asscoproduct);

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
                    $groupdata = mysqli_fetch_array($result);

                    $ids = $groupdata['id'];
                    $name = $groupdata['name'];
                    $orderfrmtpl = $groupdata['orderfrmtpl'];
                    $disabledgateways = $groupdata['disabledgateways'];
                    $hidden = $groupdata['hidden'];
                    $disabledgateways = explode(",", $disabledgateways);
                    $queryone = "SELECT * FROM tblcustomfieldsgroupnames as tcgn LEFT JOIN tblcustomfieldsgrouplinks as tcfgl on (tcgn.cfgid=tcfgl.cfgid AND tcfgl.servicegid=" . $ids . ")";
                    $result = full_query_i($queryone);
                    $option = mysqli_fetch_array($query);
                    $cdata = array();
                    while ($data = mysqli_fetch_assoc($result)) {
                        $cdata[$data['cfgid']] = $data;
                        if (isset($data['id'])) {
                            $cdata[$data['cfgid']]['check'] = true;
                        } else {
                            $cdata[$data['cfgid']]['check'] = false;
                        }
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
                    if (empty($groupdata)) {
                        $aInt->title = "Create Group";
                    } else {
                        $aInt->title = "Edit Group";
                    }
                    $aInt->assign("groupdata", $groupdata);
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
}
$aInt->assign('langs', $lang);
if (isset($templatefile) && $templatefile != "") {
    $aInt->template = $templatefile;
}

$aInt->jquerycode .=$menuselect;

$aInt->display();
?>
