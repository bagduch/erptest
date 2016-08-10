<?php

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Edit Clients Details", false);
$aInt->requiredFiles(array("clientfunctions", "customfieldfunctions", "gatewayfunctions"));
$aInt->inClientsProfile = true;
$aInt->valUserID($userid);

if ($ra->get_req_var("save")) {
	check_token("RA.admin.default");
	$email = trim($email);
	$password = trim($password);
	$result = select_query_i("tblclients", "COUNT(*)", "email='" . db_escape_string($email) . "' AND id!='" . db_escape_string($userid) . "'");
	$data = mysqli_fetch_array($result);

	if ($data[0]) {
		redir("userid=" . $userid . "&emailexists=1");
		exit();
	}
	else {
		$errormessage = "";
		run_hook("ClientDetailsValidation", array());
		$_SESSION['profilevalidationerror'] = $errormessage;
		$oldclientsdetails = getClientsDetails($userid);
		$table = "tblclients";
		$array = array(
			"firstname" => $firstname, 
			"lastname" => $lastname, 
			"companyname" => $companyname, 
			"email" => $email, 
			"address1" => $address1, 
			"address2" => $address2, 
			"city" => $city, 
			"state" => $state, 
			"postcode" => $postcode, 
			"country" => $country, 
			"phonenumber" => $phonenumber, 
			"mobilenumber" => $mobilenumber, 
			"currency" => $_POST['currency'], 
			"notes" => $notes, 
			"status" => $status, 
			"dateofbirth" => $dateofbirth,
			"taxexempt" => $taxexempt, "latefeeoveride" => $latefeeoveride, "overideduenotices" => $overideduenotices, "separateinvoices" => $separateinvoices, "disableautocc" => $disableautocc, "emailoptout" => $emailoptout, "overrideautoclose" => $overrideautoclose, "language" => $language, "billingcid" => $billingcid, "securityqid" => $securityqid, "securityqans" => encrypt($securityqans), "groupid" => $groupid);

		if (!$twofaenabled) {
			$array['authmodule'] = "";
			$array['authdata'] = "";
		}

		$where = array("id" => $userid);
		update_query($table, $array, $where);

		if ($password && $password != $aInt->lang("fields", "entertochange")) {
		    update_query("tblclients", array("password" => generateClientPW($password)), array("id" => $userid));
			run_hook("ClientChangePassword", array("userid" => $userid, "password" => $password));
		}

		$customfields = getCustomFields("client", "", $userid, "on", "");
		foreach ($customfields as $k => $v) {
			$k = $v['id'];
			$customfieldsarray[$k] = $_POST['customfield'][$k];
		}

		$updatefieldsarray = array("firstname" => "First Name", "lastname" => "Last Name", "companyname" => "Company Name", "email" => "Email Address", "address1" => "Address 1", "address2" => "Address 2", "city" => "City", "state" => "State", "postcode" => "Postcode", "country" => "Country", "phonenumber" => "Phone Number", "mobilenumber" => "Mobile Number","billingcid" => "Billing Contact");
		$updatedtickboxarray = array("latefeeoveride" => "Late Fees Override", "overideduenotices" => "Overdue Notices", "taxexempt" => "Tax Exempt", "separateinvoices" => "Separate Invoices", "disableautocc" => "Disable CC Processing", "emailoptout" => "Marketing Emails Opt-out", "overrideautoclose" => "Auto Close");
		$changelist = array();
		foreach ($updatefieldsarray as $field => $displayname) {
			if ($array[$field] != $oldclientsdetails[$field]) {
				$changelist[] = "" . $displayname . ": '" . $oldclientsdetails[$field] . "' to '" . $array[$field] . "'";
				continue;
			}
		}

		foreach ($updatedtickboxarray as $field => $displayname) {
			$oldfield = ($oldclientsdetails[$field] ? "Enabled" : "Disabled");
			$newfield = ($array[$field] ? "Enabled" : "Disabled");
			if ($oldfield != $newfield) {
				$changelist[] = "" . $displayname . ": '" . $oldfield . "' to '" . $newfield . "'";
				continue;
			}
		}

		saveCustomFields($userid, $customfieldsarray);
		clientChangeDefaultGateway($userid, $paymentmethod);

		if (!count($changelist)) {
			$changelist[] = "No Changes";
		}

		logActivity("Client Profile Modified - " . implode(", ", $changelist) . (" - User ID: " . $userid), $userid);
		run_hook("AdminClientProfileTabFieldsSave", $_REQUEST);
		run_hook("ClientEdit", array_merge(array("userid" => $userid, "olddata" => $oldclientsdetails), $array));
		redir("userid=" . $userid . "&success=true");
		exit();
	}
}

