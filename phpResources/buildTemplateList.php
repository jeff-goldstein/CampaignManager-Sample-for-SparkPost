<?php
{
//
// Get 'Published Only' if being called by the campaign manager module, but get all if called by the template manager module
//
function getTemplateListFromServer($apikey, $apiroot, $cgORtm)
{
    if ($cgORtm == "cg") $url = $apiroot . "/templates/?draft=false"; else $url = $apiroot . "/templates";
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
    
    return $templateArray = json_decode($response, true);
}

function checkAccountLevel($apikey, $apiroot, $templateArray)
{
    $accountLevel = 0;
    foreach ($templateArray as $key => $value) 
	{
    	foreach ($value as $key2 => $value2) 
    	{
        	foreach ($value2 as $key3 => $value3) 
        	{	
        		if ($key3 == "subaccount_id" && ($accountLevel <= 1))
        		{
        			$accountLevel = callTemplate($apikey, $apiroot, $value2["id"]);
        			if ($accountLevel <= 1) return $accountLevel;
        		}
        	}
    	}
    }
    return $accountLevel;
}

function callTemplate ($apikey, $apiroot, $template)
{
    	$url = $apiroot . "/templates/" . $template;
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
    	//echo "response on this so called error: " . $response;
    	$err      = curl_error($curl);
    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    	//echo "httpcode: " . $httpcode;
    	curl_close($curl);
    	if ($err) 
    	{
        	return 1;
    	}
    	if ($httpcode != 200)
    	{
    		return 2;  // Can't get this template, this means that we are using a Master Account API Key and don't want to list sub-account tagged templates
    	}
    	else
    	{
    		return 3;  // We can read the template, so we are a sub-account key and should display all templates found in the list
    	}
}
                        
//
// Build the dropdown Selector from the Template API call
//


function buildTemplateList ($apikey, $apiroot, $cgORtm)
{
	$sortarray = array();
	$accountLevel = 0; 
	$templateArray = gettemplateListFromServer($apikey, $apiroot, $cgORtm);
	$accountLevel = checkAccountLevel ($apikey, $apiroot, $templateArray);
	echo "<option value=\"\">Select a Template</option>";
	
	switch ($cgORtm )
	{
		case "cg":
			foreach ($templateArray as $key => $value) 
			{
	    		foreach ($value as $key2 => $value2) 
    			{
        			foreach ($value2 as $key3 => $value3) 
        			{	
        				if ($key3 == "id")
        				{
	        				$subaccount = $value2["subaccount_id"];
    	    				if (!$subaccount) 
        					{
        						$sortarray[$value2["id"]] = $value2["name"];
        					}
        					else
        					{
	        					if ($accountLevel == 3)
    	    					{
        							$sortarray[$value2["id"]] = $value2["name"];
        						}
        					}
        				}
					}
    	    	}
    		}
    		break;
    	case "tm":
			foreach ($templateArray as $key => $value) 
			{
    			foreach ($value as $key2 => $value2) 
    			{
        			foreach ($value2 as $key3 => $value3) 
        			{	
        				if ($key3 == "id")
	        			{
    	    				$subaccount = $value2["subaccount_id"];
        					if (!$subaccount) 
        					{
        						if ($accountLevel == 2)
        							$sortarray[$value2["id"]] = $value2["name"];
        						else
        							$sortarray[$value2["id"]] = $value2["name"] . " ( shared - read only ) ";
        					}
        					else
	        				{
    	    					if ($accountLevel == 3)
        						{
        							$sortarray[$value2["id"]] = $value2["name"];
        						}
        					}
	        			}
					}
        		}
    		}
    		break;
    }
    asort($sortarray, SORT_NATURAL | SORT_FLAG_CASE);
	foreach ($sortarray as $row => $values) 
	{
    	echo '<option value="' . $row . '">' . $values . '</option>';
	}
}

$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$cgORtm = $_POST["cgORtm"];					//cg means the campaign manager and tm is for the template manager
buildTemplateList ($apikey, $apiroot, $cgORtm);


}?>