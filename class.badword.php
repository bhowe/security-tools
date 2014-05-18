<?
/*
 * Searches page for words that shouldnt be there.
 *  http://code.google.com/apis/patentsearch/v1/
ver: 1.0.0 

settings:
	 Plugin your url, your email, and put on a cron to run once a day
 
Blake B. Howe
http://blakebbhowe.com
*/




class BadWords {
	
	
	var $badwords_stack = array("/\bviagra\b/i", "/\bcialis\b/i","/\bcasino\b/i","/\bporn\b/i");
    var $url = '';
	/**
	* @param string pass in your own array of regex badwords
	*/
	function BadWords($url) {
		
		if(empty($url)){
            throw new My_Exception('url can\'t be empty');
        }
		
		$this->url = $url;
		
	}
	

	
    function send_email($to_email,$from_email, $malware_list)
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
	
	
	function mstristr($string) { 
	
	 if(empty($string)){
            throw new Exception('Incoming page cant be empty');
        }       
	      
	    foreach($this->badwords_stack as $str) {
	       
	          if (preg_match($str, $string)) {return true;}
	       
	           }
	    return false;
	}

	function get_data()
	{
	
	 
	  	$ch = curl_init();
	  	$timeout = 5;
	  	curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1');
	  	curl_setopt($ch,CURLOPT_URL,$this->url);
	  	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	  	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	  	$data = curl_exec($ch);
	  	$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	  	curl_close($ch);
	  	return $data;
	}
	
		

 
	
}

?>