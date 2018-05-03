<?php

define("ADMINAREA", true);
require "../init.php";
require ROOTDIR . "/vendor/smarty/smarty/libs/Smarty.class.php";
$smarty = new Smarty();
$smarty->template_dir = ROOTDIR . "/" . $ra->get_admin_folder_name() . "/templates/";
$smarty->compile_dir = $templates_compiledir;
if (!function_exists("curl_init")) {
    echo "<div style=\"border: 1px dashed #cc0000;font-family:Tahoma;background-color:#FBEEEB;width:100%;padding:10px;color:#cc0000;\"><strong>Critical Error</strong><br>CURL is not installed or is disabled on your server and it is required for ra to run</div>";
    exit();
}
if (isset($_SESSION['adminid']) && !isset($_SESSION['2fabackupcodenew'])) {
    redir("", "index.php");
}

$disableadminforgottenpw = ($ra->get_config("DisableAdminPWReset") ? true : false);
$action = $ra->get_req_var("action");
$sub = $ra->get_req_var("sub");
$incorrect = $ra->get_req_var("incorrect");
$logout = $ra->get_req_var("logout");
$email = $ra->get_req_var("email");
$timestamp = $ra->get_req_var("timestamp");
$verify = $ra->get_req_var("verify");

if ($action && $disableadminforgottenpw) {
    $action = "";
}

$msgtitle = $msg = $reset = "";
if (((($action == "reset" && !$disableadminforgottenpw) && $email) && $timestamp) && $verify) {
    $result = select_query_i("tbladmins", "", array("email" => $email, "disabled" => "0"));
    $data = mysqli_fetch_array($result);
    $adminid = $data['id'];
    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $username = $data['username'];
    $email = $data['email'];
    $verifyval = md5($email . $timestamp . $adminid . $cc_encryption_hash);

    if (($adminid && $verify == $verifyval) && mktime(date("H"), date("i") - 30, date("s"), date("m"), date("d"), date("Y")) <= $timestamp) {
        $length = 10;
        $seeds = "ABCDEFGHIJKLMNPQRSTUVYXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $str = null;
        $seeds_count = strlen($seeds) - 1;
        $i = 0;

        while ($i < $length) {
            $str .= $seeds[rand(0, $seeds_count)];
            ++$i;
        }

        $newpassword = $str;
        update_query("tbladmins", array(
            "passwordhash" => password_hash($newpassword, PASSWORD_DEFAULT),
            "loginattempts" => "0"), array("email" => $email));
        $message .= "Dear " . $firstname . ",

As requested, your password for the admin area has now been reset.  Your new login details are as follows:

" . $CONFIG['SystemURL'] . (("/" . $adminfolder . "/
Username: " . $username . "
Password: " . $newpassword . "\r\n") . "
You can change your password after login from the My Account section of the admin area.");
        $ra->load_class("phpmailer");
        $mail = new PHPMailer();
        $mail->From = $CONFIG['SystemEmailsFromEmail'];
        $mail->FromName = html_entity_decode($CONFIG['SystemEmailsFromName'], ENT_QUOTES);
        $mail->Subject = "Admin Password Reset Completed";
        $mail->CharSet = $CONFIG['Charset'];

        if ($CONFIG['MailType'] == "mail") {
            $mail->Mailer = "mail";
        } else {
            $mail->IsSMTP();
            $mail->Host = $CONFIG['SMTPHost'];
            $mail->Port = $CONFIG['SMTPPort'];
            $mail->Hostname = $_SERVER['SERVER_NAME'];

            if ($CONFIG['SMTPSSL']) {
                $mail->SMTPSecure = $CONFIG['SMTPSSL'];
            }


            if ($CONFIG['SMTPUsername']) {
                $mail->SMTPAuth = true;
                $mail->Username = $CONFIG['SMTPUsername'];
                $mail->Password = decrypt($CONFIG['SMTPPassword']);
            }

            $mail->Sender = $mail->From;
        }


        if ($smtp_debug) {
            $mail->SMTPDebug = true;
        }

        $mail->Body = $message;
        $mail->AddAddress($email);

        echo pinrt_r($mail);
        if (!$mail->Send()) {
            $msg = "There was an error sending the email. Please try again.";
        } else {
            $msg = "Success! Please check your email for the newly generated password.";
            logActivity("Password Reset Completed for Admin Username " . $username);
        }

        $mail->ClearAddresses();
    } else {
        $msg = "Invalid or Expired Link Followed. Please try again.";
    }

    $action = "";
    $reset = true;
    $msgtitle = "Password Reset";
}


