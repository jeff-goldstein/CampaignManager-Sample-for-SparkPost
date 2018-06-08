<!-- Copyright 2016 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. -->
<!DOCTYPE html>
<html><head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Campaign Generator for SparkPost</title>
<link rel="shortcut icon" href="http://bit.ly/2elb0Hw">
<link rel="stylesheet" type="text/css" href="css/cgCommonFormatting.css">
</head>

<body id="bkgnd">
<?php
//
// Get values entered by user
//
include 'phpResources/getRecipientCountFromUploadedFile.php';
include 'phpResources/retrieveRecipientRecordsFromUploadedFile.php';
$apikey = $_COOKIE["sparkpostkey"];
$apiroot = $_COOKIE["sparkpostapiroot"];
$recipients = $_POST["Recipients"];
$now = $_POST["now"];
$date = $_POST["date"];
$hour = $_POST["hour"];
$minutes = $_POST["minutes"];
$tz = $_POST["tz"];
$campaign = $_POST["campaign"];
$template = $_POST["Template"];  //this is the hidden template id that the user doesn't typically see
$open = $_POST["open"];
$click = $_POST["click"];
$email = $_POST["email"];
$meta1 = trim($_POST["meta1"], " ");
$data1 = trim($_POST["data1"], " ");
$meta2 = trim($_POST["meta2"], " ");
$data2 = trim($_POST["data2"], " ");
$meta3 = trim($_POST["meta3"], " ");
$data3 = trim($_POST["data3"], " ");
$meta4 = trim($_POST["meta4"], " ");
$data4 = trim($_POST["data4"], " ");
$ipPool = trim($_POST["ipPool"], " ");
$jsonLoad  = $_POST["json"];
$globalsub = $_POST["globalsub"];
$recipientCount = $_POST["recipientCount"];
$returnpath = $_POST["returnpath"];
$domain = $_POST["domain"];
$whichrecipients = $_POST["whichrecipients"];
$recipientUploadFile = $_POST["txtUploadFile"];
$textofCurrentSelectedTemplate = $_POST["textofCurrentSelectedTemplate"];  // This is the customer facing template name
$recipientCount = $_POST["recipientCount"];  // number of recipients in file
$parametersFile = "config/Parameters.ini";
$paramonly_array = parse_ini_file( $parametersFile, true );
$cat = "local";
$BatchSize = $paramonly_array[$cat]["BatchSize"];
$UploadDir = $paramonly_array[$cat]["UploadDir"];
$uploadedFile = $UploadDir . "/" . $recipientUploadFile;
$batches = 1;

if ($returnpath != "" && $domain != "")
{
	$returnpath .= "@" . $domain;
}

$globalsubEncoded = json_encode($globalsub);
$globalsubEncoded = substr($globalsubEncoded, 1);
$globalsubEncoded = substr($globalsubEncoded, 0, -1);
if (substr($globalsubEncoded, -1) == ",") $globalsubEncoded = substr($globalsubEncoded, 0, -1); //remove trailing comma user entered

$jsonLoadEncoded = json_encode($jsonLoad);
$jsonLoadEncoded = substr($jsonLoadEncoded, 1);
$jsonLoadEncoded = substr($jsonLoadEncoded, 0, -1);

if ($BatchSize < $recipientCount)
{
	$remainder = $recipientCount % $BatchSize;
	if ($remainder > 0 ) $batches = floor($recipientCount / $BatchSize) + 1; else $batches = $recipientCount/$BatchSize;
}
?>

<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="cgBuildCampaign.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Generate Campaign</a></li>
	<li><a class="active" href="cgTemplateManager.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Template Manager</a></li>
	<li><a class="active" href="cgScheduled.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Manage Scheduled Campaigns</a></li>
	<li><a href="cgHelp.php">Help</a></li>
	<li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
	<li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
	<li><a class="active" href="cgKey.php">Log Off</a></li>
	<li class="icon">
    	<a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  	</li>
</ul>

