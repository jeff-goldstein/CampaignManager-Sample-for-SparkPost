<?php
{
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

File: sendTestEmails.php
Purpose: This program sends/creates the actual test emails by calling the 'Transmission' API.
		 It gets input from tmMain after the user request test emails to be sent.
		 Server output is gathered an passed back to tmMain for display. 
*/

//
// Get values entered by user
//
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$recipients = $_POST["recipients"];
$open = $_POST["opentracking"];
$click = $_POST["clicktracking"];
$fromemailname = $_POST["fromemailname"];
$fromemaildomain = $_POST["fromemaildomain"];
$friendlyfrom = $_POST["fromname"];
$subject = $_POST["subject"];
$replyto = $_POST["replyto"];
$jsonLoad  = $_POST["jsonLoad"];
$globalsub  = $_POST["globalsub"];
$textcode = json_encode($_POST["textcode"]);
$htmlcode = json_encode($_POST["htmlcode"]);
$emailaddresses = json_encode($_POST["emailaddresses"]);

function sendTest($emailaddress)
{
	$payload = buildPayload($emailaddress);
	$serverResponse = sendTransmission($payload);
	return $serverResponse;
}

function buildPayload($emailaddress)
{
	global $template, $recipients, $friendlyfrom, $fromemailname, $fromemaildomain, $subject, $replyto, $textcode, $htmlcode, $campaign, $open, $click; 
	global $meta1, $data1, $meta2, $data2, $meta3, $data3, $meta4, $data4, $meta5, $data5, $jsonLoad, $globalsub, $recipientCount;

	//
	//Build the payload for the Transmission API call
	//
	
	$transmissionLoad = '{"options": { "open_tracking" :';
	if ($open == "T") $transmissionLoad .= 'true, "click_tracking" : '; else $transmissionLoad .= 'false, "click_tracking" : ';
	if ($click == "T") $transmissionLoad .= 'true},'; else $transmissionLoad .= 'false},';
	$transmissionLoad .= '"content" : {"from" : {"name": "' . $friendlyfrom . '",';
	$transmissionLoad .= '"email" : "' . $fromemailname . '@' . $fromemaildomain . '"},';
	$transmissionLoad .= '"subject" : "' . $subject . '",';
	$transmissionLoad .= '"replyto" : "' . $replyto . '",';
	$transmissionLoad .= '"text" : ' . $textcode . ',';
	$transmissionLoad .= '"html" : ' . $htmlcode . '},';
	$transmissionLoad .= '"campaign_id" : "TestSend",';
	if ($returnpath != "") $transmissionLoad .= '"return_path" : "' . $returnpath . '", ';
	$transmissionLoad .= '"description" : "' . $recipientCount . ' recipients targeted",';
	if (($meta1 != "") or ($meta2 != "") or ($meta3 != "") or ($meta4 != "") or ($meta5 != ""))
	{
   		$transmissionLoad .= '"metadata" : {';
   		if ($meta1 != "") {$transmissionLoad .= '"' . $meta1 . '":"' . $data1 . '",';}
   		if ($meta2 != "") {$transmissionLoad .= '"' . $meta2 . '":"' . $data2 . '",';}
   		if ($meta3 != "") {$transmissionLoad .= '"' . $meta3 . '":"' . $data3 . '",';}
   		if ($meta4 != "") {$transmissionLoad .= '"' . $meta4 . '":"' . $data4 . '",';}
   		if ($meta5 != "") {$transmissionLoad .= '"' . $meta5 . '":"' . $data5 . '"';}
   		$transmissionLoad = trim($transmissionLoad, ",");
   		$transmissionLoad .= "},";
	}
	if (strlen($globalsub) > 2) $transmissionLoad .= $globalsub . ","; //if there is more than 2 characters there is something..hey put in any number :-)
	if ($recipients == "json")
	{
		$jsonLoad = substr($jsonLoad, 1);
		$jsonLoad = substr($jsonLoad, 0, -1);
		$transmissionLoad .= $jsonLoad;
	}
	else
	{
		$transmissionLoad .= '"recipients" : [{"address":{"email":"' . $emailaddress . '"}}]';
	}
	$transmissionLoad .= "}";
	//file_put_contents("tmtestemail.log", $transmissionLoad, LOCK_EX);
	return $transmissionLoad;
	
}

function sendTransmission($transmissionLoad)
{
	//
	// Schedule/Send the campaign
	//
	global $apikey, $apiroot;
	
	$curl = curl_init();
	$url = $apiroot . "/transmissions";
	curl_setopt_array($curl, array(
  	CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "POST",
  	CURLOPT_POSTFIELDS => "$transmissionLoad",
  	CURLOPT_HTTPHEADER => array(
    "authorization: $apikey",
    "cache-control: no-cache",
    "content-type: application/json",
  	),));
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) 
	{
  		echo "cURL Error #:" . $err;
	} 
	else 
	{
  		// nada
	}
	
	return $response;
}

if ($recipients == "list")
{
	$TestRecipientList = explode(',', $emailaddresses);
	foreach ($TestRecipientList as $emailaddress)
	{
		$emailaddress = str_replace('"', "", $emailaddress);
		$response = sendTest($emailaddress);
		echo "<style>body{font-family: courier;color: black;font-size: 8pt;}</style>Email: " . $emailaddress . $response . "<br><br>";
	}
}
else
{
	$response = sendTest("");
	echo "<style>body{font-family: courier;color: black;font-size: 8pt;}</style>" . $response;
}
}
?>
