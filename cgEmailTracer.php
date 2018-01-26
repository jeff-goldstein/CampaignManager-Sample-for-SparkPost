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

File: cgEmailTracer
Purpose: Show currently scheduled campaigns and allow user to cancel them

 -->
<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>Campaign Generator for SparkPost</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon">
<link href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css" rel="stylesheet">
<!--<link href="//code.jquery.com/ui/3.2.1/themes/base/jquery-ui.css" rel="stylesheet">-->
<link rel="stylesheet" type="text/css" href="css/tmCommonFormatting.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<!--<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />


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


#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

</style>
</head>

<body style="margin-left: 20px; margin-right: 20px">
<div class="header" style="width:1420px">
<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="cgBuildCampaign.php">Generate Campaign</a></li>
	<li><a class="active" href="cgTemplateManager.php">Template Manager</a></li>
	<li><a class="active" href="cgScheduled.php">Campaign Manager</a></li>
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
<div hidden id="showNOshow" data-show='["show", "show", "show", "show", "show"]'></div>
<br>
<table style="width:1420px; outline:red solid 1px">
<tr><td style="width:1420px;" colspan="3">
	<center><h1>Email Events</h1></center>
</td></tr>
<tr>
<td>What email address would you like to trace?
<input required id="emailaddress" name="emailaddress" type="longentry" style="width:200px">
&nbsp;&nbsp;&nbsp;<input type="button" id="getSelected" name="getSelected" onclick="getEmailEvents()" value="Search all Email Activity" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
&nbsp;&nbsp;&nbsp;<button hidden style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="printFunction()">Print this page</button>
&nbsp;&nbsp;&nbsp<button onclick="exportTableToCSV('')">Export To CSV File</button>
<!--&nbsp;&nbsp;&nbsp<button onclick="switchdisplay()">Show/Hide Details</button>-->
<br><br>
<textarea id="serverstatus" name="serverstatus" readonly rows=1 type="textnormal" style="border:none; width:100px; resize: none;">Server Results:</textarea>
<textarea id="servererror" name="servererror" readonly rows=1 type="textnormal" style="background-color: #f5f5f5; border:none; width: 1000px; resize: none;"></textarea>
	<table id="EventTable" style="width:1420px; border: 0px">
		<tr style="width=1420px">
			<td colspan="5" style="font-size: 20px;"><strong><center>Filters</center></strong></td>
		</tr>
		<tr >
			<td><input style="width:275px; border:0px" type="text" id="FromInput" onkeyup="FilterFunction()" placeholder="Filter by From Address.." title="Type in a From Address"></td>
			<td><input style="width:275px; border:0px" type="text" id="SubjectInput" onkeyup="FilterFunction()" placeholder="Filter by Subject.." title="Type in a Subject"></td>
			<td><input style="width:275px; border:0px" type="text" id="CampaignInput" onkeyup="FilterFunction()" placeholder="Filter by Campaign.." title="Type in a Campaign Name"></td>
			<td><input style="width:275px; border:0px" type="text" id="TypeInput" onkeyup="FilterFunction()" placeholder="Filter by Type.." title="Enter in a Type"></td>
			<td><input style="width:275px; border:0px" type="text" id="TimeInput" onkeyup="FilterFunction()" placeholder="Filter by Time/Date.." title="Type in a Time/Date"></td>
		</tr><tr>
			<td hidden>From&nbsp;&nbsp;<select style="color:#298272; font-size:12px;font-weight: bold;" id="fromSelect" name="fromSelect" onchange="filter('from')"></select></td>
			<td hidden>Campaign&nbsp;&nbsp;<select style="color:#298272; font-size:12px;font-weight: bold;" id="campaignSelect" name="campaignSelect" onchange="filter('campagin')"></select></td>
			<td hidden>Subject&nbsp;&nbsp;<select style="color:#298272; font-size:12px;font-weight: bold;" id="subjectSelect" name="subjectSelect" onchange="filter('subject')"></select></td>
			<td hidden>Time&nbsp;&nbsp;<select style="color:#298272; font-size:12px;font-weight: bold;" id="timeSelect" name="timeSelect" onchange="filter('time')"></select></td>
			<td hidden>Message Type&nbsp;&nbsp;<select style="color:#298272; font-size:12px;font-weight: bold;" id="typeSelect" name="typeSelect" onchange="filter('type')"></select></td>
		</tr>
	</table>
	<br><br>
<table id='EventtableDetails' border=1 style='width:1420px;'></table>

<br><br>