if (!$action) {
    if (isset($_SESSION['2faverify'])) {
        if (isset($_SESSION['2fabackupcodenew'])) {
            $msgtitle = "Login Successful";
            $msg = "Backup Codes are valid once only. It will now be reset.";
        } else {
            $msgtitle = "Two Factor Authentication";
            $msg = ($incorrect ? "The second factor you supplied was incorrect. Please try again." : "Your second factor is required to complete login.");
        }
    } else {
        if ($incorrect) {
            $msgtitle = "Login Failed. Please Try Again.";
            $msg = "Your IP has been logged and admins notified of this<br />failed login attempt.";
        } else {
            if ($logout) {
                $msgtitle = "Logged Out";
                $msg = "You have been successfully logged out.";
            } else {
                if ($reset) {

                } else {
                    $msgtitle = "Welcome Back";
                    $msg = "Please enter your login details below to authenticate.";
                }
            }
        }
    }


    if (isset($_SESSION['2fabackupcodenew'])) {
        $twofa = new RA_2FA();


        if ($twofa->setAdminID($_SESSION['2faadminid'])) {
            $backupcode = $twofa->generateNewBackupCode();
        } else {
            $backupcode = "";
        }
    }
} elseif ($action == "reset" && !$disableadminforgottenpw) {

    if ($sub == "send") {
        $result = select_query_i("tbladmins", "", array("email" => $email));
        $data = mysqli_fetch_array($result);
        $adminid = $data['id'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $username = $data['username'];
        $emailaddr = $data['email'];
        $disabled = $data['disabled'];

        if ($disabled == 1) {
            $msgtitle = "Administrator Disabled";
            $msg = "Your Administrative account has been disabled.";
        } else {
            if (!$adminid) {
                logActivity("Admin Password Reset Attempted for invalid Email: " . $email);
                $msgtitle = "Email Address Not Found";
                $msg = "Your IP has been logged and admins notified of this<br />failed reset attempt.";
            } else {
                $timestamp = time();
                $hash = md5($email . $timestamp . $adminid . $cc_encryption_hash);
                $url = ($CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL']);
                $url .= "/" . $adminfolder . "/login.php?action=reset&email=" . $email . "&timestamp=" . $timestamp . "&verify=" . $hash;
                $msg = ("Dear " . $firstname . ",
A request was recently made to reset the password for admin username '" . $username . "'.
To confirm the request and complete the reset process, simply visit the url below:
" . $url . "\r\n") . "
This link will only be valid for the next 30 minutes so if you didn't request this reset, you can simply ignore this email.
" . $CONFIG['SystemURL'] . ("/" . $adminfolder . "/");

                $ra->load_class("phpmailer");
                $mail = new PHPMailer();
                $mail->From = $CONFIG['SystemEmailsFromEmail'];
                $mail->FromName = html_entity_decode($CONFIG['SystemEmailsFromName'], ENT_QUOTES);
                $mail->Subject = "Admin Password Reset Request";
                $mail->CharSet = $CONFIG['Charset'];

                if ($CONFIG['MailType'] == "mail") {
                    $mail->Mailer = "mail";
                } else {
                    if ($CONFIG['MailType'] == "smtp") {
                        $mail->IsSMTP();
                        $mail->Host = $CONFIG['SMTPHost'];
                        $mail->Port = $CONFIG['SMTPPort'];
                        $mail->Hostname = $_SERVER['SERVER_NAME'];

                        if ($CONFIG['SMTPSSL']) {
                            $mail->SMTPSecure = $CONFIG['SMTPSSL'];
                        }


                        if ($CONFIG['SMTPUsername']) {
                            $mail->SMTPAuth = true;
                            $mail->Username = $CONFIG['SMTPUsername'];
                            $mail->Password = decrypt($CONFIG['SMTPPassword']);
                        }

                        $mail->Sender = $mail->From;
                    }
                }


                if ($smtp_debug) {
                    $mail->SMTPDebug = true;
                }

                $mail->Body = $message;
                $mail->AddAddress($email);

                if (!$mail->Send()) {
                    $msgtitle = "Password Reset";
                    $msg = "There was an error sending the email. Please try again.";
                } else {
                    $msgtitle = "Password Reset";
                    $msg = "Success! Please check your email for the next step...";
                    logActivity("Password Reset Initiated for Admin Username " . $username);
                }

                $mail->ClearAddresses();
            }
        }
    } else {
        $msgtitle = "Password Reset";
        $msg = "Enter your email address below to begin the process";
    }
}
$smarty->assign("msg", $msg);
$smarty->assign("msgtitle", $msgtitle);
$smarty->assign("backupcode", $backupcode);
$smarty->assign("remote_ip", $remote_ip);
$smarty->assign("action", $action);
$smarty->display(ROOTDIR . "/" . $ra->get_admin_folder_name() . "/templates/ra_flat/login.tpl");
?>
