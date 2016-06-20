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
 **/

function downloadLogin() {
	global $ra;
	global $CONFIG;
	global $_LANG;
	global $smarty;
	global $type;
	global $id;

	$pagetitle = $_LANG['downloadstitle'];
	$breadcrumbnav = "<a href=\"" . $CONFIG['SystemURL'] . "/index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"" . $CONFIG['SystemURL'] . "/downloads.php\">" . $_LANG['downloadstitle'] . "</a>";
	initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);
	$goto = "download";
	require "login.php";
}

define("CLIENTAREA", true);
require "init.php";
$type = $ra->get_req_var("type");
$viewpdf = $ra->get_req_var("viewpdf");
$i = (int)$ra->get_req_var("i");
$id = (int)$ra->get_req_var("id");
$folder_path = $file_name = $display_name = "";
$allowedtodownload = "";

if ($type == "i") {
	$result = select_query_i("tblinvoices", "", array("id" => $id));
	$data = mysqli_fetch_array($result);

	if (!$data['id']) {
		exit("Invalid Access Attempt");
	}

	$invoiceid = $data['id'];
	$invoicenum = $data['invoicenum'];
	$userid = $data['userid'];

	if (!$invoiceid) {
		redir("", "clientarea.php");
	}


	if (!isset($_SESSION['adminid']) && $_SESSION['uid'] != $userid) {
		downloadLogin();
	}


	if (!$invoicenum) {
		$invoicenum = $invoiceid;
	}

	require "includes/invoicefunctions.php";
	$pdfdata = pdfInvoice($id);
	header("Pragma: public");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, private");
	header("Cache-Control: private", false);
	header("Content-Type: application/pdf");
	header("Content-Disposition: " . ($viewpdf ? "inline" : "attachment") . "; filename=\"" . $_LANG['invoicefilename'] . $invoicenum . ".pdf\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . strlen($pdfdata));
	echo $pdfdata;
	exit();
	return 1;
}


if ($type == "a") {
	$result = select_query_i("tbltickets", "userid,attachment", array("id" => $id));
	$data = mysqli_fetch_array($result);
	$userid = $data['userid'];
	$attachments = $data['attachment'];
	$folder_path = $attachments_dir;
	$files = explode("|", $attachments);
	$file_name = $files[$i];
	$display_name = substr($file_name, 7);

	if ($userid && ($userid != $_SESSION['uid'] && !$_SESSION['adminid'])) {
		downloadLogin();
	}
}
else {
	if ($type == "ar") {
		$result = select_query_i("tblticketreplies", "userid,attachment", array("id" => $id));
		$data = mysqli_fetch_array($result);
		$userid = $data['userid'];
		$attachments = $data['attachment'];
		$folder_path = $attachments_dir;
		$files = explode("|", $attachments);
		$file_name = $files[$i];
		$display_name = substr($file_name, 7);

		if ($userid && ($userid != $_SESSION['uid'] && !$_SESSION['adminid'])) {
			downloadLogin();
		}
	}
	else {
		if ($type == "d") {
			$result = select_query_i("tbldownloads", "", array("id" => $id));
			$data = mysqli_fetch_array($result);
			$filename = $data['location'];
			$clientsonly = $data['clientsonly'];
			$productdownload = $data['productdownload'];

			if ($productdownload) {
				if (!$_SESSION['uid']) {
					downloadLogin();
				}

				$downloads = array();

				if ($serviceid) {
					$where = array("tblcustomerservices.id" => $serviceid, "userid" => $_SESSION['uid'], "tblcustomerservices.servicestatus" => "Active");
				}
				else {
					$where = array("userid" => $_SESSION['uid'], "tblcustomerservices.servicestatus" => "Active");
				}

				$result = select_query_i("tblcustomerservices", "DISTINCT tblservices.id,tblservices.downloads, tblservices.servertype, tblservices.configoption7", $where, "", "", "", "tblservices ON tblservices.id=tblcustomerservices.packageid");

				while ($data = mysqli_fetch_array($result)) {
					$productdownloads = $data['downloads'];
					$productdownloads = unserialize($productdownloads);

					if (is_array($productdownloads)) {
						if (in_array($id, $productdownloads)) {
							if (($data['servertype'] == "licensing" && ($data['configoption7'] == "" || ($serviceid && $data['configoption7'] != ""))) || $data['servertype'] != "licensing") {
								$downloads = array_merge($downloads, $productdownloads);
							}

							echo $_LANG['dlinvalidlink'];
							exit();
						}
					}
				}


				if ($serviceid) {
					$where = array("tblserviceaddons.hostingid" => $serviceid, "tblcustomerservices.userid" => $_SESSION['uid'], "tblserviceaddons.status" => "Active");
				}
				else {
					$where = array("tblcustomerservices.userid" => $_SESSION['uid'], "tblserviceaddons.status" => "Active");
				}

				$result = select_query_i("tblserviceaddons", "DISTINCT tbladdons.id,tbladdons.downloads", $where, "", "", "", "tbladdons ON tbladdons.id=tblserviceaddons.addonid INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblserviceaddons.hostingid");

				while ($data = mysqli_fetch_array($result)) {
					$addondownloads = $data['downloads'];
					$addondownloads = explode(",", $addondownloads);
					$downloads = array_merge($downloads, $addondownloads);
				}


				if (in_array($id, $downloads)) {
					$allowedtodownload = true;
				}


				if (!$allowedtodownload) {
					$tplfile = ROOTDIR . "/templates/" . $ra->get_sys_tpl_name() . "/downloaddenied.tpl";

					if (file_exists($tplfile)) {
						$pagetitle = $_LANG['downloadstitle'];
						$breadcrumbnav = "<a href=\"" . $CONFIG['SystemURL'] . "/index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"" . $CONFIG['SystemURL'] . "/downloads.php\">" . $_LANG['downloadstitle'] . "</a>";
						initialiseClientArea($pagetitle, "", $breadcrumbnav);
						$result = select_query_i("tblservices", "id,name,downloads", array("downloads" => array("sqltype" => "NEQ", "value" => "")));

						while ($data = mysqli_fetch_array($result)) {
							$downloads = $data['downloads'];
							$downloads = unserialize($downloads);

							if (in_array($id, $downloads)) {
								$smartyvalues['pid'] = $data['id'];
								$smartyvalues['prodname'] = $data['name'];
								break;
							}
						}

						$result = select_query_i("tbladdons", "id,name,downloads", array("downloads" => array("sqltype" => "NEQ", "value" => "")));

						while ($data = mysqli_fetch_array($result)) {
							$downloads = $data['downloads'];
							$downloads = explode(",", $downloads);

							if (in_array($id, $downloads)) {
								$smartyvalues['aid'] = $data['id'];
								$smartyvalues['addonname'] = $data['name'];
								break;
							}
						}

						outputClientArea("downloaddenied");
					}
					else {
						echo $_LANG['downloadpurchaserequired'];
					}

					exit();
				}

				$result = select_query_i("tblservices", "tblservices.configoption7", array("tblcustomerservices.id" => $serviceid, "tblservices.servertype" => "licensing"), "", "", "", "tblcustomerservices ON tblcustomerservices.packageid=tblservices.id");
				$data = mysqli_fetch_array($result);
				$supportpackage = $data['configoption7'];
				$addonid = explode("|", $supportpackage);
				$addonid = $addonid[0];

				if ($addonid) {
					$result = select_query_i("tbladdons", "name", array("id" => $addonid));
					$data = mysqli_fetch_array($result);
					$addonname = $data['name'];
					$where = "tblcustomerservices.userid='" . (int)$_SESSION['uid'] . "' AND tblserviceaddons.status='Active' AND (tblserviceaddons.name='" . mysqli_real_escape_string($addonname) . "' OR tblserviceaddons.addonid='" . (int)$addonid . "')";

					if ($pid) {
						$where .= " AND tblcustomerservices.id='" . (int)$pid . "'";
					}

					$result = select_query_i("tblserviceaddons", "COUNT(*)", $where, "", "", "", "tblcustomerservices ON tblcustomerservices.id=tblserviceaddons.hostingid");
					$data = mysqli_fetch_array($result);
					$supportpackageactive = $data[0];

					if (!$supportpackageactive) {
						$formposturl = ($CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL']);
						echo "<div align=\"center\">
<br />
<b>Your Support & Updates period for this license has expired</b><br />
You will need to renew your support & updates before you can download the latest files<br />
<br />
<form action=\"" . $formposturl . "/cart.php?a=add\" method=\"post\">
<input type=\"hidden\" name=\"productid\" value=\"" . $serviceid . "\" />
<input type=\"hidden\" name=\"aid\" value=\"" . $addonid . "\" />
<input type=\"submit\" value=\"Click Here to Renew &raquo;\" />
</form>
</div>";
						exit();
					}
				}
			}


			if ($clientsonly && !$_SESSION['uid']) {
				downloadLogin();
			}

			update_query("tbldownloads", array("downloads" => "+1"), array("id" => $id));

			if ((substr($filename, 0, 7) == "http://" || substr($filename, 0, 8) == "https://") || substr($filename, 0, 6) == "ftp://") {
				header("Location: " . $filename);
				exit();
			}
			else {
				$folder_path = $downloads_dir;
				$file_name = $filename;
				$display_name = $filename;
			}
		}
		else {
			if ($type == "f") {
				$result = select_query_i("tblclientsfiles", "userid,filename,adminonly", array("id" => $id));
				$data = mysqli_fetch_array($result);
				$userid = $data['userid'];
				$file_name = $data['filename'];
				$adminonly = $data['adminonly'];
				$folder_path = $attachments_dir;
				$display_name = substr($file_name, 11);

				if ($userid != $_SESSION['uid'] && !$_SESSION['adminid']) {
					downloadLogin();
				}


				if (!$_SESSION['adminid'] && $adminonly) {
					exit("Permission Denied");
				}
			}
			else {
				if ($type == "q") {
					if (!$_SESSION['uid'] && !$_SESSION['adminid']) {
						downloadLogin();
					}

					$result = select_query_i("tblquotes", "id,userid", array("id" => $id));
					$data = mysqli_fetch_array($result);
					$id = $data['id'];
					$userid = $data['userid'];

					if ($userid != $_SESSION['uid'] && !$_SESSION['adminid']) {
						exit("Permission Denied");
					}

					require ROOTDIR . "/includes/clientfunctions.php";
					require ROOTDIR . "/includes/invoicefunctions.php";
					require ROOTDIR . "/includes/quotefunctions.php";
					$pdfdata = genQuotePDF($id);
					header("Pragma: public");
					header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0, private");
					header("Cache-Control: private", false);
					header("Content-Type: application/pdf");
					header("Content-Disposition: " . ($viewpdf ? "inline" : "attachment") . "; filename=\"" . $_LANG['quotefilename'] . $id . ".pdf\"");
					header("Content-Transfer-Encoding: binary");
					echo $pdfdata;
					exit();
				}
			}
		}
	}
}


if (!trim($folder_path) || !trim($file_name)) {
	redir("", "index.php");
}

$folder_path_real = realpath($folder_path);
$file_path = $folder_path . $file_name;
$file_path_real = realpath($file_path);

if ($file_path_real === false || strpos($file_path_real, $folder_path_real) !== 0) {
	exit("File not found. Please contact support.");
}

run_hook("FileDownload", array());
header("Pragma: public");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, private");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $display_name . "\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($file_path_real));
readfile($file_path_real);
?>
