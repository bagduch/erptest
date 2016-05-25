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
 **/

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure General Settings");
$aInt->title = $aInt->lang("supportreq", "title");
$aInt->sidebar = "help";
$aInt->icon = "support";
ob_start();
echo "
<p class=\"bigtext\">Our online community is full of helpful resources, from how-to guides on setup, configuration, to advanced troubleshooting, as well as a thriving forum community which prides itself on giving back.</p>

<table style=\"width:100%\">
<tr>
<td style=\"width:25%;text-align:center;font-size:24px;color:#00446d;border-right:1px dashed #ccc;padding:20px;vertical-align:middle;\">Read our Do";
echo "cs</td>
<td style=\"width:25%;text-align:center;font-size:24px;color:#00446d;border-right:1px dashed #ccc;padding:20px;vertical-align:middle;\">Watch Tutorials</td>
<td style=\"width:25%;text-align:center;font-size:24px;color:#00446d;border-right:1px dashed #ccc;padding:20px;vertical-align:middle;\">Ask the Community</td>
<td style=\"width:25%;text-align:center;font-size:24px;color:#00446d;padding:20px;ve";
echo "rtical-align:middle;\">";

if ($licensing->getSupportAccess()) {
	echo "Ask Us";
}
else {
	echo " Ask Your Reseller";
}

echo "</td>
</tr>
<tr style=\"\">
<td style=\"width:25%;text-align:center;border-right:1px dashed #ccc;\"><a href=\"http://docs.ra.com/\"><img src=\"http://updates.ra.com/images/docs.gif\" alt=\"Online Documentation\" width=\"64\" height=\"64\" /></a></td>
<td style=\"width:25%;text-align:center;border-right:1px dashed #ccc;\"><a href=\"http://www.ra.com/get-support/video-tutorials/\"><img src=\"http://updates.ra.com";
echo "/images/tutorials.gif\" alt=\"Online Documentation\" width=\"64\" height=\"64\" /></a></td>
<td style=\"width:25%;text-align:center;border-right:1px dashed #ccc;\"><a href=\"http://forums.ra.com/\"><img src=\"http://updates.ra.com/images/community.gif\" alt=\"Online Documentation\" width=\"64\" height=\"64\" /></a></td>
<td style=\"width:25%;text-align:center;\"><a href=\"mailto:mtimercms@hotmail.com\">";
echo "<img src=\"http://updates.ra.com/images/submitticket.gif\" alt=\"Online Documentation\" width=\"64\" height=\"64\" /></a></td>
</tr>
<tr>
<td style=\"width:25%;text-align:center;border-right:1px dashed #ccc;padding:20px;\">Full of helpful articles and guides on how to use ra</p>
<div style=\"margin:0 auto;width:100px;\"><a class=\"btn\" href=\"http://docs.ra.com/\">Go &raquo;</a></div>
</td>
<td style=\"width:25";
echo "%;text-align:center;border-right:1px dashed #ccc;padding:20px;\">Step by step walkthrough&#8217;s on all the most common setup &#038; functionality of ra</p>
<div style=\"margin:0 auto;width:100px;\"><a class=\"btn\" href=\"http://www.ra.com/get-support/video-tutorials/\">Go &raquo;</a></div>
</td>
<td style=\"width:25%;text-align:center;border-right:1px dashed #ccc;padding:20px;\">Home to a very active ";
echo "community of ra users and enthusiasts who are always willing to help resolve issues and discuss new ideas</p>
<div style=\"margin:0 auto;width:100px;\"><a class=\"btn\" href=\"http://forums.ra.com/\">Go &raquo;</a></div>
</td>
<td style=\"width:25%;text-align:center;padding:20px;\">";

if ($licensing->getSupportAccess()) {
	echo "Can&#8217;t find what you&#8217;re looking for in our documentation? Let us help! Open a ticket";
}
else {
	echo "As your license is provided by " . ($licensing->getKeyData("reseller") ? $licensing->getKeyData("reseller") : "a reseller") . " please contact your license provider for support and assistance";
}

echo "</p>
<div style=\"margin:0 auto;width:100px;\">";

if ($licensing->getSupportAccess()) {
	echo "<a class=\"btn\" href=\"https://www.ra.com/members/submitticket.php\">Go &raquo;</a>";
}

echo "</div>
</td>
</tr>
</table>

<div style=\"margin:20px 0;padding:15px 25px;background-color:#FBF7EA;-moz-border-radius: 10px;-webkit-border-radius: 10px;-o-border-radius: 10px;border-radius: 10px;\">
    <div style=\"padding:0 0 10px 0;font-size:24px;\">Search our Help Resources</div>
    <form method=\"post\" action=\"http://docs.ra.com/Special:Search\">
    <input type=\"text\" name=\"search\" size=\"50\" st";
echo "yle=\"font-size:18px;\" />
    <input type=\"submit\" name=\"go\" value=\"Search &raquo;\" style=\"font-size:18px;\" />
    </form>
</div>

<h2>Frequently Asked Questions</h2>

<table width=\"100%\">
<tr><td width=\"50%\">
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Getting_Started\">How do I get start";
echo "ed with ra?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Further_Security_Steps\">What additional steps can I take to increase security?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"";
echo "http://docs.ra.com/Products_and_Services#Product_Groups\">How do I setup a new product?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Domain_Pricing\">No domains are listed on my order form, where do I add them?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http:/";
echo "/updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Importing_Data\">How do I add my existing customers to ra?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Email_Piping\">How do I setup Email Piping?</a></div>
</td><td width=\"50%\">
<div sty";
echo "le=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Troubleshooting_Guide\">I'm getting an error, where do I start looking for an answer?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Bla";
echo "nk_Pages\">I'm seeing a blank page, how do I troubleshoot?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Auto_Setup_Issues\">Services are not being created automatically, how do I troubleshoot?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/im";
echo "ages/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Licensing\">I'm getting a license error but not sure why?</a></div>
<div style=\"padding:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/FAQs\">I've forgotten my login details/my IP has been banned, how do I reset it?</a></div>
<div style=\"paddi";
echo "ng:2px 30px;font-size:14px;\"><img src=\"http://updates.ra.com/images/article.gif\" width=\"16\" height=\"16\" /> <a href=\"http://docs.ra.com/Upgrading\">How do I upgrade my ra installation to the latest version?</a></div>
</td></tr></table>

";
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->display();
?>