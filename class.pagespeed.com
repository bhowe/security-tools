<?
/* Page speed checker
 * 
ver: 1.0.0 

settings:
	 Plugin your url, your email, and put on a cron to run once a day
 
Blake B. Howe
http://blakebbhowe.com
*/




class PageSpeed {
	
    var $url = '';
	
	/**
	* @param string url of site to check
	*/
	
	function PageSpeed($url) {
		
		if(empty($url)){
            throw new My_Exception('url can\'t be empty');
        }
		
		$this->url = $url;
		
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
	
		

 
	
}

?>