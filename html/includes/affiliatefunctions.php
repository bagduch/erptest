<?php
/** RA - Version 0.1 **/


function affiliateActivate($userid) {
	global $CONFIG;

	$result = select_query_i("ra_user", "currency", array("id" => $userid));
	$data = mysqli_fetch_array($result);
	$clientcurrency = $data['currency'];
	$bonusdeposit = convertCurrency($CONFIG['AffiliateBonusDeposit'], 1, $clientcurrency);
	$result = select_query_i("ra_partners", "id", array("clientid" => $userid));
	$data = mysqli_fetch_array($result);
	$affiliateid = $data['id'];

	if (!$affiliateid) {
		$affiliateid = insert_query("ra_partners", array("date" => "now()", "clientid" => $userid, "balance" => $bonusdeposit));
	}

	logActivity("Activated Affiliate Account - Affiliate ID: " . $affiliateid . " - User ID: " . $userid, $userid);
	run_hook("AffiliateActivation", array("affid" => $affiliateid, "userid" => $userid));
}

?>