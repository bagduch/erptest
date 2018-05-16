<?php

function kbGetCatIds($catid) {
    global $idnumbers;
    $result = select_query_i("ra_kbcats", "id", array("parentid" => $catid, "hidden" => ""));
    while ($data = mysqli_fetch_array($result)) {
        $cid = $data[0];
        $idnumbers[] = $cid;
        kbGetCatIds($cid);
    }
}

function buildCategoriesList($level, $parentlevel, $exclude = "") {

    global $categorieslist;
    global $categories;
    if ($level == 0) {
        $le['sqltype'] = "NULL";
    }
    $result = select_query_i("ra_kbcats", "", array("parentid" => $le, "catid" => 0), "name", "ASC");
    while ($data = mysqli_fetch_array($result)) {

        $id = $data['id'];
        $parentid = $data['parentid'];
        $category = $data['name'];

        if ($id != $exclude) {
            $categorieslist .= ("<option value=\"" . $id . "\"");

            if (in_array($id, $categories)) {
                $categorieslist .= " selected";
            }

            $categorieslist .= ">";
            $i = 1;

            while ($i <= $parentlevel) {
                $categorieslist .= "- ";
                ++$i;
            }

            $categorieslist .= "" . $category . "</option>";
        }

        buildCategoriesList($id, $parentlevel + 1, $exclude);
    }
}

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Manage Knowledgebase");
$aInt->title = $aInt->lang("support", "knowledgebase");
$aInt->sidebar = "support";
$aInt->icon = "knowledgebase";
$menuselect = "$('#menu').multilevelpushmenu('expand','Utilities');";
$catid = (int) $catid;
$categorieslist = "";

if ($catid == 0) {
    $catid = null;
}

if ($addarticle) {
    check_token("RA.admin.default");
    $newarticleid = insert_query("ra_kb", array("title" => $articlename, "article" => '', "private" => "", "order" => 0, "parentid" => "0", "language" => 'en'));
    insert_query("ra_kblinks", array("categoryid" => $catid, "articleid" => $newarticleid));
    logActivity("Added New Knowledgebase Article - " . $articlename);
    redir("action=edit&id=" . $newarticleid);
    exit();
}


if ($addcategory) {
    check_token("RA.admin.default");
    if ($catid == "") {
        $cid = "NULL";
    }
    $newcatid = insert_query("ra_kbcats", array("parentid" => $cid, "name" => $catname, "catid" => 0, "description" => $description, "hidden" => $hidden, 'language' => 'en'));
    logActivity("Added New Knowledgebase Category - " . $catname);
    redir("catid=" . $newcatid);
    exit();
}


if ($action == "save") {
    check_token("RA.admin.default");
    update_query("ra_kb", array("title" => $title, "article" => html_entity_decode($article), "views" => $views, "useful" => $useful, "votes" => $votes, "private" => $private, "order" => $order), array("id" => $id));
    delete_query("ra_kblinks", array("articleid" => $id));
    foreach ($categories as $category) {
        insert_query("ra_kblinks", array("categoryid" => $category, "articleid" => $id));
    }

    if ($toggleeditor) {
        if ($editorstate) {
            redir("action=edit&id=" . $id);
        } else {
            redir("action=edit&id=" . $id . "&noeditor=1");
        }
    }

    logActivity("Modified Knowledgebase Article ID: " . $id);
    redir("catid=" . $categories[0]);
    exit();
}


if ($action == "savecat") {
    check_token("RA.admin.default");
    update_query("ra_kbcats", array(
        "name" => $name,
        "description" => $description,
        "hidden" => $hidden,
        "parentid" => $parentcategory), array("id" => $id)
    );
    foreach ($multilang_name as $language => $name) {
        delete_query("ra_kbcats", array("catid" => $id, "language" => $language));

        if ($name) {
            insert_query("ra_kbcats", array("catid" => $id, "name" => $name, "description" => html_entity_decode($multilang_desc[$language]), "language" => $language));
            continue;
        }
    }

    logActivity("Modified Knowledgebase Category (ID: " . $id . ")");
    redir("catid=" . $parentcategory);
    exit();
}


