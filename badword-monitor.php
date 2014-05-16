<?php

/**
 * Badword monitor
 * Plugin your url, your website, and put on a cron to run once a day
**/

error_reporting(E_ALL);

$email_address = 'enter@email.com';

//set as google bot lots of times its the only thing the spam will show too.
//you can do as many user agents as tou want


ini_set('user_agent', 'Googlebot/2.1 (+http://www.google.com/bot.html) ');

#add more badwords here
$badwords[0] = "/\bviagra\b/i";
$badwords[1] = "/\bcialis\b/i";
$badwords[2] = "/\bcasino\b/i";
$badwords[3] = "/\bporn\b/i";
 
//this will of course trigger it   
$url = 'wwww.vigara.com';

$data = @file_get_contents(strtolower($site->url));
	  
$bool = mstristr($data,$badwords);

if ($bool){
	send_email($email_address,$url)
}

function send_email($email, $malware_list)
{
    $to = $email;
	$subject = 'One or more of your sites have viagra malware';
	$message = ' One or more of your sites has been flagged as viagra malware';
	$message .= $malware_list;
	$headers = 'From:noreply@uptime.com' . "\r\n";
	mail($to, $subject, $message, $headers);
}

function get_data($url)
{
  	$ch = curl_init();
  	$timeout = 5;
  	curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1');
  	curl_setopt($ch,CURLOPT_URL,$url);
  	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  	$data = curl_exec($ch);
  	$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  	curl_close($ch);
  	return $data;
}


function mstrstr($string,$array) {            
    foreach($array as $str) {
        if(is_array($str)) { 
            foreach($str as $st) {
                if(!strstr($string,$st)) { break 2; }
            }
            return true;
        } else {
            if(strstr($string,$str)) { return true; }
        }
    }
    return false;
}


function mstristr($string,$array) {          
    foreach($array as $str) {
       
          if (preg_match($str, $string)) {return true;}
       
           }
    return false;
}


?>
