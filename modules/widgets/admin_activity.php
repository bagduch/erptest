<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

function widget_admin_activity($vars) {
    global $_ADMINLANG;
    $title = "Admin Activity Overview";
    $query = "select tbl1.* from tbladminlog tbl1 inner join (select * from tbladminlog order by lastvisit DESC) as tbl2 on tbl1.id = tbl2.id GROUP By tbl1.adminusername order by lastvisit DESC";
    $result = full_query_i($query);
    $table = "";
    while ($data = mysqli_fetch_array($result)) {
        $table .= "<tr>
             <td>" . $data['adminusername'] . "</td>
             <td>" . $data['ipaddress'] . "</td>
             <td>" . $data['lastvisit'] . "</td>
                  </tr>";
    }

    $content = <<<EOF
                <table class="table table-bordered widgethm">
                    <tbody>
                        <tr>
                            <th>Admin</th>
                            <th>IP Address</th>
                            <th>Last Access</th>
                        </tr>
                      $table
                    </tbody>
                </table>
EOF;

    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 1, "widget_admin_activity");
?>