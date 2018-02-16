<?php

class RADIUS {
    protected $db;
    protected $dbradius;
    protected $dbpmacct;
    protected $accounts = array();

    public $radiuspids = array(517,530,563,595,600,602,605,611,682,685,688,729,748,749,751,752,753,754,756,757,758,797,799,818,819,820);
    const DATE_FORMAT = "Y-m-d";
    const TIME_FORMAT = "H:i:s";
    const DATE_TIME_FORMAT = "Y-m-d H:i:s";

    // Bare Constructor - no initialisation happena, just connect to databases
    public function __construct() {
//    @$this->db = new mysqli("localhost", "my", "cyem2wribInidodHinshortaidciobno", "my");
//        if ($this->db->connect_errno) {echo "Problem connecting to local database.\r\n";exit;}
    @$this->dbradius = new mysqli("rad1.comms.hd.net.nz", "ispmsradius", "vedtyinthowdoigAwlumwugnanledjim", "radius", 3306);
        if ($this->dbradius->connect_errno) {echo "Problem connecting to user database.\r\n";exit;}
//    @$this->dbpmacct = new mysqli("mon.comms.hd.net.nz", "ispmspmacct", "PianJimidcimcunlezedcetCufyighNo", "pmacct");
    @$this->dbpmacct = new mysqli("10.2.2.14", "ispmspmacct", "PianJimidcimcunlezedcetCufyighNo", "pmacct");
        if ($this->dbpmacct->connect_errno) {echo "Problem connecting to accounting database.\r\n";exit;}
    }

    // return array of accounts
    // accounts[$id] = array('id'=>, 'username'=>, etc)
    public function getaccounts() {
        return $this->accounts;
    }

    // given the output of getclientsproducts() API call, populate $accounts with details
    // quota, overage cost, product type etc (all cleaned up)
    // don't populate actual usage though
    public function populateaccounts($results) {
        // get client's broadband accounts and quotas
        foreach ($results['products']['product'] as $product) {
            if (($product['status'] == "Active") && in_array($product['pid'],$this->radiuspids)) {
                $accountsarray =  array(
                    "id" => $product['id'],
                    "clientid" => $product['clientid'],
                    "username" => strtolower($product['username']),
                    "ip" => $product['dedicatedip'],
                    "address" => $product['domain'],
                    "service_type" => $product['name'],
                    "quota_unit" => "GB",
                    "registered" => $product['regdate'],
                    "next_due_date" => $product['nextduedate'],
                    "billingcycle" => $product['billingcycle'],
                    "paymentmethod" => $product['paymentmethod'],
                    "service_type" => $product['name']
                );

                $accountsarray["routes"] = $this->get_networks(strtolower($product['username']));


                // quota & overage rates
                foreach ($product['configoptions']['configoption'] as $addon) {
                    if  (preg_match("/International Data/",$addon['option'])) {
                        if (preg_match("/^(\d+) ?GB/i", $addon['value'], $matches)) {
                            $accountsarray['quota_limit'] = $matches[1]; 
                        } elseif ($addon['value'] == "Casual Rate (79c per GB)") {
                            $accountsarray['quota_limit'] = 0;
                            $accountsarray['overage_rate'] = 0.79;
                        } elseif ($addon['value'] == "Casual Rate (89c per GB)") {
                            $accountsarray['quota_limit'] = 0;
                            $accountsarray['overage_rate'] = 0.89;
                        } elseif ($addon['value'] == 'Account Policy' || $addon['value'] == 'Account CIR or PIR Policy') {
                            $accountsarray['quota_limit'] = -1;
                        } elseif ($addon['value'] == "$0.29 Per GB International Data") {
                            $accountsarray['quota_limit'] = 0;
                            $accountsarray['overage_rate'] = 0.29;
                        } elseif (preg_match("/Fixed (\d+)GB International Pack/",$addon['value'],$matches)) {
                            $accountsarray['quota_limit'] = $matches[1];
                            $accountsarray['overage_rate'] = 0.29;
                        } elseif (preg_match("/Unlimited International Data/",$addon['value'])) {
                            $accountsarray['quota_limit'] = -1;
                        }
                    }
                    if (preg_match("/Overage Rate Per GB/",$addon['option'])) {
                        list($dummy1, $accountsarray['overage_rate']) = explode('$', $addon['value']);
                    } elseif (in_array(intval($product['pid']),array(611,797))) {
                        $accountsarray['overage_rate'] = 0.79;
                    } elseif (!isset($accountsarray['overage_rate'])) {
                        $accountsarray['overage_rate'] = 0.79;
                    }
                }
                if (preg_match('/Unlimited/',$product['name'])) {
                    $accountsarray['quota_limit']=-1;
                }
                if ($product['clientid'] == 14362) { //unlimitedinternet
                    $accountsarray['quota_limit'] = -1;
                }
                if ($accountsarray['quota_limit'] == -1) {
                    $accountsarray['overage_rate'] = 0;
                }

                $this->accounts[$product['id']] = $accountsarray;
            }
        } 
    }

