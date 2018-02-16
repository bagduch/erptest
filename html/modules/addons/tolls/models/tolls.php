<?php

class tolls {

    const OUTBOUND_CALLS = "outbound-calls";
    const INBOUND_CALLS = "inbound-calls";
    const ZONE_TOLL_FREE = "toll-free";
    const ZONE_INTERNATIONAL = "international";

    public $db;
    public $db_tolls;
    private $client_specifics = array();
    private $zone_defaults;
    public $data;
    public static $colorarray = array(
        "#c40434", "#63caa6", "#bfe6d0", "#bd6589", "#c0ceb4", "#f6a889", "#16d185", "#3ac8db", "#0559a7"
    );

    /**
     * Instantiate the class by connecting to the two databases and updating the local one with the delta.
     * 
     * @return null
     */
    function __construct($accountcode = null, $period = null) {

        require ROOTDIR . "/configuration.php";

        $this->db = new mysqli($db_host, $db_username, $db_password, $db_name);
        if ($this->db->connect_errno) {
            echo "Problem connecting to local DB.";
            exit;
        }

        $this->data = $this->get_client_data($accountcode, $period);
    }

    /**
     * Returns the requested setting key from a local variable cached from the database on instantiation.
     * 
     * @return bool|string|integer
     */
    function get_all_categroy_sum($accountcode = null, $period) {
        $where = "";
        if ($accountcode != null) {
            $where = " AND accountcode =" . $accountcode;
        }
        $query = "select category,sum(bill_amount) as bill_sum,count(id) as count from hb_mod_tolls where start like '" . $period . "%' " . $where . " group by category ";
        $result = $this->db->query($query);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row['category']] = $row;
        }
        return $data;
    }

    function getOutBoundcallsIds($accountcode = null, $period) {
        $where = "";
        if ($accountcode != null) {
            $where = " AND accountcode =" . $accountcode;
        }
        $query = "select distinct(accountcode) from hb_mod_tolls where dcontext ='outbound-calls' and start like '" . $period . "%' " . $where . " And accountcode != '' order by accountcode";
      
        $result = $this->db->query($query);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['accountcode'];
        }
        return $data;
    }

    function getDetails($accountcode, $period, $cate) {
        $query = "select * from hb_mod_tolls where accountcode = " . $accountcode . " and dcontext ='outbound-calls' and start like '" . $period . "%' and category like '" . $cate . "'";

        $result = $this->db->query($query);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    function getCategroies($accountcode, $period) {
        $query = "select category from hb_mod_tolls where accountcode = " . $accountcode . " and dcontext ='outbound-calls' and start like '" . $period . "%' group by category";
        $result = $this->db->query($query);
        echo $query;
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['category'];
        }
        return $data;
    }

    function search_all($search) {

        if (is_numeric($search)) {
            $query = "select * from tblcustomfieldsvalues where value like '%" . $search . "%'";
        } else {
            $query = "select tc.firstname,tc.lastname,tcs.id from tblclients as tc INNER JOIN tblcustomerservices as tcs on tc.id = tcs.userid INNER JOIN tblservices as ts on (tcs.packageid=ts.id and ts.name like '%Toll%') where tc.firstname like '%" . $search . "%' or tc.lastname like '%" . $search . "%'";
        }
        $result = $this->db->query($query);
        $data = "<div class=\"list-group\">";
        while ($row = $result->fetch_assoc()) {
            if (isset($row['firstname'])) {

                $data .= "<button data-value=" . $row['id'] . " type=\"button\" class=\"list-group-item list-group-item-action\">" . $row['firstname'] . " " . $row['lastname'] . "</button>";
            } else {
                $data .= "<button data-value=" . $row['relid'] . " type=\"button\" class=\"list-group-item list-group-item-action\">" . $row["value"] . "</button>";
            }
        }
        $data .= "</div>";
        return $data;
    }

    function get_categroy_sum_json_data($period) {
        $i = 0;
        $date['sumbill'] = 0;
        foreach ($categories_sum as $key => $row) {

            $value = array();
            if ($key == "") {
                $key = "Unrecognise";
            }
            $data['category'][] = $key;
            $total = sizeof($categories_sum);
            $value = array_pad($value, $total, 0);
            $value[$i] = $row['bill_sum'];
            $data['categraphic'][] = array(
                "label" => $key,
                "backgroundColor" => $this->colorarray[$i],
                "borderColor" => $this->colorarray[$i],
                "borderWidth" => 1,
                "data" => $value
            );
            $data['sumbill'] += $row['bill_sum'];
            $i++;
        }
        return $data;
    }

    function get_client_data($accountcode = null, $period = null) {


        $where = "";
        if ($period != null) {
            $where = " where start like '" . $period . "%'";
        } else {
            $where = " where start like '%" . date("Y-m") . "' ";
        }
        if ($accountcode != null) {
            $where .= " AND accountcode=" . $accountcode;
        } else {
            $where .= "";
        }

        $query = "select * from hb_mod_tolls" . $where;
        $result = $this->db->query($query);
        if (!$result) {
            throw new Exception("Database Error [{$this->db->errno}] {$this->db->error}");
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row['dcontext']][$row['accountcode']][] = $row;
        }
        return $data;
    }

    /**
     * Returns an array with the type of zone (e.g. International), subzone (e.g. Australia) and rate (e.g. 0.12)
     * corresponding to the input number given, from the cached $this->zone_defaults instance variable.
     * 
     * @param string $src source phone number (SIP DDI) to match local calls unnecessarily prefixed with an area code
     * @param string $dst destination phone number to get billing information for
     * @return null
     */
    function get_call_classification($src, $dst, $is_outbound) {

        // set up constants for caller and callee termination points
        if ($is_outbound) {
            $owner = $src;
            $number = $dst;
        } else {
            $owner = $dst;
            $number = $src;
        }

        // if the callee number falls under the national zone
        if (preg_match('/^(0[34679])[0-9]+$/', $number, $matches))
        // but the area code prefix is the same as that of the caller
            if (substr($owner, 0, 2) == $matches[1])
            // then the destination number binds more tightly
            // to the local zone, so reclassify it as such
                return array(
                    "owner" => $owner,
                    "number" => $number,
                    "zone" => 'local',
                    "subzone" => 'default',
                );

        // if the customer is calling somebody
        // base the billing off the destination number
        // otherwise if somebody is calling the customer instead
        // base the billing off the source number
        foreach ($this->zone_defaults as $zone => $subzones)
            foreach ($subzones as $subzone => $prefixes)
                foreach ($prefixes as $regex => $rate)
                    if (preg_match('/' . $regex . '/', $number))
                        return array(
                            "owner" => $owner,
                            "number" => $number,
                            "zone" => $zone,
                            "subzone" => $subzone,
                        );
    }

    /**
     * Proxy method that takes a call array containing information from the remote asterisk server's call table
     * and creates new keys onto that same array with specific billing information for that specific call.
     * 
     * @param array $call array containing the asterisk row from the remote database, with information such as call time, duration, etc.
     * @return array $call array containing the asterisk row with added fields
     */
    function populate_call_specifics($call) {
        // if the cdr database says the context is outbound, then so do we
        $call['is_outbound'] = ($call['dcontext'] == self::OUTBOUND_CALLS) ? true : false;

        // initialise array indexes used conditionally to avoid errors
        $call['forward_category'] = null;
        $call['forward_subcategory'] = null;

        // identify forwarded calls
        if ($call['pai'] && $call['ucf']) {
            $call['is_forwarded'] = true;
            $call['forward_from'] = $call['pai'];
            $call['forward_to'] = $call['ucf'];
        } else {
            $call['is_forwarded'] = false;
        }

        // get details about the call: who is the owner (src or dst) based on whether or not the call is outbound or not?
        $call_bill = $this->get_call_classification($call['src'], $call['dst'], $call['is_outbound']);

        // now that we know the owner, we can log their internal product purchase number in order to know who and which one of their (possible several) services to invoice
        $result = $this->db->query("SELECT `id` FROM `tblhosting` WHERE `domain` = '" . $this->db->real_escape_string($call_bill['owner']) . "' AND `domainstatus` = 'Active'");
        list($call['accountcode']) = $result->fetch_row();

        // the noi (number of interest) is the opposite of the owner
        // if the owner is the src number (outbound call), then the noi is dst
        // if the owner is the dst number (inbound call), then the noi is src
        $call['noi'] = $call_bill['number'];

        // set billing categories based on the call classification
        $call['category'] = $call_bill['zone'];
        $call['subcategory'] = $call_bill['subzone'];
        $call['month'] = substr($call['start'], 0, 7);

        // keep a log of the original call duration
        $call['sec'] = $call['billsec'];

        // round the billable seconds to whole minutes (but keep the result in seconds)
        $call['billsec'] = (ceil($call['billsec'] / 60) * 60);

        // ensure forwarded calls are charged for according to the destination category's price classification
        if ($call['is_forwarded']) {
            // the third parameter in the following call of get_call_classification() implies 'is_outbound = true' for this specific case, but is actually irrelevant because
            // the third parameter is only used to classify which of the first two parameters are the owner and destination numbers respectively
            // and we had already identified this in the previous call to get_call_classification() above
            $call_forward_bill = $this->get_call_classification($call['forward_from'], $call['forward_to'], true); // third parameter implies 'is_outbound = true' for this specific case
            // override null values set above because now we know we have a forwarded call
            $call['forward_category'] = $call_forward_bill['zone'];
            $call['forward_subcategory'] = $call_forward_bill['subzone'];

            // valuse set to 'true' below override the is_outbound key because forwarded calls were originally inbound, but became outbound because they were forwarded
            // without this the forwarded call will be seen as incoming and will thus not be charged for, so ensure that billing-related functions believe the call is outbound even though it
            // originally was not
            $call['used_included_secs'] = $this->get_client_zone_used_included_time($call['accountcode'], $call['month'], $call['forward_category'], true, $call['billsec']);
            $call['billsec'] -= $call['used_included_secs'];
            $call['bill_rate'] = number_format($this->get_client_zone_rate($call['accountcode'], $call['forward_category'], $call['forward_subcategory'], $call['forward_to'], true), 3);
            $call['bill_amount'] = number_format(floor($call['billsec'] / 60) * $call['bill_rate'], 2);
        } else {
            // bill for the call as per usual
            // the minute rate is exposed by get_client_zone_rate() and will default to 0.00 for inbound calls
            // if billing is required for inbound calls which are NOT forwarded calls, then this would be the place to add a conditional
            $call['used_included_secs'] = $this->get_client_zone_used_included_time($call['accountcode'], $call['month'], $call['category'], $call['is_outbound'], $call['billsec']);
            $call['billsec'] -= $call['used_included_secs'];
            $call['bill_rate'] = number_format($this->get_client_zone_rate($call['accountcode'], $call['category'], $call['subcategory'], $call['dst'], $call['is_outbound']), 3);
            $call['bill_amount'] = number_format(floor($call['billsec'] / 60) * $call['bill_rate'], 2);
        }

        return $call;
    }

    /**
     * This method populates instance variables with specific client rates and free minute values if they
     * have been specified by the administrator, and also all default rates for other methods to fall back
     * to if there are no specific client rates.
     * 
     * @return null
     */
    function populate_client_specifics() {
        // the mod_tolls_rates table contains the generic local, national, mobile and international rates
        // used for all customers that don't have specific rates set.
        // it populates the $this->zone_defaults instance variable.
        $result = $this->db->query("
			SELECT *
			FROM `mod_tolls_rates`
			ORDER BY `id`
		");
        while ($row = $result->fetch_assoc()) {
            $this->zone_defaults[$row['category']][$row['subcategory']][$row['prefix']] = $row['rate'];
        }

        // the mod_tolls_options table contains specific rates and free minutes chosen by the tolls adminstrator.
        // it populates the $this->client_specifics instance variable.
        $result = $this->db->query("
			SELECT *
			FROM `mod_tolls_options`
		");
        while ($row = $result->fetch_assoc())
            $this->client_specifics[$row['hostingid']][$row['zone']][$row['subzone']] = $row;
    }

    /**
     * Given the hosting ID, zone and subzone, this method will retrieve the rate at which the call should be charged.
     * 
     * @param integer $hid the hosting ID (e.g. 15035)
     * @param string $zone the zone (e.g. international)
     * @param string subzone (e.g. australia)
     * @return double the dollar amount to charge per minute of calling (e.g. 0.05)
     */
    function get_client_zone_rate($hid, $zone, $subzone, $dst, $is_outbound) {
        if ($is_outbound) {
            if ($zone != self::ZONE_INTERNATIONAL) {
                // outbound non-international rates have no markup
                if (@$this->client_specifics[$hid][$zone][$subzone]['rate'])
                    return round($this->client_specifics[$hid][$zone][$subzone]['rate'], 3);
                else {
                    $ret = array_values($this->zone_defaults[$zone][$subzone]);
                    return round((double) $ret[0], 3);
                }
            } else {
                // outbound international rates have 15% additional markup
                if (@$this->client_specifics[$hid][$zone]['default']['rate'])
                    return round($this->client_specifics[$hid][$zone]['default']['rate'] * 1.15, 3);
                else {
                    $ret = array_values($this->zone_defaults[$zone][$subzone]);
                    return round((double) $ret[0] * 1.15, 3);
                }
            }
        } else {
            if (!$is_outbound && preg_match('/^(0800|0508)/', $dst)) {
                // inbound toll calls cost 20% extra
                $ret = array_values($this->zone_defaults[$zone][$subzone]);
                return round((double) $ret[0] * 1.20, 3);
            } elseif (!$is_outbound)
            // other inbound calls are free
                return 0.00;
        }
    }

    /**
     * This method returns the amount of time used in a zone, which was granted for free by the administrator.
     * 
     * @param integer $hid the hosting ID in question
     * @param string $month the month in question, format %Y-%m
     * @param string $zone the zone in question, e.g. local
     * @param integer $used_seconds the amount of seconds used for this particular call
     * @return integer the amount of free talktime used
     */
    function get_client_zone_used_included_time($hid, $month, $zone, $is_outbound, $used_seconds) {
        // the account has no client-specific billing; the amount of used free talktime is 0
        // also, ignore inbound talktime
        if (!@$this->client_specifics[$hid][$zone] || !$is_outbound)
            return 0;

        // this might be the beginning of the month, so the freesecs 'month' field may not yet be populated
        // let's populate it so it can be manipulated later on
        if (!isset($this->client_specifics[$hid][$zone]['default']['freesecs'][$month]))
            $this->client_specifics[$hid][$zone]['default']['freesecs'][$month] = $this->client_specifics[$hid][$zone]['default']['freemins'] * 60;

        // if the client has 34 free seconds remaining and the call duration was 13 seconds, then subtract 13 and return that
        if (($this->client_specifics[$hid][$zone]['default']['freesecs'][$month] - $used_seconds) >= 0) {
            $this->client_specifics[$hid][$zone]['default']['freesecs'][$month] -= $used_seconds;
            return $used_seconds;
        } else {
            // otherwise, the call was greater than 34 seconds long and has no free talk time left, save that value, zero it, and return it
            // in the case of this example the return value (used included talktime) would be 34
            $freesecs = $this->client_specifics[$hid][$zone]['default']['freesecs'][$month];
            $this->client_specifics[$hid][$zone]['default']['freesecs'][$month] = 0;
            return $freesecs;
        }
    }

    /**
     * This method returns how long ago, in human readable format, a date and time occured
     * 
     * @param string $date any common date format (e.g. '10 September 1992')
     * @param integer $granularity the more granularity the less accurate the output (e.g. 1 month ago instead of 3 weeks ago)
     * @return string the date in human readable format
     */
    function time_ago($date, $granularity = 1) {
        $date = strtotime($date);
        $difference = time() - $date;
        $periods = array(
            'decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1
        );
        $retval = "";

        foreach ($periods as $key => $value) {
            if ($difference >= $value) {
                $time = floor($difference / $value);
                $difference %= $value;
                $retval .= ($retval ? ' ' : '') . $time . ' ';
                $retval .= (($time > 1) ? $key . 's' : $key);
                $granularity--;
            }
            if ($granularity == '0')
                break;
        }

        return $retval . ' ago';
    }

    /**
     * This method formats a given phone number in more human-readable form, good for billing output, etc.
     * 
     * @param string $number the phone number, e.g. 92804135
     * @return string the number in more human-readable form, e.g. (09) 280-4135
     */
    function format_phone($number) {
        if (preg_match('/^(0?[3-9])?([0-9]{3})([0-9]{4})$/', $number, $matches))
            return "(" . (($matches[1]) ? $matches[1] : "&mdash;") . ") " . $matches[2] . "-" . $matches[3];
        elseif (preg_match('/^(02[1-9])([0-9]{3})([0-9]+)$/', $number, $matches))
            return $matches[1] . "-" . $matches[2] . "-" . $matches[3];
        elseif (preg_match('/^([^0]{2})([1-9]+)([0-9]{3})([0-9]{4,})$/', $number, $matches))
            return "+" . $matches[1] . "-" . $matches[2] . "-" . $matches[3] . "-" . $matches[4];
        elseif (preg_match('/^(0800|0508)([0-9]{3})([0-9]+)$/', $number, $matches))
            return $matches[1] . "-" . $matches[2] . "-" . $matches[3];
        elseif (preg_match('/unknown/', $number))
            return "Withheld";
        else
            return $number;
    }

    /**
     * This method takes seconds as an input and outputs the amount of hours, minutes and seconds it equates to
     * 
     * @param integer $seconds the number of seconds to convert
     * @return string the converted output
     */
    function format_sec2min($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;

        return $hours . "h " . $minutes . "m " . $seconds . "s";
    }

    /**
     * This method logs invoices that are created so they can be presented individually to the end-user.
     * 
     * @param integer $client_id the client's ID
     * @param integer $hosting_id the hosting ID of the account
     * @param string $period the billing period in %Y-%m format
     * @param integer $amount the billing amount to be paid
     * @param integer $invoice_id the ID of the generated invoice
     * @return null
     */
    function log_invoice($client_id, $hosting_id, $period, $amount, $invoice_id) {
        if (@$client_id && @$hosting_id && @$period)
            $this->db->query("
				INSERT INTO `mod_tolls_billing2`
				
					VALUES (
						null, '" . $client_id . "', '" . $hosting_id . "',
						'" . $this->db->real_escape_string($period) . "',
						'" . $this->db->real_escape_string($amount) . "',
						" . (is_null($invoice_id) ? 'null' : "'" . $this->db->real_escape_string($invoice_id) . "'") . "
					)
			");
        else
            trigger_error("cannot log invoice. one or more critical parameters are empty", E_USER_ERROR);
    }

    /**
     * This method creates a WHMCS invoice for a given hosting ID and billing period
     * 
     * @param integer $hostingid the hosting ID to invoice
     * @param string $period the period to invoice in %Y-%m format
     */
    function create_invoice($hostingid, $period) {

        // get client information
        $result = $this->db->query("
			SELECT `tblclients`.`id` AS clientid,
				`tblclients`.`firstname`,
				`tblclients`.`lastname`,
				`tblclients`.`companyname`,
				`tblclients`.`email`,
				`tblhosting`.`id` AS hostingid,
				`tblhosting`.`packageid`,
				`tblhosting`.`domain`,
				`tblhosting`.`username`,
				`tblhosting`.`paymentmethod`,
				`tblproducts`.`name` as product_name				
			
			FROM `tblclients`
			
				INNER JOIN `tblhosting` ON `tblhosting`.`userid` = `tblclients`.`id`
				LEFT JOIN `tblproducts` ON `tblhosting`.`packageid` = `tblproducts`.`id`
			
			WHERE `tblhosting`.`packageid` IN (520, 560, 712, 738)
			
				AND `tblhosting`.`id` = '" . $this->db->real_escape_string($hostingid) . "'
			
			ORDER BY `tblhosting`.`domain`
		");
        $client = $result->fetch_assoc();

        // get domain freebies
        $result = $this->db->query("
			SELECT *
			
			FROM `mod_tolls_options`
		");
        $freebies = array();
        while ($row = $result->fetch_assoc()) {
            $freebies[$row['hostingid']][$row['zone']] = $row;
        }

        // get usage data to bill for
        // MAX(`bill_rate`) because the first row of a category might be incoming,
        // which is usually free and so the rate would show up as 0.00 for that category
        $result = $this->db->query("
			SELECT SUBSTRING(`start`, 1, 7) AS start, `accountcode`,
				IF(`forward_category` IS NULL OR `forward_category` = '', `category`, `forward_category`) AS dependent_category,
				IF(`forward_subcategory` IS NULL OR `forward_subcategory` = '', `subcategory`, `forward_subcategory`) AS dependent_subcategory,
				MAX(`bill_rate`) AS bill_rate,
				SUM(`bill_amount`) AS total_bill, SUM(`billsec`) AS billsec,
				SUM(`used_included_secs`) AS freesec, COUNT(*) as total_calls
		
			FROM `mod_tolls_all_calls`
			
			WHERE `accountcode` = '" . $client['hostingid'] . "'
				AND `disposition` = 'ANSWERED'
				AND `start` LIKE '" . $this->db->real_escape_string($period) . "-%'" . "
			
			GROUP BY SUBSTRING(`start`, 1, 7), `dependent_category`
		");
        $bills = array();
        while ($row = $result->fetch_assoc()) {
            $row['billsec_formatted'] = $this->format_sec2min($row['billsec']);
            $row['freesec_formatted'] = $this->format_sec2min($row['freesec']);
            $bills[$row['dependent_category']] = $row;
        }

        // this is what shows up on the end-user's WHMCS invoice
        $x = array();
        $x[] = "Service plan summary: " . $client['product_name'];
        $x[] = "SIP phone number: " . $client['domain'];
        $x[] = "Billing period: Month of " . date('F Y', strtotime($period));

        // set the starting bill to 0.00
        $total_invoice_bill = 0.00;

        foreach ($bills as $category => $bill) {
            $x[] = "\n" . strtoupper($category);
            if (@$freebies[$hostingid][$category]['freemins']) {
                $freebies[$hostingid][$category]['freesecs'] = $freebies[$hostingid][$category]['freemins'] * 60;
                $x[] = 'With ' . $freebies[$hostingid][$category]['freemins'] . ' free minutes (' . $this->format_sec2min($freebies[$hostingid][$category]['freesecs']) . ' of included time) per month:';
                $x[] = '     used a total of ' . $this->format_sec2min($bill['billsec'] + $bill['freesec']) . ' talktime';
                if (($freebies[$hostingid][$category]['freesecs'] - $bill['billsec'] - $bill['freesec']) < 0)
                    $x[] = '     billed for remainder ' . $bill['billsec_formatted'] . ' @ $' . $bill['bill_rate'] . ' per minute: $' . $bill['total_bill'];
                else
                    $x[] = '     no overage incurred: $0.00';
            }
            else {
                if ($category == "toll-free")
                    $x[] = 'Used ' . $bill['billsec_formatted'] . ' of costless talktime';
                else
                    $x[] = 'Billed for ' . $bill['billsec_formatted'] . (($category != "international") ? ' @ $' . $bill['bill_rate'] . ' per minute' : '') . ': $' . $bill['total_bill'];
            }
            // sum up each individual category bill
            $total_invoice_bill += $bill['total_bill'];
        }

        // do not create an official invoice if this month's usage accounts for 50c or less in charges
        // decided by Ben Simpson on 23/03/2013
        if ($total_invoice_bill < 0.50) {

            // log that the month has been processed but set invoice_id to null
            // without this we wouldn't know if we have dealt with this month before and
            // would end up re-invoicing every time the billing event is run
            $this->log_invoice($client['clientid'], $client['hostingid'], $period, $total_invoice_bill, null);

            // stop here, do not proceed with the rest of the method which deals with
            // official WHMCS client invoicing
            return;
        }

        $postfields["itemdescription"] = implode("\n", $x);

        $postfields["itemamount"] = $total_invoice_bill;
        $postfields["itemtaxed"] = 1;
        $postfields["userid"] = $client['clientid'];
        $postfields["paymentmethod"] = $client['paymentmethod'];

        /*         * ** WHMCS JSON API Sample Code *** */
#		$url = "https://my.hd.net.nz/includes/api.php"; # URL to WHMCS API file goes here
#		$username = 'apihostingdirect'; # Admin username goes here
#		$password = 'fj237tG83F'; # Admin password goes here


        $url = $this->setting('api_url');
        $username = $this->setting('api_username');
        $password = $this->setting('api_password');

        $postfields["username"] = $username;
        $postfields["password"] = md5($password);
        $postfields["responsetype"] = "json";

        $postfields["action"] = "createinvoice";
        $postfields["date"] = date('Ymd');
        $postfields["duedate"] = date('Ymd', time() + (60 * 60 * 24 * 7));
        $postfields["sendinvoice"] = true;

        #echo '<h1>API Post</h1><pre>' . print_r($postfields, true) . '</pre>'; exit;
        #return array('result'	=> 'error', 'message'	=> $message . ' Test');
        #return array('result'	=> 'success', 'message'	=> $message . ' Test');

        $query_string = "";
        foreach ($postfields as $k => $v)
            $query_string .= "$k=" . urlencode($v) . "&";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $jsondata = curl_exec($ch);
        if (curl_error($ch))
            die("Connection Error: " . curl_errno($ch) . ' - ' . curl_error($ch) . ' Err:#' . __LINE__);
        curl_close($ch);

        $arr = json_decode($jsondata, true); # Decode JSON String
        #echo '<pre>' . print_r($arr, true) . '</pre>'; # Output XML Response as Array

        if (isset($arr['result']) && $arr['result'] != "error") {
            $this->log_invoice($client['clientid'], $client['hostingid'], $period, $total_invoice_bill, $arr['invoiceid']);
        }
    }

}

?>
