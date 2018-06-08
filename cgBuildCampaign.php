<!-- Copyright 2016 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
//
File: cgBuildSubmitCSVJSON.php 
Launch a campaign with entered data
//
 -->


<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>Campaign Generator for SparkPost</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon">
<link href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css" rel="stylesheet">
<!--<link rel="stylesheet" type="text/css" href="css/tmCommonFormatting.css">
<script type="text/javascript" src="js/cgCommonScripts.js"></script>-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>
/* Set Calendar format; Using jQuery calendar because it works better across different browsers than default form calendar */
$( function() 
{
    $( "#datepicker" ).datepicker( { dateFormat: 'yy-mm-dd' });
} );
</script>



<!-- start of tmCommonFormatting.css -->
<style>
    body 
    {
  		font-family: Helvetica, Arial;}
	}
    #bkgnd 
    {
    	background-repeat: repeat;
    }
    
    div#container 
    {
        width:500px;
        height:500px;
        overflow:auto;
    }
    
     #scrollable_table 
     {
        display: inline-block;
        overflow-y:scroll;
        border-collapse: collapse;
    }
    
    table {
    	border-collapse: collapse;
	}

    h2 {
    display: block;
    font-size: 1.5em;
    margin-top: 0.83em;
    margin-bottom: 0.83em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    font-family: Helvetica, Arial;
    }

    h3 {
    font-size: 1em;
    margin-top: 1em;
    margin-bottom: 0em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #298272;
    font-family: Helvetica, Arial;
    }

    h4 { 
    display: block;
    margin-top: 1.33em;
    margin-bottom: 1.33em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #298272;
    font-family: Helvetica, Arial;
    }
    
    select {
    padding: 16px 20px;
    border: 1px;
    border-color: #298272;
    background-color: #ffffff;
    font-size: 12px;
    font-family: Helvetica, Arial;
    }

    /* Can use this when I don't want an expanding input field */
    input[type=textnormal] {
    box-sizing: border-box;
    border: 1px solid #ccc;
    font-size: 12px;
    background-color: white;
    padding: 2px 2px 2px 4px;
    width : 300px;
    font-family: Helvetica, Arial;
    }
    
    /* Can use this when I don't want an expanding input field */
    input[type=textlong] {
    box-sizing: border-box;
    border: 1px solid #ccc;
    font-size: 12px;
    background-color: white;
    padding: 2px 2px 2px 4px;
    width : 600px;
    font-family: Helvetica, Arial;
    }

    /* This expands the text for more room while typing */
    input[type=text] {
    width: 350px;
    box-sizing: border-box;
    border: 1px solid #black;
    font-size: 12px;
    background-color: white;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    }

    input[type=text]:focus {
    width: 450px;
    border: 3px solid #555;
    }
    
    /* This expands the text for more room while typing */
    input[type=textdataentry] {
    width: 250px;
    box-sizing: border-box;
    border: 1px solid #black;
    font-size: 12px;
    background-color: white;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    }

    input[type=textdataentry]:focus {
    width: 400px;
    height: 600px;
    border: 3px solid #555;
    }
    
    /* This expands the text for more room while typing */
    input[type=longentry] {
    width: 400px;
    box-sizing: border-box;
    border: 1px solid #black;
    font-size: 12px;
    background-color: white;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    }

    input[type=longentry]:focus {
    width: 600px;
    border: 3px solid #555;
    }

    input[type=number] {
    width: 40px;
    height: 23px;
    box-sizing: border-box;
    border: 1px solid #black;
    font-size: 12px;
    background-color: white;
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding: 2px 2px 2px 4px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    }

    input[type=number]:focus {
    width: 65px;
    border: 3px solid #555;
    }

    input[type=date] {
    width: 150px;
    height: 20px;
    box-sizing: border-box;
    border: 8px solid #black;
    border-radius: 4px;
    font-size: 12px;
    background-color: white;
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding: 2px 2px 2px 4px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    }

    input[type=date]:focus {
    width: 200px;
    border: 3px solid #555;
    }
    
    textarea{
    	max-width: 1200px;
    }

    #iframe1 {
    border: solid 0 px;
    border-radius: 8px;
    padding-top: 1em;
    margin: 0 auto;
    font-family: Helvetica, Arial;
    }

    .alert {
    padding: 20px;
    background-color: #f44336;
    color: white;
    width: 1250px;
    }

    .tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: left;
    border-radius: 6px;
    padding: 5px 5px 5px 5px;
    font-size: 12px;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    }

    .tooltip:hover .tooltiptext {
    visibility: visible;
    }

    /* This forces more consistent look and field across browsers for pulldown select fields */
    @media screen and (-webkit-min-device-pixel-ratio:0) {  /*safari and chrome*/
    select {
        height:25px;
        line-height:25px;
        background:#f4f4f4;
    } 
    }
    select::-moz-focus-inner { /*Remove button padding in FF*/ 
    border: 0;
    padding: 0;
    }
    @-moz-document url-prefix() { /* targets Firefox only */
    select {
        padding: 5px 0!important;
    }
    }        
    @media screen\0 { /* IE Hacks: targets IE 8, 9 and 10 */        
    select {
        height:30px;
        line-height:30px;
    }     
    }
    body {margin:0;}
