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
File: cgTemplateManager.php 
Launch a campaign with entered data
//
 -->


<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1" name="viewport">
<meta http-equiv="pragma" content="no-cache" />
<title>Template Manager</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon">
<link href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<!--added
<script src="htmltotext/bin/cli.js"></script>
--!>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/tmCommonFormatting.css">
<script src="tinymce/js/tinymce/tinymce.min.js"></script>

<script> tinymce.init({
  selector: '#tinyhtmldraftarea',
  height: 500,
  plugins: [
        "advlist autolink lists link image charmap print anchor nonbreaking",
        "searchreplace visualblocks visualchars fullscreen help hr textcolor colorpicker",
        "insertdatetime media table contextmenu paste imagetools code"
    ],
    toolbar: "insertfile undo redo | styleselect | fontsizeselect | fontselect | bold italic | forecolor backcolor | bullist numlist outdent indent | link image | help",
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    browser_spellcheck: true,
    themes: "modern",
    skin : "pepper-grinder",
   // imagetools_cors_hosts: ['www.tinymce.com', 'codepen.io'],
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ]
})

</script>
<script src="tinymce/js/cnvrt.js"></script>
</head>

<body style="margin-left: 20px; margin-right: 20px" onload="myOnloadFunction()">

<style>
h3 {
    display: inline;
    font-size: 1em;
    margin-top: 1em;
    margin-bottom: 0em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #298272;
    font-family: Helvetica, Arial;
    }
</style>

<?php
	ini_set('post_max_size', '2000000');
    $parametersFile = "config/Parameters.ini";
    $paramonly_array = parse_ini_file( $parametersFile, true );
    $cat = "local";
    $masterapikey = $paramonly_array[$cat]["API"];
?>
<input id="apikey" name="apikey" type="hidden" value="">
<input id="apiroot" name="apiroot" type="hidden" value="">
<table border=0; width="1220px">
<tr><td>
<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="cgBuildCampaign.php">Campaign Manager</a></li>
    <li><a class="active" href="cgScheduled.php">Manage Scheduled Campaigns</a></li>
	<li><a class="active" href="cgEmailTracer.php">Email Tracer</a></li>
	<li><a class="active" href="graphing/highchart.php">Reporting</a></li>
	<li><a href="helpdocs/tmHelp.php">Help</a></li>
	<li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
	<li><a href="mailto:email.goldstein@gmail.com?subject=Template Manager">Contact</a></li>
    <li><a class="active" href="cgKey.php">Log Out</a></li>
	<li class="icon">
    	<a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
	</li>
</ul>
</td></tr>
<tr><td align="left">
<iframe src="http://free.timeanddate.com/clock/i5ze60a7/n5446/fs12/tt0/tw0/tm1/ta1/tb2" frameborder="0" width="201" height="16"></iframe>
<td></tr></table>

