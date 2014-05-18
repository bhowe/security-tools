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
	
	$badwords_start = $stack = array("/\bviagra\b/i", "/\bcialis\b/i","/\bcasino\b/i","/\bporn\b/i");
	$url = '';
	
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
            throw new My_Exception('email can\'t be empty');
        }
        
        if(empty($from_email)){
            throw new My_Exception('url can\'t be empty');
        }
        
        if(empty($malware_list)){
            throw new My_Exception('Malware list. You cant send a empty email');
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


	

 
	
}

?>