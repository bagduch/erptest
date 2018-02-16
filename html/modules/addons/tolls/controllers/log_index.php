<?php
if(!defined("RA")){die("This file cannot be accessed directly");}
ini_set('display_errors', 1);
error_reporting(E_ALL);;

# ------------------------------------------------------
# Re-sync action
if(isset($_GET['sync']) && $_GET['sync']!=''){
	$data['update_unixtime'] = strtotime($_GET['sync']);
	require(dirname(dirname(__FILE__)) . '/models/hdtolls.php');
	$hdtolls = new hdtolls();
	$data['sync'] = $hdtolls->sync($data['update_unixtime']);
	if(isset($data['sync']['errors']) && count($data['sync']['errors'])>0){$data['sync']['errors'] = implode('<br />', $data['sync']['errors']);} else {
		$data['sync']['success'] = htmlentities($_GET['sync']) . ' parsed';
	}
}


# ------------------------------------------------------
# Show rows
$sql = "SELECT * FROM mod_hdtolls_log_index ORDER BY `date_index` DESC";
$result = mysql_query($sql) or die("MySQL Error: " . mysql_errno() . ": " . mysql_error() . '<hr ><pre>' . $sql . '</pre>');
$data['numitems']	= mysql_num_rows($result);

# ------------------------------------------------------
# Pagination
$page_int				= 1;
$data['page_per']		= 25;
$data['itemlimit'] 		= $data['page_per'];
if(isset($_REQUEST['itemlimit']) && (int)$_REQUEST['itemlimit']>0){
	$data['page_per'] = (int)$_REQUEST['itemlimit'];
	$data['itemlimit'] = $data['page_per'];
} else if(isset($_REQUEST['itemlimit']) && strtolower($_REQUEST['itemlimit'])=='all'){
	$data['page_per'] = 'all';
	$data['itemlimit'] = 99999999;
}


if($data['page_per']>0){
	$data['totalpages']		= ceil($data['numitems']/$data['page_per']);
} else {
	$data['totalpages'] = 1;
}


if(isset($_REQUEST['page']) && (int)$_REQUEST['page']>0 && (int)$_REQUEST['page']<=$data['totalpages']	){
	$page_int = (int)$_REQUEST['page'];
}
$data['pagenumber']		= $page_int;

if($data['page_per']!='all'){
	if($data['pagenumber']<$data['totalpages']){
		$data['nextpage']		= $page_int+1;
	}
	if($page_int>1){
		$data['prevpage']		= $page_int-1;
	}

	$sql .= " LIMIT " . ($page_int-1)*$data['page_per'] . ", " . $data['page_per'];
}
# ------------------------------------------------------

$result = mysql_query($sql) or die("MySQL Error: " . mysql_errno() . ": " . mysql_error() . '<hr ><pre>' . $sql . '</pre>');
$data['rows'] = array();
while ($row = mysql_fetch_assoc($result, MYSQL_ASSOC)) {
	if(substr($row['errors'], 0, 1)=='[' || substr($row['errors'], 0, 1)=='{'){
		$row['errors'] = implode('<br />', json_decode($row['errors'], true));
	}
    $data['rows'][] = $row;
}
