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
$aInt = new RA_Admin("Configure Product Addons");
$aInt->title = $aInt->lang("addons", "productaddons");
$aInt->sidebar = "config";
$aInt->icon = "productaddons";
$aInt->helplink = "Product Addons";

if ($action == "save") {
    check_token("RA.admin.default");
    $apppackages = (is_array($packages) ? implode(",", $packages) : "");

    if ($id) {

        delete_query("tbladdontoservice", array("addonid" => $id));
        update_query("tbladdons", array("name" => $name, "description" => html_entity_decode($description), "billingcycle" => $billingcycle, "packages" => $apppackages, "tax" => $tax, "showorder" => $showorder, "autoactivate" => $autoactivate, "suspendproduct" => $suspendproduct, "downloads" => implode(",", $downloads), "welcomeemail" => $welcomeemail, "weight" => $weight), array("id" => $id));
    } else {
        $id = insert_query("tbladdons", array("name" => $name, "description" => html_entity_decode($description), "billingcycle" => $billingcycle, "packages" => $apppackages, "tax" => $tax, "showorder" => $showorder, "autoactivate" => $autoactivate, "suspendproduct" => $suspendproduct, "downloads" => implode(",", $downloads), "welcomeemail" => $welcomeemail, "weight" => $weight));
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
        $tabledata[] = array($name, $description, $billingcycle, $showorder, $weight, "<a href=\"" . $PHP_SELF . "?action=manage&id=" . $addonid . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "edit") . "\"></a>", "<a href=\"#\" onClick=\"doDelete('" . $addonid . "')\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "delete") . "\"></a>");
    }

    echo $aInt->sortableTable(array($aInt->lang("addons", "name"), $aInt->lang("fields", "description"), $aInt->lang("fields", "billingcycle"), $aInt->lang("addons", "showonorder"), $aInt->lang("addons", "weighting"), "", ""), $tabledata);
} else {
    if ($action == "manage") {
        if ($id) {
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
            $paymentdropdown = $aInt->cyclesDropDown($addondetail['billingcycle']);
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
$aInt->display();
?>