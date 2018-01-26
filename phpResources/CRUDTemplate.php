<?php 
{
/* Copyright 2016 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. 

File: tmCRUDTemplate.php
Purpose: This program is used to create, update and delete the template.
The json body needs to be changed at times to reflect the when the template is saved, created as a new template or being published.
*/

//
// Get values sent by tmMain.php
//
$responses = null;
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$open = $_POST["opentracking"];
$click = $_POST["clicktracking"];
$fromemailname = $_POST["fromemailname"];
$fromemaildomain = $_POST["fromemaildomain"];
$friendlyfrom = $_POST["fromname"];
$subject = $_POST["subject"];
$replyto = $_POST["replyto"];
$description = $_POST["description"];
$textcode = json_encode($_POST["textcode"]);
$htmlcode = json_encode($_POST["htmlcode"]);
$flag = trim(json_encode($_POST["flag"]), '"');
$templatelistvalue = trim(json_encode($_POST["templatelistvalue"]), '"');
$templatelisttext = trim(json_encode($_POST["templatelisttext"]), '"');
$templatenewname = trim(json_encode($_POST["templatenewname"]), '"');

$templatebody = '"options": { "open_tracking" : ';
if ($open == "true") {$templatebody .= 'true, "click_tracking" : ';} else {$templatebody .= 'false, "click_tracking" : ';}
if ($click == "true") {$templatebody .= 'true},';} else {$templatebody .= 'false},';}
$templatebody .= '"content" : {"from" : {"name": "' . $friendlyfrom . '",';
$templatebody .= '"email" : "' . $fromemailname . '@' . $fromemaildomain . '"},';
$templatebody .= '"subject" : "' . $subject . '",';
$templatebody .= '"replyto" : "' . $replyto . '",';
$templatebody .= '"text" : ' . $textcode . ',';
$templatebody .= '"html" : ' . $htmlcode . '},';
$templatebody .= '"description" : "' . $description . '"}';

$templatebodydraftupdate = '{"name" : "' . $templatelisttext . '", "id" : "' . $templatelistvalue . '", "published" : false, ' . $templatebody;
$templatebodypublishupdate = '{"name" : "' . $templatelisttext . '", "id" : "' . $templatelistvalue . '", "published" : true, ' . $templatebody;

// Removing the 'id' field so the system can default and make one.  They can only have alpha, numberic, underscores, periods and hyphens
// It's easier to simply let the system generate a long unique numberic id than to make sure the ID reflects the current rule set
// We only need to do this during the 'create' step.  All other times, we use the id which is hidden from the user.  Through the Template Manager
// application, users are only shown the 'name' field.
//
$templatebodydraft4create = '{"name" : "' . $templatenewname . '", "published" : false, ' . $templatebody;

// Development code - see what was just created
//file_put_contents("tmsavetemplatedraft.log", $templatebodydraft, LOCK_EX);
//file_put_contents("tmsavetemplatepublish.log", $templatebodypublish, LOCK_EX);

function checkTemplate ($apikey, $url, $draftorpublish, &$responses, $template)
{
	if ($draftorpublish == "draft") $url .= "/templates/" . $template . "?draft=true"; else $url .= "/templates/" . $template . "?draft=false";
	$curl = curl_init();
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
    "content-type: application/json",
  	),));
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	
	if ($err)
	{
  		 $responses .= "<br><br>Check Template Exisitence: cURL Error #:" . $err;
	} 
	else 
	{	
		if ($httpCode == 200)
		{
			return true;
		}
		else
		{
  			return false;
  		}
	}
}

