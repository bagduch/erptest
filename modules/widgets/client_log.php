<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

function widget_client_log($vars) {
    global $_ADMINLANG;
    $title = "Client Log Overview";

    $clientlogquery = "select firstname,lastname,ip,lastlogin from tblclients order by lastlogin DESC LIMIT 4";
    $result = full_query_i($clientlogquery);
    $clientlog = array();

    while ($data = mysqli_fetch_array($result)) {
        $table .= "<tr>
             <td>" . $data['firstname'] . " " . $data['lastname'] . "</td>
             <td>" . $data['ip'] . "</td>
             <td>" . $data['lastlogin'] . "</td>
                  </tr>";
    }

    $content = <<<EOF
                <table class="table table-bordered widgethm">
                    <tbody>
                        <tr>
                           <th>Client</th>
        <th>IP Address</th>
        <th>Last Access</th>
                        </tr>
                      $table
                    </tbody>
                </table>
EOF;

    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 1, "widget_client_log");
?>