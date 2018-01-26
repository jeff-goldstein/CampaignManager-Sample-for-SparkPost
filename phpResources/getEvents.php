<?php 
/* Copyright 2017 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. 

File: tmGet Events.php
Purpose:  Simply to retrieve all message events from the SparkPost server that have been created
in the last 24 hours specifically for a given Campaign id which has been hardcoded in the tmMain getEvents function.  
The campaign name could easily be exposed to the user for more flexibility. 
*/

//
//Main code body
//			
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$searchkey   = $_POST["searchkey"];

$curl     = curl_init();
$url      = $apiroot. "/message-events?campaign-id=" . $searchkey;

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
  	$responses = "<br><br>Delete Template: cURL Error #:" . $err;
  	echo $responses;
} 
else 
{
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ($httpCode != 200)
	{
		$responses = "<h4>HTTP Error from server attempting obtain data: " . $httpCode . "</h4><br>Raw message from Server<p><pre>" . $response . "</pre>";
		$responses .= "<br><br><i>Please see SparkPost server status at <a href='https://status.sparkpost.com/' target='_blank'>SparkPost Status Page</a></i>";
		echo $responses;
	}
	else
	{
  		$responses = "<br>Delete Template: " . $templatelisttext . " --> successful";
		$messageEventsArray = json_decode($response, true);
		if (strlen($response) > 50) //a response with no results is a tad under 50 characters, so there is something
		{
			echo "<center><p style='color:#298272;font-size:20px;font-family: Helvetica, Arial;'>Recently Sent Test Emails</p></center>";
			echo "<center>(Last 24 Hours)</center>";
			echo "<center>";
			echo "<table id='tableformat' border=1 style='width:1175px;'>";
			echo "<tr>";
			echo "<th><center>Sent To</center></th>";
			echo "<th><center>Subject</center></th>";
			echo "<th><center>Mail Server Event</center></th>";
			echo "<th><center>Time Stamp</center></th>";
			echo "<th><center>Bounce Details (if any) </center></th>";
			echo "</tr>";
			foreach ($messageEventsArray as $key => $value) 
  			{
  				if (!empty($value))
  				{
  					foreach ($value as $key2 => $value2)
  					{
      					$row = "<tr>";
      					$row .= "<td style='width:200px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['rcpt_to'] . "</p></td>";
      					$row .= "<td style='width:250px; padding-left: 5px;'><p style='color:blue; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['subject'] . "</p></td>";
      					$row .= "<td style='width:125px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['type'] . "</p></td>";
      					$row .= "<td style='width:200px; padding-left: 5px;'><p style='color:blue; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['tdate'] . "</p></td>";
      					if (strlen($value2['bounce_class'] > 0))
      					{
      						$row .= "<td style='width:400px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . "Bounce Class: " . $value2['bounce_class'] . " Error: " . $value2['reason'] . "</p></td>";
      					}
      					else
      					{
      						$row .= "<td style='width:400px; padding-left: 5px;'><p style='color:black'>&nbsp;</td>";
      					}
      					$row .= "</tr>";
      					echo $row;
      				}
      			}	
			}
			echo "</table>";
		}
		else echo "No data found";
	}	
}
curl_close($curl);

?>