</table></center>
<p>&nbsp;&nbsp;&nbsp;* Times are showing GMT Time</p>
<div id="dialog-modal-full" title="Full Message Events Details!" style="display: none;"></div>
<div id="dialog-modal-ip" title="IP Address Lookup by freegeoip.net" style="display: none;"></div>

<script>
function getEmailEvents()
{
	//var apikey = getCookie("sparkpostkey");
	//var apiroot = getCookie("sparkpostapiroot");
	var emailaddress = document.getElementById("emailaddress").value;
    	
    $.ajax({
    	url:'phpResources/emailtracer.php',
    	type: "POST",
    	dataType : 'json',
    	data: {"emailaddress" : emailaddress},
    	beforeSend:function()
    	{
     		$('#servererror').html("Calling SparkPost server for data...");
     		document.getElementById("EventtableDetails").innerHTML = "";
     		document.getElementById("FromInput").innerHTML = "";
     		document.getElementById("SubjectInput").innerHTML = "";
     		document.getElementById("CampaignInput").innerHTML = "";
     		document.getElementById("TimeInput").innerHTML = "";
     		document.getElementById("TypeInput").innerHTML = "";
    	},
    	complete: function (response) 
    	{
        	document.getElementById("EventtableDetails").innerHTML = response.responseJSON.details;
        	document.getElementById("fromSelect").innerHTML = response.responseJSON.fromsearch;
        	document.getElementById("campaignSelect").innerHTML = response.responseJSON.campaignsearch;
        	document.getElementById("subjectSelect").innerHTML = response.responseJSON.subjectsearch;
        	document.getElementById("timeSelect").innerHTML = response.responseJSON.timesearch;
        	document.getElementById("typeSelect").innerHTML = response.responseJSON.typesearch;
        	//initialhide();
        	if (response.responseJSON.error.length == 0) $('#servererror').html("Retrieval Done"); else $('#servererror').html(response.responseJSON.error);
    	},
    	error: function (response) {
        	alert("Problem getting data from SparkPost Server!");
        	$('#servererror').html(response);
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


function FilterFunction() 
{
	var input, filter, table, tr, td, i;
	var showNOshow = $('#showNOshow').data('show');
	var input1 = document.getElementById("FromInput");
	var input2 = document.getElementById("SubjectInput");
	var input3 = document.getElementById("CampaignInput");
	var input4 = document.getElementById("TypeInput");
	var input5 = document.getElementById("TimeInput");
	filter1 = input1.value.toUpperCase();
	filter2 = input2.value.toUpperCase();
	filter3 = input3.value.toUpperCase();
	filter4 = input4.value.toUpperCase();
	filter5 = input5.value.toUpperCase();
	table = document.getElementById("EventtableDetails");
	tr = table.getElementsByTagName("tr");	
	for (i = 0; i < tr.length; i++)
	{
		td1 = tr[i].getElementsByTagName("td")[1];
    	td2 = tr[i].getElementsByTagName("td")[2];
    	td3 = tr[i].getElementsByTagName("td")[3];
    	td4 = tr[i].getElementsByTagName("td")[4];
    	td5 = tr[i].getElementsByTagName("td")[5];
    	if (td1 || td2 || td3 || td4 || td5 ) 
    	{
      		if ((td1.innerHTML.toUpperCase().indexOf(filter1) > -1) &&
      			(td2.innerHTML.toUpperCase().indexOf(filter2) > -1) &&
      			(td3.innerHTML.toUpperCase().indexOf(filter3) > -1) &&
      			(td4.innerHTML.toUpperCase().indexOf(filter4) > -1) &&
      			(td5.innerHTML.toUpperCase().indexOf(filter5) > -1)) 
      		{
        		tr[i].style.display = "";
      		}
      		else
      		{
        		tr[i].style.display = "none";
      		}
    	}       
  	}
}

function printFunction() {
    window.print();
}

function downloadCSV(csv, filename) 
{
// Sample code taken from https://www.codexworld.com/export-html-table-data-to-csv-using-javascript/
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function exportTableToCSV() 
{
// Sample code taken from https://www.codexworld.com/export-html-table-data-to-csv-using-javascript/
// Need to make changes to remove extra carriage return(s) that where at the end of all TD fields
//
    var csv = [];
    var rows = EventtableDetails.querySelectorAll("table tr");
    var filename = document.getElementById("emailaddress").value + ".csv";
    
    for (var i = 0; i < rows.length; i++) {
        var row = ""; 
        var cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
        {
        	if (j != 0)  // we don't want the detail checkbox downloaded
        	{
        		element = cols[j].innerText;
        		element = element.trim();
        		row = row.concat(element, ',');
        	}
        }
        csv.push(row);        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}

function show_details_alert( message_id, type)
{

$.ajax({
    	url:'phpResources/getSingleMessageEventDetails.php',
    	type: "POST",
    	dataType : 'json',
    	data: {"messageID" : message_id, "message_type" : type},
    	beforeSend:function()
    	{
     		$('#servererror').html("Calling SparkPost server for data...");
    	},
    	complete: function (response) 
    	{
        	var alertText = "Details for " + response.responseJSON.results[0].friendly_from + "\n";
        	alertText = alertText + "Subject: " + response.responseJSON.results[0].subject + "\n";
        	alertText = alertText + "Campaign: " + response.responseJSON.results[0].campaign_id + "\n";
        	alertText = alertText + "From: " + response.responseJSON.results[0].friendly_from + "\n";
        	alertText = alertText + "ip_address: " + response.responseJSON.results[0].ip_address + "\n";
        	if (response.responseJSON.results[0].type == "open" ||  response.responseJSON.results[0].type == "click")
        	{
        		if (response.responseJSON.results[0].geo_ip.city != "" || response.responseJSON.results[0].geo_ip.state != "" || response.responseJSON.results[0].geo_ip.region != "")
        		{
        			alertText = alertText + "Geo IP Location Estimate\n";
        			if (response.responseJSON.results[0].geo_ip.city != "")
        				alertText = alertText + "   City:   " + response.responseJSON.results[0].geo_ip.city + "\n";
        			if (response.responseJSON.results[0].geo_ip.country != "")
        				alertText = alertText + "   State:  " + response.responseJSON.results[0].geo_ip.country + "\n";
        			if (response.responseJSON.results[0].geo_ip.region != "")
        				alertText = alertText + "   Region: " + response.responseJSON.results[0].geo_ip.region + "\n";
        		}
        	}
        	alertText = alertText + "Template: " + response.responseJSON.results[0].template_id + "\n";
        	alertText = alertText + "Record Type: " + response.responseJSON.results[0].type + "\n";
        	alertText = alertText + "IP Pool: " + response.responseJSON.results[0].ip_pool + "\n";
        	alertText = alertText + "Message Size: " + response.responseJSON.results[0].msg_size + "\n";
        	alertText = alertText + "Number of Retries: " + response.responseJSON.results[0].num_retries + "\n";
        	alertText = alertText + "Queue Time: " + response.responseJSON.results[0].queue_time + "\n";
        	var metastring = JSON.stringify(response.responseJSON.results[0].rcpt_meta);
        	alertText = alertText + "Meta Data: " + metastring + "\n";
        	if (response.responseJSON.results[0].subaccount_id) alertText = alertText + "Sub Account: " +  response.responseJSON.results[0].subaccount_id + "\n";;
        	
        	alert (alertText);
        	$('#servererror').html("Retrieval Done");
        	uncheck();
    	},
    	error: function (response) {
        	alert("Problem getting data from SparkPost Server!");
        	$('#servererror').html(response);
    	}
    	});

}

function show_details_html( message_id, type)
{

$.ajax({
    	url:'phpResources/getSingleMessageEventDetails.php',
    	type: "POST",
    	dataType : 'json',
    	data: {"messageID" : message_id, "message_type" : type},
    	beforeSend:function()
    	{
     		$('#servererror').html("Calling SparkPost server for data...");
    	},
    	complete: function (response) 
    	{
        	var alertText = "Details for " + response.responseJSON.results[0].friendly_from + "<br>";
        	alertText = alertText + "Subject: " + response.responseJSON.results[0].subject + "<br>";
        	alertText = alertText + "Campaign: " + response.responseJSON.results[0].campaign_id + "<br>";
        	alertText = alertText + "From: " + response.responseJSON.results[0].friendly_from + "\<br>";
        	alertText = alertText + "ip_address: " + response.responseJSON.results[0].ip_address + "\<br>";
        	if (response.responseJSON.results[0].type == "open" ||  response.responseJSON.results[0].type == "click")
        	{
        		if (response.responseJSON.results[0].geo_ip.city != "" || response.responseJSON.results[0].geo_ip.state != "" || response.responseJSON.results[0].geo_ip.region != "")
        		{
        			alertText = alertText + "Geo IP Location Estimate<br>";
        			if (response.responseJSON.results[0].geo_ip.city != "")
        				alertText = alertText + "&nbsp;&nbsp;&nbsp;City: " + response.responseJSON.results[0].geo_ip.city + "<br>";
        			if (response.responseJSON.results[0].geo_ip.country != "")
        				alertText = alertText + "&nbsp;&nbsp;&nbsp;Country:  " + response.responseJSON.results[0].geo_ip.country + "<br>";
        			if (response.responseJSON.results[0].geo_ip.region != "")
        				alertText = alertText + "&nbsp;&nbsp;&nbsp;Region: " + response.responseJSON.results[0].geo_ip.region + "<br>";
        		}
        	}
        	alertText = alertText + "Template: " + response.responseJSON.results[0].template_id + "<br>";
        	alertText = alertText + "Record Type: " + response.responseJSON.results[0].type + "<br>";
        	alertText = alertText + "IP Pool: " + response.responseJSON.results[0].ip_pool + "<br>";
        	alertText = alertText + "Message Size: " + response.responseJSON.results[0].msg_size + "<br>";
        	alertText = alertText + "Number of Retries: " + response.responseJSON.results[0].num_retries + "<br>";
        	alertText = alertText + "Queue Time: " + response.responseJSON.results[0].queue_time + "<br>";
        	var metastring = JSON.stringify(response.responseJSON.results[0].rcpt_meta);
        	alertText = alertText + "Meta Data: " + metastring + "<br>";
        	if (response.responseJSON.results[0].subaccount_id) alertText = alertText + "Sub Account: " +  response.responseJSON.results[0].subaccount_id + "<br>";
        	var prettiertext = response.responseText.results[0];
        	var prettierJSON = JSON.stringify(prettiertext);
        	prettierJSON = prettierJSON.replace(/","/g, '",\n"');
        	prettierJSON = prettierJSON.replace(/"},"/g, '"},\n"');
        	prettierJSON = prettierJSON.replace(/"],"/g, '"],\n"');
        	alertText = alertText + "<textarea>" + prettierJSON + "</textarea>";
        	
        	showDialog (alertText);
        	$('#servererror').html("Retrieval Done");
        	uncheck();
    	},
    	error: function (response) {
        	alert("Problem getting data from SparkPost Server!");
        	$('#servererror').html(response);
    	}
    	});

}

function show_details_local_html( row )
{

	var raw = document.getElementById("EventtableDetails").rows[row].cells.item(11).innerHTML;
	var rawArray = [];

	rawArray = JSON.parse(raw);
	
    var alertText = "<center><strong>Details for " + rawArray.friendly_from + "</center></strong><br>";
    alertText = alertText + "Subject: " + rawArray.subject + "<br>";
    alertText = alertText + "Campaign: " + rawArray.campaign_id + "<br>";
    alertText = alertText + "From: " + rawArray.friendly_from + "\<br>";
    if(rawArray.ip_address) alertText = alertText + "ip_address: " + rawArray.ip_address + "\<br>";
    if (rawArray.type == "open" ||  rawArray.type == "click")
    {
        if (rawArray.geo_ip.city != "" || rawArray.geo_ip.state != "" || rawArray.geo_ip.region != "")
        {
        	alertText = alertText + "Geo IP Location Estimate<br>";
        	if (rawArray.geo_ip.city != "")
        		alertText = alertText + "&nbsp;&nbsp;&nbspCity: " + rawArray.geo_ip.city + "<br>";
        	if (rawArray.geo_ip.country != "")
        		alertText = alertText + "&nbsp;&nbsp;&nbspCountry:  " + rawArray.geo_ip.country + "<br>";
        	if (rawArray.geo_ip.region != "")
        		alertText = alertText + "&nbsp;&nbsp;&nbspRegion: " + rawArray.geo_ip.region + "<br>";
        }
    }
    alertText = alertText + "Template: " + rawArray.template_id + "<br>";
    alertText = alertText + "Record Type: <strong>" + rawArray.type + "</strong><br>";
    alertText = alertText + "IP Pool: " + rawArray.ip_pool + "<br>";
    alertText = alertText + "Message Size: " + rawArray.msg_size + "<br>";
    if(rawArray.num_retries) alertText = alertText + "Number of Retries: " + rawArray.num_retries + "<br>";
    if(rawArray.queue_time) alertText = alertText + "Queue Time: " + rawArray.queue_time + "<br>";
    if(rawArray.num_retries) 
    {
    	var metastring = JSON.stringify(rawArray.rcpt_meta);
    	alertText = alertText + "Meta Data: " + metastring + "<br>";
    }
    alertText = alertText + "<br><strong>Full Raw Data</strong>";
    if (rawArray.subaccount_id) alertText = alertText + "Sub Account: " +  rawArray.subaccount_id + "<br>";
    $.ajax({
    url:'phpResources/prettyprintJSON.php',
    	type: "POST",
    	dataType : 'text',
    	data: {"raw" : raw},
    	complete: function (response) 
    	{
			response = response.responseText.replace(/\\\//g, '\/');
			alertText = alertText + "<textarea readonly cols='83' style='background-color:#a3e9f7; resize:none; min-height:400px; height:200px;'>" + response + "</textarea>";
			showDialogFull (alertText);
		}
    });
    
    uncheck(row);
}

function ipLocLookup (ip_address)
{
    // Working on a way to get the address of an IP
    // But have to figure out CORS restrictions, never done it before
    var formedurl = "http://freegeoip.net/JSON/" + ip_address;
    $.ajax({
    url:formedurl,
    	type: "GET",
    	dataType : 'json',
    	complete: function (response) 
    	{
			output = response.responseText;
			// Latlong.net version
			//map = "http://latlong.net/c/?lat=" + response.responseJSON.latitude + "&long=" + response.responseJSON.longitude;
			// Google maps version
			map = "https://www.google.com/maps/@?api=1&map_action=map&center=" + response.responseJSON.latitude + "," + response.responseJSON.longitude + "&zoom=12&basemap=satellite";			
			$.ajax({
    			url:'phpResources/prettyprintJSON.php',
    			type: "POST",
    			dataType : 'text',
    			data: {"raw" : output},
    			complete: function (response) 
    			{
					cleanResponse = response.responseText.slice(0,-1);
					cleanResponse = cleanResponse.substr(1);
					alertText =  "<textarea readonly cols='83' style='border:none; resize:none; min-height:250px; height:250px;'>" + cleanResponse + "\n</textarea>";
					alertText = alertText + "<a href='" + map + "' target='_blank'>Map</a>";
					showDialogIPLoc (alertText);
					//alert(response.responseText);
				}})
		}
    }) 
}

function uncheck(row) 
{    
    
    //document.getElementById("EventtableDetails").rows[row].cells.item(0).checked = false;
    var checks = document.getElementsByName('detailcheck');
	for(var i = 0; i < checks.length; ++i)
	{
    	checks[i].checked = false;
	}
}

function switchdisplay() 
{    
    var loc = document.getElementsByName('loc');
    var ipaddress = document.getElementsByName('ipaddress');
    var metadata = document.getElementsByName('metadata');
    var subaccount = document.getElementsByName('subaccount');
	for(var i = 0; i < loc.length; ++i)
	{
		if(loc[i].style.display == "none")
    		loc[i].style.display = "block";
  		else
    		loc[i].style.display = "none";
    	
    	if(ipaddress[i].style.display == "none")
    		ipaddress[i].style.display = "block";
  		else
    		ipaddress[i].style.display = "none";
    	
    	if(metadata[i].style.display == "none")
    		metadata[i].style.display = "block";
  		else
    		metadata[i].style.display = "none";
    	
    	if(subaccount[i].style.display == "none")
    		subaccount[i].style.display = "block";
  		else
    		subaccount[i].style.display = "none";
	}
}

function initialhide() 
{    
    var loc = document.getElementsByName('loc');
    var ipaddress = document.getElementsByName('ipaddress');
    var metadata = document.getElementsByName('metadata');
    var subaccount = document.getElementsByName('subaccount');
	for(var i = 0; i < loc.length; ++i)
	{
		if(loc[i].style.display == "none")
    		loc[i].style.display = "block";
  		else
    		loc[i].style.display = "none";
    	if(ipaddress[i].style.display == "none")
    		ipaddress[i].style.display = "block";
  		else
    		ipaddress[i].style.display = "none";
    	if(metadata[i].style.display == "none")
    		metadata[i].style.display = "block";
  		else
    		metadata[i].style.display = "none";
    	if(subaccount[i].style.display == "none")
    		subaccount[i].style.display = "block";
  		else
    		subaccount[i].style.display = "none";
	}
}

function showDialogFull(text)
{
    $("#dialog-modal-full").html(text);
      $("#dialog-modal-full").dialog(
      {
        width: 900,
        height: 600,
        open: function(event, ui)
        {
          var textarea = $("<textarea style='background-color:blue'></textarea>");
          $(textarea).html({
              focus: true,
              autoresize: false,
              initCallback: function()
              {
                  this.set('<p>finders keepers</p>');
              }
          });
        }
      });
}

function showDialogIPLoc(text)
{
    $("#dialog-modal-ip").html(text);
      $("#dialog-modal-ip").dialog(
      {
        width: 500,
        height: 350,
        open: function(event, ui)
        {
          var textarea = $("<textarea readonly style='background-color:blue'></textarea>");
          $(textarea).html({
              focus: true,
              autoresize: true,
              initCallback: function()
              {
                  this.set('<p>finders keepers</p>');
              }
          });
        }
      });
}


</script>

</body>
</html>