    // populate $this->accounts[$productid] with individual connection details
    // done on a per-client basis
    // takes getclientsproducts array( as ) argument 
    public function services_addclient($clientid) {
        $results = localAPI("getclientsproducts",array('clientid'=>$clientid), "apihostingdirect");
        $this->populateaccounts($results);
    } 

    public function services_addservice($serviceid) {
        $results = localAPI("getclientsproducts",array('serviceid'=>$serviceid), "apihostingdirect");
        $this->populateaccounts($results);
    }

    public function services_addpid($pid) {
        $results = localAPI("getclientsproducts",array('pid'=>$pid), "apihostingdirect");
        $this->populateaccounts($results);
    }


    public function get_service_attribute($identifier, $attribute) {
        return $this->accounts[$identifier][$attribute];
    }

    /**
     * get_networks
     *
     * Takes a username and returns an array of IPv4 addresses (as strings)
     * guy@hd.net.nz 2015-12-08
     * 
     * @param (string) username string representation of RADIUS username
     * @return (array) networks array of strings containing IPv4 addresses routed to username
     * 
     */
    public function get_networks($username) {
    // takes a username and returns an array of single IPv4 Addresses
        $routes = array();
        $stmt = $this->dbradius->prepare("SELECT value FROM `radreply` WHERE `username` = ? AND `attribute` = 'Framed-Route'");
        $stmt->bind_param("s",$username);
        $stmt->bind_result($radreplyvalue);
        $stmt->execute();
        while ($stmt->fetch()) {
            $routes[] = explode(" ", $radreplyvalue)[0];
        }
        $networks = array();
        foreach ($routes as $route) {
            $networks[$route] = $this->cidr_to_ip_set($route);
        }
        return $networks;
    }

   /** 
     * cidr_to_ip_set
     * Takes a CIDR-format IPv4 string (X.X.X.X/YY) and returns
     * an array of addresses contained 
     */
    public function cidr_to_ip_set($network) {
        $addresses = array();
        @list($ip, $cidr) = explode('/', $network);
        if (($min = ip2long($ip)) !== false) {
            $max = ($min | (1 << (32 - $cidr)) - 1);
            for ($i = $min; $i <= $max; $i++)
                $addresses[] = long2ip($i);
        }
        return $addresses;
    }



    // return up to 14 start and end dates for specified product ID, based on nextduedate
    // Earliest first, with the last period being the period including today
    // periods are inclusive of all days (and should theoretically match with invoice period)
    public function get_periods($identifier) {
        $account = &$this->accounts[$identifier];
        $service_start_date = new DateTime($account['registered']);
        $next_due_date = $account['next_due_date'];
        $next_due_day = explode('-', $next_due_date);
        $next_due_day = $next_due_day[2];

        if ($service_start_date->format('d') > $next_due_day) {
            $service_start_date->modify('+1 month');
        }

        $start = new DateTime($service_start_date->format('Y-m-' . $next_due_day));

        $end = new DateTime();
        //        $interval = DateInterval::createFromDateString('1 month');
        $interval = new DateInterval('P1M');
        $dtperiod = new DatePeriod($start, $interval, $end);
        foreach ($dtperiod as $date) {
            $d1 = clone $date;
            $d1->add($interval);
            $d1->modify('-1 second');
            $billing_dates[] = array('start' => $date , 'end' => $d1);
        }
    
        $billing_periods = array_slice($billing_dates,-14,14);

        return $billing_periods;
    }
    // return array of (start,end) DateTimes for serviceid $identifier
    // mod = 0 is current period
    // 1 is next most recent 
    public function get_period($identifier, $modifier = 0) {
        $periods = $this->get_periods($identifier);
        if (abs($modifier) >= count($periods)) {
            return null;
        } else {
            return array_reverse($periods)[$modifier];
        }
    }


