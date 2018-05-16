<?php
/**
 *
 * @ RA
 * 
 *
 **/

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Edit Clients Details");
$aInt->title = $aInt->lang("clients", "mergeclient");
ob_start();

if (!$newuserid) {
	echo "<script type=\"text/javascript\">
$(document).ready(function(){
	$(\"#clientsearchval\").keyup(function () {
		var useridsearchlength = $(\"#clientsearchval\").val().length;
		if (useridsearchlength>2) {
		$.post(\"search.php\", { clientsearch: 1, value: $(\"#clientsearchval\").val() },
			function(data){
				if (data) {
					$(\"#clientsearchresults\").html(data);
					$(\"#clientsearchresults\").slideDown(\"slow\");
				}
			});
		}
	});
});
function searchselectclient(userid,name,email) {
    $(\"#newuserid\").val(userid);
	$(\"#clientsearchresults\").slideUp(\"slow\");
}
</script>
";

	if ($error) {
		echo "<div class=\"errorbox\">" . $aInt->lang("clients", "invalidid") . "</div><br />";
	}

	echo "
<p>";
	echo $aInt->lang("clients", "mergeexplain");
	echo "</p>

<form method=\"post\" action=\"";
	echo $PHP_SELF;
	echo "?userid=";
	echo $userid;
	echo "\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\">";
	echo $aInt->lang("clients", "firstclient");
	echo "</td><td class=\"fieldarea\">";
	$result = select_query_i("ra_user", "", array("id" => $userid));
	$data = mysqli_fetch_array($result);
	$useridselect = $data['id'];
	$firstname = $data['firstname'];
	$lastname = $data['lastname'];
	echo "" . $firstname . " " . $lastname . " (" . $useridselect . ")";
	echo "</td></tr>
<tr><td class=\"fieldlabel\">";
	echo $aInt->lang("clients", "secondclient");
	echo "</td><td class=\"fieldarea\"><table cellspacing=\"0\" cellpadding=\"0\"><tr><td><input type=\"text\" name=\"newuserid\" id=\"newuserid\" size=\"10\" /></td><td>&nbsp;&nbsp; <input type=\"submit\" value=\"";
	echo $aInt->lang("invoices", "merge");
	echo "\" class=\"button\" /></td></tr></table></td></tr>
<tr><td class=\"fieldarea\" colspan=\"2\"><div align=\"center\"><input type=\"radio\" name=\"mergemethod\" value=\"to1\" id=\"to1\" /> <label for=\"to1\">";
	echo $aInt->lang("clients", "tofirst");
	echo "</label> <input type=\"radio\" name=\"mergemethod\" value=\"to2\" id=\"to2\" checked /> <label for=\"to2\">";
	echo $aInt->lang("clients", "tosecond");
	echo "</label></div></td></tr>
</table>

<br />
<div align=\"center\">";
	echo $aInt->lang("global", "clientsintellisearch");
	echo ": <input type=\"text\" id=\"clientsearchval\" size=\"25\" /></div>
<br />
<div id=\"clientsearchresults\">
<div class=\"searchresultheader\">Search Results</div>
<div class=\"searchresult\" align=\"center\">Matches will appear here as you type</div>
</div>

</form>

";
}
else {
	check_token("RA.admin.default");
	$newuserid = trim($newuserid);
	$result = select_query_i("ra_user", "id", array("id" => $newuserid));
	$data = mysqli_fetch_array($result);
	$newuserid = $data['id'];

	if (!$newuserid) {
		redir("userid=" . $userid . "&error=1");
	}


	if ($mergemethod == "to1") {
		$resultinguserid = trim($userid);
		$deleteuser = trim($newuserid);
	}
	else {
		$resultinguserid = trim($newuserid);
		$deleteuser = trim($userid);
	}

	$tables_array = array("ra_transactions", "ra_user_contacts", "tbldomains", "ra_user_mail", "tblcustomerservices", "ra_bill_lineitems", "ra_bills", "ra_notes", "ra_orders", "tblquotes", "ra_ticket_replies", "ra_ticket", "ra_systemlog", "tblsslorders");
	foreach ($tables_array as $table) {
		update_query($table, array("userid" => $resultinguserid), array("userid" => $userid));
	}

	update_query("ra_transactions_credit", array("clientid" => $resultinguserid), array("clientid" => $userid));
	$userid = $newuserid;
	foreach ($tables_array as $table) {
		update_query($table, array("userid" => $resultinguserid), array("userid" => $userid));
	}

	update_query("ra_transactions_credit", array("clientid" => $resultinguserid), array("clientid" => $userid));
	$result = select_query_i("ra_user", "credit", array("id" => $deleteuser));
	$data = mysqli_fetch_array($result);
	$credit = $data[0];
	update_query("ra_user", array("credit" => "+=" . $credit), array("id" => (int)$resultinguserid));
	$result = select_query_i("ra_partners", "", array("clientid" => $deleteuser));
	$data = mysqli_fetch_array($result);
	$affid = $data['id'];

	if ($affid) {
		$visitors = $data['visitors'];
		$balance = $data['balance'];
		$withdrawn = $data['withdrawn'];
		$result = select_query_i("ra_partners", "", array("clientid" => $resultinguserid));
		$data = mysqli_fetch_array($result);
		$newaffid = $data['id'];
		if (!$newaffid) {
			$newaffid = insert_query("ra_partners", array("date" => "now()", "clientid" => $resultinguserid));
		}

		update_query("ra_partners", array("visitors" => "+=" . (int)$visitors, "balance" => "+=" . $balance, "withdrawn" => "+=" . $withdrawn), array("id" => (int)$newaffid));
		update_query("ra_partnersaccounts", array("affiliateid" => $newaffid), array("affiliateid" => $affid));
		update_query("ra_partnershistory", array("affiliateid" => $newaffid), array("affiliateid" => $affid));
		update_query("ra_partnerswithdrawals", array("affiliateid" => $newaffid), array("affiliateid" => $affid));
		delete_query("ra_partners", array("clientid" => $deleteuser));
	}


	if ($resultinguserid != $deleteuser) {
		delete_query("ra_user", array("id" => $deleteuser));
	}

	logActivity("Merged User ID: " . $deleteuser . " with User ID: " . $resultinguserid, $resultinguserid);
	echo "<s";
	echo "cript language=\"javascript\">
window.opener.location.href = \"clientssummary.php?userid=";
	echo $resultinguserid;
	echo "\";
window.close();
</script>
";
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->displayPopUp();
?>