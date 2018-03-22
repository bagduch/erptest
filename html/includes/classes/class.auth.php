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
class RA_Auth {

    private $inputusername = "";
    private $admindata = array();
    private $logincookie = "";

    public function __construct() {
        return;
    }

    private function getInfo($where) {
        $where['disabled'] = "0";
        $result = select_query_i("tbladmins", "id,username,template,language,authmodule,loginattempts,passwordhash", $where);
        $data = mysqli_fetch_assoc($result);
        $this->admindata = $data;
        return $data['id'] ? true : false;
    }

    public function getInfobyID($adminid) {
        return $this->getInfo(array("id" => $adminid));
    }

    public function getInfobyUsername($username) {
        $this->inputusername = $username;
        return $this->getInfo(array("username" => $username));
    }

    public function comparePassword($password) {

        if (!trim($password) || !isset($this->admindata['passwordhash'])) {
            return false;
        }

        return (password_verify($password, $this->admindata['passwordhash']));
    }

    public function isTwoFactor() {
        return $this->admindata['authmodule'] ? true : false;
    }

    public function getAdminID() {
        return $this->admindata['id'];
    }

    public function getAdminUsername() {
        return $this->admindata['username'];
    }

    public function getAdminPWHash() {
        return $this->admindata['passwordhash'];
    }

    public function getAdminTemplate() {
        return $this->admindata['template'];
    }

    public function getAdminLanguage() {
        return $this->admindata['language'];
    }

    public function getAdmin2FAModule() {
        return $this->admindata['authmodule'];
    }

