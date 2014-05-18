<?php
/*
Badword monitor
Scan your website for bad phrases as google bot (very common hack)
ver: 1.0.0 

settings:
	 Plugin your url, your email, and put on a cron to run once a day
 
Blake B. Howe
http://blakebbhowe.com
*/

error_reporting(E_ALL);
require_once('class.Badword.php');

$to_email_address = 'howe.bobby@gmail.com';
$from_email_address = 'test@badwordfunkiness.com';
$url = "http://www.viagra.com/index.aspx";

//of course this email should trigger it  
$badword = new Badwords($url);
$data =$badword->get_data();
	
if ($badword->mstristr($data)){
	$badword->send_email($to_email_address,$from_email_address,$url);
	echo 'email sent to ' . $to_email_address;
}


?>
