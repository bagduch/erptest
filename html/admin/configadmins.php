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
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Administrators");
$aInt->title = $aInt->lang("administrators", "title");
$aInt->sidebar = "config";
$aInt->icon = "admins";
$aInt->helplink = "Administrators";
$validate = new RA_Validate();
$menuselect = "";
if ($action == "save") {
    check_token("RA.admin.default");
    $auth = new RA_Auth();
    $auth->getInfobyID(RA_Session::get("adminid"));

    if (!$auth->comparePassword($ra->get_req_var("confirmpassword"))) {
        $_ADMINLANG['administrators']['confirmexistingpw'] = "You must confirm your existing administrator password";
        $validate->addError(array("administrators", "confirmexistingpw"));
    } else {
        $validate->validate("required", "firstname", array("administrators", "namerequired"));

        if ($validate->validate("required", "email", array("administrators", "emailerror"))) {
            $validate->validate("email", "email", array("administrators", "emailinvalid"));
        }


        if ($validate->validate("required", "username", array("administrators", "usererror"))) {
            $existingid = get_query_val("tbladmins", "id", array("username" => $username));

            if ((!$id && $existingid) || (($id && $existingid) && $id != $existingid)) {
                $validate->addError("administrators", "userexists");
            }
        }


        if (!$id) {
            if ($validate->validate("required", "password", array("administrators", "pwerror"))) {
                $validate->validate("match_value", "password", array("administrators", "pwmatcherror"), "password2");
            }
        }
    }


    if ($validate->hasErrors()) {
        $action = "manage";
    } else {
        $supportdepts = implode(",", $deptids);
        $ticketnotify = implode(",", $ticketnotify);
        $disabled = ($disabled ? 1 : 0);

        if ($id) {
            update_query("tbladmins", array(
                "roleid" => $roleid,
                "username" => $username,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "signature" => $signature,
                "disabled" => $disabled,
                "notes" => $notes,
                "template" => $template,
                "language" => $language,
                "supportdepts" => $supportdepts,
                "ticketnotifications" => $ticketnotify
                    ), array(
                "id" => $id
                    )
            );

            if ($password) {
                update_query(
                        "tbladmins", array(
                    "password_hash" => password_hash($password, PASSWORD_DEFAULT)
                        ), array(
                    "id" => $id
                        )
                );
            }

            redir("saved=true");
        } else {
            insert_query(
                    "tbladmins", array(
                "roleid" => $roleid,
                "username" => $username,
                "passwordhash" => password_hash(trim($password), PASSWORD_DEFAULT),
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "signature" => $signature,
                "notes" => $notes,
                "template" => $template,
                "language" => $language,
                "supportdepts" => $supportdepts,
                "ticketnotifications" => $ticketnotify
                    )
            );
            redir("added=true");
        }

        exit();
    }
}


if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("tbladmins", array("id" => $id));
    redir("deleted=true");
}

ob_start();

