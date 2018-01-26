
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