<br>
<table style="width:1200px; padding-left: 20px; padding-right: 20px; border-color:red" border=1>
    <tr>
    	<td>
        	<center><h1>Template Manager</h1></center>
        	<table style="background-color:#FFFFFF; width:1200px;">
            <td style="padding-left: 8px; padding-right: 8px;">
                        <h4>Enter in a New Template Name*</h4>
                        <input id="newTemplateName" name="newTemplateName" type="longentry">
                        <h4>OR Select from a Previously Stored Template*</h4>
                        <select id="templateList" name="templateList" onchange="updateTemplateField()"></select>
				<br>
				<h4>Template Code/Text</h4>
				<div class="container" style="padding-right:4px;padding-left:1px;margin-right:2px; max-width:1200px;">
  					<ul class="nav nav-pills">
    					<li class="active"><a data-toggle="pill" href="#draft">Draft</a></li>
    					<li><a data-toggle="pill" href="#published">Published</a></li>
    					<li><a data-toggle="pill" href="#quicktest">Quicktest</a></li>
    					<li><a data-toggle="pill" href="#emailhelp">Help</a></li>
					</ul>
					<br>
  					<table style="background-color:#F6F6F6; padding:10px padding-left: 8px; padding-right: 8px;width:100%;"><tr>
  					<td width="20px"></td>
					<td>
  				<div class="tab-content" style="padding-right:4px;padding-left:1px;margin-right:2px;max-width:1200:px;">
    					<div id="draft" class="tab-pane fade in active" style="padding-right:4px;padding-left:1px;margin-right:2px; max-width:1200:px;">
      						<br><h3>Draft Template Management</h3> (sub-menu)<br><br>
      						<ul class="nav nav-pills">
    							<li class="active"><a data-toggle="pill" href="#drafthtml">HTML</a></li>
    							<li><a data-toggle="pill" href="#drafttext">Text</a></li>
    							<li><a data-toggle="pill" href="#draftpreview">Preview</a></li>
  	  						</ul>
      						<div class="tab-content">
    							<div id="drafthtml" class="tab-pane fade in active">
      								<br>
      								<input type="button" name="savedraft" value="Save Draft" data-toggle="tooltip" title="Will save draft only" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('draft')">
      								<input type="button" name="publishdraft" value="Save and Publish" data-toggle="tooltip" title="Will save Draft and Create as Current Published Version" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('publish')">
      								<input type="button" name="convert2text" value="Convert to Text" data-toggle="tooltip" title="Create Text version of HTML Structure" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="myhtml2text()">
      								<br><br>
      								<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>Both the HTML and Text versions are saved/published as pairs.  <strong>A name in the <i>New Template Name</i> field will override the stored template name, and be used when saving and/or publishing. This can be used as a way to duplicate an already stored template.</strong></p>
      								<textarea style="resize: vertical; font-size: 8pt; width:100%;max-width:1200px;" id="tinyhtmldraftarea" maxlength="200000" name="tinyhtmldraftarea" type="textdataentry" class="text" wrap="off"></textarea>
									<br>
    							</div>
    							<div id="drafttext" class="tab-pane fade">
      								<br>
      								<input type="button" value="Save Draft" data-toggle="tooltip" title="Will save draft only" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('draft')">
      								<input type="button" value="Save and Publish" data-toggle="tooltip" title="Will save Draft and Create as Current Published Version" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('publish')">
      								<br><br>
      								<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>Both the HTML and Text versions are saved/published as pairs.  <strong>A name in the <i>New Template Name</i> field will override the stored template name, and be used when saving and/or publishing. This can be used as a way to duplicate an already stored template.</strong></p>
      								<textarea style="font-size: 8pt;width:100%;max-width:1200:px;" id="drafttextCode" maxlength="200000" name="drafttextCode" type="textdataentry" class="text" wrap="off" placeholder='email text, substitituion allowed'></textarea>
    								<br><br>
    							</div>
    							<div id="draftpreview" class="tab-pane fade">
      								<br>
      								<input type="button" value="Save Draft" data-toggle="tooltip" title="Will save draft only" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('draft')">
      								<input type="button" value="Save and Publish" data-toggle="tooltip" title="Will save Draft and Create as Current Published Version" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('publish')">
      								<br><br>
      								<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>Both the HTML and Text versions are saved/published as pairs.  <strong>A name in the <i>New Template Name</i> field will override the stored template name, and be used when 
      								<br>saving and/or publishing. This can be used as a way to duplicate an already stored template.</strong></p>
                    				<input type="button" name="previewdrafthtml" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview('draft', 'html')" value="Preview Draft HTML Version">
                    				<input type="button" name="previewdrafttext" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview('draft', 'text')" value="Preview Draft Text Version">
                    				<br>
                    				<br>
                    				<iframe height="600" id="draftpreviewiframe" name="draftpreviewiframe" width="100%" style="background: #FFFFFF;" srcdoc="Make sure you selected a Template and added any substitution data if you want to see a full sample."></iframe>
    								<br><br>
    							</div>
    						</div>
  						</div>
 						<div id="published" class="tab-pane fade in">
      						<br><h3>Published Template Management</h3> (sub-menu)<br><br>
      						<ul class="nav nav-pills">
    							<li class="active"><a data-toggle="pill" href="#publishedhtml">HTML</a></li>
    							<li><a data-toggle="pill" href="#publishedtext">Text</a></li>
    							<li><a data-toggle="pill" href="#publishedpreview">Preview</a></li>
  	  						</ul>
      						<div class="tab-content">
    							<div id="publishedhtml" class="tab-pane fade in active">
      								<br>
      								<input type="button" value="Copy to Draft" data-toggle="tooltip" title="Both the HTML and Text versions are copied" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="makedraft()">
                    				<br><br>
                    				<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>The following READONLY HTML represents the <i>Published</i> version of your template.  By pressing the above button, you may move the HTML and Text over to the Draft fields for editing
      								<br>which will overwrite any work you may of done in the draft section.  If the Published HTML or Text field is empty, that published field will not overwrite the draft field.</p>
      								<textarea readonly style="resize: vertical; font-size: 8pt;width:100%;max-width:1200px" id="publishedhtmlCode" maxlength="200000" cols="140" name="publishedhtmlCode" type="textdataentry" class="text" wrap="off" placeholder='Template with published HTML data has not been selected.'></textarea>
    								<br><br>
    							</div>
    							<div id="publishedtext" class="tab-pane fade">
      								<br>
      								<input type="button" value="Copy to Draft" data-toggle="tooltip" title="Both the HTML and Text versions are copied" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="makedraft()">
      								<br><br>
                    				<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>The following READONLY TEXT represents the <i>Published</i> version of your template.  By pressing the above button, you may move the HTML and Text over to the Draft fields for editing
      								<br>which will overwrite any work you may of done in the draft section.  If the Published HTML or Text field is empty, that published field will not overwrite the draft field.</p>
      								<textarea readonly style="resize: vertical; font-size: 8pt;width:100%;max-width:1200px" id="publishedtextCode" maxlength="200000" cols="140" name="publishedtextCode" type="textdataentry" class="text" wrap="off" placeholder='Template with published TEXT data has not been selected.'></textarea>
    								<br><br>
    							</div>
    							<div id="publishedpreview" class="tab-pane fade">
      								<br>
                    				<input type="button" name="copytodraft" value="Copy to Draft" data-toggle="tooltip" title="Both the HTML and Text versions are copied" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="makedraft()">
                    				<br><br>
                    				<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>When pressing <i>Copy to Draft</i>, you will move the HTML and Text over to the Draft fields for editing, which will overwrite any work you may of done in the draft section.  
      								<br>If the Published HTML or Text field is empty, that published(s) field will not overwrite the draft field(s).</p>
                    				<input type="button" name="previewpubhtml" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview('published', 'html')" value="Preview Published HTML Version">
                    				<input type="button" name="previewpubtext" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview('published', 'text')" value="Preview Published Text Version">
                    				<br><br>
                    				<iframe height="600" id="publishedpreviewiframe" name="publishedpreviewiframe" width="100%" style="background: #FFFFFF;" srcdoc="Make sure you selected a Template and added any substitution data if you want to see a full sample."></iframe>
    								<br><br>
    							</div>
    						</div>
  						</div>
  						<div id="quicktest" class="tab-pane fade in">
      						<br>
                			<input id="quicktestdraft" type="button" value="Test Send Draft Version (with no data)" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="sendTestEmails('list', 'draft')">
                			<input id="quicktestpublished" type="button" value="Test Send Published Version (with no data)" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="sendTestEmails('list', 'published')">
                    		<br><br>
                    		<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>This feature allows for a quick and easy way to send test emails to a list of comma separated email names.  When using this feature, no recipient data is used  
                    		<br>even if already entered</i>.  Because some complex templates may fail without certain substitution data, it is recommended that you test your email with the <i>Preview</i> feature first.
                    		<br>That feature will warn you if you need to enter data before sending test emails.</p>
                    		<textarea id="emailnames" maxlength="2000" cols="140" name="emailnames" style="resize: vertical; font-size: 8pt;width:100%;max-width:1200px" wrap="off" type="textdataentry" class="text" placeholder='Please enter in a comma seperated list of email names.  This field will override any other data within the CSV or JSON tabs.