if ($action == "") {
    if ($saved) {
        infoBox($aInt->lang("administrators", "changesuccess"), $aInt->lang("administrators", "changesuccessinfo"));
    } elseif ($added) {
        infoBox($aInt->lang("administrators", "addsuccess"), $aInt->lang("administrators", "addsuccessinfo"));
    } elseif ($deleted) {
        infoBox($aInt->lang("administrators", "deletesuccess"), $aInt->lang("administrators", "deletesuccessinfo"));
    } else {
        
    }

    $data = get_query_vals("tbladmins", "COUNT(id),id", array("roleid" => "1"));
    $numrows = $data[0];
    $onlyadminid = ($numrows == "1" ? $data['id'] : 0);
    $jscode = "function doDelete(id) {
    if(id != " . $onlyadminid . "){
        if (confirm(\"" . $aInt->lang("administrators", "deletesure", 1) . "\")) {
        window.location='" . $_SERVER['PHP_SELF'] . "?action=delete&id='+id+'" . generate_token("link") . "';
        }
    } else alert(\"" . $aInt->lang("administrators", "deleteonlyadmin", 1) . "\");
    }";

    $aInt->sortableTableInit("nopagination");
    $result = select_query_i("tbladmins", "tbladmins.*,tbladminroles.name", array("disabled" => "0"), "firstname` ASC,`lastname", "ASC", "", "tbladminroles ON tbladmins.roleid=tbladminroles.id");

    while ($data = mysqli_fetch_array($result)) {
        $departments = $deptnames = array();
        $supportdepts = db_build_in_array(explode(",", $data['supportdepts']));

        if ($supportdepts) {
            $resultdeptids = select_query_i("tblticketdepartments", "name", "id IN (" . $supportdepts . ")");

            while ($data_resultdeptids = mysqli_fetch_array($resultdeptids)) {
                $deptnames[] = $data_resultdeptids[0];
            }
        }


        if (!count($deptnames)) {
            $deptnames[] = $aInt->lang("global", "none");
        }

        $tabledata[] = array(
            $data['firstname'] . " " . $data['lastname'],
            "<a href=\"mailto:" . $data['email'] . "\">" . $data['email'] . "</a>",
            $data['username'],
            $data['name'],
            implode(", ", $deptnames),
            "<a class=\"btn btn-success\" href=\"" . $PHP_SELF . "?action=manage&id=" . $data['id'] . "\"><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a>",
            "<a class=\"btn btn-danger\" href=\"#\" onClick=\"doDelete('" . $data['id'] . "')\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>"
        );
    }

    $tableactive = $aInt->sortableTable(
            array(
        $aInt->lang("fields", "name"),
        $aInt->lang("fields", "email"),
        $aInt->lang("fields", "username"),
        $aInt->lang("administrators", "adminrole"),
        $aInt->lang("administrators", "assigneddepts"),
        "",
        ""
            ), $tabledata
    );
    echo "<h2>" . $aInt->lang("administrators", "inactive") . " </h2>";
    $tabledata = array();
    $result = select_query_i("tbladmins", "tbladmins.*,tbladminroles.name", array("disabled" => "1"), "firstname` ASC,`lastname", "ASC", "", "tbladminroles ON tbladmins.roleid=tbladminroles.id");

    while ($data = mysqli_fetch_array($result)) {
        $departments = $deptnames = array();
        $supportdepts = db_build_in_array(explode(",", $data['supportdepts']));

        if ($supportdepts) {
            $resultdeptids = select_query_i("tblticketdepartments", "name", "id IN (" . $supportdepts . ")");

            while ($data_resultdeptids = mysqli_fetch_array($resultdeptids)) {
                $deptnames[] = $data_resultdeptids[0];
            }
        }


        if (!count($deptnames)) {
            $deptnames[] = $aInt->lang("global", "none");
        }

        $tabledata[] = array($data['firstname'] . " " . $data['lastname'], "<a href=\"mailto:" . $data['email'] . "\">" . $data['email'] . "</a>", $data['username'], $data['name'], implode(", ", $deptnames), "<a class=\"btn btn-success\" href=\"" . $PHP_SELF . "?action=manage&id=" . $data['id'] . "\"><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a>", "<a  class=\"btn btn-danger\" href=\"#\" onClick=\"doDelete('" . $data['id'] . "')\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>");
    }

    $tableinactive = $aInt->sortableTable(array($aInt->lang("fields", "name"), $aInt->lang("fields", "email"), $aInt->lang("fields", "username"), $aInt->lang("administrators", "adminrole"), $aInt->lang("administrators", "assigneddepts"), "", ""), $tabledata);
    $aInt->assign("infobox", $infobox);
    $aInt->assign("tableinactive", $tableinactive);
    $aInt->assign("tableactive", $tableactive);
    $template = "configadmins";
} elseif ($action == "manage") {
    if ($id) {
        $result = select_query_i("tbladmins", "", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $supportdepts = $data['supportdepts'];
        $ticketnotifications = $data['ticketnotifications'];
        $supportdepts = explode(",", $supportdepts);
        $ticketnotify = explode(",", $ticketnotifications);

        if (!$validate->hasErrors()) {
            $roleid = $data['roleid'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $email = $data['email'];
            $username = $data['username'];
            $signature = $data['signature'];
            $notes = $data['notes'];
            $template = $data['template'];
            $language = $data['language'];
            $disabled = $data['disabled'];
            $aInt->assign('data', $data);
        }

        $numrows = get_query_vals("tbladmins", "COUNT(id)", array("roleid" => "1"));
        $onlyadmin = (($numrows == "1" && $roleid == "1") ? true : false);
        $managetitle = $aInt->lang("administrators", "editadmin");
    } else {
        $supportdepts = $ticketnotify = array();
        $managetitle = $aInt->lang("administrators", "addadmin");
    }

    $language = $ra->validateLanguage($language, true);

    if ($validate->hasErrors()) {
        infoBox($aInt->lang("global", "validationerror"), $validate->getHTMLErrorOutput(), "error");
        echo $infobox;
    }


    if ($onlyadmin) {
        echo " disabled";
    }

    $result = select_query_i("tbladminroles", "", "", "name", "ASC");

    $roleoption = "";
    while ($data = mysqli_fetch_array($result)) {
        $select_roleid = $data['id'];
        $select_rolename = $data['name'];
        $roleoption .= "<option value=\"" . $select_roleid . "\"";

        if ($roleid == $select_roleid) {
            $roleoption .= " selected";
        }

        $roleoption .= ">" . $select_rolename . "</option>";
    }



    if ($id) {
        echo " (" . $aInt->lang("administrators", "entertochange") . ")";
    }

    $nodepartments = true;
    $result = select_query_i("tblticketdepartments", "", "", "order", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $deptid = $data['id'];
        $deptname = $data['name'];
        echo "<label><input type=\"checkbox\" name=\"deptids[]\" value=\"" . $deptid . "\"";

        if (in_array($deptid, $supportdepts)) {
            echo " checked";
        }

        echo "> " . $deptname . "</label> <label><input type=\"checkbox\" name=\"ticketnotify[]\" value=\"" . $deptid . "\"";

        if (in_array($deptid, $ticketnotify)) {
            echo " checked";
        }

        echo "> Enable Ticket Notifications</label><br />";
        $nodepartments = false;
    }


    if ($nodepartments) {
        echo $aInt->lang("administrators", "nosupportdepts");
    }

    $templates = array();
    $dirpath = ROOTDIR . "/" . $ra->get_admin_folder_name() . "/templates/";
    $dh = opendir($dirpath);

    while (false !== $folder = readdir($dh)) {
        if (is_file($dirpath . $folder . "/header.tpl")) {
            $templates[] = $folder;
        }
    }

    sort($templates);
    $templateoptions = "";
    foreach ($templates as $temp) {
        $templateoptions .= "<option value=\"" . $temp . "\"";

        if ($temp == $template) {
            $templateoptions .= " selected";
        }

        $templateoptions .= ">" . ucfirst($temp) . "</option>";
    }

    closedir($dh);
    $languageoption = "";
    foreach ($ra->getValidLanguages(true) as $lang) {
        $languageoption .= "<option value=\"" . $lang . "\"";

        if ($lang == $language) {
            $languageoption .= " selected=\"selected\"";
        }

        $languageoption .= ">" . ucfirst($lang) . "</option>";
    }


    if ($disabled == 1) {
        echo " checked";
    }


    if ($onlyadmin || $id == $_SESSION['adminid']) {
        echo " disabled";
    }

    echo $aInt->lang("administrators", "disableinfo");


    $aInt->assign("languageoption", $languageoption);
    $aInt->assign("templateoptions", $templateoptions);
    $aInt->assign("roleoption", $roleoption);
    $template = "configadminsmanage";
} else {
    
}

$content = ob_get_contents();
ob_end_clean();
$aInt->template = $template;
$aInt->content = $content;
$aInt->jscode = $jscode;
$aInt->jquerycode .= $menuselect;
$aInt->display();
?>