releaseSession();
ob_start();

if ($ra->get_req_var("emailexists")) {
	infoBox($aInt->lang("clients", "duplicateemail"), $aInt->lang("clients", "duplicateemailexp"), "error");
}
else {
	if ($_SESSION['profilevalidationerror']) {
		infoBox($aInt->lang("global", "validationerror"), $_SESSION['profilevalidationerror'], "error");
		unset($_SESSION['profilevalidationerror']);
	}
	else {
		if ($ra->get_req_var("success")) {
			infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"), "success");
		}
		else {
			if ($ra->get_req_var("resetpw")) {
				check_token("RA.admin.default");
				sendMessage("Automated Password Reset", $userid);
				infoBox($aInt->lang("clients", "resetsendpassword"), $aInt->lang("clients", "passwordsuccess"), "success");
			}
		}
	}
}

echo $infobox;
$clientsdetails = getClientsDetails($userid);
$firstname = $clientsdetails['firstname'];
$lastname = $clientsdetails['lastname'];
$companyname = $clientsdetails['companyname'];
$email = $clientsdetails['email'];
$address1 = $clientsdetails['address1'];
$address2 = $clientsdetails['address2'];
$city = $clientsdetails['city'];
$state = $clientsdetails['state'];
$postcode = $clientsdetails['postcode'];
$country = $clientsdetails['country'];
$phonenumber = $clientsdetails['phonenumber'];
$mobilenumber= $clientsdetails['mobilenumber'];
$currency = $clientsdetails['currency'];
$notes = $clientsdetails['notes'];
$status = $clientsdetails['status'];
$defaultgateway = $clientsdetails['defaultgateway'];
$taxexempt = $clientsdetails['taxexempt'];
$latefeeoveride = $clientsdetails['latefeeoveride'];
$overideduenotices = $clientsdetails['overideduenotices'];
$separateinvoices = $clientsdetails['separateinvoices'];
$disableautocc = $clientsdetails['disableautocc'];
$emailoptout = $clientsdetails['emailoptout'];
$overrideautoclose = $clientsdetails['overrideautoclose'];
$language = $clientsdetails['language'];
$billingcid = $clientsdetails['billingcid'];
$securityqid = $clientsdetails['securityqid'];
$securityqans = $clientsdetails['securityqans'];
$groupid = $clientsdetails['groupid'];
$twofaenabled = $clientsdetails['twofaenabled'];
$dateofbirth = $clientsdetails['dateofbirth'];


$password = $aInt->lang("fields", "entertochange");

$questions = getSecurityQuestions("");
?>

<form method="post" action="<? echo $PHP_SELF."?save=true&userid=".$userid; ?>">

