<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

require(ROOTDIR.'/includes/ticketfunctions.php');

if (!$rating) $rating='1';
if (!$startdate) $startdate = fromMySQLDate(date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y"))));
if (!$enddate) $enddate = fromMySQLDate(date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))));

$rsel[$rating] = ' selected';

$query = "SELECT ra_ticket_replies.*,ra_ticket.tid AS ticketid FROM ra_ticket_replies INNER JOIN ra_ticket ON ra_ticket.id=ra_ticket_replies.tid WHERE ra_ticket_replies.admin!='' AND ra_ticket_replies.rating='".(int)$rating."' AND ra_ticket_replies.date BETWEEN '".db_make_safe_human_date($startdate)."' AND '".db_make_safe_human_date($enddate)."' ORDER BY date DESC";
$result = full_query_i($query);
$num_rows = mysqli_num_rows($result);

$reportdata["title"] = "Support Ticket Ratings Reviewer";
$reportdata["description"] = "This report is showing all $num_rows ticket replies rated $rating between $startdate & $enddate for review";

$reportdata["headertext"] = '<form method="post" action="reports.php?report=ticket_ratings_reviewer">
<p align="center"><b>Filter:</b> Rating: <select name="rating"><option'.$rsel[1].'>1</option><option'.$rsel[2].'>2</option><option'.$rsel[3].'>3</option><option'.$rsel[4].'>4</option><option'.$rsel[5].'>5</option></select> Between Dates: <input type="text" name="startdate" value="'.$startdate.'" class="datepick" /> and <input type="text" name="enddate" value="'.$enddate.'" class="datepick" /> <input type="submit" value="Filter List" /></p>
</form>';

$reportdata["tableheadings"] = array("Ticket #","Date","Message","Admin","Rating");

while ($data = mysqli_fetch_array($result)) {
	$tid = $data["tid"];
    $ticketid = $data["ticketid"];
	$date = $data["date"];
	$message = $data["message"];
	$admin = $data["admin"];
	$rating = $data["rating"];

	$date = fromMySQLDate($date,true);
    $message = ticketMessageFormat($message);

	$reportdata["tablevalues"][] = array('<a href="supporttickets.php?action=viewticket&id='.$tid.'">'.$ticketid.'</a>',$date,'<div align="left">'.$message.'</div>',$admin,$rating);
}

?>