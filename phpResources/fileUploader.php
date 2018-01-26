<?php
//******************************************************************************
//check if any errors occurred during the upload.
ini_set('memory_limit', '512M');
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set("auto_detect_line_endings", true);

$parametersFile = "../config/Parameters.ini";
$paramonly_array = parse_ini_file( $parametersFile, true );
$cat = "local";
$uploadDir = $paramonly_array[$cat]["UploadDir"];

$isDocUploaded = true; //initialise flag that says whether jpg was uploaded
$errorsOccurred = false;
 
$value = $_FILES['file']['error']; 
if($_FILES['file']['name'] != '') {
    if($value > 0) {
        $isDocUploaded = false; //attachment was not submitted or an upload error occurred
        $errorsOccurred = true;
        switch($value) {    //check which error code was returned
            case 1:
                echo '<p style="color: rgb(178,34,34)">** Error: Attachment file is larger than allowed by the server.</p>';
                break;
            case 2:
                echo '<p style="color: rgb(178,34,34)">** Error: Attachment file is larger than the maximum allowed - 100kb.</p>';
                break;
            case 3:
                echo '<p style="color: rgb(178,34,34)">** Error: Attachment file was only partially uploaded.</p>';
                break;
            case 4: echo 'No attachment file was uploaded. '; break;
        }
    }
    if($isDocUploaded && $_FILES['file']['name'] != '') {
        $uploadedFile = "../" . $uploadDir . "/" .$_FILES['file']['name'];
        if(@is_uploaded_file($_FILES['file']['tmp_name'])) //initial temp location of uploaded file
        { 
            if(@!move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) { //move the file to folder
                echo '<p style="color: rgb(178,34,34)"><br />**Error**: Could not move '.$_FILES['file']['name'].' to the destination folder ' . $uploadDir . ' on the server.<br /></p>';
                $errorsOccurred = true;
            } 
        } 
        else
        {
            echo '<p style="color: rgb(178,34,34)">Error: '.$_FILES['file']['name'].' was not uploaded correctly to temp area.</p>';
            $errorsOccurred = true;
        }
    }
}

?>
