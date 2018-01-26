<?php

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$from   = $_POST["from"];
$to   = $_POST["to"];
//$from = "2017-06-30T00:00";
//$to = "2018-01-31T00:00";
$metrics = "count_bounce,count_inband_bounce,count_outofband_bounce";
$slice = "day"; //acceptable values 1min,5min,15min,hour,12hr,day,week,month

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

$count_bounce = array();
$count_inband_bounce = array();
$count_outofband_bounce = array();
$ts = array();
$response_decoded= json_decode ( $response, true );
$results = $response_decoded["results"];
foreach ($results as $key => $value)
{
	foreach ($value as $key2 => $value2)
	{
		if ($key2 == "count_bounce") array_push ($count_bounce, $value2);
		if ($key2 == "count_inband_bounce") array_push ($count_inband_bounce, $value2);
		if ($key2 == "count_outofband_bounce") array_push ($count_outofband_bounce, $value2);
		if ($key2 == "ts") array_push ($ts, substr($value2, 0, -15));
	}
}

$fullArray = array('bounced' => $count_bounce, 'inband' => $count_inband_bounce, 'outofband' => $count_outofband_bounce, 'ts' => $ts);

$json_encoded_string = json_encode ($fullArray);
echo $json_encoded_string;

?>