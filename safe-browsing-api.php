<?php

# docs 
#https://developers.google.com/safe-browsing/lookup_guide
#to test your app google has provided  http://ianfette.org

/**
 * Defining constants for the CGI parameters of the HTTP GET Request
 * */
define ( 'API_KEY', "ABQIAAAA07YIKE_VFqHP6okZ4_cN7BQjUVBXIsBvXh4Tl03t-qq8YIw2yA" );
define ( 'PROTOCOL_VER', '3.0' );
define ( 'CLIENT', 'checkURLapp' );
define ( 'APP_VER', '1.0' );


/**
 * Function for sending a HTTP GET Request
 * to the Google Safe Browsing Lookup API
 */
function get_data($url) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        
        $data = curl_exec ( $ch );
        $httpStatus = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        curl_close ( $ch );
        
        return array (
                        'status' => $httpStatus,
                        'data' => $data 
        );
}

/**
 * Function for analyzing and paring the
 * data received from the Google Safe Browsing Lookup API 
 */
function send_response($input) {
        if (! empty ( $input )) {
                $urlToCheck = urlencode ( $input );
                
                $url = 'https://sb-ssl.google.com/safebrowsing/api/lookup?client=' . CLIENT . '&apikey=' . API_KEY . '&appver=' . APP_VER . '&pver=' . PROTOCOL_VER . '&url=' . $urlToCheck;
                
                echo $url;
                
                $response = get_data ( $url );
                
                if ($response ['status'] == 204) {
                        return json_encode ( array (
                                        'status' => 204,
                                        'checkedUrl' => $urlToCheck,
                                        'message' => 'The website is not blacklisted and looks safe to use.' 
                        ) );
                } elseif ($response ['status'] == 200) {
                        return json_encode ( array (
                                        'status' => 200,
                                        'checkedUrl' => $urlToCheck,
                                        'message' => 'The website is blacklisted as ' . $response ['data'] . '.' 
                        ) );
                } else {
                        return json_encode ( array (
                                        'status' => 501,
                                        'checkedUrl' => $urlToCheck,
                                        'message' => 'Something went wrong on the server. Please try again.' 
                        ) );
                }
        } else {
                return json_encode ( array (
                                'status' => 401,
                                'checkedUrl' => '',
                                'message' => 'Please enter URL.' 
                ) );
        }
        ;
}



$checkMalware = send_response ('http://ianfette.org'); #will return 204
$checkMalware = json_decode($checkMalware, true);

$malwareStatus = $checkMalware['status'];

echo $malwareStatus;

?>