# CampaignManager-Sample-for-SparkPost
New Free Campaign Generator for SparkPost, Yes Free
High Level Description
I'm happy to say that a new free application has been created to fill the SparkPost hole of not being able to create an email/sms/push campaign without coding or a fee based ESP partner. The application, " Campaign Generator for SparkPost" (CG) takes a nifty approach of using your own API Key's in a web hosted application so there is NO install or set-up unless you want to download the application from a GitHub site at https://github.com/jeff-goldstein/CampaignManager-Sample-for-SparkPost and use the application on premise.

CG is not competition for a full campaign manager system like those that supports segmentation, data management and reporting; this is a lite weight campaign generator that has some fairly cool capabilities that you won't see anywhere else. Before getting started on details about the application, as one of the architects] of the application I want to tell you about one of the founding principles of the application, "keep no data". As it will be described below, the user will enter in a SparkPost API key in order for the application to obtain SparkPost account information like stored template names, recipient lists, sending domain names, etc. What is does not do is store information about your account on the CG server. To be clear, it will not store your API Key or User Data at all. We welcome feature enhancement requests as-long-as it can be done leveraging the SparkPost API's and does not involve storing any data on the CG server. More details about application functionality will be described below after we get through some of the typical GitHub repository sections.

Prerequisites
Before using this library, you must have:

A SparkPost Account, [sign up for a new account][sparkpost sign up] or login to SparkPost
A valid SparkPost API Key. Check out our Support Center for information on how to create API keys with the following rights: 'Recipient Lists: Read/Write, Templates: Read/Write, Transmissions: Read/Write and Sending Domains: Read'.
If you are a SparkPost Elite/Enterprise customer, you need to enter your API root URL as well.
Installation
Copy all files that start with 'cg' and place onto your web server
Using Campaign Generator for SparkPost
Logging In
The landing page for the application is at http://www.geekwithapersonality.com/cgKey.php. If you are a SparkPost.com users, all you have to do is enter a SparkPost API Key that has the following rights: 'Recipient Lists: Read/Write, Templates: Read/Write, Transmissions: Read/Write and Sending Domains: Read'.

If you are a SparkPost Elite/Enterprise customer, you need to enter your API root URL as well. That's it! The CG system will now be able to schedule and cancel campaigns on your behalf.

Generating a Campaign with Stored Template && Stored Recipients
For Campaign Generation, the system allows for three different approaches, one where you leverage both stored templates and stored recipients, other where you can paste in your own CSV or JSON list of recipients along with their substitution data or you can upload a CSV file for conversion to the JSON structure needed by SparkPost. Most of the fields and features for each approach are the same. They all use stored templates, allow for campaigns to be immediately kicked off or scheduled for up to one month out (SparkPost limitation), create a campaign name, set open and click tracking flags and create up to 4 metadata key/value pairs.

Each approach also allows for the user to preview what the email may look like. The CG system takes the template and the data from the first recipient and merges them together to show you what the email would look like for that user.

While this goes a long way in helping the user to see if they have the right set of data for this particular template, the CG system goes further and scans both the user substitution data and the template to determine how many fields match eachother.

If the substitution data from the first recipient doesn't have the validated data, CG will throw up an alert box and set the submit button to a state where the campaign can not be sent until the issue is fixed. If you know that the first user will fail in this way, and you want to use that template/recipient list combo, just don't do a preview before hitting the submit button.

Generating a Campaign by entering in recipeint data via CSV or JSON format
As described, the first approach is to simply use one of the stored recipient lists for your account. But if you don't have stored recipients or you want to use the CG system with a list that is not already stored, you can enter them into the CG system either by CSV list or a JSON structure. Most people know what a Comma Separated Value list is and how to work them. Because SparkPost doesn't understand the CSV format, the CSV list must be converted into a JSON structure in order to be used; which it can do for you. That means the CG system needs to impose some rules to the CSV file:

The only field that MUST be in the list is the "address" field that holds the users email address for the email; all other fields are optional.
The first row is a list of the field names.
All following rows are for data.
If you are using recipient level substitution data, you MUST have a field called "substitution", and it's corresponding data will be an underscore in each data row.
For further info, please see the CG Help Text. You can enter up to 700k of data, so you can enter in a lot of recipients. The 'Convert to JSON' button will take that CSV list and convert the data into a structure that SparkPost understands, JSON; and place the output in the field directly below the CSV data entry field. Another output of the convert process is the number of rows converted; check to make sure it's in the ballpark you were expecting . If you wish to enter JSON directly without going through CSV, you may do so.

Some of you may be saying, but what about global data? Well that is supported in both Campaign Generation approaches. There is a data entry field that is displayed when you press the 'Show Global Sub' button. In that field, you can enter up to 70k of global substitution data! That data will not only be used in the email, but is used in the Preview and the Template/Data comparisons.

Cancelling Scheduled Campaigns
The 'Manage Scheduled Campaigns' tab will take you to a table showing you a list of your scheduled campaigns. All you need to do is to copy one of the Campaign Id's and paste it into the field at the bottom of the page, then press the 'Cancel Campaign' button. Right now, any Campaign that has 10 minutes before it's scheduled time can be cancelled.

Message Events
If you want to review what happened to a specific email, you can go to the tab labeled 'Email Tracer'.  There you can enter in a specific email address and the system will grab everything SparkPost has for that user for the last 10 days.  From there you have various filter capabilities in order to scope down the data.

Analytics
The newest component is an Analytics page.  The data that is obtained from SparkPost is fixed but you can select what days over the last six month you want to look at.  Also, several of the reports allow for you to 'zoom' into the report simply by dragging your cursor across the graph.

OK, so wrapping this up. Campaign Generator for SparkPost is free, and ready for you to use. Because it does NOT hold any data and uses your API Keys, you won't see anyone else's data and they won't see yours. Please hit the 'Contact' link at the top if you have any questions, find any bugs, or have any enhancement requests. This is open sourced so if you want to fix something or add something, please feel free, all help is appreciated.
