<?php
/** RA - Version 0.1 **/


define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("List Support Tickets");
$aInt->title = $aInt->lang("support", "insertkblink");
ob_start();
echo "
";
echo "<s";
echo "cript language=\"JavaScript\">
function insertKBLink(id) {
	window.opener.insertKBLink('";
echo $CONFIG['SystemURL'];
echo "/knowledgebase.php?action=displayarticle&catid=";
echo $cat;
echo "&id='+id);
	window.close();
}
</script>

<p><b>Categories</b></p>
";

if ($cat == "") {
	$cat = 0;
}

$result = select_query_i("ra_kbcats", "", array("parentid" => $cat), "name", "ASC");

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$name = $data['name'];
	$description = $data['description'];
	echo "<a href=\"" . $PHP_SELF . "?cat=" . $id . "\"><b>" . $name . "</b></a> - " . $description . "<br>";
	$catdone = true;
}


if (!$catdone) {
	echo $aInt->lang("support", "nocatsfound") . "<br>";
}

echo "<p><b>Articles</b></p>
";
$result = select_query_i("ra_kb", "", array("categoryid" => $cat), "title", "ASC", "", "ra_kblinks ON ra_kb.id=ra_kblinks.articleid");

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$category = $data['category'];
	$title = $data['title'];
	$article = $data['article'];
	$views = $data['views'];
	$article = strip_tags($article);
	$article = trim($article);
	$article = substr($article, 0, 100) . "...";
	echo "<a href=\"#\" onClick=\"insertKBLink('" . $id . "');\"><b>" . $title . "</b></a><br>" . $article . "<br>";
	$articledone = true;
}


if (!$articledone) {
	echo $aInt->lang("support", "noarticlesfound") . "<br>";
}

echo "
<p><a href=\"javascript:history.go(-1)\">";
echo "<";
echo "< ";
echo $aInt->lang("global", "back");
echo "</a></p>

";
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->displayPopUp();
?>