ul.topnav {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

ul.topnav li {float: left;}

ul.topnav li a {
  display: inline-block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  transition: 0.3s;
  font-size: 17px;
}

ul.topnav li a:hover {background-color: #555;}

ul.topnav li.icon {display: none;}

@media screen and (max-width:680px) {
  ul.topnav li:not(:first-child) {display: none;}
  ul.topnav li.icon {
    float: right;
    display: inline-block;
  }
}

@media screen and (max-width:680px) {
  ul.topnav.responsive {position: relative;}
  ul.topnav.responsive li.icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  ul.topnav.responsive li {
    float: none;
    display: inline;
  }
  ul.topnav.responsive li a {
    display: block;
    text-align: left;
  }
}

/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the buttons inside the tab */
div.tab button2 {
    background-color: #e0e2e5;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
div.tab button2:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button2.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

<!--end tmCommonFormatting -->


#scrollable_table tr:last-child td {
    border-bottom:0;
}

.vert-text
{
  width:130px;
  height:50px;
  -ms-transform:rotate(270deg); /* IE 9 */
  -moz-transform:rotate(270deg); /* Firefox */
  -webkit-transform:rotate(270deg); /* Safari and Chrome */
  -o-transform:rotate(270deg); /* Opera */
}


.fixed {
	position:fixed;
    width:100%;
    top:0;
    left:0;        
}
</style>
   
</head>

<body style="margin-left: 20px; margin-right: 20px" onload="cleanup(), fillfields()">

<?php
	ini_set('post_max_size', '2000000');
    //include 'cgPHPLibraries.php';
    
    $parametersFile = "config/Parameters.ini";
	$paramonly_array = parse_ini_file( $parametersFile, true );
	$cat = "local";
	$uploadDir = $paramonly_array[$cat]["UploadDir"];
	$MAXUIRecipientRetrieval = $paramonly_array[$cat]["MAXUIRecipientRetrieval"];
	$UploadFileSizeLimit = $paramonly_array[$cat]["UploadFileSizeLimit"];
	$UploadFileSizeWarning = $paramonly_array[$cat]["UploadFileSizeWarning"];
?>

<!--<div class="header">-->
<div>
<table border=0; width="1420px">
<tr><td>
<ul class="topnav" id="generatorTopNav">
  <li><a class="active" href="cgTemplateManager.php">Template Manager</a></li>
  <li><a class="active" href="cgScheduled.php">Manage Scheduled Campaigns</a></li>
  <li><a class="active" href="cgEmailTracer.php">Email Tracer</a></li>
  <li><a class="active" href="graphing/highchart.php">Reporting</a></li>
  <li><a href="helpdocs/cgHelp.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
  <li><a class="active" href="cgKey.php">Logoff</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  </li>
</ul>
</td></tr></table><table>
<tr><td align="left">
<iframe src="http://free.timeanddate.com/clock/i5ze60a7/n5446/fs12/tt0/tw0/tm1/ta1/tb2" frameborder="0" width="201" height="16"></iframe>
</td><td width="1200px" align="right">
<div id="google_translate_element"></div>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</td></tr></table>
</div>

<br>
<table style="width:1420px; outline:red solid 1px">
<tr><td style="width:1420px;" colspan="3">
	<center><h1>Campaign Generator</h1></center>
</td></tr>
<tr>
<td>
	<table width="850" height="900">
        <td style="padding-left: 8px; padding-right: 8px;">
            <form class="cgform" action="cgConfirmSubmission.php" onsubmit="recipientcount('submit'), prepsubmit()" method="POST" height="900">
                <!-- Start: set hidden fields we need passed when submission is requested -->
                <input id="apikey" name="apikey" type="hidden" value="">
                <input id="apiroot" name="apiroot" type="hidden" value="">
                <input id="recipientCount" name="recipientCount" type="hidden" value="">
                <input id="textofCurrentSelectedTemplate" name="textofCurrentSelectedTemplate" type="hidden" value="">
				<!-- End: set hidden fields we need passed when submission is requested -->
                
                <h3>Select a Template (Listing Published Templates Only)*</h3>
                <select style="color:#298272; font-size:12px;font-weight: bold;" id="Template" name="Template" required title="Template field is required"></select> 
                <br><br>
                <table style="background-color: #f5f5f5; width:900px; padding:8px;">
                <tr><td style="background-color: #f5f5f5; width:900px; padding:8px;">
                	<text style="color:#298272; font-size:16px;font-weight: bold;">Data Input Section*</text>
                	<br>
                	<text style="font-size:12px">Note: You may enter data three different ways. 
                	<br>&nbsp;&nbsp;&nbsp;&nbsp;1) Select a stored recipient list 
                	<br>&nbsp;&nbsp;&nbsp;&nbsp;2) Upload a CSV file (uploaded or pasted data takes precedence over selected recipient list)
                	<br>&nbsp;&nbsp;&nbsp;&nbsp;3) Paste CSV or JSON into fields</text>
               		<br><br>
               		<div id="regFormContainer" class="bar-colors-borders">
                        <select style="background-color:#ffffff; color:#298272; font-size:12px;font-weight: bold;" id="Recipients" name="Recipients" title="Select a stored recipeint from this list versus uploading a file or placing data into the fields below" onchange="recipientcount('recipientlistchange')"></select>
                        <br><br>
                        &nbsp;&nbsp;<text style="color:#298272; font-size:12px; font-weight: bold; font-family: Helvetica, Arial">Select File for Upload --></text>
                        <input type="file" name="txtUploadFile" id="txtUploadFile" value="Upload File" title="Use this button to upload a file of recipeints versus using a stored recipeint list from the list above, or cut/paste data into the fields below" />
                        <br>
                        <span id="uploaded_notes"></span>
					</div> 
					<br>
					<textarea id="csvMessageText" name="csvMessageText" readonly rows=1 type="textnormal" style="background-color: #f5f5f5; border:none; width: 825px; resize: none;">Due to browser limitations, only the first 1,000 rows will be displayed for any uploaded files</textarea>
                	<table><tr><td style="word-break:break-all; width:12px"><center>CSV</center></td>
                	<td><textarea id="csv" maxlength="200000" cols="120" name="csv" style="resize:vertical; min-height:70px;" title="Place data directly into this or any of the data fields, versus selecting a stored recipeint list from the above list, or uploading a file." placeholder=
