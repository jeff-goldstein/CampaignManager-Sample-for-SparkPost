<!-- Copyright 2017 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. 

File: tmHelp.php
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
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/cgCommonScripts.js"></script>
<link rel="stylesheet" type="text/css" href="css/tmCommonFormatting.css">
<script src="tinymce/js/tinymce/tinymce.min.js"></script>
</head>

<body id="bkgnd" style="margin-left: 20px; margin-right: 20px">
<table border="0;" width="1220px">
<tbody>
<tr>
<td>
<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="cgKey.php">Home</a></li>
	<li><a href="https://developers.sparkpost.com/" target="_blank" rel="noopener">SparkPost Documentation</a></li>
	<li><a href="mailto:admin@company.com?subject=cgMail">Contact</a></li>
	<li class="icon">
    	<a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  	</li>
</ul>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table border="0;" width="1220px">
<tbody>
<tr>
<td><center>
<h1>Template Manager Help</h1>
<h3>Template Manager Support for SparkPost Sub-Accounts</h3><br>
</center></td>
</tr>
</tbody>
<tbody>
<tr>
<td colspan="2">
<p><strong>Welcome</strong> to <em><strong>Template Manager</strong></em>. First things first.... This tool was not created nor is it supported by SparkPost. This tool was created by some SparkPost community contributors and is open for anyone to use. This tool is free to use, but at your own risk. The Template Manager DOES NOT hold any of your information, it&nbsp;leverages a user key along with SparkPost's API's to create, update or delete templates. All connections to the SparkPost platform are through their published API's and are restricted to their capabilities and limitations so reading their API documents will go a long way into understanding this application. The code behind this site is available in the Github repository at: ***fix location***</p>
<p>The company that gave you your user key for the <em><strong>Template Manager</strong></em> has what is known as a <em><strong>Master Account</strong></em> within SparkPost. Each of their users (you) have sub-accounts within their system. This allows them to keep your templates, sending domains and email sending data separate from their other users. When this document refers to the <em><strong>Master Account</strong></em>, it is referring to the company that gave you your Template Manager key. Anytime the document refers to the <strong><em>sub-account</em></strong> user, it is referring to you, the reader.</p>
</td>
</tr>
<tr>
<td colspan="2"><em><strong>Template Manager</strong></em> was created in order to fill missing SparkPost functionality where <em><strong>sub-account&nbsp;</strong></em>users cannot Add, Change or Delete templates specific to their <strong><em>sub-accounts</em></strong>. Currently (as of Oct 2017) the SparkPost UI is only written with the <em><strong>Master</strong></em> Account in mind. So <em><strong>Template Manager</strong></em> was created as a way to allow <em><strong>sub-account</strong></em> users to manager their templates within SparkPost. When using this tool, please understand the nuances of SparkPost templating because I won't document all of them in this tool. You can refer to their documentation at&nbsp;<a href="https://developers.sparkpost.com/">SparkPost Documentation&nbsp;</a>for a deeper understanding on <em><strong>Templates</strong></em> and their corresponding <em><strong>S</strong><strong>ubstitution Data</strong></em> capabilities.</td>
</tr>
<tr>
<td><br />
<h1>Logging in with a SparkPost Key</h1>
<strong>The</strong> first step in using the <em><strong>Template Manager</strong></em> is to obtain a SparkPost key which will give this application access to your account. This key is NOT stored by this application and is only used during your sessions. If you don't have a key you must reach out to your contact at ***replace with contact info***, not SparkPost. SparkPost can not help you obtain keys.
<p>Once you have the key, you can use it on the login/entry page. In order to protect your key, the system will obfuscate your key through the rest of the session. The <em><strong>Template Manager</strong></em> Application has turned off auto-fill for the field that requests the key, so the browser may not fill in that field the next time you come back to that page.&nbsp;</p>
</td>
</tr>
<tr>
<td>
<p>At the top of the Template Manager page, the user has the option to either enter in the name of a new template they wish to create, or to select a stored template from the pulldown. On coming to this page, the system will automatically go to the users SparkPost account and&nbsp;build a list of all the templates available to that user. Available templates may conprise of two groups of templates; the templates created by this user, and those created by the master account and shared with all users. Those shared from the master account are read only and can not be changes. They can be <em><strong>copied</strong></em> though by taking the following steps:</p>
<ol>
<li>Select a shared template from the Stored Template pull down</li>
<li>Enter in a new template name at the top of the page</li>
<li>Under the <em><strong>Draft</strong></em>&nbsp;template editing section, press either the <em><strong>Save Draft</strong></em> or <strong><em>Save and Publish</em></strong> button.&nbsp;</li>
</ol>
<p>Now you have an editable version of that shared template. If the master copy is changed at a latter time, those changes <em><strong>will not</strong></em> affect your copy.</p>
<br />
<h1>Draft</h1>
<p>As discussed earlier, there can be two copies of your template, <strong><em>Draft</em></strong> and <em><strong>Published</strong></em>. You should think of them as two seperate entities. Also, each copy has both a HTML and Text version of the template. To move from the <em><strong>Draft</strong></em> or <em><strong>Published</strong></em> copies of your template, simply select the appropriate link under the <em><strong>Template Code/Text header</strong></em>. When working with the <em><strong>Draft</strong></em> copy of the HTML template, you are presented with an embedded WYSIWYG HTML editor. Since most editors are similar, this help section will not be a tutorial on using the editor. Under the draft section you are also able to set up your <em><strong>Email Text</strong></em> section. Since the email text section is simple text, you are only presented with a simple text dialog box for entering in that data. If you choose to save off the draft, or even publish the draft, you may do so within either <strong><em>Draft</em></strong> page. The <strong><em>Preview</em></strong> tab/link allows you to see what the email may look like went sent. If you have entered in data within the <strong><em>Test Recipient Data</em></strong> section, that data will be used to further demonstrate what the email may look like. with real data If data for more than one recipient has been entered; only the data for the first recipient will be used for the <strong><em>Preview</em></strong>.</p>
<p>Please keep in mind, that when you use the <em><strong>Save and Publish</strong></em> button, you are writing over your current published version.&nbsp;</p>
<p>Another feature of the <em><strong>Draft HTML</strong></em> section is the <em><strong>Convert to Text</strong></em> function. This feature reads your HTML source code and tries to create a close approximation of your email but in plain text. There are limitations to this process, especially around tables. Please review your text carefully and manually make the changes necessary.</p>
<br />
<h1>Published</h1>
<p>The <em><strong>Published</strong></em> section is similar&nbsp;to the <em><strong>Draft</strong></em> section in the fact that they both have a&nbsp;<em><strong>HTML</strong></em> and <em><strong>Text</strong></em> section; but&nbsp;there are numerous&nbsp;differences, most of which the <em><strong>Published</strong></em> version is&nbsp;<em><strong>READ ONLY</strong></em>. Yes, Published template data can not be modified. If you want to modify that HTML or Text, you must copy the HTML and Text data over to the <em><strong>Draft</strong></em> section for editing. There is a handy button in the <em><strong>Published</strong></em> section to help with that. Keep in mind, the <em><strong>Copy</strong></em> button will overwrite your current draft data. Please notice&nbsp;that supporting data fields, From, Reply To, Subject, etc, are by design NOT copied from the Published template to Draft. You must go to the bottom of the page and make those changes yourself.</p>
<table>
<tbody>
<tr>
<td>* note * If you copy published code to the draft HTML/Text fields, that data will only be kept once you press the <em><strong>Save Draft</strong></em> or <em><strong>Save and Publish</strong></em> button.</td>
</tr>
</tbody>
</table>
<p>You can also Preview your published code by going to the <em><strong>Preview</strong></em> button in the <em><strong>Published</strong></em> section.</p>
<br />
<h1>Quicktest</h1>
<p>This is where things get a little complicated. At the bottom of the <em><strong>Template Manager</strong></em>page are buttons that allow you to send test emails to email address that have been entered into the <em><strong>Test Recipient Data</strong></em> section. That powerful option gives you the ability to send a unique email to each user with their own data set. That data must be entered in either in CSV or JSON format; which depending on your template, can be very complex. The <em><strong>Help</strong></em> tab within that section will discribe how this is done in detail.</p>
<p>But sometimes you don't want to enter in data and you just want to see what the email will look like even if it might look poorly without data. This is where <em><strong>Quicktest</strong></em> comes in. <em><strong>Quicktest</strong></em> takes a list of comma seperated email addresses and will send the same email to all users entered into the <em><strong>Quicktest</strong></em> data field. Even if there is data within the <em><strong>Test Recipient Data</strong></em> fields, they will <em><strong>NOT</strong></em> be used. To use&nbsp;the data within the <em><strong>Test Recipient Data</strong></em> section, you must use the buttons toward the bottom of the page.</p>
<br />
<h1>Substitution Fields</h1>
<p>SparkPost has a very rich substitution capability that can be used to personalize any email. For the most part, substitution fields are represented within the HTML body (or CSS style section) by double curly brackets. For example, if you want to personalize the greeting, you might have the following HTML code in the template:</p>
<pre>&lt;h3&gt; Hello {{first_name}},&lt;/h3&gt;</pre>
<p>This is a very simple example of embedding the substitution fields within the HTML code. In reality, you would probably have a default entry just in case the first name wasn't available:</p>
<pre>&lt;h3&gt; Hello {{ name or 'Customer' }}, &lt;/h3&gt;</pre>
<p>SparkPost also has other conditional capabilities, the ability to loop through arrays of data and a myraid of other capabilities.&nbsp;For a full explanation of the substitution capabilities that are available to you, please refer to the documentation at SparkPost: <a href="https://developer.sparkpost.com/api/substitutions-reference.html" target="_blank" rel="noopener">SparkPost Substitution Documentation</a>.</p>
</td>
</tr>
<tr>
<td>
<p>Personalization can be anywhere from simply adding the recipients name to the email to showing unique information within the body of the email that reflects things like products, news, jobs, etc. If the template being created uses substitution fields, most users want to see/preview what the template would look like with sample data. That is where&nbsp;the <em><strong>Test Recipient Data</strong></em> section comes in. The <strong><em>Test Recipient Data</em></strong> section allows you to enter in substitution data in order to preview what the email will look like. It even gives you the ability to send as many test emails that you wish to send, each with their own unique set of substitution data!</p>
<p>To support this flexible feature, the substitution data can be as complex as you need, but does have a length limit of 70K characters. This data will be used in the Preview feature within the <em><strong>Draft/Published Preview</strong></em> sections as-well-as the test sends when using the <em><strong>Send Test Draft/Published Emails with Data</strong></em> buttons at the bottom of the <em><strong>Template Manager</strong></em> page.</p>
<br />
<h1>Using CSV or JSON Test Data</h1>
<p>Warning! THIS DATA IS NOT SAVED OFF WITH THE TEMPLATE NOR KEPT IN COOKIES ON YOUR COMPUTER. IT MUST BE RE-ENTERED EACH TIME YOU COME TO THIS APPLICATION.</p>
<p>There are three data entry sections that can be used for test data; <em><strong>Recipient CSV,</strong></em><em><strong>Recipient JSON</strong></em> and <em><strong>Global</strong></em> Substitution Data fields. SparkPost substitution data must be in what is call a JSON format. JSON, or JavaScript Object Notation, is a minimal, readable format for structuring data. It is used primarily to transmit data between a server and web application.</p>
<p>The two primary parts that make up JSON are keys and values. Together they make a key/value pair.</p>
<ul>
<li><strong>Key:</strong>&nbsp;A key is always a string enclosed in quotation marks.</li>
<li><strong>Value:</strong>&nbsp;A value can be a string, number, boolean expression, array, or object.</li>
<li><strong>Key/Value Pair:</strong>&nbsp;A key value pair follows a specific syntax, with the key followed by a colon followed by the value. Key/value pairs are comma separated.</li>
</ul>
<p>A simple internet search will help you understand JSON further if you need a deeper understanding. While JSON is a well known data format for developers, it's not as well known as CSV files are to most people. So in order to help users test their templates, the <em><strong>Template Manager</strong></em> supports test data being entered into this system via CSV format.</p>
<p>The only field that MUST be in the list is the "address" field; all others are optional. Once entered, press the <em><strong>Convert CSV to JSON</strong></em>&nbsp;button in order to change your CSV input into the JSON format the server can understand. The first row is always the names of the fields, separated by commas and incased in quotes. A very simple Recipient CSV sample with just address names will look like this:</p>
<p><code>"address"</code><br /><code>"jeff.gold@sparkpost.com",</code><br /><code>"austin23112@hotmail.com",</code><br /><code>"donna@geeks.com"</code></p>
<p>&nbsp;</p>
<p>The following JSON output will be created. This structure is actually an array of the names just entered. Notice that the JSON structure actually starts with the word "recipients". That is automatically added for you and is mandatory for the SparkPost email platform.</p>
<p><code>{"recipients":[{"address":"jeff.gold@sparkpost.com"},</code><br /><code>{"address":"austin23112@hotmail.com"},</code><br /><code>{"address":"donna@geeks.com"}]}</code></p>
<p>&nbsp;</p>
<p>If you have substitution data SparkPost expects that data to be gathered into a group called <em><strong>subtitution_data.&nbsp;</strong></em> That means the csv entry file must have&nbsp;a field named "<em><strong>substitution</strong></em>".&nbsp; All of the data following&nbsp;the <em><strong>substitution</strong></em> field&nbsp;will be considered substitution_data fields. Here is an example:</p>
<pre><code>"address","UserName","substitution","first","id","city"
"jeff.goldstein@sparkpost.com","Sam Smith","_","Sam",342,"Princeton"
"austein@hotmail.com","Fred Frankly","_","Fred",38232,"Nowhere"
"jeff@geekswithapersonality.com","Zachary Zupers","_","Zack",9,"Hidden Town"
</code></pre>
<p>&nbsp;</p>
<p><strong>Please notice that in each data row, there is an underscore that represents the data for the "substitution" field. IT MUST BE AN UNDERSCORE!! The system will use the combination of the substitution/underscore to determine the beginning of the substitution data.</strong>&nbsp;Upon pressing the <em><strong>Convert CSV to JSON</strong></em> button the above sample will change to the following JSON format which will be displayed in the <em><strong>Recipient JSON</strong></em> entry field (if you have data in the <em><strong>Recipient JSON</strong></em> data field prior to hitting the 'Convert to JSON' button, that data will be overridden):</p>
<table>
<tbody>
<tr>
<td><code>{"recipients":[{"address":"jeff.goldstein@sparkpost.com","UserName":"Sam Smith","substitution_data":{"first":"Sam","id":"342","city":"Princeton"}},</code><br /><code>{"address":"austein@hotmail.com","UserName":"Fred Frankly","substitution_data":{"first":"Fred","id":"38232","city":"Nowhere"}},</code><br /><code>{"address":"jeff@geekswithapersonality.com","UserName":"Zachary Zupers","substitution_data":{"first":"Zack","id":"9","city":"Hidden Town"}}]}</code></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p>{Yes that is ugly; with a free tool call&nbsp;<a href="http://jsonlint.com/">JSONLINT</a>, you can see what the output looks like in human readable form. Notice that the dash does not show up in your data:</p>
<p>&nbsp;</p>
<pre><code> "recipients": [{
 "address": "jeff.goldstein@sparkpost.com",
 "UserName": "Sam Smith",
 "substitution_data": {
 "first": "Sam",
 "id": "342",
 "city": "Princeton"
 }
 }, {
 "address": "austein@hotmail.com",
 "UserName": "Fred Frankly",
 "substitution_data": {
 "first": "Fred",
 "id": "38232",
 "city": "Nowhere"
 }
 }, {
 "address": "jeff@geekswithapersonality.com",
 "UserName": "Zachary Zupers",
 "substitution_data": {
 "first": "Zack",
 "id": "9",
 "city": "Hidden Town"
 }
 }]
}
</code></pre>
<p><em>NOTE: JSONLINT is a great tool. There is also a PRO version: <a href="http://pro.jsonlint.com/">PRo JSONLINT</a>. JSON LINT is open source GitHub repository available. These tools can save you a ton of time in figuring out why your JSON is being rejected.</em></p>
<p>Again, the JSON structure must start with the <em><strong>recipients&nbsp;</strong></em>key field which is followed by an array of recipient data.</p>
<p>If you wish, you can skip the CSV process and enter (cut/paste) JSON directly into the <em><strong>Recipient JSON</strong></em> data field, go right ahead. There is a sample of what the structure needs to look like in the data field itself. The field is expected to be a good JSON format and can have as many users in it as you with up to 700K of data. Depending on the number of substitution fields you have that could be a lot of users for your test emails!!</p>
<p>There is also a Validate JSON Structure button that will check to make sure that you have a valid JSON format. IT DOES NOT make sure that this is a valid SparkPost format; it is simply validating against proper JSON formatting rules.</p>
<br />
<h1>Global Data</h1>
<p>When entered, Global data will also be used during the test email send. Global Substitution fields are similar to Recipient data but they are used for every recipient. If the same name is used in both Global and Recipients data fields, the Recipient data will override the Global data. Global fields are general data fields like company URL's, CSS color and format changes and even product array's that are referenced within the recipient data structures.</p>
<br />
<h1>Template References</h1>
<table style="width: 1178px;" border="2" cellpadding="5">
<tbody>
<tr>
<th style="width: 635px;">
<h3>Description</h3>
</th>
<th style="width: 541px;">
<h3>Reference Link</h3>
</th>
</tr>
<tr>
<td style="width: 635px;">SparkPost Blogs focused on Templating. Most of these are technical in nature and very good references for the beginner.</td>
<td style="width: 541px;"><a href="https://www.sparkpost.com/blog/category/feature/templates/">https://www.sparkpost.com/blog/category/feature/templates/</a></td>
</tr>
<tr>
<td style="width: 635px;">SparkPost Substitution reference guide. Very technical walkthrough of all substitution capabilities.</td>
<td style="width: 541px;"><a href="https://developers.sparkpost.com/api/substitutions-reference.html">https://developers.sparkpost.com/api/substitutions-reference.html</a></td>
</tr>
<tr>
<td style="width: 635px;">&nbsp;Github repository of complex template samples along with supporting JSON data formats.</td>
<td style="width: 541px;"><a href="https://github.com/jeff-goldstein/sparkpost/tree/master/Templates">https://github.com/jeff-goldstein/sparkpost/tree/master/Templates</a></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td><br />
<h1>Template Settings</h1>
<p>Both versions of the template, Draft and Published, have their own sets of description data. In this section you are able to update the Draft data and Save off or Save and Publish just like the template itself. Also, just like the template itself, the Published area is read only. Unlike the template though, when you copy the template data from the published version to the draft version, the corresponding description data is NOT copied over since Production environments that the Published version uses is often different than those in development that the Draft version will use. Thus you will have to make those changes yourself.</p>
</td>
</tr>
<tr>
<td>
<p>At the bottom of the page there are several buttons that help manage the rest of the Template Management system.</p>
<table style="width: 1220px;" border="2" cellpadding="5">
<tbody>
<tr>
<td style="width: 237px;">
<h3>Button Name</h3>
</td>
<td style="width: 981px;">
<h3>Button Description</h3>
</td>
</tr>
<tr>
<td style="width: 237px;">Send Test Draft Email(s) with Data</td>
<td style="width: 981px;">
<p>This button allows you to send test emails using the <em><strong>Draft</strong></em> version of your template along with the data entered into the <em><strong>Test Recipient Data</strong></em> Section. Only the data within the <em><strong>Recipient JSON</strong></em> and <em><strong>Global</strong></em> fields are used. So you must make sure that you press the <em><strong>Convert CSV to JSON</strong></em> button within the <em><strong>Recipient CSV</strong></em> section in order to use any entered CSV data.</p>
<p>Because the CSV and corresponding <em><strong>Recipient JSON</strong></em> data can have data for more than one person; this button will send data to every person represented in the <em><strong>Recipient JSON</strong></em> data field. This is a great way to test different sets of data when complex templates are being used.</p>
</td>
</tr>
<tr>
<td style="width: 237px;">Send Test Published Email(s) with Data</td>
<td style="width: 981px;">
<p>This button allows you to send test emails using the <em><strong>Published</strong></em> version of your template along with the data entered into the <em><strong>Test Recipient Data</strong></em> Section. Only the data within the <em><strong>Recipient JSON</strong></em> and <em><strong>Global</strong></em> fields are used. So you must make sure that you press the <em><strong>Convert CSV to JSON</strong></em> button within the <em><strong>Recipient CSV</strong></em> section in order to use any entered CSV data.</p>
<p>Because the CSV and corresponding <em><strong>Recipient JSON</strong></em> data can have data for more than one person; this button will send data to every person represented in the <em><strong>Recipient JSON</strong></em> data field. This is a great way to test different sets of data when complex templates are being used.</p>
</td>
</tr>
<tr>
<td style="width: 237px;">Delete Template</td>
<td style="width: 981px;">The Delete button will delete both version of the template. SparkPost treats both versions of the template as a single entity/object, so there is no way to delete one version and still keep the other.</td>
</tr>
<tr>
<td style="width: 237px;">Retrieve Sending Results</td>
<td style="width: 981px;">
<p>&nbsp;Email......well is email. Sometimes when you send an email it gets to the recipient in a flash and sometimes it takes a little longer or something goes wrong all together. The Template Manager allows you to peer into the email system and see what exactly happened to those emails that you tried to send. Each email will get two or more data entries that reflect what happened to the email. The first entry is something called 'injection'. That just means that the SparkPost system accepted the request to send the email. Typically, the second data point will be a 'delivery' record. This means that the email was created and the receiving email server acknowledged receipt of the email. Besides those two data points (events) you will probably see entries for Opens and Clicks (if you have SparkPost Open/Click tracking turned on) and Bounces. Bounces are often seen when you try to send to someone who has been put on the suppression list, a delay at the receiving email server, or you simply entered in a bad email address.</p>
<p>All in all there are around 18 different possible data entries that you may see; for a full definition of each please go to SparkPost Support and read the following <a title="SparkPost Events" href="https://www.sparkpost.com/docs/tech-resources/webhook-event-reference/">document</a>. For simplicity only a few fields are displayed, but no filtering is done on the data/event types themselves.</p>
<p>Typically, it takes SparkPost about 30 seconds before any data will be available and pulling that data may take 10-15 seconds to come back.</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</body>
</html>