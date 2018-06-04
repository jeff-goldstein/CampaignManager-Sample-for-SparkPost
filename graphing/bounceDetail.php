<?php

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$from   = $_POST["from"];
$to   = $_POST["to"];
//$from = "2017-06-30T00:00";
//$to = "2018-01-31T00:00";
$metrics = "count_bounce,count_inband_bounce,count_outofband_bounce&limit=500";
//$slice = "day";  //acceptable values 1min,5min,15min,hour,12hr,day,week,month

//https://api.sparkpost.com/api/v1/metrics/deliverability/bounce-reason?from=2017-08-30T08:00&to=2018-01-20T09:00&metrics=count_bounce,count_inband_bounce,count_outofband_bounce&limit=500

$curl     = curl_init();
$url      = $apiroot. "/metrics/deliverability/bounce-reason?from=" . $from . "&to=" . $to . "&metrics=" . $metrics;
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

$bounce_class_name = array();
$bounce_class_description = array();
$bounce_category_name = array();
$classification_id = array();
$count_bounce = array();
$count_inband_bounce = array();
$count_outofband_bounce = array();
$reason = array();
$response_decoded= json_decode ( $response, true );
$results = $response_decoded["results"];
//var_dump ($results);

foreach ($results as $key => $value)
{
	foreach ($value as $key2 => $value2)
	{
		if ($key2 == "bounce_class_name") array_push ($bounce_class_name, $value2);
		if ($key2 == "bounce_class_description") array_push ($bounce_class_description, $value2);
		if ($key2 == "bounce_category_name") array_push ($bounce_category_name, $value2);
		if ($key2 == "classification_id") array_push ($classification_id, $value2);
		if ($key2 == "count_bounce") array_push ($count_bounce, $value2);
		if ($key2 == "count_inband_bounce") array_push ($count_inband_bounce, $value2);
		if ($key2 == "count_outofband_bounce") array_push ($count_outofband_bounce, $value2);
		if ($key2 == "reason") array_push ($reason, $value2);
	}
}


$fullArray = array(
	'bounce_class_name' => $bounce_class_name, 
	'bounce_class_description' => $bounce_class_description, 
	'bounce_category_name' => $bounce_category_name, 
	'classification_id' => $classification_id,
	'count_bounce' => $count_bounce,
	'count_inband_bounce' => $count_inband_bounce,
	'count_outofband_bounce' => $count_outofband_bounce,
	'reason' => $reason,
	'error' => $error_response,
);

$json_encoded_string = json_encode ($fullArray);
echo $json_encoded_string;

?>
