
function generatorNav() 
{
    var x = document.getElementById("generatorTopNav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } 
    else 
    {
        x.className = "topnav";
    }
}




function gettext(html)
{
	var htmlToText = require('html-to-text');

	//htmlToText.fromString(html);
	document.getElementById("serverresults").srcdoc = "got something, better than nothing";
	//htmlToText.fromString(html, 
		//{tables: ['#convert']}, 
		//(err, text) => 
		//{
			//if (err) return console.error(err);
			//console.log(text);
		//}
	//);
}