<table class="sui-table sui-hover sui-selectable">
  <tbody>
    <tr class="sui-columnheader">
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr class="sui-columnheader">
      <td width="13%">First Name</td>
      <td width="36%"><input type="text" name="firstname" id="firstname" value="<?= $firstname ?>"></td>
      <td width="51%">Company Name</td>
      <td width="51%"><input type="text" name="companyname" id="companyname" value="<?= $companyname ?>"></td>
      <td width="51%">Address</td>
      <td width="51%"><input type="text" name="address1" id="address1" value="<?= $address1 ?>"></td>
    </tr>
    <tr class="sui-columnheader">
      <td>Last Name</td>
      <td><input type="text" name="lastname" id="lastname" value="<?= $lastname ?>"></td>
      <td>Email Address</td>
      <td><input type="text" name="email" id="email" value="<?= $email ?>"></td>
      <td>Region</td>
      <td><input type="text" name="state" id="state" value="<?= $state ?>"></td>
    </tr>
    <tr class="sui-columnheader">
      <td>Date of Birth</td>
      <td><input type="text" name="dateofbirth" id="dateofbirth" value="<?= $dateofbirth ?>"></td>
      <td>Password</td>
      <td><input type="text" name="password" id="password" value="<?= $password ?>"></td>
      <td>City</td>
      <td><input type="text" name="city" id="city" value="<?= $city ?>"></td>
    </tr>
    <tr class="sui-columnheader">
      <td>Phone Number</td>
      <td><input type="text" name="phonenumber" id="phonenumber" value="<?= $phonenumber ?>"></td>
      <td>Payment Method</td>
      <td>&nbsp;</td>
      <td>Postcode</td>
      <td><input type="text" name="postcode" id="postcode" value="<?= $postcode ?>"></td>
    </tr>
    <tr class="sui-columnheader">
      <td>Mobile Number</td>
      <td><input type="text" name="mobilenumber" id="mobilenumber" value="<?= $mobilenumber ?>"></td>
      <td>Billing Contact</td>
      <td>&nbsp;</td>
      <td>Country</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="sui-columnheader">
      <td>Client Group</td>
      <td>&nbsp;</td>
      <td>Currency</td>
      <td>&nbsp;</td>
      <td>Status</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="sui-columnheader">
      <td colspan="5"><input type="checkbox" name="checkbox" id="checkbox">
        <label for="checkbox">Dont Apply Late Fees |
          <input type="checkbox" name="checkbox2" id="checkbox2">
          Don't Send Overdue Emails</label>
        |
        <label for="checkbox2">
          <input type="checkbox" name="checkbox3" id="checkbox3">
          Separate Invoices for Services</label>
        |
        <label for="checkbox3">
          <input type="checkbox" name="checkbox4" id="checkbox4">
          Disable Automatic CC Processing</label></td>
      <td>Credit Risk
        <div class="progress">
          <div class="progress-bar progress-bar-danger" style="width: 10%"></div>
        </div></td>
    </tr>
  </tbody>
</table>

<?
echo "&resetpw=true";
echo generate_token("link");
echo "\"><img src=\"images/icons/resetpw.png\" border=\"0\" align=\"absmiddle\" /> ";
echo $aInt->lang("clients", "resetsendpassword");
echo "</a></td><td class=\"fieldlabel\">";
echo $aInt->lang("clients", "latefees");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"latefeeoveride\"";

if ($latefeeoveride == "on") {
	echo " checked";
}
echo $aInt->lang("clients", "latefeesdesc");
echo "</td><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "paymentmethod");
echo "</td><td class=\"fieldarea\">";
$paymentmethod = $defaultgateway;
echo paymentMethodsSelection($aInt->lang("clients", "changedefault"), 21);
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("clients", "overduenotices");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"overideduenotices\"";

if ($overideduenotices == "on") {
	echo " checked";
}

echo " tabindex=\"16\"> ";
echo $aInt->lang("clients", "overduenoticesdesc");
echo "</td><td class=\"fieldlabel\">";
echo $aInt->lang("clients", "billingcontact");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"billingcid\" tabindex=\"22\"><option value=\"0\">";
echo $aInt->lang("global", "default");
echo "</option>";
$result = select_query_i("tblcontacts", "", array("userid" => $userid), "firstname` ASC,`lastname", "ASC");

while ($data = mysqli_fetch_array($result)) {
	echo "<option value=\"" . $data['id'] . "\"";

	if ($data['id'] == $billingcid) {
		echo " selected";
	}

	echo ">" . $data['firstname'] . " " . $data['lastname'] . "</option>";
}

echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("clients", "taxexempt");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"taxexempt\"";

if ($taxexempt == "on") {
	echo " checked";
}