"But any Global Data will be used if entered"'></textarea>
                    		<br><br>
  						</div>
  						<div id="emailhelp" class="tab-pane fade in">
  							<h4>Help</h4>
                    		<iframe height="600" id="emailtemplatehelp" name="emailtemplatehelp" width="100%" style="background: #FFFFFF;" srcdoc="Email Template Help file is Missing if you see this text."></iframe>
  							<br><br>
  						</div>
					</div>
					</td>
					<td width="20px">
					</tr></table>
				</div>
				
                <input id="Recipients" name="Recipients" type="hidden" value="cgJson">
                <br>
                <h4>Test Recipient Data</h4>
                <div class="container" style="padding-right:4px;padding-left:1px;margin-right:2px;max-width:1200px;">
  					<ul class="nav nav-pills">
    					<li class="active"><a data-toggle="pill" href="#CSV">Recipient CSV</a></li>
    					<li><a data-toggle="pill" href="#JSON">Recipient JSON</a></li>
    					<li><a data-toggle="pill" href="#GlobalData">Global</a></li>
    					<li><a data-toggle="pill" href="#DataHelp">Help</a></li>
					</ul>
					<br>
  					<table style="background-color:#F6F6F6; padding:10px padding-left: 8px; padding-right: 8px;width:100%;"><tr>
  					<td width="20px"><td>
					<td>
  					<div class="tab-content">
 						<div id="CSV" class="tab-pane fade in active">
                			<br>
                			<input type="button" name="convertButton" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 10px; background-color: #72A4D2;" onclick="convert()" value="Convert CSV to JSON">
        					<br><br>
                    		<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>All Recipient data must be in JSON format to be used by the email server.  This feature allows you to enter the data in CSV format and to convert it into a JSON
                    		<br>structure that the email system understands.  Pressing the 'Convert CSV to JSON' button will update the 'Recipient JSON' tab.</p>
                    		<textarea id="csv" maxlength="200000" cols="140" name="csv" style="resize: vertical; font-size: 8pt;width:100%;max-width:1200px" wrap="off" type="textdataentry" class="text" placeholder='"address","UserName","substitution","first","id","city"
