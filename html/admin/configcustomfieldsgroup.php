<?php

/**
 * @ RA
 * */
define("ADMINAREA", true);
require "../init.php";
include_once '../includes/servicefunctions.php';
$aInt = new RA_Admin("View Products/Services");
$aInt->title = "Product/Service Custom Fields";
$aInt->sidebar = "config";
$aInt->icon = "configoptions";
$aInt->helplink = "Configurable Options";
$menuselect = "$('#menu').multilevelpushmenu('expand','Services');";
//$aInt->template= "configcustomfieldsgroup";
$action = $ra->get_req_var("action");
$id = $ra->get_req_var("id");
if ($manageoptions) {
    $result = select_query_i("ra_currency", "", "", "code", "ASC");
    while ($data = mysqli_fetch_array($result)) {
        $curr_id = $data['id'];
        $curr_code = $data['code'];
        $currenciesarray[$curr_id] = $curr_code;
    }
    $totalcurrencies = count($currenciesarray) * 2;

    if ($deleteconfigoption) {
        check_token("RA.admin.default");
        checkPermission("Delete Products/Services");
        delete_query("tblservicetogroup", array("gid" => $confid));
        delete_query("ra_catalog_user_sales_fieldsgroup", array("id" => $confid));
        delete_query("ra_catalog_user_sales_fields", array("gid" => $confid));
        redir("manageoptions=true&cid=" . $cid);
        exit();
    }
    $aInt->title = "Configurable Options";
    $result = select_query_i("ra_catalog_user_sales_fieldsgroup", "", array("id" => $cid));
    $data = mysqli_fetch_array($result);
    $cid = $data['id'];
    $name = $data['name'];
    ob_start();
    echo "";
    echo "<script language=\"JavaScript\">
function deletegroupoption(id) {
	if (confirm(\"Are you sure you want to delete this product configuration option?\")) {
		window.location='";
    echo $PHP_SELF;
    echo "?manageoptions=true&cid=";
    echo $cid;
    echo "&deleteconfigoption=true&confid='+id+'";
    echo generate_token("link");
    echo "';
	}
}
function closewindow() {
	window.opener.document.managefrm.submit();
	window.close();
}
</script>

<form method=\"post\" action=\"";
    echo $_SERVER['PHP_SELF'];
    echo "?manageoptions=true&cid=";
    echo $cid;

    if ($gid) {
        echo "&gid=" . $gid;
    }

    echo "&save=true\">

<p>Option Name: <input type=\"text\" name=\"configoptionname\" size=\"50\" value=\"";
    echo $optionname;
    echo "\" /> Option Type: ";
    echo "<s";
    echo "elect name=\"configoptiontype\"><option value=\"1\"";

    if ($optiontype == "1") {
        echo " selected";
    }

    echo ">Dropdown</option><option value=\"2\"";

    if ($optiontype == "2") {
        echo " selected";
    }

    echo ">Radio</option><option value=\"3\"";

    if ($optiontype == "3") {
        echo " selected";
    }

    echo ">Yes/No</option><option value=\"4\"";

    if ($optiontype == "4") {
        echo " selected";
    }

    echo ">Quantity</option></select>";

    if ($optiontype == "4") {
        echo "<br>Minimum Quantity Required: <input type=\"text\" name=\"qtyminimum\" size=\"6\" value=\"" . $qtyminimum . "\" /> Maximum Allowed: <input type=\"text\" name=\"qtymaximum\" size=\"6\" value=\"" . $qtymaximum . "\" /> (Leave blank for no limit)";
    }

    echo "</p>

<table width=100% align=center cellpadding=2 cellspacing=1 bgcolor=#cccccc>
<tr bgcolor=\"#efefef\" style=\"text-align:center;font-weight:bold;\"><td>Options</td><td width=70> </td><td width=70> </td><td width=70>";
    echo $aInt->lang("billingcycles", "onetime");
    echo "/<br />";
    echo $aInt->lang("billingcycles", "monthly");
    echo "</td><td width=70>Quarterly</td><td width=70>Semi-Annual</td><td width=70>Annual</td><td width=70>Biennial</td><td width=70>Triennial</td><td width=50>Order</td><td width=30>Hide</td></tr>
";
    $x = 0;
    $query = "SELECT * FROM ra_catalog_user_sales_addons_optionssub WHERE configid=" . (int) $cid . " ORDER BY sortorder ASC,id ASC";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        ++$x;
        $optionid = $data['id'];
        $optionname = $data['optionname'];
        $sortorder = $data['sortorder'];
        $hidden = $data['hidden'];
        echo ("<tr bgcolor=\"#ffffff\" style=\"text-align:center;\"><td rowspan=\"" . $totalcurrencies . "\"><input type=\"text\" name=\"optionname[" . $optionid . "]") . "\" value=\"" . $optionname . "\" size=\"40\">";

        if (1 < $x) {
            echo "<br><a href=\"#\" onclick=\"deletegroupoption('" . $optionid . "');return false;\"><img src=\"images/icons/delete.png\" border=\"0\">";
        }

        echo "</td>";
        $firstcurrencydone = false;
        foreach ($currenciesarray as $curr_id => $curr_code) {
            $result2 = select_query_i("ra_catalog_pricebook", "", array("type" => "configoptions", "currency" => $curr_id, "relid" => $optionid));
            $data = mysqli_fetch_array($result2);
            $pricing_id = $data['id'];

            if (!$pricing_id) {
                insert_query("ra_catalog_pricebook", array("type" => "configoptions", "currency" => $curr_id, "relid" => $optionid));
                $result2 = select_query_i("ra_catalog_pricebook", "", array("type" => "configoptions", "currency" => $curr_id, "relid" => $optionid));
                $data = mysqli_fetch_array($result2);
            }

            $val[1] = $data['msetupfee'];
            $val[2] = $data['qsetupfee'];
            $val[3] = $data['ssetupfee'];
            $val[4] = $data['asetupfee'];
            $val[5] = $data['bsetupfee'];
            $val[11] = $data['tsetupfee'];
            $val[6] = $data['monthly'];
            $val[7] = $data['quarterly'];
            $val[8] = $data['semiannually'];
            $val[9] = $data['annually'];
            $val[10] = $data['biennially'];
            $val[12] = $data['triennially'];

            if ($firstcurrencydone) {
                echo "</tr><tr bgcolor=\"#ffffff\" style=\"text-align:center;\">";
            }

            echo (((((((((((((((((("<td rowspan=\"2\" bgcolor=\"#efefef\"><b>" . $curr_code . "</b></td><td>Setup</td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[1]\" size=\"10\" value=\"" . $val['1'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[2]\" size=\"10\" value=\"" . $val['2'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[3]\" size=\"10\" value=\"" . $val['3'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[4]\" size=\"10\" value=\"" . $val['4'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[5]\" size=\"10\" value=\"" . $val['5'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[11]\" size=\"10\" value=\"" . $val['11'] . "\"></td>";

            if (!$firstcurrencydone) {
                echo (("<td rowspan=\"" . $totalcurrencies . "\"><input type=\"text\" name=\"sortorder[" . $optionid . "]") . "\" value=\"" . $sortorder . "\" style=\"width:100%;\"></td><td rowspan=\"" . $totalcurrencies . "\"><input type=\"checkbox\" name=\"hidden[" . $optionid . "]") . "\" value=\"1\"";

                if ($hidden) {
                    echo " checked";
                }

                echo " /></td>";
            }

            echo (((((((((((((((((("</tr><tr bgcolor=\"#ffffff\" style=\"text-align:center;\"><td>Pricing</td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[6]\" size=\"10\" value=\"" . $val['6'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[7]\" size=\"10\" value=\"" . $val['7'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[8]\" size=\"10\" value=\"" . $val['8'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[9]\" size=\"10\" value=\"" . $val['9'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[10]\" size=\"10\" value=\"" . $val['10'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[12]\" size=\"10\" value=\"" . $val['12'] . "\"></td>";
            $firstcurrencydone = true;
        }

        echo "</tr>";
    }


    if (($optiontype == "1" || $optiontype == "2") || $x == "0") {
        echo "<tr bgcolor=\"#efefef\"><td colspan=\"9\"><B>Add Option:</B> <input type=\"text\" name=\"addoptionname\" size=\"60\"></td><td><input type=\"text\" name=\"addsortorder\" value=\"0\" style=\"width:100%;\"></td><td><input type=\"checkbox\" name=\"addhidden\" value=\"1\" /></td></tr>
";
    }

    echo "</table>

<p align=\"center\"><input type=\"submit\" value=\"Save Changes\" class=\"button\" /> <input type=\"button\" value=\"Close Window\" onclick=\"closewindow();\" class=\"button\" /></p>

</form>

";
    $content = ob_get_contents();
    ob_end_clean();
    $aInt->content = $content;
    $aInt->displayPopUp();
    exit();
}


if ($action == "savegroup") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");

    if ($id) {
        update_query("ra_catalog_user_sales_fieldsgroup", array("name" => $name), array("id" => $id));
        $response = "saved";
    } else {
        $id = insert_query("ra_catalog_user_sales_fieldsgroupnames", array("name" => $name));
        $response = "added";
    }

    delete_query("ra_catalog_user_sales_fieldsgrouplinks","");
      foreach ($productlinks as $pid) {
          update_query("ra_catalog", array("cpid" => $id), array("id" => $pid));
      }


    if ($fieldname) {
        foreach ($fieldname as $fid => $value) {

            update_query("ra_catalog_user_sales_fields",
              array(
                "fieldname" => $value,
                "fieldtype" => $fieldtype[$fid],
                "description" => $description[$fid],
                "fieldoptions" => $fieldoptions[$fid],
                "regexpr" => html_entity_decode($regexpr[$fid]),
                "adminonly" => $adminonly[$fid] == "on",
                "required" => $required[$fid],
                "showorder" => $showorder[$fid],
                "showinvoice" => $showinvoice[$fid],
                "sortorder" => $sortorder[$fid]
              ),
              array("id" => $fid)
            );

        }
    }

    if ($addfieldname) {
        insert_query(
          "ra_catalog_user_sales_fields",
          array(
            "gid" => $id,
            "type" => "product",
            "fieldname" => $addfieldname,
            "fieldtype" => $addfieldtype,
            "description" => $adddescription,
            "fieldoptions" => $addfieldoptions,
            "regexpr" => html_entity_decode($addregexpr),
            "adminonly" => $addadminonly,
            "required" => $addrequired,
            "showorder" => $addshoworder,
            "showinvoice" => $addshowinvoice,
            "sortorder" => $addsortorder
          )
        );
    }

    redir("action=managegroup&id=" . $id);
    exit();
}



if ($action == "duplicate") {
    check_token("RA.admin.default");
    checkPermission("Create New Products/Services");
    $result = select_query_i("ra_catalog_user_sales_fieldsgroup", "", array("id" => $existinggroupid));
    $data = mysqli_fetch_array($result);
    $addstr = "";
    foreach ($data as $key => $value) {

        if (is_numeric($key)) {
            if ($key == "0") {
                $value = "";
            }


            if ($key == "1") {
                $value = $newgroupname;
            }

            $addstr .= "'" . db_escape_string($value) . "',";
            continue;
        }
    }

    $addstr = substr($addstr, 0, 0 - 1);
    full_query_i("INSERT INTO ra_catalog_user_sales_fieldsgroup VALUES (" . $addstr . ")");
    $newgroupid = mysqli_insert_id();
    $result = select_query_i("ra_catalog_user_sales_fields", "", array("gid" => $existinggroupid));

    while ($data = mysqli_fetch_array($result)) {
        $configid = $data['id'];
        $addstr = "";
        foreach ($data as $key => $value) {

            if (is_numeric($key)) {
                if ($key == "0") {
                    $value = "";
                }


                if ($key == "1") {
                    $value = $newgroupid;
                }

                $addstr .= "'" . db_escape_string($value) . "',";
                continue;
            }
        }

        $addstr = substr($addstr, 0, 0 - 1);
        full_query_i("INSERT INTO ra_catalog_user_sales_fieldsgroup VALUES (" . $addstr . ")");
        $newconfigid = mysqli_insert_id();
        $result2 = select_query_i("ra_catalog_user_sales_fields", "", array("gid" => $configid));

        while ($data = mysqli_fetch_array($result2)) {
            $optionid = $data['id'];
            $addstr = "";
            foreach ($data as $key => $value) {

                if (is_numeric($key)) {
                    if ($key == "0") {
                        $value = "";
                    }


                    if ($key == "1") {
                        $value = $newconfigid;
                    }

                    $addstr .= "'" . db_escape_string($value) . "',";
                    continue;
                }
            }

            $addstr = substr($addstr, 0, 0 - 1);
            full_query_i("INSERT INTO ra_catalog_user_sales_fields VALUES (" . $addstr . ")");
        }
    }

    redir("duplicated=true");
    exit();
}
if ($action == 'save') {

    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");

    if ($optionname == "") {
        $optionname = array();
    }

    if (isset($_POST['deletefield'])) { // if deleting a field, nothing else is changed
        delete_query("ra_catalog_user_sales_fieldsgroupmembers", array("cfid" => $_POST['deletefield'], "cfgid" => $id));
        delete_query("ra_catalog_user_sales_fields", array("cfid" => $_POST['deletefield']));
    } else { // otherwise we go ahead and reapply all of the form data
        // update $groupname
        if (isset($_POST['name']))
        {
          update_query("ra_catalog_user_sales_fieldsgroupnames", array("name" => db_escape_string($_POST['name']), "cfgid" => $id));
        }

        delete_query("ra_catalog_user_sales_fieldsgrouplinks", array("cfgid"=>$id));
        if (isset($_POST["productlinks"])) {
          foreach ($_POST["productlinks"] as $productlink) {
            insert_query("ra_catalog_user_sales_fieldsgrouplinks",array("cfgid"=>$id,"serviceid"=>$productlink));
          }
        }

        if (isset($_POST['fieldname'])) {
            foreach ($_POST['fieldname'] as $fid => $value) {
                $customfieldname = array(
                    "fieldname" => $value,
                    "fieldtype" => $_POST['fieldtype'][$fid],
                    "description" => $_POST['description'][$fid],
                    "fieldoptions" => $_POST['fieldoptions'][$fid],
                    "regexpr" => html_entity_decode($_POST['regexpr'][$fid]),
                    "adminonly" => $_POST['adminonly'][$fid] == "on" ? 1 : 0,
                    "required" => $_POST['required'][$fid] == "on" ? 1 : 0,
                    "showorder" => $_POST['showorder'][$fid] == "on" ? 1 : 0,
                    "showinvoice" => $_POST['showinvoice'][$fid] == "on" ? 1 : 0,
                    "sortorder" => $_POST['sortorder'][$fid]
                );
                update_query("ra_catalog_user_sales_fields", $customfieldname, array("cfid" => $fid));
            }
            if (isset($_POST['updatelinkfieldname'])) {
                foreach ($_POST['updatelinkfieldname'] as $upfid => $uvalue) {
                    $updatelinkfield = array(
                        "fieldname" => $_POST['updatelinkfieldname'][$upfid],
                        "fieldtype" => $_POST['updatelinkfieldtype'][$upfid],
                        "description" => "",
                        "fieldoptions" => "",
                        "regexpr" => "",
                        "adminonly" => 0,
                        "required" => $_POST['updatelinkrequired'][$upfid] == "on" ? 1 : 0,
                        "showorder" => 0,
                        "showinvoice" => 0,
                        "sortorder" => 0
                    );
                    update_query("ra_catalog_user_sales_fields", $updatelinkfield, array("cfid" => $upfid));
                }
            }
        }
        if (isset($_POST['addfieldname']) && $_POST['addfieldname'] != "") {
            $addcustomfieldname = array(
                "fieldname" => $_POST['addfieldname'],
                "fieldtype" => $_POST['addfieldtype'],
                "description" => $_POST['adddescription'],
                "fieldoptions" => $_POST['addfieldoptions'],
                "regexpr" => html_entity_decode($_POST['addregexpr']),
                "adminonly" => $_POST['addadminonly'] == "on" ? 1 : 0,
                "required" => $_POST['addrequired'] == "on" ? 1 : 0,
                "showorder" => $_POST['addshoworder'] == "on" ? 1 : 0,
                "showinvoice" => $_POST['addshowinvoice'] == "on" ? 1 : 0,
                "sortorder" => $_POST['addsortorder']
            );
            $nfid = insert_query("ra_catalog_user_sales_fields", $addcustomfieldname);

            if (isset($_POST['addlinkfieldname'])) {
                if (is_array($_POST['addlinkfieldname'])) {
                    foreach ($_POST['addlinkfieldname'] as $key => $row) {
                        $linkfields = array(
                            "fieldname" => $_POST['addlinkfieldname'][$key],
                            "fieldtype" => $_POST['addlinkfieldtype'][$key],
                            "description" => "",
                            "fieldoptions" => "",
                            "regexpr" => "",
                            "adminonly" => 0,
                            "required" => $_POST['addlinkrequired'][$key] == "on" ? 1 : 0,
                            "showorder" => 0,
                            "showinvoice" => 0,
                            "sortorder" => 0,
                            "parent_id" => $nfid
                        );
                        $newid = insert_query("ra_catalog_user_sales_fields", $linkfields);
                        insert_query("ra_catalog_user_sales_fieldsgroupmembers", array("cfgid" => $id, "cfid" => $newid));
                    }
                }
            }
        }


        if (isset($nfid)) {

            insert_query("ra_catalog_user_sales_fieldsgroupmembers", array("cfgid" => $id, "cfid" => $nfid));
        }
    }
    redir("action=managegroup&id=" . $id . "&success=true");
    exit();
}

if ($action == "deleteoption") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");
    delete_query("ra_catalog_user_sales_addons_options", array("id" => $opid));
    delete_query("ra_catalog_user_sales_addons_optionssub", array("configid" => $opid));
    delete_query("tblhostingconfigoptions", array("configid" => $opid));
    redir("action=managegroup&id=" . $id);
    exit();
}


if ($action == "deletegroup") {

    check_token("RA.admin.default");
    checkPermission("Delete Products/Services");
    delete_query("ra_catalog_user_sales_fieldsgroupnames", array("cfgid" => $id));
    delete_query("ra_catalog_user_sales_fieldsgroupmembers", array("cfgid" => $id));


    redir("deleted=true");
    exit();
}

ob_start();
$jscode = "function doDelete(id) {
if (confirm(\"Are you sure you want to delete this configurable option group?\")) {
window.location='" . $_SERVER['PHP_SELF'] . "?action=deletegroup&id='+id+'" . generate_token("link") . "';
}}";

if ($action == "") {
    if ($deleted) {
        infoBox("Success", "The option group has been deleted successfully!");
    }
    if ($duplicated) {
        infoBox("Success", "The option group has been duplicated successfully!");
    }

    $aInt->sortableTableInit("nopagination");
    $result = select_query_i("ra_catalog_user_sales_fieldsgroupnames", "", "", "name", "ASC");

    while ($data = mysqli_fetch_array($result)) {

        $id = $data['cfgid'];
        $name = $data['name'];
        $tabledata[] = array($id, $name);
    }

    $aInt->assign('tabledatas', $tabledata);
    $aInt->assign("infobox", $infobox);
    $aInt->template = "customfieldgroup/configcustomfieldsgroup";

// action=managegroup
// Usually POST - actions:
// * Modify name in ra_catalog_user_sales_fieldsgroupnames
// * Change assigned products in ra_catalog_user_sales_fieldsgrouplinks
// * Define new custom field as a member of the group (ra_catalog_user_sales_fields)
// * Change existing customfield attributes (ra_catalog_user_sales_fields)
} elseif ($action == "managegroup") {
    $allservice = array();
    $services = select_query_i('ra_catalog', "id,type,name");
    // populate $allservice with [id,type,name]
    while ($data = mysqli_fetch_array($services)) {
        $allservice [$data['id']] = array(
            'data' => $data,
//            'check' => in_array($data['id'], $linkedproducts) ? "selected" : ""
           'check' => ""
        );
    }
    // populate $allservice['id'] with " selected" for html decoration if in  ra_catalog_user_sales_fieldsgroupmembers
    $linkedproducts = select_query_i("ra_catalog_user_sales_fieldsgrouplinks","serviceid",array(cfgid=>$id));
    while ($data = mysqli_fetch_array($linkedproducts)) {
      $allservice[$data['serviceid']]['check'] = " selected";
    }

    $aInt->assign('productlinks', $allservice);
    if ($id) {

        if ($_GET['success']) {
            infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"));
        }
        $action = "save";
        $steptitle = "Manage Group";
        $query = "select ra_catalog_user_sales_fields.* from ra_catalog_user_sales_fields INNER JOIN ra_catalog_user_sales_fieldsgroupmembers on (ra_catalog_user_sales_fields.cfid = ra_catalog_user_sales_fieldsgroupmembers.cfid AND ra_catalog_user_sales_fieldsgroupmembers.cfgid=" . $id . ") ORDER BY ra_catalog_user_sales_fields.sortorder";
        $result = full_query_i($query);
        $datas = array();
        while ($data = mysqli_fetch_assoc($result)) {
            $datas[$data['cfid']] = $data;
        }
        foreach ($datas as $cfid => $row) {
            if (isset($datas[$row["parent_id"]])) {
                $datas[$row["parent_id"]]['children'][] = $row;
                unset($datas[$cfid]);
            }
        }
        $query = "select * from ra_catalog_user_sales_fieldsgroupnames where cfgid=" . $id;
        $result = full_query_i($query);
        $data2 = mysqli_fetch_assoc($result);
        $name = $data2['name'];
        $aInt->assign('datas', $datas);




        $aInt->assign('name', $name);
        $aInt->assign('id', $id);
        $aInt->assign('infobox', $infobox);
        $aInt->template = "customfieldgroup/managegroup";
    } else {
        $action = "savegroup";
        checkPermission("Create New Products/Services");
        $steptitle = "Create a New Group";
        $id = "";

        $aInt->template = "customfieldgroup/creategroup";
    }

    $result = select_query_i("ra_catalog", "ra_catalog.id,ra_catalog.name,ra_catalog_groups.name AS groupname", "", "groupname` ASC,`name", "ASC", "", "ra_catalog_groups ON ra_catalog.gid=ra_catalog_groups.id");

    echo "</select></td></tr></table>";
} elseif ($action == "duplicategroup") {
    checkPermission("Create New Products/Services");
    echo "
<p><b>Duplicate Group</b></p>

<form method=\"post\" action=\"";
    echo $PHP_SELF;
    echo "?action=duplicate\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=150 class=\"fieldlabel\">Existing Group</td><td class=\"fieldarea\">";
    echo "<s";
    echo "elect name=\"existinggroupid\">";
    $result = select_query_i("ra_catalog_user_sales_fieldsgroup", "", "", "id", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $name = $data['name'];



        echo "<option value=\"" . $id . "\">" . $name . "</option>";
    }

    echo "</select></td></tr>
<tr><td class=\"fieldlabel\">New Group Name</td><td class=\"fieldarea\"><input type=\"text\" name=\"newgroupname\" size=\"50\"></td></tr>
</table>
<P ALIGN=\"center\"><input type=\"submit\" value=\"Continue >>\" class=\"button\"></P>
</form>

";
} else {

}



$aInt->jquerycode = $jquerycode;
$aInt->jquerycode .= $menuselect;
$aInt->jscode = $jscode;
//$aInt->template = "customfieldgroup/view";
$aInt->display();
?>
