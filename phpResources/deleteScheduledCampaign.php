<?php
{

$CampaignArray = $_POST["DeleteArray"];
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$campaigns = explode(',', $_POST['CampaignArray']);

$deleted = "The following were deleted: "; 
foreach ($CampaignArray as $key => $value)
{
	if ($value)
	{	
		$url = $apiroot . "/transmissions/" . $value;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "$url",
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
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		$deleted .= $value . " ";
		if ($err) 
		{
  			$deleted .= "cURL Error #:" . $err;
		}
	}
}
echo $deleted;

}
?>