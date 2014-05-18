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
	
	
	/**
	* @param string pass in your own array of regex badwords
	*/
	function BadWords($badwords) {
		$this->query = $query;
	}
	
	//use the default
	function BadWords($badwords) {
		$this->query = $query;
	}
	


	

 
	
}

?>