<script>
function generatorNav() {
    var x = document.getElementById("generatorTopNav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>

<table><tr><td width="1200px" align="left">
<div id="google_translate_element"></div>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</td></tr></table>

<br><table width = 1000 border=0><tr><td><h1><center>Campaign Submission Receipt</center></h1></td></tr></table>

<?php
//
//Build a table to echo what the user entered; this will be used in the email 
//

$validationText = "<table><td rowspan='20' width='20'></td>";
$validationText .= "<tr><td><h4>Email confirmation will be sent To: " . $email . "</h4></td></tr>";
$validationText .= "<tr><td><form><table width='1000' border=1 cellpadding='20'><tr><td>";
$validationText .= "<h5>Campaign Name: " . $campaign . "</h5>";
$validationText .= "<h5>Return Path: " . $returnpath . "</h5>";
$validationText .= "<h5>Template: " . $textofCurrentSelectedTemplate . "</h5>";
switch ($whichrecipients)
{
case 'InputBox':
  	$validationText .= "<h5>Only the recipients that displayed within the recipient data field box will be used.  There are : " . $recipientCount . " target recipients</h5>";
	break;
	
case 'FullFile':
  	$validationText .= "<h5>Recipients from the file: <i>" . $recipientUploadFile . "</i> will be used.  There are " . $recipientCount . " target recipients in the file</h5>";
	break;
	
case 'RecipientSelect':
  	$validationText .= "<h5>Recipient List <i>: " . $recipients . "</i> selected which has a recipient list of " . $recipientCount . " recipients</h5>";
	break;
}	

$validationText .= "<h5>Send Now Flag: " . $now . "</h5>";
$validationText .= "<h5>";
if ($date && $now) {$validationText .= "</h5><h4><strong><i>**Notice** Send Date/Time was entered and will override Send Now Flag, Campaign Scheduled</i></strong><br>";}
$validationText .= "   Scheduled Date/Time: " . $date . " at: " . $hour . ":" . $minutes . " with a timezone offset from GMT: " . $tz;
if ($date && $now) {$validationText .= "</h4>";} else {$validationText .= "</h5>";}
$validationText .= "<h5>Track Open Flag: " . $open . "</h5>";
$validationText .= "<h5>Track Clicks Flag: " . $click . "</h5>";
$validationText .= "<h5>IP Pool: " . $ipPool . "</h5>";
if (strlen($globalsub) > 5) $validationText .= "<h5>Global Substitution Text Entered</h5>"; else $validationText .= "<h5>Global Substitution Text Not Entered</h5>";
$validationText .= "<form action=''>";
switch ($whichrecipients)
{
case 'InputBox':
  	$validationText .= "<input style='font-size:12px' type='radio' name='whichrecipients' value='InputBox' checked> Use only data displayed in data fields<br>";
  	$validationText .= "<input disabled style='font-size:12px' type='radio' name='whichrecipients' value='FullFile'> Full Uploaded File<br>";
  	$validationText .= "<input disabled style='font-size:12px' type='radio' name='whichrecipients' value='RecipientSelect'> Use selected stored recipient list: " . $recipients . "<br>";
	break;
	
case 'FullFile':
  	$validationText .= "<input disabled style='font-size:12px' type='radio' name='whichrecipients' value='InputBox'> Use only data displayed in data fields<br>";
  	$validationText .= "<input style='font-size:12px' type='radio' name='whichrecipients' value='FullFile' checked> Full Uploaded File<br>";
	$validationText .= "<input disabled style='font-size:12px' type='radio' name='whichrecipients' value='RecipientSelect'> Use selected stored recipient list: " . $recipients . "<br>";
	break;
	
case 'RecipientSelect':
  	$validationText .= "<input disabled style='font-size:12px' type='radio' name='whichrecipients' value='InputBox'> Use only data displayed in data fields<br>";
  	$validationText .= "<input disabled style='font-size:12px' type='radio' name='whichrecipients' value='FullFile'> Full Uploaded File<br>";
  	$validationText .= "<input style='font-size:12px' type='radio' name='whichrecipients' value='RecipientSelect' checked> Use selected stored recipient list: " . $recipients . "<br>";
	break;
}	
$validationText .= "</form><br>";
$validationText .= "<p><table width='400'>";
if ($meta1 || $meta2 || $meta3 || $meta4 ) 
{
   $validationText .= "<tr><th align=left>Metadata Names</th><th align=left>Metadata Values</th></tr>";
   if ($meta1) {$validationText .= "<tr><td width=200 height=30>" . $meta1 . "</td><td width=200 height=30>" . $data1 . "</td></tr>";}
   if ($meta2) {$validationText .= "<tr><td width=200 height=30>" . $meta2 . "</td><td width=200 height=30>" . $data2 . "</td></tr>";}
   if ($meta3) {$validationText .= "<tr><td width=200 height=30>" . $meta3 . "</td><td width=200 height=30>" . $data3 . "</td></tr>";}
   if ($meta4) {$validationText .= "<tr><td width=200 height=30>" . $meta4 . "</td><td width=200 height=30>" . $data4 . "</td></tr>";}
}

$validationText .= "</table>";
$validationText .= "</td></table></tr></form></td></tr></table>";

echo $validationText;
?>
<br><br>
<textarea id="warning" name="warning" readonly rows=1 type="textnormal" style=" border:none; width: 1325px; resize: none;"></textarea>
<br>
<table>
	<td rowspan='20' width='20'></td>
	<tr>
		<td>
			<button type="button" id="confirmed" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="confirmedSend()">Press to Send/Schedule Campaign</button>
		</td>
	</tr>
	<tr><td>
        <div class="main">
        <iframe id="serverResults" height="390" width="1010" frameBorder="0"></iframe></div>
    </td></tr>
</table>

<script>

function confirmedSend() 
{
    var template = "<?php echo $template; ?>";
    var recipients = "<?php echo $recipients; ?>";
    var campaign = "<?php echo $campaign; ?>";
    var returnpath = "<?php echo $returnpath; ?>";
    var open = "<?php echo $open; ?>";
    var click = "<?php echo $click; ?>";
    var now = "<?php echo $now; ?>";
    var date = "<?php echo $date; ?>";
    var hour = "<?php echo $hour; ?>";
    var minutes = "<?php echo $minutes; ?>";
    var tz = "<?php echo $tz; ?>";
    var meta1 = "<?php echo $meta1; ?>";
    var data1 = "<?php echo $data1; ?>";
    var meta2 = "<?php echo $meta2; ?>";
    var data2 = "<?php echo $data2; ?>";
    var meta3 = "<?php echo $meta3; ?>";
    var data3 = "<?php echo $data3; ?>";
    var meta4 = "<?php echo $meta4; ?>";
    var data4 = "<?php echo $data4; ?>";
    var ipPool = "<?php echo $ipPool; ?>";
    var recipientCount = "<?php echo $recipientCount; ?>";
    var validationText = "<?php echo $validationText; ?>";
    var email = "<?php echo $email; ?>";
    var jsonLoad = "<?php echo $jsonLoadEncoded; ?>";
    var globalsub = "<?php echo $globalsubEncoded; ?>";
    var whichrecipients = "<?php echo $whichrecipients; ?>";
    var recipientUploadFile = "<?php echo $recipientUploadFile; ?>";
    var batches = "<?php echo $batches; ?>";
    var BatchSize = "<?php echo $BatchSize; ?>";
    var recipientCount = "<?php echo $recipientCount; ?>";
    
    $.ajax({
      
    url:'cgSendCampaign.php',
    type: "POST",
    data: {"template" : template, "recipients" : recipients, "validationText" : validationText, "email" : email, "jsonLoad" : jsonLoad, "recipientCount" : recipientCount, "recipientUploadFile" : recipientUploadFile,
      	   "campaign" : campaign, "returnpath" : returnpath, "open" : open, "click" : click, "now" : now, "date" : date, "hour" : hour, "minutes" : minutes, "tz" : tz, "globalsub" : globalsub, "count" : recipientCount,
      	   "meta1" :  meta1, "data1" : data1,   "meta2" :  meta2, "data2" : data2, "meta3" :  meta3, "data3" : data3, "meta4" :  meta4, "data4" : data4, "ipPool" : ipPool, "whichrecipients" : whichrecipients },
    beforeSend:function()
    {
     	if (whichrecipients == "FullFile") 
     	{
     		$('#warning').html("  Sending Campaign...With your settings of sending batch sizes of " + BatchSize + " emails, it will take " + batches + " batch request(s) to send all your emails.  Please expect approximately 2-10 seconds to prepare and send each batch.");
     	}
    }, 
    complete: function (response) 
    {
    	// This is for error checking  in order to see echo'ed items...
        $('#serverResults').contents().find('html').html(response.responseText);
        var schedButton = document.getElementById("confirmed");
		var location = response.responseText.search('"errors":');
		if (location > 0)
		{
			schedButton.style.backgroundColor = "red";
        	schedButton.style.color = "black";
        	schedButton.textContent = "Ouch!! Something went Wrong";
        	schedButton.disabled = true;
        }
		else
		{
        	schedButton.style.backgroundColor = "#298272";
        	schedButton.style.color = "white";
        	schedButton.textContent = "Gone....Hasta La Vista Baby";
        	schedButton.disabled = true;
        }
    },
    error: function () 
    {
        $('#serverResults').contents().find('html').html(response.responseText);
    }
	});
}
</script>



</body>
</html>