    // takes product id, 'inbound' or 'ourbound', datetime start, datetime end
    // start up to but not including very end 
    // (ie 2015-12-12 through 2015-12-15 will include all hours on the 12th through 14th)
    // aggregate: none (full records - currently half hour)
    //            hour (2015-XX-XX 13:00:00 goes through to 13:29:59)
    //            day  (2015-XX-XX 00:00:00)
    //            full (starttime through to just before endtime
    //
    //            period is array(start=>DateTime,end=>DateTime)
    public function get_usage($identifier,$period, $aggregate="hour") {
        $real_usage = array();
        // Don't even look at usage before the product's first registration date or in the future
        $startstamp = max($period['start']->getTimestamp(),strtotime($this->accounts[$identifier]['registered']));
        $stopstamp = min($period['end']->getTimestamp(), time());

        // alter query directly based on aggregation period
        switch($aggregate) {
            case "none": $dateselect = $dategroup = ", FROM_UNIXTIME(STAMP_INSERTED) ";break;
            case "hour": $dateselect = $dategroup = ", DATE_FORMAT(FROM_UNIXTIME(STAMP_INSERTED),'%Y-%m-%d %H:00:00') ";break;
            case "day":  $dateselect = $dategroup = ", DATE_FORMAT(FROM_UNIXTIME(STAMP_INSERTED),'%Y-%m-%d 00:00:00') ";break;
            case "full": 
            default:     $dateselect = ", FROM_UNIXTIME(MIN(STAMP_INSERTED)) ";$dategroup = "";break;
            }
        // Collate IP addresses 
        $ips = array();
        foreach ($this->accounts[$identifier]['routes'] as $route) { $ips += $route; }
        $ips[] = $this->accounts[$identifier]['ip'];
        // actually run the query against pmacct - ignore the mess, this is already optimised
        $sql = "
            (SELECT agent_id ".$dateselect." AS timestamp, SUM(bytes), 'inbound' AS direction FROM inbound_sflow_partition
            WHERE
                (stamp_inserted NOT BETWEEN 1504094400 AND 1508151600) AND
                (stamp_inserted BETWEEN ? AND ? -1) AND 
                ip_dst IS NOT NULL AND
                (   0";
                    foreach ($ips as $ip) {
                        $sql .= sprintf(" OR ip_dst= INET6_ATON(\"%s\") ",$ip);
                        //$sql .= sprintf(" OR INET6_NTOA(ip_dst) LIKE \"%s\" ",$ip);
                    }
        $sql .= "
                )                
            GROUP BY 
                agent_id
                ".$dategroup."
                    ) UNION (
                    SELECT agent_id ".$dateselect." AS timestamp, SUM(bytes), 'outbound' AS direction FROM outbound_sflow_partition
            WHERE
                (stamp_inserted NOT BETWEEN 1504094400 AND 1508151600) AND
                (stamp_inserted BETWEEN ? AND ? -1) AND
                (   0";
                    foreach ($ips as $ip) {
                        $sql .= sprintf(" OR ip_src= INET6_ATON(\"%s\") ",$ip);
                        //$sql .= sprintf(" OR INET6_NTOA(ip_src) LIKE \"%s\" ",$ip);
                    }
        $sql .= " )
            GROUP BY
                agent_id
                ".$dategroup."
            )
            ORDER BY timestamp ASC
";
//        mail("guy@hd.net.nz","sql",$sql);
        $stmt = $this->dbpmacct->prepare($sql);
        $stmt->bind_param("dddd",
            $startstamp,$stopstamp,$startstamp,$stopstamp
        );
        $stmt->execute();
        $stmt->bind_result($agentid,$datetime,$bytes,$direction);
        
        while ($data = $stmt->fetch()) {
            $day = date(self::DATE_FORMAT, strtotime($datetime));
            $hour = date(self::TIME_FORMAT, strtotime($datetime));

            // populate real_usage appropriately
            switch($agentid) {
            case 20:
                $dataclass = 'charged_in';break;
            case 21:
                $dataclass = 'charged_out';break;
            case 14:case 16:case 18:
                $dataclass = 'uncharged_in';break;
            case 15:case 17:case 19:
                $dataclass = 'uncharged_out';break;
            default:
                $dataclass = 'unknown';
            }

            if (isset($real_usage[$dataclass])) { 
                $real_usage[$dataclass] += $bytes; 
            } else {
                $real_usage[$dataclass] = $bytes;
            }


                if (isset($real_usage[$day][$dataclass])) { 
                    $real_usage[$day][$dataclass] += $bytes; 
                } else {
                    $real_usage[$day][$dataclass] = $bytes;
                }

                if (isset($real_usage[$day][$hour][$dataclass])) { 
                    $real_usage[$day][$hour][$dataclass] += $bytes; 
                } else {
                    $real_usage[$day][$hour][$dataclass] = $bytes;
            }

        }
        $real_usage['charged_total'] = 0;
        if (isset($real_usage['charged_in'])) { $real_usage['charged_total'] += $real_usage['charged_in']; }
        if (isset($real_usage['charged_out'])) { $real_usage['charged_total'] += $real_usage['charged_out']; }
        // return populated array with usage for period in question
        //


        return $real_usage;
    }


    // when the time comes to generate an invoice for the next period (28 days beforehand)
    // also generate an invoice for the period which has already completed
    // ie:
    //   next due date is 29/03/2016 (but could also be 29/04 or 29/05, depending on billing muckups)
    //   invoice is being generated on 01/03/2016 for 29/03->28/04
    //   at this time, we need to add on the usage from 29/01->28/02
    //   end date will be the day before the previous $dayno to occur
    //   start date will be for the month proceeding
    public function get_last_invoice_data($serviceid) {
        // assume that account has been loaded
        $period = $this->get_period($serviceid,1);

        $invoicedataarray = array(
            'client_id' => $this->accounts[$serviceid]['clientid'],
            'service_id' => $serviceid,
            'address'   => $this->accounts[$serviceid]['address'],
            'quota_limit'  => $this->accounts[$serviceid]['quota_limit'],
            'invoice_id' => 0,
            'upload' => 0,
            'download' => 0,
            'quota_usage' => 0,
            'quota_excess' => 0,
            'excess_rate' => 0,
            'bill_amount' => 0,
            'invoice_id' => 0,
            'service_type' => $this->accounts[$serviceid]['service_type'],
            'paymentmethod' => $this->accounts[$serviceid]['paymentmethod']
        );

        if (is_null($period)) {
            $invoicedataarray['period_start'] = "&mdash;";
            $invoicedataarray['period_end'] = "&mdash;";
            return $invoicedataarray;
        } else {
            $invoicedataarray['period_start'] = $period['start']->format('Y-m-d');
            $invoicedataarray['period_end']   = $period['end']->format('Y-m-d');
        }

            // check if this has already been invoiced for
            $sql = "SELECT download,upload,invoiceid  FROM mod_hdradius_invoiced
                    WHERE period_start = ? AND period_end = ? AND serviceid = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssi",
                $invoicedataarray['period_start'],
                $invoicedataarray['period_end'],
                $serviceid
            );
            $stmt->execute();
            $stmt->bind_result($sqldownload,$sqlupload,$sqlinvoiceid);
            while ($row = $stmt->fetch()) {
                $invoicedataarray['invoice_id'] = $sqlinvoiceid;
                $invoicedataarray['download'] = $sqldownload;
                $invoicedataarray['upload'] = $sqlupload;
            }

        // use cached data if possible
        if ($this->accounts[$serviceid]['quota_limit'] >= 0) {
            if  ($sqlupload > 1 || $sqldownload > 1) {
//                echo "Using Cached Data";
                $invoicedataarray['quota_usage'] = $sqldownload+$sqlupload;
                $invoicedataarray['invoice_id'] = $sqlinvoiceid;
            } else {
//                echo "Using Realtime Data\n";
                $usage = $this->get_usage($serviceid,$period,"full");
                $invoicedataarray['quota_usage'] = ($usage['charged_total'])/1024/1024/1024; // GB
                $invoicedataarray['upload'] = ($usage['charged_out'])/1024/1024/1024; // GB
                $invoicedataarray['download'] = ($usage['charged_in'])/1024/1024/1024; // GB
            }
            $invoicedataarray['quota_excess'] = max($invoicedataarray['quota_usage'] - $invoicedataarray['quota_limit'],0);
            $invoicedataarray['excess_rate'] = $this->accounts[$serviceid]['overage_rate'];
            $invoicedataarray['bill_amount'] = $invoicedataarray['excess_rate'] * $invoicedataarray['quota_excess'];
        }

        return $invoicedataarray;
            
    }

    // given service id, YYYY-MM-DD start, $YYYY-MM-DD end, check if has already been invoiced
    public function check_if_invoiced($serviceid,$start,$end) {
        echo "one";
        $stmt = $this->db->prepare("SELECT serviceid FROM mod_hdradius_invoiced WHERE serviceid = ? and start = ? and end = ?");
        $this->bind_param("iss",$serviceid,$start,$end);
        echo "two";
        $this->execute();
        while ($stmt->fetch()) {
            return true;
        }
        return false;
    }

        
     
    public function log_invoiceitem($itemarray) {
        // $serviceid,$period_start,$period_end,$download,$upload,$invoiceid) {
        $stmt = $this->db->prepare("INSERT INTO mod_hdradius_invoiced (serviceid,period_start,period_end,download,upload,invoiceid)  VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("issddi",$itemarray[0],$itemarray[1],$itemarray[2],$itemarray[3],$itemarray[4],$itemarray[5]);
        $stmt->execute();
    }

    

}

?>
