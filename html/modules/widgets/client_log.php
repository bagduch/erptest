<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

function widget_client_log($vars) {
    global $_ADMINLANG;
    $title = "Client Log Overview";

    $clientlogquery = "select id,firstname,lastname,ip,lastlogin from ra_user where lastlogin between Now() AND Now()-Interval 30 minute order by lastlogin DESC LIMIT 4";
    $result = full_query_i($clientlogquery);

    if ($result->num_rows > 0) {
        while ($data = mysqli_fetch_array($result)) {
            $table .= "<tr>
             <td><a href='clientssummary.php?userid=" . $data['id'] . "'" . $data['firstname'] . " " . $data['lastname'] . "</a></td>
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
    } else {
        $content = "Online Customers: 0";
    }
    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 1, "widget_client_log");
?>