'"address","UserName","substitution","first","id","city"
"jeff.goldstein@sparkpost.com","Sam Smith","_","Sam",342,"Princeton"
"austein@hotmail.com","Fred Frankly","_","Fred",38232,"Nowhere"
"jeff@geekswithapersonality.com","Zachary Zupers","_","Zack",9,"Hidden Town"'></textarea>
					</td></tr></table>
					<text style="font-size:12px">*** Field Note: The address field is mandatory, all fields after the substitution field will be treated as such.  Place a "_" in each row corresponding to the substitution column</text>
					<br><br>
    				<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="convert()" value="Convert to JSON">
    				&nbsp;&nbsp;
					<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview(), match()" value="Preview & Data Validation">
					&nbsp;&nbsp;
					<input type="button" name="isjson" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 10px; background-color: #72A4D2;" onclick="IsJsonString()" title="See Preview Box for Answer" value="Validate JSON Structure">
					<br><br>
					<textarea id="recipientMessageText" name="recipientMessageText" readonly rows=1 type="textnormal" style="background-color: #f5f5f5; border:none; width: 825px; resize: none;">JSON data in SparkPost specific format</textarea>
    				<table><tr><td style="word-break:break-all; width:12px"><center>JSON</center></td>
    				<td><textarea id="json" name="json" maxlength="675000" cols="120" style="resize:vertical; min-height:70px;" placeholder=
