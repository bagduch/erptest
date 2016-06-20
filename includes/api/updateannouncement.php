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

if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("tblannouncements", "id", array("id" => $announcementid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Announcement ID Not Found");
	return false;
}

$title = html_entity_decode($title);
$announcement = html_entity_decode($announcement);
insert_query("tblannouncements", array("date" => $date, "title" => $title, "announcement" => $announcement, "published" => $published), array("id" => $announcementid));
run_hook("AnnouncementEdit", array("announcementid" => $announcementid, "date" => $date, "title" => $title, "announcement" => $announcement, "published" => $published));
$apiresults = array("result" => "success", "announcementid" => $announcementid);
?>