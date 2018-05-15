<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Product Addons");
$aInt->title = $aInt->lang("addons", "productaddons");
$aInt->sidebar = "config";
$aInt->icon = "productaddons";
$aInt->helplink = "Product Addons";
$name = $ra->get_req_var("name");
$description = $ra->get_req_var("description");
$tax = $ra->get_req_var("tax");
$showorder = $ra->get_req_var("showorder");
$billingcycle = $ra->get_req_var("paytype");
$autoactivate = $ra->get_req_var("autoactivate");
$suspendproduct = $ra->get_req_var("suspendproduct");
$welcomeemail = $ra->get_req_var("welcomeemail");
$weight = $ra->get_req_var("weight");
$action = $ra->get_req_var("action");
$id = $ra->get_req_var("id");
$menuselect = "$('#menu').multilevelpushmenu('expand','Services');";

if ($action == "save") {
    check_token("RA.admin.default");
    $apppackages = (is_array($packages) ? implode(",", $packages) : "");

    if ($id) {
        $data = array("name" => $name, "description" => html_entity_decode($description), "billingcycle" => $billingcycle, "packages" => $apppackages, "tax" => $tax, "showorder" => $showorder, "autoactivate" => $autoactivate, "suspendproduct" => $suspendproduct, "welcomeemail" => $welcomeemail == 0 ? "NULL" : $welcomeemail, "weight" => $weight);


        delete_query("tbladdontoservice", array("addonid" => $id));
        update_query("tbladdons", $data, array("id" => $id));
    } else {
        $id = insert_query("tbladdons", array("name" => $name, "description" => html_entity_decode($description), "billingcycle" => $billingcycle, "packages" => $apppackages, "tax" => $tax, "showorder" => $showorder, "autoactivate" => $autoactivate, "suspendproduct" => $suspendproduct, "welcomeemail" => $welcomeemail, "weight" => $weight));
        $creatednew = true;
    }
    if (is_array($packages)) {
        foreach ($packages as $row) {
            insert_query("tbladdontoservice", array("addonid" => $id, "serviceid" => $row));
        }
    }

    foreach ($_POST['currency'] as $currency_id => $pricing) {

        if ($creatednew) {
            insert_query("tblpricing", array("type" => "addon", "currency" => $currency_id, "relid" => $id, "msetupfee" => $pricing['msetupfee'], "monthly" => $pricing['monthly']));
            continue;
        }

        update_query("tblpricing", array("msetupfee" => $pricing['msetupfee'], "monthly" => $pricing['monthly']), array("type" => "addon", "currency" => $currency_id, "relid" => $id));
    }

    run_hook("AddonConfigSave", array("id" => $id));

    if ($creatednew) {
        redir("created=true");
    } else {
        redir("saved=true");
    }

    exit();
}


if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("tbladdons", array("id" => $id));
    delete_query("tblpricing", array("type" => "addon", "relid" => $id));
    infoBox($aInt->lang("addons", "addondeletesuccess"), $aInt->lang("addon", "addondelsuccessinfo"));
    redir("deleted=true");
    exit();
}

ob_start();

if (!$action) {
    if ($saved) {
        infoBox($aInt->lang("addons", "changesuccess"), $aInt->lang("addons", "changesuccessinfo"));
    }


    if ($deleted) {
        infoBox($aInt->lang("addons", "addondeletesuccess"), $aInt->lang("addons", "addondelsuccessinfo"));
    }


    if ($created) {
        infoBox($aInt->lang("addons", "addonaddsuccess"), $aInt->lang("addons", "addonaddsuccessinfo"));
    }

    echo $infobox;
    $aInt->deleteJSConfirm("doDelete", "addons", "areyousuredelete", $_SERVER['PHP_SELF'] . "?action=delete&id=");
    echo "
<p>";
    echo $aInt->lang("addons", "description");
    echo "</p>

<p>";
    echo "<s";
    echo "trong>";
    echo $aInt->lang("addons", "options");
    echo ":</strong> <a href=\"";
    echo $PHP_SELF;
    echo "?action=manage\">";
    echo $aInt->lang("addons", "addnew");
    echo "</a></p>

";
    $aInt->sortableTableInit("nopagination");
    $result = select_query_i("tbladdons", "", "", "weight` ASC,`name", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $addonid = $data['id'];
        $packages = $data['packages'];
        $name = $data['name'];
        $description = $data['description'];
        $recurring = $data['recurring'];
        $setupfee = $data['setupfee'];
        $billingcycle = $data['billingcycle'];
        $showorder = $data['showorder'];
        $weight = $data['weight'];
        $showorder = ($showorder ? "<img src=\"images/icons/tick.png\" alt=\"Yes\" border=\"0\" />" : "&nbsp;");
        $tabledata[] = array($name, $description, $billingcycle, $showorder, $weight, "<a href=\"" . $PHP_SELF . "?action=manage&id=" . $addonid . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $addonid . "')\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
    }

    echo $aInt->sortableTable(array($aInt->lang("addons", "name"), $aInt->lang("fields", "description"), $aInt->lang("fields", "billingcycle"), $aInt->lang("addons", "showonorder"), $aInt->lang("addons", "weighting"), "", ""), $tabledata);
} else {
    if ($action == "manage") {
        if ($id) {
            $currency = getCurrency();
            $managetitle = $aInt->lang("addons", "editaddon");
            $result = select_query_i("tbladdons", "*", array("id" => $id));
            $addondetail = mysqli_fetch_assoc($result);


            $query = "select tblservices.id,tblservices.name,tbladdontoservice.serviceid,tblservicegroups.name as groupname from tblservices LEFT JOIN tblservicegroups ON tblservices.gid=tblservicegroups.id LEFT JOIN tbladdontoservice ON (tblservices.id=tbladdontoservice.serviceid and tbladdontoservice.addonid=" . $id . ")";

            $result = full_query_i($query);
            $service = array();
            while ($data = mysqli_fetch_array($result)) {
                $service[$data["id"]] = $data;
                if ($data['id'] == $data['serviceid']) {
                    $service[$data["id"]]['check'] = "checked";
                } else {
                    $service[$data["id"]]['check'] = "";
                }
            }

            $result2 = select_query_i("tblpricing", "", array("type" => "addon", "currency" => $currency['id'], "relid" => $id));
            $price = array();
            while ($data = mysqli_fetch_assoc($result2)) {
                $price[$data['currency']] = $data;
            }
       
            $aInt->assign("price", $price);
            $aInt->assign("paymentoption", $paymentoption);
            $aInt->assign("addon", $addondetail);
            $aInt->assign("service", $service);
        } else {
            $managetitle = $aInt->lang("addons", "createnew");
            $packages = array();
            $weight = 0;
        }


        $aInt->template = "addon/edit";
    }
}


$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jscode = $jscode;
$aInt->jquerycode .=$menuselect;
$aInt->display();
?>