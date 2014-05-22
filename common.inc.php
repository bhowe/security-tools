<?php
 function send_email($to_email,$from_email, $Message)
	{
	    if(empty($to_email)){
            throw new Exception('email can\'t be empty');
        }
        
        if(empty($from_email)){
            throw new Exception('from email can\'t be empty');
        }
        
        if(empty($malware_list)){
            throw new Exception('Malware list. You cant send a empty email');
        }   
        
	    $to = $to_email;
		$subject = 'One or more of your sites have viagra malware';
		$message = ' One or more of your sites has been flagged as viagra malware';
		$message .= $malware_list;
		$headers = $from_email . "\r\n";
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
	
	
?>