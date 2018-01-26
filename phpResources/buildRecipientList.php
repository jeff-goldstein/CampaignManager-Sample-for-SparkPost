<?php
{
//
// Get The Stored Recipient-Lists Templates from the Account
//
function getRecipientListFromServer($apikey, $apiroot)
{
    $url = $apiroot . "/recipient-lists";
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array("authorization: $apikey","cache-control: no-cache","content-type: application/json")
    ));

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);

    if ($err) 
    {
        echo "cURL Error #:" . $err;
    }

    // Convert JSON string to Array
    return $recipientsArray = json_decode($response, true);
}


//
// Build the dropdown Selector from the Template API call
//
function buildRecipientList ($apikey, $apiroot)
{
	$recipientArray = getRecipientListFromServer($apikey, $apiroot);
	echo "<option selected=\"selected\" value=\"Recipient List Not Entered\">Select a Recipient</option>";
	foreach ($recipientArray as $key => $value) 
	{
		foreach ($value as $key2 => $value2) 
		{
	        foreach ($value2 as $key3 => $value3) 
	        {
	            if ($key3 == "id") echo '<option value="' . $value3 . '">' . $value2["name"] . " (" . $value2["total_accepted_recipients"] . ' Recipients)</option>';
	        }
	    }
	}
}

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
buildRecipientList ($apikey, $apiroot);
}?>