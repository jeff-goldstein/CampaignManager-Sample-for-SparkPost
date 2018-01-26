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

File: cgKey.php
Purpose: Landing page for logging in.
-->
<!DOCTYPE html>
<html>
<head>
<title>Campaign Generator for SparkPost</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon" type="image/vnd.microsoft.icon">
<link rel="stylesheet" type="text/css" href="css/cgCommonFormatting.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="js/cgCommonScripts.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-86406300-1', 'auto');
  ga('send', 'pageview');
  
function checkcookieEnabled()
{
	submitButton = document.getElementById("submit");
	if(!navigator.cookieEnabled) 
	{
		submitButton = document.getElementById("submit");
		submitButton.disabled = true;
		//alert("Warning, cookies are not enabled for this browser but are necessary for security. \nSubmit button turned off.");
		showDialog("Warning, cookies are not enabled for this browser but are necessary for security. <br><br><center>Submit button turned off.</center>")
	}
	else
	{
		submitButton.disabled = false;
	}
}

function showDialog(text)
{
    $("#dialog-modal").html(text);
      $("#dialog-modal").dialog(
      {
        width: 600,
        height: 200,
        open: function(event, ui)
        {
          var textarea = $('<textarea style="height: 276px;">');
          $(textarea).html({
              focus: true,
              autoresize: false,
              initCallback: function()
              {
                  this.set('<p>Lorem...</p>');
              }
          });
        }
      });
}

function onSubmit ()
{
	var date = new Date();
	date.setTime(date.getTime() + (2 * 24 * 60 * 60 * 1000));  //The first number represents the number of days before expiring
	var expires = date.toUTCString();
	var key = document.getElementById("key").value;
	document.cookie = "sparkpostkey=" + key + "; expires=" + expires + ";";
	document.getElementById("key").value = "";
	var apiroot = document.getElementById("apiroot").value
	document.cookie = "sparkpostapiroot=" + apiroot + "; expires=" + expires + ";";
	document.getElementById("apiroot").value = "";
}

</script>
</head>

<body onload="checkcookieEnabled()">
<ul class="topnav" id="generatorTopNav">
	<li><a href="helpdocs/cgHelp.php">Help</a></li>
	<li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
	<li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
	<li class="icon">
    	<a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  	</li>
</ul>

<center>
    <br><h1>Campaign and Template Manager<br>Using the SparkPost Email Platform</h1><br><br>
</center>
<table border="0" width="75%" cellpadding="20">
    <tr>
        <td>
    		<form action="graphing/highchart.php" id="keyform" name="keyform" onsubmit="onSubmit()">
        		<h3>Your SparkPost API Key:</h3>
        		<input id="key" name="apikey" placeholder="API Key.." required=true type="text" autocomplete="off">
        		<br><br>
        		<h5>Optional for Enterprise/Elite Users: Enter your API Root URL: Empty will default to: https://api.sparkpost.com/api/v1/</h5> 
        		<input id="apiroot" name="apiroot" placeholder="API Root Directory" type="text" value="https://api.sparkpost.com/api/v1">
        		<br><br>
        		<input  type="submit" value="Submit" name="submit" id="submit">
    		</form>
    	</td>
    </tr>
</table>
<table border="0" width="75%" cellpadding="20">
    <tr>
        <td>
            Note: These API Keys are created from a SparkPost admin page from with either your or your ESP's SparkPost account.  
            Please remember that the SparkPost system only shows your API Key "once", so you need to keep the API Key safe where 
            you can get to it each time you use this or any application that needs an API Key.  If you loose the API Key you can always create a new one.<p>
                    
            At a minimum, your API key needs the following access: 'Recipient Lists: Read/Write, Templates: Read/Write, Transmissions: Read/Write, Sending Domains: Read and Message-Events: Read'.
            
            <!--<br><br><br><h2>For a secure link you may use <a href="https://www.trymsys.net/apps/campaigner1/cgKey.php"> Secured Campaign Generator</a></h2>-->
        </td>
    </tr>
</table>
<br><br><br>
<center>
    <table border="1" style="background-color:#a3e9f7" width="75%" cellpadding="20">
        <tr>
            <td>
                This tool is free to use at your own risk and should be up
                and running at all times but is not monitored for uptime.
                The Campaign Generator uses your API key to create, delete and update items from your 
                own SparkPost account.  The applications saves two types of data:
                <ul>
                	<li> Browser cookies for your credentials (which will expire after two days) 
                	<li> Any file uploads you use during Campaign creations are held on the server 
                </ul>
                The code behind this site is available from my Github repository at: <br><code><a href=
                "https://github.com/jeff-goldstein/sparkpost/tree/master/cgCampaignSubmission" target="_blank">
                https://github.com/jeff-goldstein/sparkpost/tree/master/cgCampaignSubmission</a>.</code>
            </td>
        </tr>
    </table>
</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
 <p><a href="javascript:void(null);" onclick="showDialog('Lorem ipsum dolor');">Open</a></p>

<div id="dialog-modal" title="Cookies Turned Off!" style="display: none;"></div>
<script>
  
    
    
</script>
*v1.05 Last Updated Dec 1, 2017<br>
<ul>
<li> Added Template Management
<li> Major code cleaning
</ul>
</body>
</html>
