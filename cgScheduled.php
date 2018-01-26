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

File: cgScheduled
Purpose: Show currently scheduled campaigns and allow user to cancel them

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
<link rel="stylesheet" type="text/css" href="css/tmCommonFormatting.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<style>
#tableformat 
{
    font-family: Helvetica, Arial;
    border-collapse: collapse;
    width: 100%;
    padding: 2px 2px 2px 20px;
}

#tableformat td, #customers th 
{
    border: 1px solid #ddd;
    padding: 8px;
}

#tableformat tr:nth-child(even){background-color: #fcf7f4;}

#tableformat tr:hover {background-color: #e5e4e3;}

#tableformat th 
{
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #a3e9f7;
    color: black;
}

th, td 
{
   border: 1px solid black;
   width: 600px;
   padding: 2px 2px 2px 2px;
   border-collapse: collapse;
}

tbody tr:nth-child(odd) 
{
   background-color: #e8f9f9;
}


</style>
</head>

<body style="margin-left: 20px; margin-right: 20px" onload="getScheduledCampaigns()">
<div class="header" style="width:1420px">
<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="cgBuildCampaign.php">Generate Campaign</a></li>
	<li><a class="active" href="cgTemplateManager.php">Template Manager</a></li>
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

<iframe src="http://free.timeanddate.com/clock/i5ze60a7/n5446/fs12/tt0/tw0/tm1/ta1/tb2" frameborder="0" width="201" height="16"></iframe>
</div>

<br>
<table style="width:1415px; outline:red solid 1px">
<tr><td style="width:1415px;" colspan="3">
	<center><h1>Scheduled Campaigns</h1></center>
</td></tr>
<tr>
<td>
<div class="selectTable">
	<table id="CampaignTable" type="#tableformat" style="width:1415px; border: 0px"><tbody></tbody></table></center>
</div>
<br><br>

<p><h3>&nbsp;&nbsp;&nbsp;Select the Campaign(s) you wish to cancel</h3>
<br>&nbsp;&nbsp;&nbsp;<input type="button" id="getSelected" name="getSelected" onclick="deletebuttonClicked()" value="Cancel Selected Campaign(s)" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" ><br><br>
<br><br><br>
<input readonly id="deletetext" style="border:none" value=""/>
<input readonly id="Deletedids" style="width:1200px; border: none" value=""/>

</table></center>
<p>&nbsp;&nbsp;&nbsp;* Your Campaign Time has been converted to (and showing) GMT Time</p>
<p>&nbsp;&nbsp;&nbsp;* Due to the speed of the cancels versus SparkPost being able to update their repository fast enough a 10 second delay has been added to this function before re-building the table of your deletions complete.</p>

<script>
function getScheduledCampaigns ()
{
	var apikey = getCookie("sparkpostkey");
	var apiroot = getCookie("sparkpostapiroot");
    	
    $.ajax({
    	url:'phpResources/buildFullScheduledCampaignTable.php',
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

function deleteScheduledCampaign (terminateThese)
{
	var apikey = getCookie("sparkpostkey");
	var apiroot = getCookie("sparkpostapiroot");
	//var templateid = document.getElementById("id").value;
    	
    $.ajax({
    	url:'phpResources/deleteScheduledCampaign.php',
    	type: "POST",
    	data: {"DeleteArray" : terminateThese},
    	success: function (response) 
    	{
        	document.getElementById("Deletedids").value = response;
    	},
    	error: function (response) {
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

function generatorNav() {
    var x = document.getElementById("generatorTopNav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}


function deletebuttonClicked() 
{
    var terminateThese = [];
    $('.selectTable input[type="checkbox"]:checked').each(function() {
      terminateThese.push($(this).val());
    });
    document.getElementById("deletetext").value = "Deleting Campaigns(s): ";
    document.getElementById("Deletedids").value = terminateThese.join(', ');
    deleteScheduledCampaign(terminateThese);
    document.getElementById("deletetext").value = "Deleted Campaigns(s): ";
    //document.getElementById("Deletedids").value = terminateThese.join(', ');
    var delayInMilliseconds = 10000; //10 seconds
	setTimeout(function() 
	{
		getScheduledCampaigns();
	}, 
	delayInMilliseconds);
};

function changeall()
{
	var checked = document.getElementById('mastercheck').checked;
	if (checked) checkall(); else uncheckall();		
}

function uncheckall() 
{    
    var checks = document.getElementsByName('isSelected');
	for(var i = 0; i < checks.length; ++i)
	{
    	checks[i].checked = false;
	}
}

function checkall() 
{        
    var checks = document.getElementsByName('isSelected');
	for(var i = 0; i < checks.length; ++i)
	{
    	checks[i].checked = true;
	}
}

</script>

</body>
</html>