function deleteTemplate ($apikey, $url, $templateID, $templatelisttext, &$responses)
{
	$curl = curl_init();
	$url .= "/templates/" . $templateID;
	curl_setopt_array($curl, array(
  	CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "DELETE",
  	CURLOPT_HTTPHEADER => array(
    "authorization: $apikey",
    "cache-control: no-cache",
    "content-type: application/json",
  	),));
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	if ($err)
	{
  		 $responses .= "<br><br>Delete Template: cURL Error #:" . $err;
	} 
	else 
	{
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($httpCode != 200)
		{
			$responses .= "HTTP Error from server attempting to Delete Template " . $templatelisttext . ": " . $httpCode . "<br>" . $response;
		}
		else
		{
  			$responses .= "<br>Delete Template: " . $templatelisttext . " --> successful";
  		}
	}
	curl_close($curl);
}


function createtemplate ($apikey, $url, $templatebody, $templatenewname, &$templateID, &$responses)
{
	$curl = curl_init();
	$url .= "/templates";
	curl_setopt_array($curl, array(
  	CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "POST",
  	CURLOPT_POSTFIELDS => "$templatebody",
  	CURLOPT_HTTPHEADER => array(
    "authorization: $apikey",
    "cache-control: no-cache",
    "content-type: application/json",
  	),));
	
	$response = curl_exec($curl);
	$err = curl_error($curl);	 

	if ($err) 
	{
  		$responses .= "<br><br>Create Draft: cURL Error #:" . $err;
	} 
	else 
	{
  		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($httpCode != 200)
		{
			$responses .= "HTTP Error from server during Template Create Process: " . $httpCode . "<br>" . $response;
		}
		else
		{
  			$encodedResponse = json_decode($response, true);
  			$templateID = $encodedResponse["results"]["id"];
  			$responses .= "<br>Create New Draft: " . $templatenewname . "--> successful";
  		}
	}
	curl_close($curl);
}


function updatetemplate ($apikey, $url, $templatepostbody, $draftorpublish, $templateID, $templatelisttext, &$responses)
{
	$curl = curl_init();
	$url .= "/templates/" . $templateID;
	curl_setopt_array($curl, array(
  	CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "PUT",
  	CURLOPT_POSTFIELDS => "$templatepostbody",
  	CURLOPT_HTTPHEADER => array(
    "authorization: $apikey",
    "cache-control: no-cache",
    "content-type: application/json",
  	),));
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	if ($err) 
	{
  		$responses .= "<br><br>Save Draft: cURL Error #:" . $err;
	} 
	else 
	{
  		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($httpCode != 200)
		{
			$responses .= "HTTP Error from server while saving " .$draftorpublish . ": " . $httpCode . "<br>" . $response;
		}
		else
		{
  			if ($draftorpublish === "draft")
  			{
  				$responses .= "<br>Saving: " . $templatelisttext . " --> successful";
  			}
  			else
  			{
  				$responses .= "<br>Publishing: " . $templatelisttext . " --> successful";
  			}
  		}
	}
	curl_close($curl);
}

// 
// Main logic
// If Delete
// else check the new name field, if there is content, then we are creating a new template
//		after creating the new template, if a published action is requested, do so
// if the new name field is blank, update a current template and publish if requested
//
if ($flag == "delete")
{
	deleteTemplate($apikey, $apiroot, $templatelistvalue, $templatelisttext, $responses);
}
else
{
	if (strlen($templatenewname) > 0)
	{
		$templateID = NULL;
		createtemplate ($apikey, $apiroot, $templatebodydraft4create, $templatenewname, $templateID, $responses);
		$templatebodypublishupdate = '{"id" : "' . $templateID . '", "published" : true, ' . $templatebody;
		if ($flag == "publish")
		{
			updatetemplate ($apikey, $apiroot, $templatebodypublishupdate, "published", $templateID, $templatenewname, $responses );
		}
	}
	else
	{
		updatetemplate ($apikey, $apiroot, $templatebodydraftupdate, "draft", $templatelistvalue, $templatelisttext, $responses);
		if ($flag == "publish")
		{
			updatetemplate ($apikey, $apiroot, $templatebodypublishupdate, "published", $templatelistvalue, $templatelisttext, $responses);
		}
	}		
}
echo $responses;
} 
?>
