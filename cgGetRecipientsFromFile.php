<?php
//******************************************************************************

ini_set('memory_limit', '512M');
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set("auto_detect_line_endings", true);
include 'phpResources/getRecipientCountFromUploadedFile.php';
include 'phpResources/retrieveRecipientRecordsFromUploadedFile.php';


$parametersFile = "config/Parameters.ini";
$paramonly_array = parse_ini_file( $parametersFile, true );
$cat = "local";
$uploadDir = $paramonly_array[$cat]["UploadDir"];
 
$numberofrecipients = $_POST["numberofrecipients"];
$file = $_POST["file"];
$uploadedFile = $uploadDir . "/" . $file;
$count = getRecipientCount ($uploadedFile);
 
$recipientData = getRecipientsFromFile (1, $numberofrecipients, $uploadedFile);

$recipientDataDecoded = json_decode ($recipientData, true);

$csvrecipients = $recipientDataDecoded["csvoutput"];
$jsonrecipients = $recipientDataDecoded["jsonoutput"];

$json_array=array(
	'recipientcount' => $count,
	'csvlist' => $csvrecipients,
	'jsonlist' => $jsonrecipients,
);

$json_encoded_string = json_encode ($json_array);
echo $json_encoded_string;

?>
