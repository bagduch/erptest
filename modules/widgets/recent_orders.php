<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

function widget_recent_left_orders($vars) {
    global $_ADMINLANG;
    $title = "Recent Order";

    $clientlogquery = "select tlo.id,tlc.firstname,tlc.lastname,tlo.ordernum,tlo.date,tlo.amount,tlo.status,ti.status as paymentstatus from tblorders as tlo 
        INNER JOIN tblclients as tlc ON tlo.userid=tlc.id 
        INNER JOIN tblinvoices as ti on tlo.invoiceid = ti.id
        where tlo.status='Pending' ORDER BY tlo.date DESC limit 5
";
    $result = full_query_i($clientlogquery);
    $clientlog = array();

    while ($data = mysqli_fetch_array($result)) {
        $table .= "<tr>
            <td><a href='orders.php?action=view&id=".$data['id']."'>" . $data['ordernum'] . "</a></td>
             <td>" . $data['firstname'] . " " . $data['lastname'] . "</td>
             <td>" . $data['date'] . "</td>
             <td>$" . $data['amount'] . "</td>
                 <td>" . $data['status'] . "</td>
                     <td>" . $data['paymentstatus'] . "</td>
                  </tr>";
    }

    $content = <<<EOF
                <table class="table table-bordered widgethm">
                    <tbody>
                        <tr>
                           <th>Order Number</th>
        <th>Name</th>
        <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Payemnt Status</th>
                        </tr>
                      $table
                    </tbody>
                </table>
EOF;

    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 1, "widget_recent_left_orders");
?>