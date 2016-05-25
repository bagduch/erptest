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

$title = html_entity_decode($title);
$announcement = html_entity_decode($announcement);
$id = insert_query("tblannouncements", array("date" => $date, "title" => $title, "announcement" => $announcement, "published" => $published));
run_hook("AnnouncementAdd", array("announcementid" => $id, "date" => $date, "title" => $title, "announcement" => $announcement, "published" => $published));
$apiresults = array("result" => "success", "announcementid" => $id);
?>