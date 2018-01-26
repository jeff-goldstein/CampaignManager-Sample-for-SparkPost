<?php
{

$recipients = $_POST["recipients"];
//$buffer = substr($trimmedkeys, 0, -1) . PHP_EOL;   
$rec_count = 0;
$mismatch = false;
$mismatchCount = 0;
$mismatchRows = " [ ";
$buffer = NULL;
$csvtojson = array();
$ptr = strtok($recipients, "\n");
$keyarray = explode ("," , $ptr);
foreach ($keyarray as $key => $value)
{
 	$trimmedkeys .= trim($value) . ',';
}
					
$buffer = substr($trimmedkeys, 0, -1) . PHP_EOL;
$keyarray = str_getcsv($buffer, ",");
$ptr = strtok("\n");
while ($ptr !== false)
{
    $rec_count++;
    $line = $ptr;
    $line = mb_convert_encoding($line, "UTF-8");
    $line = str_replace("'", '"', $line);
    $linearray = str_getcsv($line, ",");
    $csv2json[]=array_combine($keyarray, $linearray);
    if ($csv2json[$rec_count-1] == false)
    {
        $mismatch = true; $mismatchCount++;
        $mismatchRows .= $rec_count . ",";
        $csv2json[$rec_count-1] = "**addmyeolhere**Row number " . $rec_count . ": Number of fields mismatch number of keys in header row: " . $line . " **addmyeolhere**";
    }
    $ptr = strtok("\n");
}

if ($mismatch == true) echo "Warning " . $mismatchCount . " rows had a different number of fields than the header row, they are row(s)" . substr($mismatchRows, 0, -1) . " ].\nThe following output is NOT working JSON due to the mismatches!\n\n";   	
$jsonstring = json_encode($csv2json);
$jsonstring = str_replace ('\"', '"', $jsonstring);
$jsonstring = str_replace ("\/", "/", $jsonstring);
$jsonstring = mb_convert_encoding($jsonstring, "UTF-8");
file_put_contents ('recipients.txt', $recipients, LOCK_EX );
file_put_contents ('json.txt', $jsonstring, LOCK_EX );	
$gotSubstitutions = substr_count($jsonstring, '"substitution":"_"'); 
if ($gotSubstitutions > 0)
{			
	$jsonstring = str_replace ('"substitution":"_",', '"substitution_data": {', $jsonstring);
	$jsonstring = str_replace ('},', '}},' . PHP_EOL, $jsonstring);
	$jsonstring = str_replace ('}]', '}}]', $jsonstring);
}
$jsonstring = str_replace ('**addmyeolhere**",' , '",' . PHP_EOL . PHP_EOL, $jsonstring); // Adding End of lines at the end of any row but the last one to help them stand out
$jsonstring = str_replace ('"**addmyeolhere**' , PHP_EOL . '"' , $jsonstring); // Adding EOL (return) to the beginning of the lines to help them stand out
$jsonstring = str_replace ('**addmyeolhere**' , PHP_EOL . '"' , $jsonstring); //Adding the EOL if the last row was incorrect

$jsonstring = '{"recipients":' . $jsonstring . '}';	
					
echo $jsonstring;
				

}?>