    private function getAdminUserAgent() {
        return array_key_exists("HTTP_USER_AGENT", $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : "";
    }

    public function generateAdminSessionHash($raclass = false) {
        global $ra;

        if ($raclass) {
            $haship = ($raclass->get_config("DisableSessionIPCheck") ? "" : $raclass->get_user_ip());
            $cchash = $raclass->get_hash();
        } else {
            $haship = ($ra->get_config("DisableSessionIPCheck") ? "" : $ra->get_user_ip());
            $cchash = $ra->get_hash();
        }

        $hash = sha1($this->getAdminID() . $this->getAdminUserAgent() . $this->getAdminPWHash() . $haship . substr(sha1($cchash), 20));
        return $hash;
    }

    public function setSessionVars($raclass = false) {
        $_SESSION['adminid'] = $this->getAdminID();
        $_SESSION['adminpw'] = $this->generateAdminSessionHash($raclass);
        conditionally_set_token(genRandomVal());
    }

    public function processLogin() {
        global $ra;
        update_query("tbladminlog", array("logouttime" => "now()"), array("adminusername " => $this->getAdminUsername(), "logouttime" => "00000000000000"));
        insert_query("tbladminlog", array("adminusername" => $this->getAdminUsername(), "logintime" => "now()", "lastvisit" => "now()", "ipaddress" => $ra->get_user_ip(), "sessionid" => session_id()));
        update_query("tbladmins", array("loginattempts" => "0"), array("username" => $this->getAdminUsername()));
        run_hook("AdminLogin", array("adminid" => $this->getAdminID(), "username" => $this->getAdminUsername()));
    }

    public function getRememberMeCookie() {
        $remcookie = RA_Cookie::get("AU");

        if (!$remcookie) {
            $remcookie = RA_Cookie::get("AUser");
        }

        return $remcookie;
    }

    public function isValidRememberMeCookie($raclass = false) {
        global $ra;

        $cookiedata = $this->getRememberMeCookie();

        if ($cookiedata) {
            $cookiedata = explode(":", $cookiedata);

            if ($this->getInfobyID($cookiedata[0])) {
                if ($raclass) {
                    $hash = $raclass->get_hash();
                } else {
                    $hash = $ra->get_hash();
                }

                $cookiehashcompare = sha1($this->generateAdminSessionHash($raclass) . $hash);

                if ($cookiedata[1] == $cookiehashcompare) {
                    return true;
                }
            }
        }

        return false;
    }

    public function setRememberMeCookie() {
        global $ra;

        RA_Cookie::set("AU", $this->getAdminID() . ":" . sha1($_SESSION['adminpw'] . $ra->get_hash()), "12m");
    }

    public function unsetRememberMeCookie() {
        RA_Cookie::delete("AU");
    }

    private function getWhiteListedIPs() {
        global $ra;

        $ips = array();
        $whitelistedips = unserialize($ra->get_config("WhitelistedIPs"));
        foreach ($whitelistedips as $whitelisted) {
            $ips[] = $whitelisted['ip'];
        }

        return $ips;
    }

    private function isWhitelistedIP($ip) {
        $whitelistedips = $this->getWhiteListedIPs();

        if (in_array($ip, $whitelistedips)) {
            return true;
        }

        $ipparts = explode(".", $ip);

        if (3 <= count($ipparts)) {
            $ip = $ipparts[0] . "." . $ipparts[1] . "." . $ipparts[2] . ".*";

            if (in_array($ip, $whitelistedips)) {
                return true;
            }
        }


        if (2 <= count($ipparts)) {
            $ip = $ipparts[0] . "." . $ipparts[1] . ".*.*";

            if (in_array($ip, $whitelistedips)) {
                return true;
            }
        }

        return false;
    }

    private function getLoginBanDate() {
        global $ra;

        return date("Y-m-d H:i:s", mktime(date("H"), date("i") + $ra->get_config("InvalidLoginBanLength"), date("s"), date("m"), date("d"), date("Y")));
    }

    public function failedLogin() {
        global $ra;

        $remote_ip = $ra->get_user_ip();

        if ($this->isWhitelistedIP($remote_ip)) {
            return false;
        }

        $loginfailures = unserialize($ra->get_config("LoginFailures"));

        if (!is_array($loginfailures[$remote_ip])) {
            $loginfailures[$remote_ip] = array();
        }


        if ($loginfailures[$remote_ip]['expires'] < time()) {
            $loginfailures[$remote_ip]['count'] = 0;
        }

        ++$loginfailures[$remote_ip]['count'];
        $loginfailures[$remote_ip]['expires'] = time() + 30 * 60;

        if (3 <= $loginfailures[$remote_ip]['count']) {
            unset($loginfailures[$remote_ip]);
            insert_query("tblbannedips", array("ip" => $remote_ip, "reason" => "3 Invalid Login Attempts", "expires" => $this->getLoginBanDate()));
        }

        $ra->set_config("LoginFailures", serialize($loginfailures));

        if (isset($this->admindata['username'])) {
            $username = $this->admindata['username'];
            sendAdminNotification("system", "ra Admin Failed Login Attempt", "<p>A recent login attempt failed.  Details of the attempt are below.</p><p>Date/Time: " . date("d/m/Y H:i:s") . ("<br>Username: " . $username . "<br>IP Address: " . $remote_ip . "<br>Hostname: ") . gethostbyaddr($remote_ip) . "</p>");
            logActivity("Failed Admin Login Attempt - Username: " . $username);
            return null;
        }

        sendAdminNotification("system", "ra Admin Failed Login Attempt", "<p>A recent login attempt failed.  Details of the attempt are below.</p><p>Date/Time: " . date("d/m/Y H:i:s") . "<br>Username: " . $this->inputusername . ("<br>IP Address: " . $remote_ip . "<br>Hostname: ") . gethostbyaddr($remote_ip) . "</p>");
        logActivity("Failed Admin Login Attempt - IP: " . $remote_ip);
    }

    public function isLoggedIn() {
        return isset($_SESSION['adminid']);
    }

    public function logout() {
        if ($this->isLoggedIn()) {
            update_query("tbladminlog", array("logouttime" => "now()"), array("sessionid" => session_id()));
            $adminid = $_SESSION['adminid'];
            session_unset();
            session_destroy();
            $this->unsetRememberMeCookie();
            run_hook("AdminLogout", array("adminid" => $adminid));
            return true;
        }

        return false;
    }

    public function isSessionPWHashValid($raclass = false) {
        if (isset($_SESSION['adminpw'])) {
            if ($_SESSION['adminpw'] == $this->generateAdminSessionHash($raclass)) {
                return true;
            }
        }

        return false;
    }

    public function updateAdminLog() {
        global $ra;
        error_log(print_r($this, 1));

        if (!$this->isLoggedIn()) {
            return false;
        }

        $result = select_query_i("tbladminlog", "id", "lastvisit>='" . date("Y-m-d H:i:s", mktime(date("H"), date("i") - 15, date("s"), date("m"), date("d"), date("Y"))) . "' AND sessionid='" . db_escape_string(session_id()) . "' AND logouttime='00000000000000'");
        $data = mysqli_fetch_array($result);
        $adminlogid = $data['id'];

        if ($adminlogid) {
            update_query("tbladminlog", array("lastvisit" => "now()"), array("id" => $adminlogid));
        } else {
            full_query_i("UPDATE tbladminlog SET logouttime=lastvisit WHERE adminusername='" . mysqli_real_escape_string($this->getAdminUsername()) . "' AND logouttime='00000000000000'");
            insert_query("tbladminlog", array("adminusername" => $this->getAdminUsername(), "logintime" => "now()", "lastvisit" => "now()", "ipaddress" => $ra->get_user_ip(), "sessionid" => session_id()));
        }

        return true;
    }

    public function destroySession() {
        session_unset();
        session_destroy();
        return true;
    }

}

?>
