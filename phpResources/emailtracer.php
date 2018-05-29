<?php
{
//Purpose:  To retrieve all message events from the SparkPost server that have been created
//in the last 10 days.

//
//Main code body
//			
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$emailaddress   = $_POST["emailaddress"];
date_default_timezone_set('America/Los_Angeles');
$end = date('Y-m-dTh:i', time() + 86400);
$end = str_replace ('PDT', 'T', $end);
$start = date('Y-m-dTh:i', time() - 864000);
$start = str_replace ('PDT', 'T', $start);

$curl     = curl_init();
$url      = $apiroot. "/message-events?recipients=" . urlencode($emailaddress) . "&from=" . $start . "&to=" . $end;
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
$responses = "";
$fromArray = array();
$campaignArray = array();
$subjectArray = array();
$typeArray = array();
$timeArray = array();

if ($err)
{
  	$responses = "<br><br>Get Events: cURL Error #:" . $err;
} 
else 
{
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ($httpCode != 200)
	{
		$responses = "<h4>HTTP Error from server attempting obtain data: " . $httpCode . "</h4><br>Raw message from Server<p><pre>" . $response . "</pre>";
		$responses .= "<br><br><i>Please see SparkPost server status at <a href='https://status.sparkpost.com/' target='_blank'>SparkPost Status Page</a></i>";
	}
	else
	{
		$messageEventsArray = json_decode($response, true);
		if (strlen($response) > 50) //a response with no results is a tad under 50 characters, so there is something
		{
			$tabledetails = "<center>";
			$tabledetails .= "<table id='EventtableDetails' border=1 style='width:1750px;'>";
			$tabledetails .= "<tr>";
			$tabledetails .= "<th style='width:50px'><center>Click<br>for full<br>details</center></th>";	
			$tabledetails .= "<th style='width:150px'><center>From</center></th>";	
			$tabledetails .= "<th><center>Subject</center></th>";
			$tabledetails .= "<th style='width:100px'><center>Campaign Id</center></th>";
			$tabledetails .= "<th style='width:50px'><center>Mail Server Event</center></th>";
			$tabledetails .= "<th style='width:175px'><center>Time Stamp</center></th>";
			$tabledetails .= "<th id='bounce' name='bounce'><center>Bounce Details (if any) </center></th>";
			$tabledetails .= "<th id='loc' name='loc' style='width:150px'><center>Est. Loc When Clicked</center></th>";
			$tabledetails .= "<th style='width:75px' id='ipaddress' name='ipaddress' ><center>IP Address<br><p style='font-size:10px'>(click 4 details)</p></center></th>";
			$tabledetails .= "<th id='metadata' name='metadata' ><center>Meta Data</center></th>";
			$tabledetails .= "<th style='width:50px' id='subaccount' name='subaccount'><center>Sub Account ID</center></th>";
			$tabledetails .= "<th hidden id='raw' name='raw'><center>Raw</center></th>";
			$tabledetails .= "</tr><tbody>";
			$index = 0;
			foreach ($messageEventsArray as $key => $value) 
  			{
  				if (!empty($value))
  				{
  					foreach ($value as $key2 => $value2)
  					{
      					$index++;
      					if (!in_array($value2['friendly_from'], $fromArray, true)) array_push($fromArray, $value2["friendly_from"]);
      					if (!in_array($value2['campaign_id'], $campaignArray, true)) array_push($campaignArray, $value2["campaign_id"]);
      					if (!in_array($value2['subject'], $subjectArray, true)) array_push($subjectArray, $value2["subject"]);
      					if (!in_array($value2['type'], $typeArray, true)) array_push($typeArray, $value2["type"]);
      					if (!in_array($value2['tdate'], $timeArray, true)) array_push($timeArray, $value2["tdate"]);
      					$tabledetails .= "<tr>";
      					//$tabledetails .= "<td style='width:50px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'><center><input id='detailcheck' name='detailcheck' type='checkbox' onclick='show_details_html(" . '"' . $value2['message_id'] . '","' . $value2['type'] . '"' . ")'>" . "</center></p></td>";
      					$tabledetails .= "<td style='width:50px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'><center><input id='detailcheck' name='detailcheck' type='checkbox' onclick='show_details_local_html(" . '"' . $index . '"' . ")'>" . "</center></p></td>";
      					if ((strlen($value2['friendly_from']) > 0)) $tabledetails .= "<td style='width:150px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['friendly_from'] . "</p></td>";
      					else $tabledetails .= "<td style='width:150px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . "N/A of Generation Failures" . "</p></td>";
      					if ((strlen($value2['subject']) > 0)) $tabledetails .= "<td style='width:100px; padding-left: 5px;'><p style='color:blue; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['subject'] . "</p></td>";
      					else $tabledetails .= "<td style='width:150px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . "N/A of Generation Failures" . "</p></td>";
      					$tabledetails .= "<td style='width:250px; padding-left: 5px;'><p style='color:blue; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['campaign_id'] . "</p></td>";
      					$tabledetails .= "<td style='width:50px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['type'] . "</p></td>";
      					$displayDate = str_replace ('T', ' @ ', $value2['tdate']);
      					$displayDate = substr($displayDate, 0, -8);
      					$tabledetails .= "<td style='width:175px; padding-left: 5px;'><p style='color:blue; font-family: Helvetica, Arial;font-size:12px;'>" . $displayDate . "</p></td>";
      					if ((strlen($value2['bounce_class']) > 0)) $tabledetails .= "<td id='bounce' name='bounce' style='width:400px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . "Bounce Class: " . $value2['bounce_class'] . " Error: " . $value2['reason'] . "</p></td>";
      					else $tabledetails .= "<td id='bounce' name='bounce' style='width:400px; padding-left: 5px;'><p style='color:black'>" . $value2['reason'] . "</p></td>";
      					$tabledetails .= "<td id='loc' name='loc' style='width:150px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>";
      					$tabledetails .=  $value2['geo_ip']['city']; 
      					$tabledetails .=  ' ' . $value2['geo_ip']['country'];
      					$tabledetails .= " </p></td>";
      					$tabledetails .= "<td id='ipaddress' name='ipaddress' style='width:75px; padding-left: 5px;'><p style='color:blue; font-family: Helvetica, Arial;font-size:12px;' onclick='ipLocLookup(" .'"' . $value2['ip_address'] . '"' . ")'>" . $value2['ip_address'] . "</p></td>";
      					if (!json_encode($value2['meta_data']))
      						$tabledetails .= "<td id='metadata' name='metadata' style='width:125px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . json_encode($value2['meta_data']) . "</p></td>";
      					else $tabledetails .= "<td id='metadata' name='metadata' style='width:125px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'></p></td>";
      					$tabledetails .= "<td id='subaccount' name='subaccount' style='width:50px; padding-left: 5px;'><p style='color:black; font-family: Helvetica, Arial;font-size:12px;'>" . $value2['subaccount_id'] . "</p></td>";
      					$tabledetails .= "<td hidden id='raw' name='raw'>" . json_encode($value2) . "</td>";
      					$tabledetails .= "</tr>";
      				}
      			}	
			}
			$tabledetails .= "</tbody></table>";
		}
		else 
		{
			//echo "No data found";
		}
	}	
}
curl_close($curl);

// The following asort and selects are not used today but I decided to leave the code in in case someone want's to leverage this approach
/*asort($fromArray, SORT_NATURAL | SORT_FLAG_CASE);
asort($campaignArray, SORT_NATURAL | SORT_FLAG_CASE);
asort($subjectArray, SORT_NATURAL | SORT_FLAG_CASE);
asort($typeArray, SORT_NATURAL | SORT_FLAG_CASE);
asort($timeArray, SORT_NATURAL | SORT_FLAG_CASE);
		
$fromSearch = "<option value=\"From Address Not Entered\">Select a From Address</option>";
$campaignidSearch = "<option value=\"Campaign Not Entered\">Select a Campaign</option>";
$subjectSearch = "<option value=\"Subject Not Entered\">Select a Subject</option>";
$typeSearch = "<option value=\"Type Not Entered\">Select a Type</option>";
$timeSearch = "<option value=\"Time Not Entered\">Select a Time/Date</option>";

foreach ($fromArray as $row => $values) 
{
    $fromSearch .= '<option value="' . $values . '">' . $values . '</option>';
}
foreach ($campaignArray as $row => $values) 
{
    $campaignSearch .= '<option value="' . $values . '">' . $values . '</option>';
}
foreach ($subjectArray as $row => $values) 
{
    $subjectSearch .= '<option value="' . $values . '">' . $values . '</option>';
}
foreach ($typeArray as $row => $values) 
{
    $typeSearch .= '<option value="' . $values . '">' . $values . '</option>';
}
foreach ($timeArray as $row => $values) 
{
    $timeSearch .= '<option value="' . $values . '">' . $values . '</option>';
}
*/

$json_array=array(
	'details' => $tabledetails,
//	'fromsearch' => $fromSearch,
//	'campaignsearch' => $campaignidSearch,
//	'subjectsearch' => $subjectSearch,
//	'typesearch' => $typeSearch,
//	'timesearch' => $timeSearch,
	'error' => $responses,
	'url' => $url,
);
$json_encoded_string = json_encode ($json_array);
echo $json_encoded_string;
					
}
?>
