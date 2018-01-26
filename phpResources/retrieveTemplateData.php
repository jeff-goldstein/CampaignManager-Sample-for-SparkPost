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

File: tmRetriveTemplate.php
Purpose:  This application gets all data there is for a template.  It starts out by getting the latest version
of the template, then identifying if it's a draft or published version.  After that, it will attempt to get the
other version. 
*/
		
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$template   = $_POST["template"];

$curl     = curl_init();
$url      = $apiroot. "/templates/" . $template;

// Get the latest version of the template - we don't know if it's going to be a draft or published version yet
//
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
$encodedResponse = json_decode($response, true);
$errorFromAPI    = $encodedResponse["errors"];
$publishedornot = $encodedResponse["results"]["published"];
$haspublishedornot = $encodedResponse["results"]["has_published"];
$hasdraftornot = $encodedResponse["results"]["has_draft"];
$err = curl_error($curl);
curl_close($curl);

$fromEmail = $encodedResponse["results"]["content"]["from"]["email"];
$fromEmailPieces = explode ("@", $fromEmail);

if ($publishedornot === true) // This is a published version
{
	$publishedHTML = $encodedResponse["results"]["content"]["html"];
	$publishedText = $encodedResponse["results"]["content"]["text"];
	$publishedtemplateFromName =  $encodedResponse["results"]["content"]["from"]["name"];
	$publishedtemplateSubDomainFirstHalfName =  $fromEmailPieces[0];
	$publishedtemplateSubDomainSecondHalf =  $fromEmailPieces[1];
	$publishedtemplateSubject =  $encodedResponse["results"]["content"]["subject"];
	$publishedtemplateReplyto =  $encodedResponse["results"]["content"]["replyto"];
	$publishedtemplateDescription =  $encodedResponse["results"]["description"];
	$publishedtemplateOpenTracking =  $encodedResponse["results"]["options"]["open_tracking"];
	$publishedtemplateClickTracking =  $encodedResponse["results"]["options"]["click_tracking"];
}
else // This is a draft version
{
	$draftHTML = $encodedResponse["results"]["content"]["html"];
	$draftText = $encodedResponse["results"]["content"]["text"];
	$drafttemplateFromName =  $encodedResponse["results"]["content"]["from"]["name"];
	$drafttemplateSubDomainFirstHalfName =  $fromEmailPieces[0];
	$drafttemplateSubDomainSecondHalf =  $fromEmailPieces[1];
	$drafttemplateSubject =  $encodedResponse["results"]["content"]["subject"];
	$drafttemplateReplyto =  $encodedResponse["results"]["content"]["replyto"];
	$drafttemplateDescription =  $encodedResponse["results"]["description"];
	$drafttemplateOpenTracking =  $encodedResponse["results"]["options"]["open_tracking"];
	$drafttemplateClickTracking =  $encodedResponse["results"]["options"]["click_tracking"];
}

//
// if the first call retrieved a Published version and there is a Draft version
// notice, that this if statement is exclusive of the next if statement.  Only one of them CAN be true.
//
if (($publishedornot === true) && ($hasdraftornot === true))  
{
	$url .= "?draft=true";
	$curl     = curl_init();
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
	$encodedResponse = json_decode($response, true);
	$draftHTML = $encodedResponse["results"]["content"]["html"];
	$draftText = $encodedResponse["results"]["content"]["text"];
	$fromEmail = $encodedResponse["results"]["content"]["from"]["email"];
	$fromEmailPieces = explode ("@", $fromEmail);
	
	$drafttemplateFromName =  $encodedResponse["results"]["content"]["from"]["name"];
	$drafttemplateSubDomainFirstHalfName =  $fromEmailPieces[0];
	$drafttemplateSubDomainSecondHalf =  $fromEmailPieces[1];
	$drafttemplateSubject =  $encodedResponse["results"]["content"]["subject"];
	$drafttemplateReplyto =  $encodedResponse["results"]["content"]["replyto"];
	$drafttemplateDescription =  $encodedResponse["results"]["description"];
	$drafttemplateOpenTracking =  $encodedResponse["results"]["options"]["open_tracking"];
	$drafttemplateClickTracking =  $encodedResponse["results"]["options"]["click_tracking"];	
	$err = curl_error($curl);
	curl_close($curl);
}

//
// if the last call retrieved a Draft version and there is a Published version 
// notice, that this if statement is exclusive of the previous if statement.  Only one of them CAN be true.
//
if (($publishedornot === false) && ($haspublishedornot === true)) 
{
	$url .= "?draft=false";
	$curl     = curl_init();
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
	$encodedResponse = json_decode($response, true);
	$publishedHTML = $encodedResponse["results"]["content"]["html"];
	$publishedText = $encodedResponse["results"]["content"]["text"];
	$fromEmail = $encodedResponse["results"]["content"]["from"]["email"];
	$fromEmailPieces = explode ("@", $fromEmail);	
	
	$publishedtemplateFromName =  $encodedResponse["results"]["content"]["from"]["name"];
	$publishedtemplateSubDomainFirstHalfName =  $fromEmailPieces[0];
	$publishedtemplateSubDomainSecondHalf =  $fromEmailPieces[1];
	$publishedtemplateSubject =  $encodedResponse["results"]["content"]["subject"];
	$publishedtemplateReplyto =  $encodedResponse["results"]["content"]["replyto"];
	$publishedtemplateDescription =  $encodedResponse["results"]["description"];
	$publishedtemplateOpenTracking =  $encodedResponse["results"]["options"]["open_tracking"];
	$publishedtemplateClickTracking =  $encodedResponse["results"]["options"]["click_tracking"];
	$err = curl_error($curl);
	curl_close($curl);
}

//
// Package it all up and send back
//
$json_array=array(
'publishedtemplateHTML' => $publishedHTML,
'publishedtemplateText' => $publishedText,
'drafttemplateHTML' => $draftHTML,
'drafttemplateText' => $draftText,
'templateName' =>  $encodedResponse["results"]["name"],
'templateID' =>  $encodedResponse["results"]["id"],
'templateSubaccount' =>  $encodedResponse["results"]["subaccount_id"],
'drafttemplateFromName' =>  $drafttemplateFromName,
'drafttemplateSubDomainFirstHalfName' =>  $drafttemplateSubDomainFirstHalfName,
'drafttemplateSubDomainSecondHalf' =>  $drafttemplateSubDomainSecondHalf,
'drafttemplateSubject' =>  $drafttemplateSubject,
'drafttemplateReplyto' =>  $drafttemplateReplyto,
'drafttemplateDescription' =>  $drafttemplateDescription,
'drafttemplateOpenTracking' =>  $drafttemplateOpenTracking,
'drafttemplateClickTracking' =>  $drafttemplateClickTracking,
'publishedtemplateFromName' =>  $publishedtemplateFromName,
'publishedtemplateSubDomainFirstHalfName' =>  $publishedtemplateSubDomainFirstHalfName,
'publishedtemplateSubDomainSecondHalf' =>  $publishedtemplateSubDomainSecondHalf,
'publishedtemplateSubject' =>  $publishedtemplateSubject,
'publishedtemplateReplyto' =>  $publishedtemplateReplyto,
'publishedtemplateDescription' =>  $publishedtemplateDescription,
'publishedtemplateOpenTracking' =>  $publishedtemplateOpenTracking,
'publishedtemplateClickTracking' =>  $publishedtemplateClickTracking,
);


$json_encoded_string = json_encode ($json_array);
echo $json_encoded_string;
?>