"jeff.goldstein@sparkpost.com","Sam Smith","_","Sam",342,"Princeton"
"austein@hotmail.com","Fred Frankly","_","Fred",38232,"Nowhere"
"jeff@geekswithapersonality.com","Zachary Zupers","_","Zack",9,"Hidden Town"'></textarea>
                    		<br><br>
                		</div>
                		<div id="JSON" class="tab-pane fade in">
                			<br>
 							<input type="button" name="isjson" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 10px; background-color: #72A4D2;" onclick="IsJsonString()" value="Validate JSON Structure">
                    		<br><br>
                    		<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>When entering the JSON data manually (or pasting into the field) please note the specific structure.  You may enter in recipient data for <i>as many people as you wish</i>.  
                    		<br>Each recipient will get their own email using their own specific test data entered into this field.</p>
                    		<textarea id="json" name="json" style="resize: vertical; font-size: 8pt;width:100%;max-width:1200px" class="text" wrap="on" maxlength="675000" cols="140" placeholder='{"recipients":[
{"address":"jeff.goldstein@sparkpost.com","UserName":"Sam Smith","substitution_data":{"first":"Sam","id":"342","city":"Princeton"}},
{"address":"austein@hotmail.com","UserName":"Fred Frankly","substitution_data":{"first":"Fred","id":"38232","city":"Nowhere"}},
{"address":"jeff@geekswithapersonality.com","UserName":"Zachary Zupers","substitution_data":{"first":"Zack","id":"9","city":"Hidden Town"}}
]}'></textarea>
                			<br><br>
                		</div>
                		<div id="GlobalData" class="tab-pane fade in">
                			<br>
 							<p style='color:black; font-family: Helvetica, Arial;font-size:10px;'>Optional: Global data is substitution data that is used for all recipients.  For testing you may enter up to 70k of global substitution data that must be in a JSON format.</p>
                    		<textarea id="globalsub" name="globalsub" style="resize: vertical; font-size: 8pt; width:100%; max-width:1200px;" class="text" wrap="off" maxlength="70000" cols="140" placeholder='"substitution_data": {
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
							<br><br>
						</div>
  						<div id="DataHelp" class="tab-pane fade in">
  							<h4>Help</h4>
                    		<iframe height="600" id="subanddatahelp" name="subanddatahelp" width="100%" style="background: #FFFFFF;" srcdoc="Substitution Help file is Missing if you see this text."></iframe>
  							<br><br>
  						</div>
					</div>
				</div>
				</td><td width="20px"><td></tr></table>
			</div>       
            	<br>
                <br>
                <table style="width:1200px;" border=3>
				<caption><center><h2>Template Settings</h2></center></caption>
               	<tr>
            	<th><center><h2>Draft</h2></center></th>
				<th><center><h2>Published (read only)</h2></center></th>
				</tr>
            
                <tr><td style="width:600px; padding:10px;">
                	<h3>From Name</h3> (A Friendly from for your recipients)<br>
                	<input id="drafttemplateFromName" name="drafttemplateFromName" type="textnormal" value="">
                	<br>
                	<br>
                	<h3>From Email*</h3>
                	<br>
                	<input id="draftfromemailname" name="draftfromemailname" type="text">@<select id="draftfromemaildomain" name="draftfromemaildomain"></select>
                	<br>
                	<br>
                	<h3>Reply To</h3> (An email address to reply to)<br>
                	<input id="drafttemplatereplyto" name="drafttemplatereplyto" type="textnormal" value="">
                	<br>
                	<br>
                	<h3>Subject*</h3>
                	<br>
                	<input id="drafttemplateSubject" name="drafttemplateSubject" type="longentry" value="">
                	<br>
                	<br>
                	<h3>Description</h3>
                	<br>
                	<input id="drafttemplateDescription" name="drafttemplateDescription" type="longentry" value="">
                	<br>
                	<br>
                	<h3>Engagement Tracking</h3>
                	<br>
                	<input checked id="drafttemplateOpenTracking" name="drafttemplateOpenTracking" type="checkbox" value="T"> Turn on Open Tracking<br>
                	<input checked id="drafttemplateClickTracking" name="drafttemplateClickTracking" type="checkbox" value="T"> Turn on Click Tracking<br>
                	<br>
                </td>
                <td style="width:600px; padding:10px;">
                	<h3>From Name</h3> (A Friendly from for your recipients)<br>
                	<input readonly id="publishedtemplateFromName" name="publishedtemplateFromName" type="textnormal" value="">
                	<br>
                	<br>
                	<h3>From Email*</h3>
                	<br>
                	<input readonly id="publishedfromemailname" name="publishedfromemailname" type="textnormal" style='width:250px;'>@
                	<input readonly id="publishedfromemaildomain" name="publishedfromemaildomain" type="textnormal" style='width:275px;'>
                	<br>
                	<br><br>
                	<h3>Reply To</h3> (An email address to reply to)<br>
                	<input readonly id="publishedtemplatereplyto" name="publishedtemplatereplyto" type="textnormal" value="">
                	<br>
                	<br>
                	<h3>Subject*</h3>
                	<br>
                	<input readonly id="publishedtemplateSubject" name="publishedtemplateSubject" type="longentry" value="">
                	<br>
                	<br>
                	<h3>Description</h3>
                	<br>
                	<input readonly id="publishedtemplateDescription" name="publishedtemplateDescription" type="longentry" value="">
                	<br>
                	<br>
                	<h3>Engagement Tracking</h3>
                	<br>
                	<input onclick="return false;" checked id="publishedtemplateOpenTracking" name="publishedtemplateOpenTracking" type="checkbox" value="T"> Turn on Open Tracking<br>
                	<input onclick="return false;" checked id="publishedtemplateClickTracking" name="publishedtemplateClickTracking" type="checkbox" value="T"> Turn on Click Tracking<br>
                	<br>
                </td></tr>
                </table><br><br>
                <input id="sendtestdraft" type="button" value="Send Test Draft Email(s) with Data" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="sendTestEmails('usejson', 'draft')">
                &nbsp;&nbsp;<input id="sendtestpublished" type="button" value="Send Test Published Email(s) with Data" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="sendTestEmails('usejson', 'published')">
                &nbsp;&nbsp;<input name="deletetemplate" type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="CRUDtemplate('delete')" value="Delete Template">
                &nbsp;&nbsp;<input name="getevents" type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="getEvents()" value="Retrieve Sending Results">
                <br>
    			<br>
                <h3>Results from email Server</h3><br><br>
                <iframe height="200" id="serverresults" name="serverresults" width="100%" style="background: #FFFFFF; font-family: courier; font-size: 10px;" srcdoc="Server Output."></iframe>
            </td>
        </table>
    	</td>
    </tr>
