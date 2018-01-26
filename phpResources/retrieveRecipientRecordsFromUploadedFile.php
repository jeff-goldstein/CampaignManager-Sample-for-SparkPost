<?php
{

function getRecipientsFromFile($start, $numberofrecipients, $uploadedfile)
{    
    $rec_count = 0;
    $buffer = NULL;
    $csvtojson = array();
    $recipients = NULL;
    //$file = new SplFileObject($uploadedfile, 'r');
    $file = fopen($uploadedfile, 'r');
    if (!feof($file))
    {
        //$file->seek(0);
    	//$keyarray = $file->fgetcsv();
    	$keyarray = fgetcsv($file);
    
		foreach ($keyarray as $key => $value)
		{
 			$trimmedkeys .= trim($value) . ',';
		}
					
		$buffer = substr($trimmedkeys, 0, -1) . PHP_EOL;
		$recipients .= $buffer;
		$keys = $buffer;
		$keyarray = str_getcsv($buffer, ",");
		for ($i = 1; $i < $start; $i++)
		{
			$line = fgets($file);
		}
        while (($rec_count < $numberofrecipients) && (!feof($file)))
        {
            $line = fgets($file);
            $line = mb_convert_encoding($line, "UTF-8");
            $line = str_replace("'", '"', $line);
            $recipients .= $line;
            $linearray = str_getcsv($line, ",");
            $csv2json[]=array_combine($keyarray, $linearray);
            if ($csv2json[$rec_count] == false)
            {
            	$recDisplay = $rec_count + $start;
            	$csv2json[$rec_count] = "Row number " . $recDisplay . ": Number of fields mismatch number of keys in header row";
            }
            $rec_count++;
        }
    }
    	
    fclose($file);  // This closes the file	
	$jsonstring = json_encode($csv2json);
	$jsonstring = str_replace ("\/", "/", $jsonstring);
	$recipients = mb_convert_encoding($recipients, "UTF-8");
	$jsonstring = mb_convert_encoding($jsonstring, "UTF-8");
	file_put_contents ('recipients.txt', $recipients, LOCK_EX );
	file_put_contents ('json.txt', $jsonstring, LOCK_EX );	
	$gotSubstitutions = substr_count($jsonstring, '"substitution":"_"'); 
	if ($gotSubstitutions > 0)
	{			
		$jsonstring = str_replace ('"substitution":"_",', '"substitution_data": {', $jsonstring);
		$jsonstring = str_replace ('}', '}}', $jsonstring);
	}
	$jsonstring = '{"recipients":' . $jsonstring . '}';			
    if (!empty($recipients))
    {
        $json_array=array(
			'csvoutput' => $recipients,
			'jsonoutput' => $jsonstring,
			'keys' => $keys,
		);
		$json_encoded_string = json_encode ($json_array);
		if (!empty($json_encoded_string))
		{ 
			return $json_encoded_string;
		}
		else
		{
			echo "File was uploaded and first 1000 lines found, but failed the encoding step. Data can't be passed to this UI";
		}				
	}
	else
	{
		echo "Something went wrong processing file, sorry cant tell you what. Is the file empty?";
	}
}


}?>