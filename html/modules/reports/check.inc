<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sql = "select * from ra_admin as tba INNER JOIN ra_adminroles AS tbar on tba.roleid=tbar.id where tba.id=" . $_SESSION['adminid'];
$result = full_query_i($sql);
$data = mysqli_fetch_assoc($result);
$report = explode(",", $data['report']);


if (!in_array($_GET['report'], $report)) {
    echo "Premission deny please contact admin";
    exit();
}
