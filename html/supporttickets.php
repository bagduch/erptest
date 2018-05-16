<?php
/** RA - Version 0.1 **/

define("CLIENTAREA", true);
require "init.php";
require "includes/ticketfunctions.php";
$pagetitle = $_LANG['supportticketspagetitle'];
$breadcrumbnav = "<a href=\"index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"clientarea.php\">" . $_LANG['clientareatitle'] . "</a> > <a href=\"supporttickets.php\">" . $_LANG['supportticketspagetitle'] . "</a>";
$templatefile = "supportticketslist";
$pageicon = "images/supporttickets_big.gif";
initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);

if (isset($_SESSION['uid'])) {
	checkContactPermission("tickets");
	$usingsupportmodule = false;

	if ($CONFIG['SupportModule']) {
		if (!isValidforPath($CONFIG['SupportModule'])) {
			exit("Invalid Support Module");
		}

		$supportmodulepath = "modules/support/" . $CONFIG['SupportModule'] . "/supporttickets.php";

		if (file_exists($supportmodulepath)) {
			$usingsupportmodule = true;
			$templatefile = "";
			require $supportmodulepath;
			outputClientArea($templatefile);
			exit();
		}
	}

    $result = select_query_i("tbltickets", 
        "COUNT(id)", 
        "userid='" . mysqli_real_escape_string($_SESSION['uid']) . "' AND status!='Closed'"
    );
	$data = mysqli_fetch_array($result);
	$smartyvalues['numopentickets'] = $data[0];

	if ($searchterm = $ra->get_req_var("searchterm")) {
		check_token();
		$smartyvalues['searchterm'] = $smartyvalues['q'] = $searchterm;
		$searchterm = mysqli_real_escape_string(trim($searchterm));
		$where = "tbltickets.userid='" . mysqli_real_escape_string($_SESSION['uid']) . ("' AND (tbltickets.tid='" . $searchterm . "' OR (tbltickets.title LIKE '%" . $searchterm . "%' OR tbltickets.message LIKE '%" . $searchterm . "%' OR tblticketreplies.message LIKE '%" . $searchterm . "%'))");
		$result = full_query_i("SELECT COUNT(DISTINCT tbltickets.id) FROM tbltickets LEFT JOIN tblticketreplies ON tbltickets.id = tblticketreplies.tid WHERE " . $where);
		$data = mysqli_fetch_array($result);
		$numtickets = $data[0];
		$smartyvalues['numtickets'] = $numtickets;
		list($orderby, $sort, $limit) = clientAreaTableInit("tickets", "lastreply", "DESC", $numtickets);
		$smartyvalues['orderby'] = $orderby;
		$smartyvalues['sort'] = strtolower($sort);

        switch($orderby) {
            case "date":        $orderby = "tbltickets.date";   break;
            case "dept":        $orderby = "did";               break;
            case "subject":     $orderby = "title";             break;
            case "status":      $orderby = "status";            break;
            case "urgency":
            case "priority":    $orderby = "urgency";           break;
            default: $orderby = "lastreply";
        }


		if (!in_array($sort, array("ASC", "DESC"))) {
			$sort = "ASC";
		}


		if (strpos($limit, ",")) {
			$limit = explode(",", $limit);
			$limit = (int)$limit[0] . "," . (int)$limit[1];
		}
		else {
			$limit = (int)$limit;
		}

		$tickets = array();
		$result = full_query_i("SELECT DISTINCT tbltickets.id FROM tbltickets LEFT JOIN tblticketreplies ON tbltickets.id = tblticketreplies.tid WHERE " . $where . (" ORDER BY " . $orderby . " " . $sort . " LIMIT " . $limit));

		while ($data = mysqli_fetch_array($result)) {
			$id = $data['id'];
			$result2 = select_query_i("tbltickets", "", array("userid" => $_SESSION['uid'], "id" => $id));
			$data = mysqli_fetch_array($result2);
			$tid = $data['tid'];
			$c = $data['c'];
			$deptid = $data['did'];
			$date = $data['date'];
			$date = fromMySQLDate($date, 1, 1);
			$subject = $data['title'];
			$tstatus = $data['status'];
			$urgency = $data['urgency'];
			$lastreply = $data['lastreply'];
			$lastreply = fromMySQLDate($lastreply, 1, 1);
			$clientunread = $data['clientunread'];
			$tstatus = getStatusColour($tstatus);
			$dept = getDepartmentName($deptid);
			$urgency = $_LANG["supportticketsticketurgency" . strtolower($urgency)];
			$tickets[] = array("id" => $id, "tid" => $tid, "c" => $c, "date" => $date, "department" => $dept, "subject" => $subject, "status" => $tstatus, "urgency" => $urgency, "lastreply" => $lastreply, "unread" => $clientunread);
		}
	}
	else {
		$result = select_query_i("tbltickets", "COUNT(id)", array("userid" => $_SESSION['uid']));
		$data = mysqli_fetch_array($result);
		$numtickets = $data[0];
		$smartyvalues['numtickets'] = $numtickets;
		list($orderby, $sort, $limit) = clientAreaTableInit("tickets", "lastreply", "DESC", $numtickets);
		$smartyvalues['orderby'] = $orderby;
		$smartyvalues['sort'] = strtolower($sort);

        switch($orderby) {
            case "date":        $orderby = "date";      break;
            case "dept":        $orderby = "deptname";  break;
            case "subject":     $orderby = "title";     break;
            case "status":      $orderby = "status";    break;
            case "urgency":
            case "priority":    $orderby = "urgency";   break;
            default: $orderby = "lastreply";
        }

		$tickets = array();
        $result = select_query_i("tbltickets", 
                "tbltickets.*,
                tblticketdepartments.name AS deptname", 
                array(
                     "userid" => $_SESSION['uid']
                 ), 
                 $orderby, 
                 $sort, 
                 $limit, 
                 " tblticketdepartments ON tblticketdepartments.id=tbltickets.did"
             );

		while ($data = mysqli_fetch_array($result)) {
			$id = $data['id'];
			$tid = $data['tid'];
			$c = $data['c'];
			$deptid = $data['did'];
			$date = $data['date'];
			$date = fromMySQLDate($date, 1, 1);
			$subject = $data['title'];
			$tstatus = $data['status'];
			$urgency = $data['urgency'];
			$lastreply = $data['lastreply'];
			$lastreply = fromMySQLDate($lastreply, 1, 1);
			$clientunread = $data['clientunread'];
			$tstatus = getStatusColour($tstatus);
			$dept = getDepartmentName($deptid);
			$urgency = $_LANG["supportticketsticketurgency" . strtolower($urgency)];
			$tickets[] = array("id" => $id, "tid" => $tid, "c" => $c, "date" => $date, "department" => $dept, "subject" => $subject, "status" => $tstatus, "urgency" => $urgency, "lastreply" => $lastreply, "unread" => $clientunread);
		}
	}

	$smarty->assign("tickets", $tickets);
	$smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numtickets));
}
else {
	$goto = "supporttickets";
	include "login.php";
}

outputClientArea($templatefile);
// vim: ai ts=4 sts=4 et sw=4 ft=php
?>
