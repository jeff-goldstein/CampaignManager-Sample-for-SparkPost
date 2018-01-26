<?php

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$from   = $_POST["from"];
$to   = $_POST["to"];
//$from = "2017-06-30T00:00";
//$to = "2018-01-31T00:00";
$metrics = "total_msg_volume,count_unique_clicked,count_unique_rendered,count_delayed,count_sent,count_spam_complaint,count_targeted";
$slice = "day";  //acceptable values 1min,5min,15min,hour,12hr,day,week,month

//api.sparkpost
//$apikey = "e8e6345ff301a92842beebff298541a18ffdbff7";
//$apiroot = "https://api.sparkpost.com/api/v1";

//demo.e
//$apikey = "8df0073be24464a4f5f7d88318b1e654a729ac9c";
//$apiroot = "https://demo.api.e.sparkpost.com/api/v1";

$curl     = curl_init();
$url      = $apiroot. "/metrics/deliverability?from=" . $from . "&to=" . $to . "&precision=" . $slice . "&metrics=" . $metrics;
//echo $url;
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
$responses = NULL;

if ($err)
{
  	$responses = "<br><br>Mettics Call cURL Error #:" . $err;
  	echo $responses;
} 

$count_rejected = array();
$count_policy_rejected = array();
$count_outofband_bounce = array();
$count_generation_rejection = array();
$ts = array();
$response_decoded= json_decode ( $response, true );
$results = $response_decoded["results"];
//var_dump ($results);

$fullArray = array(
	'total_msg_volume' => $results[0]['total_msg_volume'],
	'count_unique_clicked' => $results[0]['count_unique_clicked'], 
	'count_unique_rendered' => $results[0]['count_unique_rendered'],
	'count_delayed' => $results[0]['count_delayed'],
	'count_sent' => $results[0]['count_sent'],
	'count_spam_complaint' => $results[0]['count_spam_complaint'],
	);

$json_encoded_string = json_encode ($fullArray);
echo $json_encoded_string;

?>