<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// set the root directory for the uploads
	if ($_SERVER['HTTP_HOST']=='fm.local:8888'){
		$dir = "/Users/neilthompson/Development/simpleFileManager/src/files/";
		$webdir = "http://fm.local:8888/";
	}else{
		$dir = "/var/www/simpleFileManager/files/";
		$webdir = "<your remote url>";
	}

	// Are we logged in?
	checkSession('login.php');
	
	// has a file been uploaded?
	if (!empty($_FILES)){

		$allowedExts = array("jpg", "png", "JPG", "PNG", "jpeg", "JPEG", "PDF", "pdf", "txt");
		$msg = '';

		// Loop through each file
		for( $i=0 ; $i < count($_FILES['file']['name']) ; $i++ ) {

		// validate the uploaded file
		$temp = explode(".", $_FILES['file']['name'][$i]);
		$extension = end($temp);

		// check that the file if of the correct type and not too big
		if ((($_FILES["file"]["type"][$i] == "image/gif")
			|| ($_FILES["file"]["type"][$i] == "image/jpeg")
			|| ($_FILES["file"]["type"][$i] == "image/jpg")
			|| ($_FILES["file"]["type"][$i] == "image/pjpeg")
			|| ($_FILES["file"]["type"][$i] == "image/x-png")
			|| ($_FILES["file"]["type"][$i] == "image/png")
			|| ($_FILES["file"]["type"][$i] == "text/plain")
			|| ($_FILES["file"]["type"][$i] == "application/pdf"))
			&& ($_FILES["file"]["size"][$i] < 10000000)
			&& in_array($extension, $allowedExts)) {
				if ($_FILES["file"]["error"][$i] > 0) {
					$msg = 'ERROR: '.$_FILES["file"]["name"][$i].' '.$_FILES["file"]["error"][$i];
				} else {
					// move the tmp file to final resting place
					move_uploaded_file($_FILES["file"]["tmp_name"][$i], 
						$dir .date('Ymd_His').'_'.$_FILES["file"]["name"][$i]);
				}
		} else {
			$msg = 'ERROR: Invalid File: '.$_FILES["file"]["name"][$i];
		}

		if (!empty($msg)) echo $msg.'<br>';
	  }

		
	}elseif (!empty($_REQUEST['file'])){
		// request to delete a file
		unlink($dir . $_REQUEST['file']);
		header('Location: index.php');
	}elseif (!empty($_REQUEST['zip'])){
		// create a zip file and download
		$zipFile = date('Ymd_His').'_uploads.zip';
		$zip = new ZipArchive();
    	//create the file and throw the error if unsuccessful
    	if ($zip->open('/tmp/'.$zipFile, ZIPARCHIVE::CREATE )!==TRUE) {
        	exit("cannot open zip archive\n");
    	}
    	//add each files of $file_name array to archive
		if ($files = scandir($dir)) {
			foreach ($files as $file) {
    			if ($file != '.' && $file != '..') {
			        $zip->addFile($dir.$file,$file);
	    		}
			}
		}
    	$zip->close();
		header("Content-type: application/zip"); 
		header("Content-Disposition: attachment; filename=$zipFile");
		header("Content-length: " . filesize('/tmp/'.$zipFile));
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		readfile('/tmp/'.$zipFile);
		unlink('/tmp/'.$zipFile);
		die;
	}elseif (!empty($_REQUEST['delete'])){
    	//add each files of $file_name array to archive
		if ($files = scandir($dir)) {
			foreach ($files as $file) {
    			if ($file != '.' && $file != '..') {
					unlink($dir.$file);
	    		}
			}
		}
		header('Location: index.php');
	}elseif (!empty($_REQUEST['logout'])){
		header('Location: index.php');
		session_destroy();
	}else{
		//echo 'Oops how did we get here?';
	}

?>
<html>
	<head>
	<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
		<link rel="icon" href="/apple-touch-icon.png">
		<title>Simple File Manager</title>
		<style>
			body {
				font-family: Arial, sans-serif;
				text-align: center;
				padding: 20px;
			}
			h1 {
				font-size: 24px;
			}
			h2 {
				font-size: 20px;
			}
			.buttonStyle, #addContentBtn {
				display: inline-block;
				padding: 10px 20px;
				background-color: #007bff;
				color: #fff;
				text-decoration: none;
				border-radius: 5px;
				font-weight: bold;
			}
			input[type=submit] {
				display: inline-block;
				padding: 10px 20px;
				background-color: #c83349;
				font-size: 16px;
				color: #fff;
				text-decoration: none;
				border-radius: 5px;
				border: none;
				font-weight: bold;
			}
			.buttonStyleRed {
				display: inline-block;
				padding: 10px 20px;
				background-color: #c83349;
				font-size: 16px;
				color: #fff;
				text-decoration: none;
				border-radius: 5px;
				font-weight: bold;
			}
			tr:nth-child(even) {
 				background-color: #f2f2f2;
			}
			.center {
				margin-left: auto;
				margin-right: auto;
			}
			.greyhead{
				background-color: grey;
				font-weight: bolder;
			}
		</style>
	</head>
	
	<body>

	<header>
        <h1>Simple File Manager</h1>
    </header>

	<h2>File Upload</h2>

	<form name="addContent" action="index.php" method="post" enctype="multipart/form-data" >
			<input type="file" name="file[]" id="file" multiple="multiple">
			<input type="submit" value="Upload" id="addContentBtn">
	</form>

	<h2>Uploaded Files</h2>

<?php

	// display a list of files available
	if ($files = scandir($dir)) {
		if (count($files)==2){
			echo '<p>None</p>';
		}else{
			echo '<table border="1" width="60%" class="center"><thead class="greyhead"><td>File</td><td>Delete?</td></thead><tbody>';
			foreach ($files as $file) {
				if ($file != '.' && $file != '..') {
					echo '<tr><td><a href="'.$webdir.'files/'.$file.'" download>'.$file.'</a></td><td align="middle">
							(<a href="index.php?file='.$file.'">x</a>)</td></tr>';
					
				}
			}	
			echo '</tbody></table>';
		}
	}

?>
<p>
	<a href="index.php?zip=yes" class="buttonStyle">Download zip archive</a>&nbsp;
	<a href="index.php?delete=yes" class="buttonStyleRed" onclick='return confirm("Are you sure?")'>Delete all files</a>&nbsp;
	<a href="index.php?logout=yes" class="buttonStyle">Logout</a></p>
</body>
</html>

<?php

	function checkSession($redirect = null) {
		
		session_start();
		
		if (empty($_SESSION['loggedon']) or $_SESSION['loggedon'] != 'true') {

			if ($redirect != null) {
				header("Location: ".$redirect);
			}else{
				header("Location: /");
			}
			die;
  
		}else{
			if (time () - $_SESSION['stamp'] < $_SESSION['ttl']) {

				$_SESSION['stamp'] = time ();

			}else{

				session_destroy();
				header("Location: index.php");
  
			}

		}

	}
	
?>

