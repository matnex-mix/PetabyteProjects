<?php
	session_start();

	if(!isset($_POST["fileType"])) {
		die("Eroor Processing The Request");
	}

	$filetype = strtolower(base64_decode($_POST["fileType"]));
	$filesize = 5000000;

	if(isset($_POST["fileSize"])) {
		$filesize =  $_POST["fileSize"];
	}
	$callback = "";

	if(isset($_POST["callback"])) {
		$callback =  $_POST["callback"];
	}

	$target_dir = "../../../uploads/".$_SESSION["details"]["ID"]."/".$_POST["UploadFolder"]."/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    $realpath = "uploads/".$_SESSION["details"]["ID"]."/".$_POST["UploadFolder"]."/".basename($_FILES["fileToUpload"]["name"]);

    if(isset($_POST["fileName"])) {
    	$target_file = $target_dir . $_POST["fileName"];
    	$realpath = "uploads/".$_SESSION["details"]["ID"]."/".$_POST["UploadFolder"]."/".$_POST["fileName"];
    }

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > $filesize) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if(strpos($filetype, $imageFileType) === FALSE) {
        echo "Sorry Only ".$filetype." files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

        	echo "Upload Successfull. Redirecting...<script>setTimeout(function() { history.back(); }, 300);</script>";

        	eval($callback);
            
        } else {

            echo "Sorry, there was an error uploading your file.";

        }
    }