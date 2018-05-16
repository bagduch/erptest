<?php
/** RA - Version 0.1 **/


class RA_Invoices extends RA_TableModel {
	public function _execute($criteria = null) {
		return $this->getInvoices($criteria);
	}

	public function getInvoices($criteria = array()) {
		global $aInt;
		global $currency;

		$query = " FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid";
		$filters = $this->buildCriteria($criteria);
		$query .= (count($filters) ? " WHERE " . implode(" AND ", $filters) : "");
		$result = full_query_i("SELECT COUNT(*)" . $query);
		$data = mysqli_fetch_array($result);
		$this->getPageObj()->setNumResults($data[0]);
		$gateways = new RA_Gateways();
		$orderby = $this->getPageObj()->getOrderBy();

		if ($orderby == "clientname") {
			$orderby = "firstname " . $this->getPageObj()->getSortDirection() . ",lastname " . $this->getPageObj()->getSortDirection() . ",companyname";
		}


		if ($orderby == "id") {
			$orderby = "ra_bills.invoicenum " . $this->getPageObj()->getSortDirection() . ",ra_bills.id";
		}

		$invoices = array();
		$query = "SELECT ra_bills.*,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_user.groupid,ra_user.currency" . $query . " ORDER BY " . $orderby . " " . $this->getPageObj()->getSortDirection() . " LIMIT " . $this->getQueryLimit();
		$result = full_query_i($query);

		while ($data = mysqli_fetch_array($result)) {
			$id = $data['id'];
			$invoicenum = $data['invoicenum'];
			$userid = $data['userid'];
			$date = $data['date'];
			$duedate = $data['duedate'];
			$subtotal = $data['subtotal'];
			$credit = $data['credit'];
			$total = $data['total'];
			$gateway = $data['paymentmethod'];
			$status = $data['status'];
			$firstname = $data['firstname'];
			$lastname = $data['lastname'];
			$companyname = $data['companyname'];
			$groupid = $data['groupid'];
			$currency = $data['currency'];
			$clientname = $aInt->outputClientLink($userid, $firstname, $lastname, $companyname, $groupid);
			$paymentmethod = $gateways->getDisplayName($gateway);
			$currency = getCurrency("", $currency);
			$totalformatted = formatCurrency($credit + $total);
			$statusformatted = $this->formatStatus($status);
			$date = fromMySQLDate($date);
			$duedate = fromMySQLDate($duedate);

			if (!$invoicenum) {
				$invoicenum = $id;
			}

			$invoices[] = array("id" => $id, "invoicenum" => $invoicenum, "userid" => $userid, "clientname" => $clientname, "date" => $date, "duedate" => $duedate, "subtotal" => $subtotal, "credit" => $credit, "total" => $total, "totalformatted" => $totalformatted, "gateway" => $gateway, "paymentmethod" => $paymentmethod, "status" => $status, "statusformatted" => $statusformatted);
		}

		return $invoices;
	}

	private function buildCriteria($criteria) {
		$filters = array();

		if ($criteria['clientid']) {
			$filters[] = "userid=" . (int)$criteria['clientid'];
		}


		if ($criteria['clientname']) {
			$filters[] = "concat(firstname,' ',lastname) LIKE '%" . db_escape_string($criteria['clientname']) . "%'";
		}


		if ($criteria['invoicenum']) {
			$filters[] = "(ra_bills.id='" . db_escape_string($criteria['invoicenum']) . "' OR ra_bills.invoicenum='" . db_escape_string($criteria['invoicenum']) . "')";
		}


		if ($criteria['lineitem']) {
			$filters[] = "ra_bills.id IN (SELECT invoiceid FROM ra_bill_lineitems WHERE description LIKE '%" . db_escape_string($criteria['lineitem']) . "%')";
		}


		if ($criteria['paymentmethod']) {
			$filters[] = "ra_bills.paymentmethod='" . db_escape_string($criteria['paymentmethod']) . "'";
		}


		if ($criteria['invoicedate']) {
			$filters[] = "ra_bills.date='" . toMySQLDate($criteria['invoicedate']) . "'";
		}


		if ($criteria['duedate']) {
			$filters[] = "ra_bills.duedate='" . toMySQLDate($criteria['duedate']) . "'";
		}


		if ($criteria['datepaid']) {
			$filters[] = "ra_bills.datepaid>='" . toMySQLDate($criteria['datepaid']) . "' AND ra_bills.datepaid<='" . toMySQLDate($criteria['datepaid']) . "235959'";
		}


		if ($criteria['totalfrom']) {
			$filters[] = "ra_bills.total>='" . db_escape_string($criteria['totalfrom']) . "'";
		}


		if ($criteria['totalto']) {
			$filters[] = "ra_bills.total<='" . db_escape_string($criteria['totalto']) . "'";
		}


		if ($criteria['status']) {
			if ($criteria['status'] == "Overdue") {
				$filters[] = "ra_bills.status='Unpaid' AND ra_bills.duedate<'" . date("Ymd") . "'";
			}
			else {
				$filters[] = "ra_bills.status='" . db_escape_string($criteria['status']) . "'";
			}
		}

		return $filters;
	}

    public function formatStatus($status) {
        return sprintf("<span class=\"%s\">%s</span>",$status,$status);
	}

	public function getInvoiceTotals() {
		global $currency;

		$invoicesummary = array();
		$result = full_query_i("SELECT currency,COUNT(ra_bills.id),SUM(total) FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.status='Paid' GROUP BY ra_user.currency");

		while ($data = mysqli_fetch_array($result)) {
			$invoicesummary[$data[0]]['paid'] = $data[2];
		}

		$result = full_query_i("SELECT currency,COUNT(ra_bills.id),SUM(total)-COALESCE(SUM((SELECT SUM(amountin) FROM ra_transactions WHERE ra_transactions.invoiceid=ra_bills.id)),0) FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.status='Unpaid' AND ra_bills.duedate>='" . date("Ymd") . "' GROUP BY ra_user.currency");

		while ($data = mysqli_fetch_array($result)) {
			$invoicesummary[$data[0]]['unpaid'] = $data[2];
		}

		$result = full_query_i("SELECT currency,COUNT(ra_bills.id),SUM(total)-COALESCE(SUM((SELECT SUM(amountin) FROM ra_transactions WHERE ra_transactions.invoiceid=ra_bills.id)),0) FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.status='Unpaid' AND ra_bills.duedate<'" . date("Ymd") . "' GROUP BY ra_user.currency");

		while ($data = mysqli_fetch_array($result)) {
			$invoicesummary[$data[0]]['overdue'] = $data[2];
		}

		$totals = array();
		foreach ($invoicesummary as $currency => $vals) {
			$currency = getCurrency("", $currency);

			if (!isset($vals['paid'])) {
				$vals['paid'] = 0;
			}


			if (!isset($vals['unpaid'])) {
				$vals['unpaid'] = 0;
			}


			if (!isset($vals['overdue'])) {
				$vals['overdue'] = 0;
			}

			$paid = formatCurrency($vals['paid']);
			$unpaid = formatCurrency($vals['unpaid']);
			$overdue = formatCurrency($vals['overdue']);
			$totals[] = array("currencycode" => $currency['code'], "paid" => $paid, "unpaid" => $unpaid, "overdue" => $overdue);
		}

		return $totals;
	}
}

?>
