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
$aInt = new RA_Admin("List Clients");
$aInt->title = $aInt->lang("clients", "viewsearch");
$aInt->sidebar = "clients";
$aInt->icon = "clients";
$name = "clients";
$orderby = "id";
$sort = "DESC";
$pageObj = new RA_Pagination($name, $orderby, $sort);
$pageObj->digestCookieData();
$tbl = new RA_ListTable($pageObj);
$tbl->setColumns(array("checkall", array("id", $aInt->lang("fields", "id")), array("firstname", $aInt->lang("fields", "firstname")), array("lastname", $aInt->lang("fields", "lastname")), array("companyname", $aInt->lang("fields", "companyname")), array("email", $aInt->lang("fields", "email")), $aInt->lang("fields", "services"), "Products", array("datecreated", $aInt->lang("fields", "created")), array("status", $aInt->lang("fields", "status"))));
$clientsModel = new RA_Clients($pageObj);
$filters = new RA_Filter();
ob_start();
echo $aInt->Tabs(array($aInt->lang("global", "searchfilter")), true);



$langdata = array(
    'searchfilterlang' => $aInt->lang("global", "searchfilter"),
    'clientnamelang' => $aInt->lang("fields", "clientname"),
    'companynamelang' => $aInt->lang("fields", "companyname"),
    'emaillang' => $aInt->lang("fields", "email"),
    'addresslang' => $aInt->lang("fields", "address"),
    'anylang' => $aInt->lang("global", "any"),
    'statuslang' => $aInt->lang("fields", "status"),
    'activelang' => $aInt->lang("status", "active"),
    'inactivelang' => $aInt->lang("status", "inactive"),
    'closelang' => $aInt->lang("status", "closed"),
    'statelang' => $aInt->lang("fields", "state"),
    'clientgrouplang' => $aInt->lang("fields", "clientgroup"),
    'activelang' => $aInt->lang("status", "active"),
    'cardlast4lang' => $aInt->lang("fields", "cardlast4"),
    'searchlng' => $aInt->lang("global", "search"),
    'phonenumberlang' => $aInt->lang("fields", "phonenumber"),
    'currencylang' => $aInt->lang("currencies", "currency"),
);


$clientgroup = array();
foreach ($clientsModel->getGroups() as $id => $values) {
    $clientgroup [$id] = $values['name'];
}

$result = select_query_i("tblcurrencies", "id,code", "", "code", "ASC");
$currencys = array();
while ($data = mysqli_fetch_assoc($result)) {
    $currencys[$data['id']] = $data['code'];
}


//if ($status == "Active") {
//    echo " selected";
//}
//
//
//echo $aInt->lang("status", "active");
//echo "</option><option value=\"Inactive\"";
//
//if ($status == "Inactive") {
//    echo " selected";
//}
//
//
//echo $aInt->lang("status", "inactive");
//echo "</option><option value=\"Closed\"";
//
//if ($status == "Closed") {
//    echo " selected";
//}


$filterdata = array(
    "userid" => $filters->get("userid"),
    "clientname" => $filters->get("clientname"),
    "companyname" => $filters->get("companyname"),
    "email" => $filters->get("email"),
    "address" => $filters->get("address"),
    "country" => $filters->get("country"),
    "status" => $filters->get("status"),
    "state" => $filters->get("state"),
    "clientgroup" => $filters->get("clientgroup"),
    "phonenumber" => $filters->get("phonenumber"),
    "currency" => $filters->get("currency"),
    "cardlastfour" => $filters->get("cardlastfour"),
    "customfields" => $filters->get("customfields")
);








$result = select_query_i("tblcustomfields", "id,fieldname", array("type" => "client"));

while ($data = mysqli_fetch_array($result)) {
    $fieldid = $data['id'];
    $fieldname = $data['fieldname'];
    echo "<tr><td class=\"fieldlabel\">" . $fieldname . "</td><td class=\"fieldarea\" colspan=\"3\"><input type=\"text\" name=\"customfields[" . $fieldid . "]\" size=\"30\" value=\"" . $customfields[$fieldid] . "\" /></td></tr>";
}


$filters->store();

$clientsModel->execute($filterdata);
$numresults = $pageObj->getNumResults();


if ($filters->isActive() && $numresults == 1) {
    $client = $pageObj->getOne();
    redir("userid=" . $client['id'], "clientssummary.php");
} else {
    $clientlist = $pageObj->getData();
    foreach ($clientlist as $client) {
	    $linkopen = sprintf("<a href=\"clientssummary.php?userid=%d\"%s>",
		    $client['id'],
		    ($client['groupcolor'] ? " style=\"background-color:" . $client['groupcolor'] . "\"" : "")
	    );

        
        $linkclose = "</a>";
        $tbl->addRow(
                array(
                    sprintf(
                        "<input type=\"checkbox\" name=\"selectedclients[]\" value=\"%d\" class=\"checkall\">",
                        $client['id']
                    ),
                    $linkopen . $client['id'] . $linkclose,
                    $linkopen . $client['firstname'] . $linkclose, 
                    $linkopen . $client['lastname'] . $linkclose, 
                    $client['companyname'],
                    sprintf(
                        "<a href=\"sendmessage.php?type=general&id=%d\">%s</a>",
                        $client['id'],
                        $client['email']
                    ),
                    sprintf("%s (%s)",$client['services'],$client['totalservices']),
                    sprintf("%s (%s)",$client['products'],$client['totalproducts']),
                    "<span class=\"label " . strtolower($client['status']) . "\">" . $client['status'] . "</span>"));
    }


    $table = $tbl->output();
    unset($clientlist);
    unset($clientsModel);
}
$aInt->assign('table', $table);
$aInt->assign('lang', $langdata);
$aInt->assign('filterdata', $filterdata);
$aInt->assign('clientgroups', $clientgroup);
$aInt->assign('currencys', $currencys);
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->template = 'client/clients';
$aInt->display();
?>
