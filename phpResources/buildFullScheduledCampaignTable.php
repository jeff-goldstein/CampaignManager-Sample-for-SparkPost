<?php
{
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$count=0;

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

echo "<tr>";
echo "<th style='width:15px'><center><input unchecked type='checkbox' id='mastercheck' name='mastercheck' onchange='changeall()'/></center></th>";
echo "<th><center>Campaign Name</center></th>";
echo "<th><center>Template ID</center></th>";
echo "<th><center>Recipient List</center></th>";
echo "<th><center>Number of Recipients</center></th>";
echo "<th><center>Scheduled Time for Launch*</center></th>";
echo "<th><center>Internal Campaign ID Number</center></th>";
echo "</tr>";

//
// Display only records where the 'state' field is 'submitted'
//
foreach ($someArray as $key => $value) 
{
  	foreach ($value as $key2 => $value2) 
	{
		if ($value2['state']=="submitted") 
		{
      		$count++;
      		//$row  = "<tr><td style='width:15px'><input type='checkbox' name='isSelected[]' value='" . $value2['id'] . "' />";
      		$row  = "<tr><td style='width:15px'><input type='checkbox' name='isSelected' value='" . $value2['id'] . "' />";
      		$row .= "</td><td><h3 style='color:black'>" . $value2['campaign_id'];
			$row .= "</h3></td><td><h3 style='color:black'>" . $value2['content']['template_id'];
      		if ($value2['recipients']['list_id'])
      			$row .= "</h3></td><td><h3 style='color:black'>" . $value2['recipients']['list_id'];
      		else
      			$row .= "</h3></td><td><h3 style='color:black'>" . "Uploaded recipients";
      		$row .= "</h3></td><td><h3 style='color:black'>" . $value2['description'];
      		$startTimeFull = $value2['start_time'];
			$endDatePos = strpos($startTimeFull, "T");
      		$schedDate = substr($startTimeFull, 0, $endDatePos);
      		$schedTime = substr($startTimeFull, $endDatePos + 1, 5);
      		$row .= "</h3></td><td><h3 style='color:black'>" . $schedDate . " at " . $schedTime;
      		$row .= "</h3></td><td><h3 style='color:black'>" . $value2['id'] . "</h3></td></tr>";
      		echo $row;
      	}
	}
}
if ($count < 0) 
{
    $row .= "<tr><td colspan=7 border='0'><br><br><center><h3>No Campaigns Scheduled at this time</h3></center></td></tr>";
    echo $row;
}
}	
?>