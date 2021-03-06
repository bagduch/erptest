<?php
/** RA - Version 0.1 **/


define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Edit Clients Products/Services");
$aInt->title = $aInt->lang("clients", "transferownership");
ob_start();

if ($action == "") {
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
    $(\"#clientsearchresults\").slideUp();
}
</script>
";

	if ($error) {
		echo "<div class=\"errorbox\">" . $aInt->lang("clients", "invalidowner") . "</div><br />";
	}

	echo "
<form method=\"post\" action=\"";
	echo $PHP_SELF;
	echo "?action=transfer&type=";
	echo $type;
	echo "&id=";
	echo $id;
	echo "\">
";
	echo $aInt->lang("clients", "transferchoose");
	echo "<br /><br />
<div align=\"center\">
";
	echo $aInt->lang("fields", "clientid");
	echo ": <input type=\"text\" name=\"newuserid\" id=\"newuserid\" size=\"10\" /> <input type=\"submit\" value=\"";
	echo $aInt->lang("domains", "transfer");
	echo "\" class=\"button\" /><br /><br />
";
	echo $aInt->lang("global", "clientsintellisearch");
	echo ": <input type=\"text\" id=\"clientsearchval\" size=\"25\" />
</div>
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
		redir("type=" . $type . "&id=" . $id . "&error=1");
	}


	if ($type == "hosting") {
		$result = select_query_i("tblcustomerservices", "userid", array("id" => $id));
		$data = mysqli_fetch_array($result);
		$userid = $data['userid'];
		logActivity("Moved Service ID: " . $id . " from User ID: " . $userid . " to User ID: " . $newuserid, $newuserid);
		update_query("tblcustomerservices", array("userid" => $newuserid), array("id" => $id));
		echo "<s";
		echo "cript language=\"javascript\">
window.opener.location.href = \"clientshosting.php?userid=";
		echo $newuserid;
		echo "&id=";
		echo $id;
		echo "\";
window.close();
</script>
";
	}
	else {
		if ($type == "domain") {
			$result = select_query_i("tbldomains", "userid", array("id" => $id));
			$data = mysqli_fetch_array($result);
			$userid = $data['userid'];
			logActivity("Moved Domain ID: " . $id . " from User ID: " . $userid . " to User ID: " . $newuserid, $newuserid);
			update_query("tbldomains", array("userid" => $newuserid), array("id" => $id));
			echo "<s";
			echo "cript language=\"javascript\">
window.opener.location.href = \"clientsdomains.php?userid=";
			echo $newuserid;
			echo "&id=";
			echo $id;
			echo "\";
window.close();
</script>
";
		}
	}
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->displayPopUp();
?>