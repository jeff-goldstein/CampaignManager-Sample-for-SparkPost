<?php
{
//Purpose:  Simply to retrieve all message events from the SparkPost server that have been created
//in the last 24 hours specifically for a given Campaign id which has been hardcoded in the tmMain getEvents function.  
//The campaign name could easily be exposed to the user for more flexibility. 

//
//Main code body
//			
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$message_id   = $_POST["messageID"];
$message_type   = $_POST["message_type"];

date_default_timezone_set('America/Los_Angeles');
$end = date('Y-m-dTh:i', time() + 86400);
$end = str_replace ('PST', 'T', $end);
$start = date('Y-m-dTh:i', time() - 864000);
$start = str_replace ('PST', 'T', $start);

$curl     = curl_init();
$url      = $apiroot. "/message-events?message_ids=" . $message_id . "&events=" . $message_type . "&from=" . $start . "&to=" . $end;

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "authorization: $apikey",
        "cache-control: no-cache",
        "content-type: application/json"
    )
));
    
$response = curl_exec($curl);
$err = curl_error($curl);
$response_encoded = json_encode($response);

if ($err)
{
  	$error_response = "<br><br>Get Events: cURL Error #:" . $err;
} 
else 
{
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ($httpCode != 200)
	{
		$error_response = "<h4>HTTP Error from server attempting obtain data: " . $httpCode . "</h4><br>Raw message from Server<p><pre>" . $response . "</pre>";
		$error_response .= "<br><br><i>Please see SparkPost server status at <a href='https://status.sparkpost.com/' target='_blank'>SparkPost Status Page</a></i>";
	}
}
	
if (strlen($error_response))
{
	$json_array=array(
		'error' => $responses,
		'url' => $url);
	$json_encoded_string = json_encode ($json_array);
	echo $json_encoded_string;
}
else
{		
	echo $response;
}		

}
					
?>
