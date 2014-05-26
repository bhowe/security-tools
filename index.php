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

/*
require_once('class.Badword.php');

$to_email_address = 'howe.bobby@gmail.com';
$from_email_address = 'test@badwordfunkiness.com';
$url_to_test_badwords = "http://www.viagra.com/index.aspx";

//of course this email should trigger it  
$badword = new Badwords();
$data =$badword->get_data($url_to_test);
	
if ($badword->mstristr($data)){
	
	echo 'site contained email sent to ' . $to_email_address;
}

*/


$safe_browsing_url = ("https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey=ABQIAAAA07YIKE_VFqHP6okZ4_cN7BQjUVBXIsBvXh4Tl03t-qq8YIw2yA&appver=1.0&pver=3.0&url=");
$malware_testing_url = "http://malware.testing.google.test/testing/malware/";
$good_testing_url = "http://google.com";
$encoded_url =  urlencode($malware_testing_url);
$prep_url =$safe_browsing_url .$encoded_url;
$safe_browing_data = get_data($prep_url);

if ($safe_browing_data  !== "ok"){
echo 'MALWARE FOUND: ' . $malware_testing_url   ;
}

function get_data($url)
{
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if($response === 200)
	return "malware";
  if($response === 204)
	return "ok";
  curl_close($ch);
  return $data;
}



?>
