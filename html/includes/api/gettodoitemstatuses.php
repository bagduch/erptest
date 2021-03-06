<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$statuses = array("New" => array("count" => 0, "overdue" => 0), "Pending" => array("count" => 0, "overdue" => 0), "In Progress" => array("count" => 0, "overdue" => 0), "Completed" => array("count" => 0, "overdue" => 0), "Postponed" => array("count" => 0, "overdue" => 0));
$todo_result = full_query_i("SELECT status, COUNT(*) AS count FROM tbltodolist GROUP BY status");

while ($todo = mysqli_fetch_assoc($todo_result)) {
	$statuses[$todo['status']]['count'] = $todo['count'];
}

$todo_over_due_result = full_query_i("SELECT status, COUNT(*) AS count FROM tbltodolist WHERE DATE(duedate) <= CURDATE() GROUP BY status");

while ($todo_over_due = mysqli_fetch_assoc($todo_over_due_result)) {
	$statuses[$todo_over_due['status']]['overdue'] = $todo_over_due['count'];
}

$apiresults = array("result" => "success", "totalresults" => 5);
foreach ($statuses as $key => $status) {
	$apiresults['todoitemstatuses']['status'][] = array("type" => $key, "count" => $status['count'], "overdue" => $status['overdue']);
}

$responsetype = "xml";
?>