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

File: BuildPreviewWithEnteredTemplateCode.php
Purpose: Show what the template & data combination may look like. Input includes
the template data, test data and the sending domain information.

*/

function isJson($string) 
{
	global $json_check_result;
	json_decode($string);
	switch (json_last_error()) 
	{
        case JSON_ERROR_NONE:
            $json_check_result = ' No errors';
        break;
        case JSON_ERROR_DEPTH:
            $json_check_result =  ' Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $json_check_result = ' Underflow or the modes mismatch, check brackets?';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $json_check_result = ' Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $json_check_result = ' Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $json_check_result = ' Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $json_check_result = ' Unknown error';
        break;
	}
	return (json_last_error() == JSON_ERROR_NONE);
}


//
// Get passed parameters
//
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$enteredRecipientData	= $_POST["entered"];				// List of comma separated email addresses
$globalsub			= $_POST["globalsub"];					// Global substitution data
$templatecode		= json_encode($_POST["templatecode"]);	// This may be Draft or Published, HTML or Text
$fromemaildomain	= $_POST["fromemaildomain"];
$localpart			= $_POST["localpart"];

$rec_sub = " ";  //set to a default where I know nothing was entered
//
// Not trusting the user, lets validate the recipient data as valid JSON.  This only validates against being valid Json, it does
// not validate against being in the proper structure for SparkPost.
// The global data entry is NOT checked because it's not expected to be in a perfect json structure like the recipeint data is.
//
//  If we have valid json, grab the first entry because the preview can only use data from one recipient
//
if (strlen($enteredRecipientData) > 1)	
{
	$checkjson = isJson ($enteredRecipientData);
	if ($checkjson != 1) 
	{
		echo "<h4>JSON structure error on entered Recipient data: " . $json_check_result . "</h4>";
		echo "<br><br>";
		echo "<h5>This is a great site for validating your JSON input: <a href=\"http://jsonlint.com/#/\" target=\"_blank\">JsonLint</a></h5>";
		return;
	}
	else
	{
		//get first record of manually entered recipient data
		$makeArray = json_decode($enteredRecipientData, true);
		$one_entry = $makeArray['recipients'][0]['substitution_data'];
		$rec_sub  = json_encode($one_entry);
	}
}

//
// Now let's see what data we actually have, and create one substitution group to use in the preview
//
if ((strlen($globalsub) > 1) && (strlen($rec_sub) > 4)) //we have both global and personal substitution data
{
	// We will concatenate recipient data after the global data into one substitution_data object
	// We need to remove/change some quotes, commas, brackets to do this
	if (substr($globalsub, -1) == ",") $globalsub = substr($globalsub, 0, -1); //remove trailing comma user entered
	$globalsub = trim ($globalsub); //remove any white space
	if (substr($globalsub, -1) == "}") $globalsub = substr($globalsub, 0, -1) . ","; //change closing bracket to comma
	$rec_sub = substr($rec_sub, 1);  //remove opening bracket 
	$subEntry = "{" . $globalsub . $rec_sub;
}
if ((strlen($globalsub) > 1) && (strlen($rec_sub) < 4)) //global substitution only
{
	if (substr($globalsub, -1) == ",") $globalsub = substr($globalsub, 0, -1); //remove trailing comma user entered
	$globalsub = trim ($globalsub); //remove any white space
	$subEntry = "{" . $globalsub;
}
if ((strlen($globalsub) < 1) && (strlen($rec_sub) > 4)) //personal substitution only
{
	$subEntry = '{"substitution_data":' . $rec_sub;
}
// Preview must have something in the substitution array, so create an empty array object for use later
if ((strlen($globalsub) < 1) && (strlen($rec_sub) < 4))
{
	$rec_sub = json_decode ("{}");
	$subEntry = '{"substitution_data":' . json_encode($rec_sub);
}

//some of these can get big, so let's clean them out
unset($enteredRecipientData); unset($rec_sub); unset($makeArray); unset($one_entry);


$buildtemppayload =  $subEntry . ',"content":{"from" : "sandbox@sparkpostbox.com" ,"subject":"nada here","html": ' . $templatecode . '}}';

$curl     = curl_init();
$url = $apiroot . "/utils/content-previewer";
curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $buildtemppayload,
    CURLOPT_HTTPHEADER => array(
        "authorization: $apikey",
        "cache-control: no-cache",
        "content-type: application/json"
    )));
    
$response = curl_exec($curl);
$encodedResponse = json_decode($response, true);
$errorFromAPI    = $encodedResponse["errors"];
$preview = $encodedResponse["results"]["html"];
$err     = curl_error($curl);
    
curl_close($curl);
if ($err) 
{
    $preview = "cURL Error #:" . $err;
    echo $preview;
} 
else 
{
    if ($errorFromAPI != null) 
    {
        $error_out = $response1 . "<h3>Matching Problem between template and data</h3>";
        $error_string .= var_export($errorFromAPI, true);
        $error_out .= "<pre>" . $error_string . "</pre>";
        $error_out .= "<br><br>";
        echo $error_out;
    } 
    else 
    {
        echo $preview;
    }
}
}
?>