'{"recipients":[
{"address":"jeff.goldstein@sparkpost.com","UserName":"Sam Smith","substitution_data":{"first":"Sam","id":"342","city":"Princeton"}},
{"address":"austein@hotmail.com","UserName":"Fred Frankly","substitution_data":{"first":"Fred","id":"38232","city":"Nowhere"}},
{"address":"jeff@geekswithapersonality.com","UserName":"Zachary Zupers","substitution_data":{"first":"Zack","id":"9","city":"Hidden Town"}}
]}'></textarea>
    				</td></tr></table>
    				<text style="font-size:12px">*** Field Note: Notice that the whole block must be bracketed with curly brackets {}. Converting from CSV to JSON will add the outside brackets automatically</text>
    				<br><br>
    				<textarea id="globaltext" name="globaltext" readonly rows=1 type="textnormal" style="background-color: #f5f5f5; border:none; width: 825px; resize: none;">Input Global Substitution Data in JSON Format up to 70k of data</textarea>
    				<br>
    				<table><tr><td style="word-break:break-all; width:12px">JSON</td>
    				<td><textarea id="globalsub" name="globalsub" maxlength="70000" cols="120" style="resize:vertical; min-height:70px; height:200;" placeholder=
    			'"substitution_data": {
"subject" : "More Wonderful Items Picked for You",
"link_format": "style= \"font-family: arial, helvetica, sans-serif; color: rgb(85,85, 90); font-size: 12px; text-decoration: none;\"",
"dynamic_html": {
	"member_level" : "<strong>GOLD</strong>",
	"job1" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Inside Sales Representative, San Francisco, CA</a>",
	"job2" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Sales Development Representative, San Francisco, CA</a>",
	"job3" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Social Media Marketing, San Francisco, CA</a>",
	"job4" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"http://page.messagesystems.com/careers\">Platform Developer, Columbia, MD</a>",
	"job5" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"http://page.messagesystems.com/careers\">Rain Catcher & Beer Drinker, Seattle, WA</a>"
},
"default_jobs": ["job1", "job3"],
"backgroundColor" : "#ffffff",
"company_home_url" : "www.sparkpost.com",
"company_logo" : "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTVYSp0xUPD8yNMYOyTS20VZBwbzt4J-pjta3FtjcT_0rM-cj2o"
}'></textarea>
				</td></tr></table>
				<text style="font-size:12px">*** Field Note: Notice that there are no {} on the outside of the substitution-data block</text>
				<br><br>
				<form action="">
					What recipient data do you wish to use for this campaign?*<br>
  					<input required style="font-size:12px" type="radio" id="whichrecipients" name="whichrecipients" value="InputBox"> Data In Boxes Above<br>
  					<input style="font-size:12px" type="radio" id="whichrecipients" name="whichrecipients" value="FullFile"> Uploaded File<br>
  					<input style="font-size:12px" type="radio" id="whichrecipients" name="whichrecipients" value="RecipientSelect"> Recipient List<br>
				</form><br>
				</td></tr></table>
                <h3>Launch now OR enter data & time of campaign launch (YYYY-MM-DD HH:mm)*
                	<div class="tooltip"><a><img height="35" src="http://www.geekwithapersonality.com/pictures/info.png" width="35"></a> 
                		<span class="tooltiptext">Note:<br>1) Campaigns scheduled within 10 minutes of running cannot be cancelled.<p>2) Campaigns can only be scheduled one month out.</span>
                	</div>
                </h3>
                <input checked id="now" name="now" type="checkbox" value="T"> OR Enter Date/Time 
                <input data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" id="datepicker" name="date" placeholder="YYYY-MM-DD" type="text" style="width:100px">
                <input data-format="HH" data-template="HH" max="23" min="0" name="hour" size="6" type="number" value="00"> 
                <input data-format="MM" data-template="MM" max="59" min="0" name="minutes" size="6" type="number" value="00"> 
				<select style="color:#298272; font-size:12px;font-weight: bold;" id="TimeZone" name="TimeZone" title="Select Time Zone for Campaign Launch"></select>
                <h3>Campaign Name:*</h3>
                <input name="campaign" required="" type="longentry" style="width:400px">
                <br>
                <h3>Global Return Path (Required for Elite/Enterprise SparkPost Users):*</h3>
                <input required id="returnpath" name="returnpath" type="longentry" style="width:200px">@
                <select required id="domain" name="domain" style="color:#298272; font-size:12px;font-weight: bold;"></select> 
                <br><br>
                <input checked name="open" type="checkbox" value="T"> Turn on Open Tracking
                <br>
                <input checked name="click" type="checkbox" value="T"> Turn on Click Tracking
                <br><br>
                IP Pool*: <input required name="ipPool" type="textnormal" value="default">
                <br>
                <h3>Optional Items (leave blank if you don't want to use them)...</h3>
                <h4>Want Proof, Enter Your Email Address Here</h4>
                <input name="email" type="email" style="width:400px" value="">
                <h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>
                Metadata Field Name: <input name="meta2" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data2" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta3" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data3" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta4" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data4" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta5" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data5" type="textnormal" value="">
                <br><br><br>
                <input id="submit" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="submit" value="Submit"> 
                <input size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="reset" value="Reset" onclick="resetpreview(), resetsummary()"><p><p>
            </form>
        </td>
    </table>
</td>
<td style="width:"5px"></td>

<td  valign="top" style="padding-top: 20px;">
    <table id="CampaignTable" border="1" cellpadding="10" bgcolor="FFFFFF" width="450"  height="500"></table>
    <br><br>
    <button id="toggle" onclick="showhide()">Substitution or Template Validation</button>
    <table id="substitutionTable" border="0" bgcolor="FFFFFF" width="450"  height="450" style="display:block">
        <tr>
            <td>
                <div class="main">
                    <iframe id="substitution" height="450" width="450" style="background: #FFFFFF;" cellpadding="10" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
                </div>
            </td>
        </tr>
    </table>
    <table id="templateTable" border="0" bgcolor="FFFFFF" width="450"  height="450" style="display:none">
        <tr>
            <td>
                <div class="main">
                    <iframe id="template" height="450" width="450" style="background: #FFFFFF;" cellpadding="10" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
                 </div>
            </td>
        </tr>
    </table>
</td>
</tr>
</table>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Mandatory fields
<table cellpadding="25" border=0>
	<tr>
		<td>
    		<h3>Preview Using Selected Template and First Member of JSON Recipient List</h3><br>
    		<i>**This feature is still in beta...Still working on error messaging...Large Recipient Lists may cause the Preview to malfunction</i>
    		<div class="main">
        		<iframe height="600" id="iframe1" name="iframe1" width="1300" style="background: #FFFFFF;" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
    		</div>
    	</td>
	</tr>
</table>
<script type="text/javascript">

function fillfields()
{
	document.getElementById("apikey").value = getCookie("sparkpostkey");
	document.getElementById("apiroot").value = getCookie("sparkpostapiroot");
	var apikey = document.getElementById("apikey").value;
	var apiroot = document.getElementById("apiroot").value;
	
	$.ajax({
    	url:'phpResources/buildRecipientList.php',
    	type: "POST",
    	complete: function (response) 
    	{
        	document.getElementById("Recipients").innerHTML = response.responseText;
    	},
    	error: function () {
        	alert("Problem obtaining stored recipient list?  Does your api key have access?");
    	}
    	});
    	
	$.ajax({
    	url:'phpResources/buildTemplateList.php',
    	type: "POST",
    	data: {"cgORtm" : "cg"},
    	complete: function (response) 
    	{
        	document.getElementById("Template").innerHTML = response.responseText;
    	},
    	error: function () {
        	alert("Problem obtaining stored template list?  Does your api key have access?");
    	}
    	});
    	
    $.ajax({
    	url:'phpResources/buildSendingDomainList.php',
    	type: "POST",
    	complete: function (response) 
    	{
        	document.getElementById("domain").innerHTML = response.responseText;
    	},
    	error: function () {
        	alert("Problem obtaining stored domain list?  Does your api key have access?");
    	}
    	});
    	
    $.ajax({
    	url:'phpResources/buildTimeZoneList.php',
    	type: "POST",
    	complete: function (response) 
    	{
        	document.getElementById("TimeZone").innerHTML = response.responseText;
    	},
    	error: function () {
        	alert("Problem building Timezone list!  Resource file 'buildTimeZoneList.php' may be missing?");
    	}
    	});
    	
    $.ajax({
    	url:'phpResources/buildSummaryScheduledCampaignTable.php',
    	type: "POST",
    	complete: function (response) 
    	{
        	document.getElementById("CampaignTable").innerHTML = response.responseText;
    	},
    	error: function () {
        	alert("Problem building Campaign Table of scheduled campaigns!  Does your api key have access?");
    	}
    	});
}


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

//
// Clear Useless data before sending for transmission
//
function prepsubmit() 
{
    var csv = document.getElementById("csv");
    csv.value='' ;
    document.getElementById("textofCurrentSelectedTemplate").value = $("#Template option:selected").text()
    //var domain = document.getElementById("domain");
    //var selecteddomain = domain.options[domain.selectedIndex].value;
    //var returnpath = document.getElementById("returnpath");
    //returnpath.value = returnpath.value.concat('@');
    //returnpath.value = returnpath.value.concat(selecteddomain);
 }
 
function cleanup() 
{
// Need to clean up this field in case they did a backpage in the browser
// 
    var returnpath = document.getElementById("returnpath");
    var location = returnpath.value.search("@");
	if (location > 0) {returnpath.value = returnpath.value.substring(0, location)};
 }

function recipientcount(whocalled) 
{
    var recipientCount = document.getElementById("recipientCount");
    var recipientlist = document.getElementById("Recipients");
    var whichrecipients =  $('input[name=whichrecipients]:checked').val();
    var json = document.getElementById("json");
    var uploadfilename = document.getElementById("txtUploadFile");
    
    if (whocalled == 'recipientlistchange')
    {
    	getRecipientListCount (recipientlist.value);
    }
    else
    {
    	switch (whichrecipients)
    	{
    		case "InputBox" :
				var doubleqoute = (json.value.match(/"address"/g) || []).length;
    			var singlequote = (json.value.match(/'address'/g) || []).length;
				recipientCount.value = singlequote + doubleqoute;
				break;
			case "FullFile" :
				filename = "uploads" + uploadfilename;
				jQuery.get(filename, function(data) 
				{
    				alert(data);
				});
				var lines = data.split("\n").length;
				recipientCount.value=lines;
				break;
			case "RecipientSelect" :
				getRecipientListCount (recipientlist.value);
				
		}
	}
}		

function getRecipientListCount(recipientlist)
{
    var recipientCount = document.getElementById("recipientCount");
	$.ajax({
    url:'phpResources/getRecipientListCount.php',
    type: "POST",
    data: {"recipients" : recipientlist},
    complete: function (response) 
    {
        recipientCount.value=response.responseText;
    },
    error: function () 
    {
        $('#output').html('0');
    }
    });
} 
 

function showhide() {
    var e = document.getElementById("substitutionTable");
    var f = document.getElementById("templateTable");
    if (e.style.display == 'none') {e.style.display = "block"} else {e.style.display = 'none'};
    if (f.style.display == 'none') {f.style.display = "block"} else {f.style.display = 'none'};
 }
 
 function showGlobalSubField() {
    var d = document.getElementById("globalButton");
    var e = document.getElementById("globalsub");
    var f = document.getElementById("globaltext");
    if (e.style.display == 'none') {e.style.display = "block"} else {e.style.display = 'none'};
    if (f.style.display == 'none') {f.style.display = "block"} else {f.style.display = 'none'};
    if (d.value == 'Show Global Sub') {d.value = "Hide Global Sub"} else {d.value = 'Show Global Sub'};
 }

function generatePreview()
{
	var template = document.getElementById("Template");
	var recipientSelect = document.getElementById("Recipients");
    var recipientJSON = document.getElementById("json").value;
    var globalsub = document.getElementById("globalsub").value;

    $.ajax({
      url:'phpResources/buildPreviewWithStoredTemplate.php',
      type: "POST",
      data: {"template" : template.value,   "recipientSelect" : recipientSelect.value, "recipientJSON" : recipientJSON, "globalsub" : globalsub },
      complete: function (response) 
      {
          $('#iframe1').contents().find('html').html(response.responseText);  //response text obtaining preview
          xbutton = document.getElementById("submit");
          var strCheck1 = "attempt to call non-existent macro";
          var strCheck2 = "crash";
          var location1 = response.responseText.search(strCheck1);
          var location2 = response.responseText.search(strCheck2);
          if (location1 > 0  && location2 > 0)
          {
              xbutton.disabled = true;
              xbutton.value = "Submit";
              xbutton.style.backgroundColor = "red";
              xbutton.style.color = "black";
              alert("Warning!! Your data protection check was triggered, bad Recipient List selected - Submit Turned off!");
          }
          else
          {  
              var strCheck = "Matching Problem";
              var location = response.responseText.search(strCheck);
              if (location > 0) 
              {
                  xbutton.disabled = true;
                  xbutton.value = "Submit";
                  xbutton.style.backgroundColor = "red";
                  xbutton.style.color = "black";
                  alert("Warning!! Template & Recipient error detected; please see preview box - Submit Turned off!");
              }
              else
              {   
                  xbutton.disabled = false;
                  xbutton.value = "Submit";
                  xbutton.style.color = "white";
                  xbutton.style.backgroundColor = "#72A4D2";
              }
          }
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    }); 
    return false;
}

function match()
{
	var template = document.getElementById("Template");
    var recipients = document.getElementById("Recipients");
    var convertedJson = document.getElementById("json").value;
    var globalsub = document.getElementById("globalsub").value;
    
    $.ajax({
      url:'phpResources/templateDataComp.php',
      type: "POST",
      data: {"template" : template.value, "recipients" : recipients.value, "type" : "substitution", "entered" : convertedJson, "globalsub" : globalsub},
      complete: function (response) 
      {
          $('#substitution').contents().find('html').html(response.responseText);
          $.ajax({
      		url:'phpResources/templateDataComp.php',
      		type: "POST",
      		data: {"template" : template.value, "recipients" : recipients.value, "type" : "template", "entered" : convertedJson, "globalsub" : globalsub},
      		complete: function (response) 
      		{
          		$('#template').contents().find('html').html(response.responseText);
      		},
      		error: function () {
          		$('#output').html('Bummer: there was an error!');
      		}
    		});
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    });
    
    return false;
}

function resetpreview()
{
	$('#iframe1').contents().find('html').html("<p>Please select your Template and Recipient List</p>");
	xbutton = document.getElementById("submit");
	xbutton.disabled = false;
    xbutton.value = "Submit";
    xbutton.style.color = "white";
    xbutton.style.backgroundColor = "#72A4D2";
}

function resetsummary()
{
	$('#template').contents().find('html').html("<p>Please select your Template and Recipient List</p>");
	$('#substitution').contents().find('html').html("<p>Please select your Template and Recipient List</p>");
}

function IsJsonString(jsonstrvar) 
{
//
// Give the user a quick json string validation check
// This does not validate that the Json structure is a good SparkPost Json structure though
//
// Called by 'isjson' button and CSV2JSON function for validation
//
    if (!jsonstrvar) jsonstrvar = document.getElementById("json").value;
    try {
        JSON.parse(jsonstrvar);
    } catch (e) {
        $('#iframe1').contents().find('html').html("Bad Json String, Jsonlint: https://jsonlint.com/ is a good validation tool.");
        return false;
    }
    $('#iframe1').contents().find('html').html("Good Json String");
    return true;
}

function convert ()
{
	var csvRecipients = document.getElementById("csv").value;
	
	$.ajax({
      url:'phpResources/convertCSV2SparkPostJSON.php',
      type: "POST",
      data: {"recipients" : csvRecipients},
      complete: function (response) 
      {
          document.getElementById("json").value = response.responseText;
      },
      error: function () {
          $('#iframe1').contents().find('html').html('Bummer: there was an error calling the convert program phpResources/convertCSV2SparkPostJSON.php!');
      }
    });
	
}

$(document).ready(function()
{
	$(document).on('change', '#txtUploadFile', function(){

	var UploadFileSizeLimit = "<?php echo $UploadFileSizeLimit; ?>";
	var UploadFileSizeWarning = "<?php echo $UploadFileSizeWarning; ?>";
	var UploadFileSizeLimitDisplay = UploadFileSizeLimit / 1000000;
	
	var name = document.getElementById("txtUploadFile").files[0].name;
	var form_data = new FormData();
	var ext = name.split('.').pop().toLowerCase();
	if(jQuery.inArray(ext, ['csv','txt']) == -1) 
	{
		alert("Invalid File Type");
	}
	var oFReader = new FileReader();
	oFReader.readAsDataURL(document.getElementById("txtUploadFile").files[0]);
	var f = document.getElementById("txtUploadFile").files[0];
	var fsize = f.size||f.fileSize;
	if(fsize > UploadFileSizeLimit)
	{
		alert("Upload file size to big! This process is limited to " + UploadFileSizeLimitDisplay + "MB.");
	}
	else
	{
  		if(fsize > UploadFileSizeWarning)
  	{
   		alert("Upload file size is large! While the whole file will be uploaded, only the first 1,000 records will be displayed for your validation process.");
  	}
   	form_data.append("file", document.getElementById('txtUploadFile').files[0]);
   	var filename = document.getElementById("txtUploadFile").value
   	filename = filename.split('\\').pop();
   	$.ajax({
    url:"phpResources/fileUploader.php",
    method:"POST",
    dataType: 'text',
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend:function(){
     	$('#csv').html("File Uploading...");
     	$("#json").val("File Uploading...");
    },   
    success:function(data)
    {
		if (!data)				// Upload is good
		{
			var MAXUIRecipientRetrieval = "<?php echo $MAXUIRecipientRetrieval; ?>";
			$.ajax({
      			url:'cgGetRecipientsFromFile.php',
 	     		type: "POST",
 	     		dataType : 'json',
    	  		data: {"numberofrecipients" : MAXUIRecipientRetrieval, "file" : filename},
      			success: function (data) 
      			{
	          		var buildtext =  "Uploaded ";
					buildtext = buildtext.concat(data.recipientcount);
					buildtext = buildtext.concat(" recipients..");
					if (data.recipientcount > 1000)
					{
						buildtext = buildtext.concat(" Due to browser limitations, only the first 1,000 rows are displayed");
					}
					document.getElementById("csvMessageText").value = buildtext;
					document.getElementById("recipientCount").value = data.recipientcount;
     				$('#csv').html(data.csvlist);
     				$('#json').val(data.jsonlist);
     				var lines = data.csvlist.split('\n');
    	 			var res = lines[0].match(/,\s*address\s*,/i);
	    			if (!res)
    				{
    					alert("The mandatory field, 'Address' is not found in the first row!  Email submissions will fail.");
    				}
      			},
      			error: function (results) {
          			$('#output').html('Bummer: there was an error!');
      			}
			})
		}
    },
    error: function (data) {
          $('#csv').html(data.responseText);
      }
   });
  }
 });
});

$(window).scroll(function () {
    if ($(window).scrollTop() > 200) {
        $(".header").addClass("fixed");
    } 
    else
    {
        $(".header").removeClass("fixed");
    }
});


</script> 

</body>
</html>

