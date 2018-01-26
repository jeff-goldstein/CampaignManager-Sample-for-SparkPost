<?php

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$from   = $_POST["from"];
$to   = $_POST["to"];
//$from = "2017-06-30T00:00";
//$to = "2018-01-31T00:00";
$metrics = "count_accepted,count_clicked,count_unique_rendered,count_rejected,count_policy_rejection,count_generation_failed,count_generation_rejection,count_bounce,count_inband_bounce,count_outofband_bounce";
$slice = "day";  //acceptable values 1min,5min,15min,hour,12hr,day,week,month

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
  	$responses = "<br><br>Mettics Call cURL Error #:" . $err;
  	echo $responses;
}
else 
{
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ($httpCode != 200)
	{
		if ($httpCode == 429)
		{
			$error_response = "To many calls to Metrics API";
		}
		else
		{
			$error_response = "<h4>HTTP Error from server attempting obtain data: " . $httpCode . "</h4><br>Raw message from Server<p><pre>" . $response . "</pre>";
			$error_response .= "<br><br><i>Please see SparkPost server status at <a href='https://status.sparkpost.com/' target='_blank'>SparkPost Status Page</a></i>";
		}
	}
}

$count_accepted = array();
$count_clicked = array();
$count_unique_rendered = array();
$ts = array();
$count_rejected = array();
$count_policy_rejected = array();
$count_outofband_bounce = array();
$count_generation_rejection = array();
$count_generation_failed = array();
$count_bounce = array();
$count_inband_bounce = array();
$count_outofband_bounce = array();
$response_decoded= json_decode ( $response, true );
$results = $response_decoded["results"];
date_default_timezone_set('America/Los_Angeles');
foreach ($results as $key => $value)
{
	foreach ($value as $key2 => $value2)
	{
		//echo $key2 . "value: " . $value2 . PHP_EOL;
		// efficiency data
		if ($key2 == "count_accepted") array_push ($count_accepted, $value2);
		if ($key2 == "count_clicked") array_push ($count_clicked, $value2);
		if ($key2 == "count_unique_rendered") array_push ($count_unique_rendered, $value2);
		if ($key2 == "ts") array_push ($ts, substr($value2, 0, -15));
		// reject data
		if ($key2 == "count_rejected") array_push ($count_rejected, $value2);
		if ($key2 == "count_policy_rejected") array_push ($count_policy_rejected, $value2);
		if ($key2 == "count_generation_failed") array_push ($count_generation_failed, $value2);
		if ($key2 == "count_generation_rejection") array_push ($count_generation_rejection, $value2);
		// bounce data
		if ($key2 == "count_bounce") array_push ($count_bounce, $value2);
		if ($key2 == "count_inband_bounce") array_push ($count_inband_bounce, $value2);
		if ($key2 == "count_outofband_bounce") array_push ($count_outofband_bounce, $value2);
	}
}

$fullArray = array(
	'accepted' => $count_accepted, 
	'clicked' => $count_clicked, 
	'opened' => $count_unique_rendered, 
	'ts' => $ts,
	'bounced' => $count_bounce, 
	'inband' => $count_inband_bounce, 
	'outofband' => $count_outofband_bounce,
	'rejected' => $count_rejected, 
	'policy' => $count_policy_rejected, 
	'generation_failed' => $count_generation_failed, 
	'generation_rejection' => $count_generation_rejection
);

$json_encoded_string = json_encode ($fullArray);
echo $json_encoded_string;

?>