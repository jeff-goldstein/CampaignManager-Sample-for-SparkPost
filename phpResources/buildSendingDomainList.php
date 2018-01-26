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

File: buildSendingDomainList.php
Purpose:  Build the sending domain list using both the users API key and a master key for shared domains 
*/



//
// Get The 'Domains' from the Account
//
function getDomainListFromServer($apikey, $apiroot)
{
    $url = $apiroot . "/sending-domains";
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
            "content-type: application/json"
        )
    ));

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    return $domainArray = json_decode($response, true);
}


//
// Build the dropdown Selector from the Template API call
//
function buildDomainList ($apikey, $apiroot, $masterkey)
{
	//There are two api keys, one for the sub-account and one for the master account that might be sharing sending domains
	//
	$checkarray = array();
	$domainArray = getDomainListFromServer($apikey, $apiroot);
	echo "<option selected=\"selected\" value=\"\">Select a Domain</option>";
	foreach ($domainArray as $key => $value) 
	{
		foreach ($value as $key2 => $value2) 
		{
			if ($value2["status"]["compliance_status"]=="valid")
			{
				echo '<option value="' . $value2["domain"] . '">' . $value2["domain"] . '</option>';
				array_push($checkarray, $value2["domain"]);
			}
		}
	}
	if ($key != $masterkey)
	{
		$domainArray = getDomainListFromServer($masterkey, $apiroot);
		foreach ($domainArray as $key => $value) 
		{
			foreach ($value as $key2 => $value2) 
			{
				if (($value2["status"]["compliance_status"]=="valid") && ($value2["shared_with_subaccounts"]==true))
				{
					$found = array_search($value2["domain"], $checkarray);
					if (!$found) echo '<option value="' . $value2["domain"] . '">' . $value2["domain"] . ' (shared via master account)</option>';
				}
			}
		}
	}
}


$parametersFile = "../config/Parameters.ini";
$paramonly_array = parse_ini_file( $parametersFile, true );
$cat = "local";
$masterapikey = $paramonly_array[$cat]["API"];

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
buildDomainList ($apikey, $apiroot, $masterapikey);	

}?>