<?php

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$from   = $_POST["from"];
$to   = $_POST["to"];
//$from = "2017-06-30T00:00";
//$to = "2018-01-31T00:00";
$metrics = "count_accepted,count_clicked,count_unique_rendered";
$slice = "day";  //acceptable values 1min,5min,15min,hour,12hr,day,week,month

//api.sparkpost
//$apikey = "e8e6345ff301a92842beebff298541a18ffdbff7";
//$apiroot = "https://api.sparkpost.com/api/v1";

//demo.e
//$apikey = "8df0073be24464a4f5f7d88318b1e654a729ac9c";
//$apiroot = "https://demo.api.e.sparkpost.com/api/v1";

// https://api.sparkpost.com/api/v1/metrics/deliverability/time-series?from=2017-12-01T07:00&to=2017-12-31T08:00&precision=day&metrics=count_accepted,count_clicked,count_unique_rendered

$curl     = curl_init();
$url      = $apiroot. "/metrics/deliverability/time-series?from=" . $from . "&to=" . $to . "&precision=" . $slice . "&metrics=" . $metrics;
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
  	$responses = "<br><br>Metrics Call cURL Error #:" . $err;
  	echo $responses;
} 

$count_accepted = array();
$count_clicked = array();
$count_unique_rendered = array();
$ts = array();
$response_decoded= json_decode ( $response, true );
$results = $response_decoded["results"];
date_default_timezone_set('America/Los_Angeles');
foreach ($results as $key => $value)
{
	foreach ($value as $key2 => $value2)
	{
		//echo $key2 . "value: " . $value2 . PHP_EOL;
		if ($key2 == "count_accepted") array_push ($count_accepted, $value2);
		if ($key2 == "count_clicked") array_push ($count_clicked, $value2);
		if ($key2 == "count_unique_rendered") array_push ($count_unique_rendered, $value2);
		if ($key2 == "ts") array_push ($ts, substr($value2, 0, -15));
	}
}

$fullArray = array('accepted' => $count_accepted, 'clicked' => $count_clicked, 'opened' => $count_unique_rendered, 'ts' => $ts);

$json_encoded_string = json_encode ($fullArray);
echo $json_encoded_string;

?>