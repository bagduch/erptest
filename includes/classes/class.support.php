<?php

/**
 *
 * @ RA
 *
 * */
class RA_Support {

    public $tickets;
    public $ticket;
    public $aInt;

    public function __construct($id, $aInt) {

        if (isset($id)) {
            $result = select_query_i("tbltickets", "", array("id" => $id));
            $this->ticket = mysqli_fetch_array($result);
        } else {
            $result = select_query_i("tbltickets");
            $this->tickets = mysqli_fetch_array($result);
        }
    }

    public function getLang($aInt) {
        $this->aInt = $aInt;
    }

    public function getTicketstatus() {

        $status = array();
        $result = select_query_i("tblticketstatuses", "", "", "sortorder", "ASC");
        while ($data = mysqli_fetch_array($result)) {
            $status[] = array("title" => $data['title'], "color" => $data['color'], "id" => $data['id']);
        }

        return $status;
    }

    public function getTicketstatusHtml() {


        if (!empty($this->ticket)) {
            $view = $this->ticket['status'];
        }
        $status = $this->getTicketstatus();

        foreach ($status as $row) {
            $statuseshtml.= "<option style=\"color:" . $row['color'] . "\" value=\"" . $row['title'] . "\"" . ($row['title'] == $view ? " selected" : "") . ">" . $row['title'] . "</option>";
        }

        return $statuseshtml;
    }

    public function getMenuItem($link) {
        $ticketmenustatus[0] = array(
            "name" => "All Tickets",
            "icon" => "",
            "link" => $link . "?status=",
        );
        $statuses = $this->getTicketstatus();
        foreach ($statuses as $row) {
            $ticketmenustatus[] = array(
                "name" => $row['title'],
                "icon" => "",
                "link" => $link . "?status=" . $row['title'],
            );
        }

        $menuitem = array(array(
                "name" => "View Tickets",
                "id" => "tickets",
                "icon" => 'fa fa-ticket',
                "items" => array(
                    array(
                        "title" => "View Tickets",
                        "id" => "ticketmenu",
                        "id" => "tickets",
                        "icon" => 'fa fa-ticket',
                        "items" => $ticketmenustatus
                    )
                )
            )
        );

        return json_encode($menuitem);
    }

    public function tablehtml($tabledata, $view) {


        $tableformurl = "?view=" . $view . "&sub=multipleaction";
        $tableformbuttons = "<input onclick=\"return confirm('" . $this->aInt->lang("support", "massmergeconfirm", "1") . "');\" type=\"submit\" value=\"" . $this->aInt->lang("clientsummary", "merge") . "\" name=\"merge\" class=\"btn-small\" /> <input onclick=\"return confirm('" . $this->aInt->lang("support", "masscloseconfirm", "1") . "');\" type=\"submit\" value=\"" . $this->aInt->lang("global", "close") . "\" name=\"close\" class=\"btn-small\" /> <input onclick=\"return confirm('" . $this->aInt->lang("support", "massdeleteconfirm", "1") . "');\" type=\"submit\" value=\"" . $this->aInt->lang("global", "delete") . "\" name=\"delete\" class=\"btn-small\" />";
        $table = $this->aInt->sortableTable(
                array(
            "checkall",
            "",
            array("title", $this->aInt->lang("fields", "subject")),
            "Client",
            $this->aInt->lang("support", "department"),
            "Tag",
            array("flag", "Assigned To"),
            array("status", $this->aInt->lang("fields", "status")),
            "Last Replier",
            array("lastreply", $this->aInt->lang("support", "lastreply"))
                ), $tabledata, $tableformurl, $tableformbuttons, true
        );

        return $table;
    }

}

?>