echo " tabindex=\"17\"> ";
echo $aInt->lang("clients", "taxexemptdesc");
echo "</td><td class=\"fieldlabel\">";
echo $aInt->lang("global", "language");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"language\" tabindex=\"23\"><option value=\"\">";
echo $aInt->lang("global", "default");
echo "</option>";
foreach ($ra->getValidLanguages() as $lang) {
	echo "<option value=\"" . $lang . "\"";

	if ($language && $lang == $ra->validateLanguage($language)) {
		echo " selected=\"selected\"";
	}

	echo ">" . ucfirst($lang) . "</option>";
}

echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("clients", "separateinvoices");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"separateinvoices\"";

if ($separateinvoices == "on") {
	echo " checked";
}

echo " tabindex=\"18\"> ";
echo $aInt->lang("clients", "separateinvoicesdesc");
echo "</td><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "status");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"status\" tabindex=\"24\">
<option value=\"Active\"";

if ($status == "Active") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "active");
echo "</option>
<option value=\"Inactive\"";

if ($status == "Inactive") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "inactive");
echo "</option>
<option value=\"Closed\"";

if ($status == "Closed") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "closed");
echo "</option>
</select></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("clients", "disableccprocessing");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"disableautocc\"";

if ($disableautocc == "on") {
	echo " checked";
}

echo " tabindex=\"19\"> ";
echo $aInt->lang("clients", "disableccprocessingdesc");
echo "</td><td class=\"fieldlabel\">";
echo $aInt->lang("currencies", "currency");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"currency\" tabindex=\"25\">";
$result = select_query_i("tblcurrencies", "id,code", "", "code", "ASC");

while ($data = mysqli_fetch_array($result)) {
	echo "<option value=\"" . $data['id'] . "\"";

	if ($data['id'] == $currency) {
		echo " selected";
	}

	echo ">" . $data['code'] . "</option>";
}

echo "</select></td></tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "clientgroup");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"groupid\" tabindex=\"27\"><option value=\"0\">";
echo $aInt->lang("global", "none");
echo "</option>
";
$result = select_query_i("tblclientgroups", "", "", "groupname", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$group_id = $data['id'];
	$group_name = $data['groupname'];
	$group_colour = $data['groupcolour'];
	echo "<option style=\"background-color:" . $group_colour . "\" value=" . $group_id . "";

	if ($group_id == $groupid) {
		echo " selected";
	}

	echo ">" . $group_name . "</option>";
}

echo "</select></td><td class=\"fieldlabel\">";
echo $aInt->lang("twofa", "title");
echo "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"twofaenabled\"";

if ($twofaenabled) {
	echo " checked";
}

echo " value=\"1\" tabindex=\"27\"> ";
echo $aInt->lang("clients", "2faenabled");
echo "</label></td></tr>
<tr>";
$taxindex = 0;
$customfields = getCustomFields("client", "", $userid, "on", "");
$x = 0;
foreach ($customfields as $customfield) {
	++$x;
	echo "<td class=\"fieldlabel\">" . $customfield['name'] . "</td><td class=\"fieldarea\">" . str_replace(array("<input", "<select", "<textarea"), array("<input tabindex=\"" . $taxindex . "\"", "<select tabindex=\"" . $taxindex . "\"", "<textarea tabindex=\"" . $taxindex . "\""), $customfield['input']) . "</td>";

	if ($x % 2 == 0 || $x == count($customfields)) {
		echo "</tr><tr>";
	}

	++$taxindex;
}

$hookret = run_hook("AdminClientProfileTabFields", $clientsdetails);
foreach ($hookret as $hookdat) {
	foreach ($hookdat as $k => $v) {
		echo "<td class=\"fieldlabel\">" . $k . "</td><td class=\"fieldarea\" colspan=\"3\">" . $v . "</td></tr>";
	}
}

echo "</table>

<img src=\"images/spacer.gif\" height=\"10\" width=\"1\"><br>
<div align=\"center\"><input type=\"submit\" value=\"";
echo $aInt->lang("global", "savechanges");
echo "\" class=\"btn btn-primary\" tabindex=\"";
echo $taxindex++;
echo "\"> <input type=\"reset\" value=\"";
echo $aInt->lang("global", "cancelchanges");
echo "\" class=\"button\" tabindex=\"";
echo $taxindex++;
?>
</div>
</form>
<?php
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->display();
?>
