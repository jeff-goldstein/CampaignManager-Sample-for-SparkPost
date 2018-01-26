<?php

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];

$curl = curl_init();
$url = $apiroot . "/transmissions/";
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
)));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) 
{
    echo "cURL Error #:" . $err;
}
$someArray = json_decode($response, true);

echo "<tr><td style='height:20px'><center><h3>Already Scheduled Campaigns</h3></center></td></tr>";
echo "<tr style='vertical-align:top'><td>";
echo "<table style='width:430px'><tr>";
echo "<th style='text-align:left'><u>Campaign Name</u></th>";
echo "<th></th>";
echo "<th style='text-align:left'><u>Scheduled Time for Launch</u></th>";
echo "</tr>";

foreach ($someArray as $key => $value) 
{
    foreach ($value as $key2 => $value2) 
    {
        if ($value2['state'] == "submitted")
        {
            echo "<tr><td width=215><h4>" . $value2['campaign_id'] . "</h4></td><td width='5'></td><td width=210><h4>" . $value2['start_time'] . "</h4></td></tr>";
        }
    }
}
echo "</table></td></tr></table>";
    
?>