if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("ra_kb", array("id" => $id));
    delete_query("ra_kblinks", array("articleid" => $id));
    logActivity("Deleted Knowledgebase Article (ID: " . $id . ")");
    redir("catid=" . $catid);
    exit();
}


if ($action == "deletecategory") {
    check_token("RA.admin.default");
    delete_query("ra_kblinks", array("categoryid" => $id));
    delete_query("ra_kbcats", array("id" => $id));
    delete_query("ra_kbcats", array("parentid" => $id));
    full_query_i("DELETE FROM ra_kb WHERE parentid=0 AND id NOT IN (SELECT articleid FROM ra_kblinks)");
    logActivity("Deleted Knowledgebase Category (ID: " . $id . ")");
    redir("catid=" . $catid);
    exit();
}

ob_start();

if ($action == "") {
    if (!$catid) {
        $catid = 0;
    }

    $breadcrumbnav = "";

    if ($catid != "0") {
        $result = select_query_i("ra_kbcats", "", array("id" => $catid));
        $data = mysqli_fetch_array($result);
        $catid = $data['id'];

        if (!$catid) {
            $aInt->gracefulExit("Category ID Not Found");
        }

        $catparentid = $data['parentid'];
        $catname = $data['name'];
        $catbreadcrumbnav = " > <a href=\"" . $PHP_SELF . "?catid=" . $catid . "\">" . $catname . "</a>";

        if ($catparentid != NULL || $catparentid != "") {
            while ($catparentid != "0") {
                $result = select_query_i("ra_kbcats", "", array("id" => $catparentid));
                $data = mysqli_fetch_array($result);
                $cattempid = $data['id'];
                $catparentid = $data['parentid'];
                $catname = $data['name'];
                $catbreadcrumbnav = " > <a href=\"" . $PHP_SELF . "?catid=" . $cattempid . "\">" . $catname . "</a>" . $catbreadcrumbnav;
            }

            $breadcrumbnav .= $catbreadcrumbnav;
        }
    }

    $aInt->deleteJSConfirm("doDelete", "support", "kbdelsure", $_SERVER['PHP_SELF'] . "?catid=" . $catid . "&action=delete&id=");
    $aInt->deleteJSConfirm("doDeleteCat", "support", "kbcatdelsure", $_SERVER['PHP_SELF'] . "?catid=" . $catid . "&action=deletecategory&id=");
    echo "<div class=\"card\">" . $aInt->Tabs(array($aInt->lang("support", "addcategory"), $aInt->lang("support", "addarticle")), true);
    echo "
<div id=\"tab0box\" class=\"tabbox\">
  <div id=\"tab_content\">

<form method=\"post\" action=\"";
    echo $PHP_SELF;
    echo "?catid=";
    echo $catid;
    echo "&addcategory=true\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
    echo $aInt->lang("support", "catname");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"catname\" size=\"40\"> <input type=\"checkbox\" name=\"hidden\"> ";
    echo $aInt->lang("support", "ticktohide");
    echo "</td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("fields", "description");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"description\" size=\"100\"></td></tr>

</table>
<img src=\"images/spacer.gif\" width=\"1\" height=\"10\"><br>
<div align=\"center\"><input type=\"submit\" value=\"";
    echo $aInt->lang("support", "addcategory");
    echo "\" class=\"button\"></div>
</form>

  </div>
</div>
<div id=\"tab1box\" class=\"tabbox\">
  <div id=\"tab_content\">

";

    if ($catid != "") {
        echo "<form method=\"post\" action=\"";
        echo $PHP_SELF;
        echo "?catid=";
        echo $catid;
        echo "&addarticle=true\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
        echo $aInt->lang("support", "articlename");
        echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"articlename\" size=\"60\"></td></tr>
</table>
<img src=\"images/spacer.gif\" width=\"1\" height=\"10\"><br>
<div align=\"center\"><input type=\"submit\" value=\"";
        echo $aInt->lang("support", "addarticle");
        echo "\" class=\"button\"></div>
</form>
";
    } else {
        echo $aInt->lang("support", "kbnotoplevel");
    }

    echo "
  </div>
</div>

";
    echo "<p>" . $aInt->lang("support", "youarehere") . (": <a href=\"" . $PHP_SELF . "\">") . $aInt->lang("support", "kbhome") . "</a> " . $breadcrumbnav . "</p>";
    $result = full_query_i("SELECT * FROM ra_kbcats WHERE parentid IS null ORDER BY name ASC");
    /* 	$result = select_query_i(
      "ra_kbcats",
      "",
      array(
      "parentid" => array(
      "sqltype" => "NEQ",
      "value" => null
      )
      ),
      "name",
      "ASC"

      );
     */

    $numcats = mysqli_num_rows($result);

    if ($numcats != "0") {
        echo "
<p><b>";
        echo $aInt->lang("support", "categories");
        echo "</b></p>

<table width=100%><tr>
";

        if ($catid == "") {
            $catid = "0";
        }

//		$result = select_query_i("ra_kbcats", "", array("parentid" => $catid, "catid" => 0), "name", "ASC");
        $stmt = full_query_i("SELECT * FROM ra_kbcats WHERE parentid IS NULL");
//        $stmt->bind_param($catid,$catname,$description,$hidden);a
//        $stmt->bind_param();
        //       $result = $stmt->execute();

        $i = 0;

        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $name = $data['name'];
            $description = $data['description'];
            $hidden = $data['hidden'];
            $idnumbers = array();
            $idnumbers[] = $id;
            kbGetCatIds($id);
            $queryreport = "";
            foreach ($idnumbers as $idnumber) {
                $queryreport .= " OR categoryid='" . $idnumber . "'";
            }

            $queryreport = substr($queryreport, 4);
            $result2 = select_query_i("ra_kb", "COUNT(*)", "parentid=NULL AND (" . $queryreport . ")", "", "", "", "ra_kblinks ON ra_kb.id=ra_kblinks.articleid");
            $data2 = mysqli_fetch_array($result2);
            $numarticles = $data2[0];
            echo "<td width=33%><img src=\"../images/folder.gif\" align=\"absmiddle\"> <a href=\"" . $PHP_SELF . "?catid=" . $id . "\"><b>" . $name . "</b></a> (" . $numarticles . ") <a href=\"" . $PHP_SELF . "?action=editcat&id=" . $id . "\"><img src=\"images/edit.gif\" align=\"absmiddle\" border=\"0\" alt=\"" . $aInt->lang("global", "edit") . ("\" /></a> <a href=\"#\" onClick=\"doDeleteCat(" . $id . ")\"><img src=\"images/delete.gif\" align=\"absmiddle\" border=\"0\" alt=\"") . $aInt->lang("global", "delete") . "\" /></a>";

            if ($hidden == "on") {
                echo " <font color=#cccccc>(" . strtoupper($aInt->lang("fields", "hidden")) . ")</font>";
            }

            echo "<br>" . $description . "</td>";
            ++$i;

            if ($i % 3 == 0) {
                echo "</tr><tr><td><br></td></tr><tr>";
                $i = 0;
            }
        }

        echo "</tr></table>

";
    }

    $result = select_query_i("ra_kb", "", array("categoryid" => $catid), "order` ASC,`title", "ASC", "", "ra_kblinks ON ra_kb.id=ra_kblinks.articleid");
    $numarticles = mysqli_num_rows($result);

    if ($numarticles != "0") {
        echo "
<p><b>";
        echo $aInt->lang("support", "articles");
        echo "</b></p>

<table width=100%><tr>
";

        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $category = $data['category'];
            $title = $data['title'];
            $article = strip_tags($data['article']);
            $views = $data['views'];
            $private = $data['private'];
            $article = substr($article, 0, 150) . "...";
            echo "<p><img src=\"../images/article.gif\" align=\"absmiddle\"> <a href=\"" . $PHP_SELF . "?action=edit&id=" . $id . "\"><b>" . $title . "</b></a> <a href=\"#\" onClick=\"doDelete(" . $id . ")\"><img src=\"images/delete.gif\" align=\"absmiddle\" border=\"0\" alt=\"" . $aInt->lang("global", "delete") . "\"></a></font>";

            if ($private == "on") {
                echo " <font color=#cccccc>(" . strtoupper($aInt->lang("support", "clientsonly")) . ")</font>";
            }

            echo "<br>" . $article . "<br><font color=#cccccc>" . $aInt->lang("support", "views") . (": " . $views . "</p>");
        }

        echo "</tr></table>

