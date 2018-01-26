<?php
{

function getRecipientCount ($uploadedFile)
{
    $count = -1;  // Start at -1 so we don't count the column definition line
    $file = fopen($uploadedFile, 'r');
	while (!feof($file))
	{
		$count++;
		$line = fgets($file);
	}
	return $count;
}

}?>