</table>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Mandatory fields


<script type="text/javascript">

function myOnloadFunction()
{
//
// Build the list of Available templates
// Obtain Supporting Help Text
//
	document.getElementById("apikey").value = getCookie("sparkpostkey");
	document.getElementById("apiroot").value = getCookie("sparkpostapiroot");
	var apikey = document.getElementById("apikey").value;
	var apiroot = document.getElementById("apiroot").value;
	    
    $.ajax({
        url : "helpdocs/tmtemplatehelp.html",
        dataType: "text",
        success : function (data) {
            document.getElementById("emailtemplatehelp").srcdoc = data;
        }
    });
    
    $.ajax({
        url : "helpdocs/tmsubanddatahelp.html",
        dataType: "text",
        success : function (data) {
            document.getElementById("subanddatahelp").srcdoc = data;
        }
    });
    
    templateListBuilder("");
    
    $.ajax({
    url:'phpResources/buildSendingDomainList.php',
    type: "POST",
    complete: function (response) 
    {
        document.getElementById("draftfromemaildomain").innerHTML = response.responseText;
    },
    error: function () 
    {
        alert("Problem obtaining stored domain list?  Does your api key have access?");
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

function myhtml2text()
{
//
// Translate the Draft HTML code into a Text template
//
// Called by 'convert2text' button
//
	var templateHTML = tinymce.get('tinyhtmldraftarea').getContent();

	$.ajax({
      url:'phpResources/translatehtmltotext.php',
      type: "POST",
      data: {"html" : templateHTML},
      complete: function (response) 
      {
          document.getElementById("drafttextCode").value = response.responseText;
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    }); 
    return false;
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
        document.getElementById("serverresults").srcdoc = "Bad Json String, Jsonlint: https://jsonlint.com/ is a good validation tool.";
        return false;
    }
    document.getElementById("serverresults").srcdoc = "Good Json String";
    return true;
}

function generatePreview(draftorpublished, htmlortext)
{
//
// Previews can be any combination of Draft/HTML, Draft/Text, Published/HTML or Published/Text
// Because the output if an iframe which does not leverage text encoding like /n or /t, output for Text is placed into a HTML textarea which does support that encoding
// This code also checkes for non-existent macro calls.  Calling a non-existent macro on purpose is a trick that can be used to validate against missing data
//    so this function has special code just for that error message
//
// Called by 'previewdrafthtml, previewdrafttext, previewpubhtml, previewpubtext' buttons
//
	if (draftorpublished == 'draft') 													// grab all draft input
	{ 
		var templateHTML = tinymce.get('tinyhtmldraftarea').getContent();
		var templateText = document.getElementById('drafttextCode').value;  
	} 
	else 																				// or grab all published input
	{ 
		var templateHTML = document.getElementById('publishedhtmlCode').value;
		var templateText = document.getElementById('publishedtextCode').value;
	}	
    if (htmlortext == 'html') { var templatecode = templateHTML; } else { var templatecode = templateText }  	// decide which preview is being requested HTML or Text
    var recipient = "cgJson";																					// Tell BuildPreview that recipient data is coming from a JSON structure and not to get the data from a recipient list
    var convertedJson = document.getElementById("json").value;
    var globalsub = document.getElementById("globalsub").value;
    if (draftorpublished == 'draft') var fromemaildomain = document.getElementById("draftfromemaildomain").value; else fromemaildomain = document.getElementById("publishedfromemaildomain").value;
    var localpart = "anything";
    

    $.ajax({
      url:'phpResources/buildPreviewWithEnteredTemplateCode.php',
      type: "POST",
      data: {"templatecode" : templatecode, "recipients" : recipient, "entered" : convertedJson, "globalsub" : globalsub , "fromemaildomain" : fromemaildomain.value, "localpart" : localpart},
      complete: function (response) 
      {
      	  if (htmlortext == 'text')
          {
             var pretext = '<textarea style="height:1200px; width:1200px; border: none;">';
             var posttext = '</textarea>';
             var fulltext = pretext.concat(response.responseText, posttext);
           }  
          if (htmlortext == 'html')
          {
          	if (draftorpublished == 'draft') { $('#draftpreviewiframe').contents().find('html').html(response.responseText); } else { $('#publishedpreviewiframe').contents().find('html').html(response.responseText); }
          }
          else
          {
          	if (draftorpublished == 'draft') { $('#draftpreviewiframe').contents().find('html').html(fulltext); } else { $('#publishedpreviewiframe').contents().find('html').html(fulltext); }
          }
          var strCheck1 = "attempt to call non-existent macro";
          var strCheck2 = "crash";
          var location1 = response.responseText.search(strCheck1);
          var location2 = response.responseText.search(strCheck2);
          if (location1 > 0  && location2 > 0)
          {
              alert("Warning!! Your data protection check was triggered, missing necessary test data!");
          }
          else
          {  
              var strCheck = "Matching Problem";
              var location = response.responseText.search(strCheck);
              if (location > 0) 
              {
                  alert("Warning!! Template & data error detected; please see preview box for further details");
              }
          }
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    }); 
    return false;
}

function updateTemplateField()
{
//
// Called when a new item in the Template List is called.  Update both the Published and Draft fields simultaneously
//
// Called by the 'templateList' onchange method
//
	var templateList = document.getElementById("templateList");
    
    $.ajax({
      url:'phpResources/retrieveTemplateData.php',
      type: "POST",
      dataType: 'json',
      data: {"template" : templateList.value},
      success: function (response) 
      {
        document.getElementById("drafttextCode").value = response.drafttemplateText;
        document.getElementById("publishedtextCode").value = response.publishedtemplateText;
        document.getElementById("publishedhtmlCode").value = response.publishedtemplateHTML;
        //document.getElementById("tinyhtmlarea").value = response.drafttemplateHTML;
        tinymce.get('tinyhtmldraftarea').setContent(response.drafttemplateHTML);
        //tinymce.get('publishedhtmlCode').setContent(response.publishedtemplateHTML);
        
        document.getElementById("drafttemplateFromName").value = response.drafttemplateFromName;
        document.getElementById("draftfromemailname").value = response.drafttemplateSubDomainFirstHalfName;
        document.getElementById("draftfromemaildomain").value = response.drafttemplateSubDomainSecondHalf;
        document.getElementById("drafttemplateSubject").value = response.drafttemplateSubject;
        document.getElementById("drafttemplatereplyto").value = response.drafttemplateReplyto;
        document.getElementById("drafttemplateDescription").value = response.drafttemplateDescription;
        if ( response.drafttemplateOpenTracking == true ) document.getElementById("drafttemplateOpenTracking").checked = true; else document.getElementById("drafttemplateOpenTracking").checked = false;
        if ( response.drafttemplateClickTracking == true ) document.getElementById("drafttemplateClickTracking").checked = true; else document.getElementById("drafttemplateClickTracking").checked = false;
        
        document.getElementById("publishedtemplateFromName").value = response.publishedtemplateFromName;
        document.getElementById("publishedfromemailname").value = response.publishedtemplateSubDomainFirstHalfName;
        document.getElementById("publishedfromemaildomain").value = response.publishedtemplateSubDomainSecondHalf;
        document.getElementById("publishedtemplateSubject").value = response.publishedtemplateSubject;
        document.getElementById("publishedtemplatereplyto").value = response.publishedtemplateReplyto;
        document.getElementById("publishedtemplateDescription").value = response.publishedtemplateDescription;
        if ( response.publishedtemplateOpenTracking == true ) document.getElementById("publishedtemplateOpenTracking").checked = true; else document.getElementById("publishedtemplateOpenTracking").checked = false;
        if ( response.publishedtemplateClickTracking == true ) document.getElementById("publishedtemplateClickTracking").checked = true; else document.getElementById("publishedtemplateClickTracking").checked = false;
		document.getElementById("serverresults").srcdoc = "";
		$('#draftpreviewiframe').contents().find('html').html("");
		$('#publishedpreviewiframe').contents().find('html').html("");
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    });
    
    return false;
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
          document.getElementById("serverresults").srcdoc = 'Bummer: there was an error calling the convert program phpResources/convertCSV2SparkPostJSON.php!';
      }
    });
	
}


function sendTestEmails(dataflag, draftpublished)
{
//
//  Sends emails either from the QuickTest that only uses email names and the template for the email
//  or sends emails using the template and all entered substitution data
//
//  Called by the following buttons, 'quicktestdraft, quicktestpublished, sendtestpublished, sendtestdraft'
//
    if (dataflag == "usejson") {var recipients = "json";} else {var recipients = "list"};											// 'json' represents using entered substitution data while 'list' represent using the QuickTest function without substitution data
    if (dataflag == "usejson") {var convertedJson = document.getElementById("json").value;} else {var convertedJson = ""};
    if (dataflag == "usejson") {var globalsub = document.getElementById("globalsub").value;} else {var globalsub = ""};
    var emailaddresses = document.getElementById("emailnames").value;																// get list of comma separated emails in case they were entered
    if (draftpublished == 'draft') var fromname = document.getElementById("drafttemplateFromName").value; else var fromname = document.getElementById("publishedtemplateFromName").value;
    if (draftpublished == 'draft') var fromemailname = document.getElementById("draftfromemailname").value; else var fromemailname = document.getElementById("publishedfromemailname").value;
    if (draftpublished == 'draft') var fromemaildomain = document.getElementById("draftfromemaildomain").value; else fromemaildomain = document.getElementById("publishedfromemaildomain").value;
    if (draftpublished == 'draft') var subject = document.getElementById("drafttemplateSubject").value; else subject = document.getElementById("publishedtemplateSubject").value;
    if (draftpublished == 'draft') var opentracking = document.getElementById("drafttemplateOpenTracking").value; else opentracking = document.getElementById("publishedtemplateOpenTracking").value;
    if (draftpublished == 'draft') var clicktracking = document.getElementById("drafttemplateClickTracking").value; else clicktracking = document.getElementById("publishedtemplateClickTracking").value;
    if (draftpublished == 'draft') var replyto = document.getElementById("drafttemplatereplyto").value; else document.getElementById("publishedtemplatereplyto").value;
   
    if (draftpublished == 'draft') 
    {
    	var htmlcode = tinymce.get('tinyhtmldraftarea').getContent(); 
    	var textcode = document.getElementById("drafttextCode").value;
    } 
    else 
    {
    	var htmlcode = document.getElementById('publishedhtmlCode').value; 
    	var textcode = document.getElementById("publishedtextCode").value;
    }
    
    $.ajax({
      url:'phpResources/sendTestEmails.php',
      type: "POST",
      dataType: 'text',
      data: {"globalsub" : globalsub, "jsonLoad" : convertedJson, "recipients" : recipients, "textcode" : textcode, "htmlcode" : htmlcode, "replyto" : replyto,
      "fromname" : fromname, "fromemailname" : fromemailname, "fromemaildomain" : fromemaildomain, "subject" : subject, "opentracking" : opentracking, "clicktracking" : clicktracking, "emailaddresses" : emailaddresses },
      success: function (response) 
      {
         document.getElementById("serverresults").srcdoc = response;
      },
      error: function (response) {
          document.getElementById("serverresults").srcdoc = response;
      }
    });
    
    return false;
}


function templateListBuilder(settothis)
{
// Builds a list of all available templates.  This can comprise of both those created by this sub-account and those shared by the master account
// If this function is called after a new template is created, it will set the current selection to that template name
//
// This function is called at the beginning of the page load and from the 'CRUDtemplate' function.
//
	$.ajax({
    	type: "POST",
   		url: 'phpResources/buildTemplateList.php',
    	dataType: 'text',
    	data: {"cgORtm" : "tm"},
    	success: function (response) 
     	{
         	document.getElementById("templateList").innerHTML = response;
         	document.getElementById("newTemplateName").value = "";	
         	if (settothis != "")
         	{
         		var dd = document.getElementById('templateList');
				for (var i = 0; i < dd.options.length; i++) 
				{
    				if (dd.options[i].text === settothis) 
    				{
        				dd.selectedIndex = i;
        				break;
    				}
				}
			}
      	},
      	error: function (response) 
      	{
          	document.getElementById("serverresults").srcdoc = response;
      	}
	});
}

function CRUDtemplate(flag)
{
//
// Upldate, Delete, or Create new templates
//
// Called by the 'savedraft, publishdraft and deletetemplate' buttons
//

	var templateListValue = document.getElementById("templateList");
	var templateNewName = document.getElementById("newTemplateName").value;
    var emailaddresses = document.getElementById("emailnames").value;
    var fromname =      document.getElementById("drafttemplateFromName").value;
    var fromemailname =    document.getElementById("draftfromemailname").value;
    var fromemaildomain =     document.getElementById("draftfromemaildomain").value;
    var subject =      document.getElementById("drafttemplateSubject").value;
    var description =      document.getElementById("drafttemplateDescription").value;
    if (document.getElementById("drafttemplateOpenTracking").checked) var opentracking = true; else var opentracking = false;
    if (document.getElementById("drafttemplateClickTracking").checked) var clicktracking = true; else var clicktracking = false;
    //var clicked = document.getElementById("drafttemplateOpenTracking").value;
    //if($('#drafttemplateOpenTracking').attr('checked')) {var opentracking = true; else var opentracking = false};
    //if($('#drafttemplateClickTracking').attr('checked')) {var clicktracking = true; else var clicktracking = false};
    var replyto =      document.getElementById("drafttemplatereplyto").value;
    var htmlcode = tinymce.get('tinyhtmldraftarea').getContent(); 
    var textcode = document.getElementById("drafttextCode").value;	   
    var templateListText = templateListValue.options[ templateListValue.selectedIndex ].text;
	
	switch(flag)
	{
	case 'delete':
		$.ajax({
		url:'phpResources/CRUDTemplate.php',
      	type: "POST",
      	dataType: 'text',
      	data: {"flag" : flag, "templatelistvalue" : templateListValue.value, "templatelisttext" : templateListText, "templatenewname" : templateNewName},
      		success: function (response) 
      		{
         		document.getElementById("serverresults").srcdoc = response;
     			templateListBuilder (apiroot, apikey, "");
				document.getElementById("newTemplateName").value = "";
    			document.getElementById("emailnames").value = "";
    			document.getElementById("drafttemplateFromName").value = "";
    			document.getElementById("draftfromemailname").value = "";
    			document.getElementById("draftfromemaildomain").value = "";
    			document.getElementById("drafttemplateSubject").value = "";
    			document.getElementById("drafttemplateDescription").value = "";
    			document.getElementById("drafttemplateOpenTracking").checked = true;
    			document.getElementById("drafttemplateClickTracking").checked = true;
    			document.getElementById("drafttemplatereplyto").value = "";
    			document.getElementById("publishedtemplateFromName").value = "";
    			document.getElementById("publishedfromemailname").value = "";
    			document.getElementById("publishedfromemaildomain").value = "";
    			document.getElementById("publishedtemplateSubject").value = "";
    			document.getElementById("publishedtemplateDescription").value = "";
    			document.getElementById("publishedtemplateOpenTracking").checked = true;
    			document.getElementById("publishedtemplateClickTracking").checked = true;
    			document.getElementById("publishedtemplatereplyto").value = "";
    			tinymce.get('tinyhtmldraftarea').setContent("");
    			document.getElementById("drafttextCode").value = "";
    			document.getElementById('publishedhtmlCode').value = "";
    			document.getElementById("publishedtextCode").value = "";
      		},
      		error: function (response) {
          		document.getElementById("serverresults").srcdoc = response;
      		}
    	});
		break;
    case 'draft':
    case 'publish':
    	if ((htmlcode.length != 0) || (textcode.length !=0))
    	{
    		$.ajax({
      			url:'phpResources/CRUDTemplate.php',
      			type: "POST",
      			dataType: 'text',
      			data: {"textcode" : textcode, "htmlcode" : htmlcode, "replyto" : replyto, "flag" : flag, "templatelistvalue" : templateListValue.value, "description" : description,
      			"templatelisttext" : templateListText, "templatenewname" : templateNewName, "fromname" : fromname, "fromemailname" : fromemailname, "fromemaildomain" : fromemaildomain, "subject" : subject, "opentracking" : opentracking, "clicktracking" : clicktracking},
      			success: function (response) 
      			{
         			document.getElementById("serverresults").srcdoc = response;
     				if (flag == "publish")
     				{
     					document.getElementById("publishedhtmlCode").value = htmlcode; 
    					document.getElementById("publishedtextCode").value = textcode;
    					
    					document.getElementById("publishedtemplateFromName").value = document.getElementById("drafttemplateFromName").value;
        				document.getElementById("publishedfromemailname").value = document.getElementById("draftfromemailname").value;
        				document.getElementById("publishedfromemaildomain").value = document.getElementById("draftfromemaildomain").value;
        				document.getElementById("publishedtemplateSubject").value = document.getElementById("drafttemplateSubject").value;
        				document.getElementById("publishedtemplatereplyto").value = document.getElementById("drafttemplatereplyto").value;
        				document.getElementById("publishedtemplateDescription").value = document.getElementById("drafttemplateDescription").value;
        				document.getElementById("publishedtemplateOpenTracking").checked = document.getElementById("drafttemplateOpenTracking").checked;
        				document.getElementById("publishedtemplateClickTracking").checked = document.getElementById("drafttemplateClickTracking").checked;
    				}
    				else 
    				{
    					if (templateNewName.length != 0)
    					{
    						document.getElementById("publishedhtmlCode").value = "";
    						document.getElementById("publishedtextCode").value = "";
    						document.getElementById("publishedhtmlCode").placeholder = "Template with published HTML data has not been selected"; 
    						document.getElementById("publishedtextCode").placeholder = "Template with published Text data has not been selected";
    					} 
    				}
    				if (templateNewName.length != 0)
    				{
    					templateListBuilder(templateNewName);
    					//resetSelector(templateNewName);
    				}	
      			},
      			error: function (response) {
          			document.getElementById("serverresults").srcdoc = response;
      			}
    		});
    	}
    	else
    	{
			tinymce.get('tinyhtmldraftarea').setContent("Did not process your save/publish request because both the draft HTML and Text fields are empty.");
			document.getElementById("drafttextCode").placeholder="Did not process your save/publish request because both the draft HTML and Text fields are empty.";
			document.getElementById("serverresults").srcdoc = "Did not process your save/publish request because both the draft HTML and Text fields are empty.";
    	}
    }
    return false;
}

function getEvents()
{
//
// Sometimes test emails don't go out as fast as you would like them to.  So it's nice to have a way to see what is going on.  This function graps all message events from
// the last 24 hours.  
//
// Called by 'getevents' button
//
    var searchkey = "TestSend";
    
    $.ajax({
      url:'phpResources/getEvents.php',
      type: "POST",
      dataType: 'text',
      data: {"searchkey" : searchkey},
      success: function (response) 
      {
        document.getElementById("serverresults").srcdoc = response;
      },
      error: function ( response) {
      	document.getElementById("serverresults").srcdoc = response;
      }
    });
    
    return false;
}

function makedraft()
{
//
// If you want to copy over the production html and text over to the draft fields overriding them with the published data
//
// This function is called by the 'copytodraft' button
//
	if (document.getElementById("publishedtextCode").value.length > 0) { document.getElementById("drafttextCode").value = document.getElementById("publishedtextCode").value; }
	if (document.getElementById("publishedhtmlCode").value.length > 0) { tinymce.get('tinyhtmldraftarea').setContent(document.getElementById("publishedhtmlCode").value); }
	$('#draftpreviewiframe').contents().find('html').html("");
}

</script> 
</body>
</html>