";
    } else {
        echo "<p><b>" . $aInt->lang("support", "noarticlesfound") . "</b></p>";
    }
} elseif ($action == "edit") {

    $result = select_query_i("ra_kb", "", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $title = $data['title'];
    $article = $data['article'];
    $views = $data['views'];
    $useful = $data['useful'];
    $votes = $data['votes'];
    $private = $data['private'];
    $order = $data['order'];
    $multilang_title = array();
    $multilang_article = array();
    $result = select_query_i("ra_kb", "", array("parentid" => $id));

    while ($data = mysqli_fetch_array($result)) {
        $language = $data['language'];
        $multilang_title[$language] = $data['title'];
        $multilang_article[$language] = $data['article'];
    }

    $categories = array();
    $result = select_query_i("ra_kblinks", "", array("articleid" => $id));

    while ($data = mysqli_fetch_array($result)) {
        $categories[] = $data['categoryid'];
    }

    $jscode = "function showtranslation(language) {
    $(\"#translation_\"+language).slideToggle();
}";
    echo "
<form method=\"post\" action=\"";
    echo $PHP_SELF;
    echo "?catid=";
    echo $category;
    echo "&action=save&id=";
    echo $id;
    echo "\">
<input type=\"hidden\" name=\"editorstate\" value=\"";
    echo $noeditor;
    echo "\" />

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
    echo $aInt->lang("support", "categories");
    echo "</td><td class=\"fieldarea\">";

    echo "<s";
    echo "elect name=\"categories[]\" size=\"8\" multiple style=\"width:80%;\">";
    buildCategoriesList(0, 0);
    echo $categorieslist;
    echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("fields", "title");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"title\" value=\"";
    echo $title;
    echo "\" size=\"70\"></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("support", "views");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"views\" value=\"";
    echo $views;
    echo "\" size=\"10\"></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("support", "votes");
    echo "</td><td class=\"fieldarea\">For <input type=\"text\" name=\"useful\" value=\"";
    echo $useful;
    echo "\" size=\"10\"> Total <input type=\"text\" name=\"votes\" value=\"";
    echo $votes;
    echo "\" size=\"10\"></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("support", "private");
    echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"private\"";

    if ($private == "on") {
        echo " checked";
    }

    echo "> ";
    echo $aInt->lang("support", "privateinfo");
    echo "</td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("customfields", "order");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"order\" value=\"";
    echo $order;
    echo "\" size=\"10\"></td></tr>
</table>

<br />

<textarea name=\"article\" rows=\"20\" style=\"width:100%\" class=\"tinymce\">";
    echo $article;
    echo "</textarea>

<p align=\"center\"><input type=\"submit\" name=\"toggleeditor\" value=\"";
    echo $aInt->lang("emailtpls", "rteditor");
    echo "\" class=\"btn\" /> <input type=\"submit\" value=\"";
    echo $aInt->lang("global", "savechanges");
    echo "\" class=\"btn\" /></p>

<h2>";
    echo $aInt->lang("support", "announcemultiling");
    echo "</h2>

";
    foreach ($ra->getValidLanguages() as $language) {

        if ($language != $CONFIG['Language']) {
            echo "<p><b><a href=\"#\" onClick=\"showtranslation('" . $language . "');return false;\">" . ucfirst($language) . "</a></b></p>
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\" id=\"translation_" . $language . "\"";

            if (!$multilang_title[$language]) {
                echo " style=\"display:none;\"";
            }

            echo ">
<tr><td width=\"15%\" class=\"fieldlabel\">" . $aInt->lang("fields", "title") . "</td><td class=\"fieldarea\"><input type=\"text\" name=\"multilang_title[" . $language . "]\" value=\"" . $multilang_title[$language] . "\" size=\"70\"></td></tr>
<tr><td class=\"fieldlabel\">" . $aInt->lang("support", "article") . "</td><td class=\"fieldarea\"><textarea name=\"multilang_article[" . $language . "]\" rows=\"20\" style=\"width:100%\" class=\"tinymce\">" . $multilang_article[$language] . "</textarea></td></tr>
</table>";
            continue;
        }
    }

    closedir($dh);
    echo "
<p align=\"center\"><input type=\"submit\" value=\"";
    echo $aInt->lang("global", "savechanges");
    echo "\" class=\"btn\" /></p>

</form>

";

    if (!$noeditor) {
        $aInt->richTextEditor();
    }
} elseif ($action == "editcat") {
    $result = select_query_i("ra_kbcats", "", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $parentid = $data['parentid'];
    $name = $data['name'];
    $description = $data['description'];
    $hidden = $data['hidden'];
    $categories = array();
    $categories[] = $parentid;
    $multilang_name = array();
    $multilang_desc = array();
    $result = select_query_i("ra_kbcats", "", array("catid" => $id));

    while ($data = mysqli_fetch_array($result)) {
        $language = $data['language'];
        $multilang_name[$language] = $data['name'];
        $multilang_desc[$language] = $data['description'];
    }

    echo "
<form method=\"post\" action=\"";
    echo $PHP_SELF;
    echo "?action=savecat&id=";
    echo $id;
    echo "\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
    echo $aInt->lang("support", "parentcat");
    echo "</td><td class=\"fieldarea\">";
    echo "<s";
    echo "elect name=\"parentcategory\">
<option value=\"\">";
    echo $aInt->lang("support", "toplevel");
    echo "</option>
";
    buildCategoriesList(0, 0, $id);
    echo $categorieslist;
    echo "?></select></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("support", "catname");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"name\" value=\"";
    echo $name;
    echo "\" size=\"40\"></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("fields", "description");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"description\" value=\"";
    echo $description;
    echo "\" size=\"100\"></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("fields", "hidden");
    echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"hidden\"";

    if ($hidden == "on") {
        echo " checked";
    }

    echo "> ";
    echo $aInt->lang("fields", "hiddeninfo");
    echo "</td></tr>
</table>

<h2>";
    echo $aInt->lang("support", "announcemultiling");
    echo "</h2>

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
";
    foreach ($ra->getValidLanguages() as $language) {
        echo "<tr><td width=\"15%\" class=\"fieldlabel\">" . ucfirst($language) . "</td><td class=\"fieldarea\">" . $aInt->lang("fields", "name") . ": <input type=\"text\" name=\"multilang_name[" . $language . "]\" value=\"" . $multilang_name[$language] . "\" size=\"40\"> " . $aInt->lang("fields", "description") . ": <input type=\"text\" name=\"multilang_desc[" . $language . "]\" value=\"" . $multilang_desc[$language] . "\" size=\"60\"></td></tr>
";
    }

    echo "</table>

<p align=\"center\"><input type=\"submit\" value=\"";
    echo $aInt->lang("global", "savechanges");
    echo "\" class=\"btn\" /></p>

</form>

";
} else {
    
}


$content = ob_get_contents();
ob_end_clean();

$aInt->content = $content;
$aInt->jquerycode = $jquerycode . $menuselect;
$aInt->jscode = $jscode;
$aInt->display();
?>
