<?php

	// set the root directory for the uploads
	$dir = "/var/www/html/upload/files/";
    $webdir = "http://yourdomain.com/upload/";
	
	// Are we logged in?
	checkSession('login.php');
	
	// has a file been uploaded?
	if (!empty($_FILES)){
		// validate the uploaded file
      	$allowedExts = array("jpg", "png", "JPG", "PNG", "jpeg", "JPEG");
      	$temp = explode(".", $_FILES["file"]["name"]);
      	$extension = end($temp);

		// check that the file if of the correct type and not too big
		if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "application/pdf")
            || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 10000000)
            && in_array($extension, $allowedExts)) {
			if ($_FILES["file"]["error"] > 0) {
				$msg = 'ERROR: '.$_FILES["file"]["error"];
        	} else {
        		
        		// move the tmp file to final resting place
        		move_uploaded_file($_FILES["file"]["tmp_name"], 
        			$dir .date('Ymd_His').'_'.$_FILES["file"]["name"]);

			}
		} else {
        	$msg = 'ERROR: Invalid File';
		}

		echo $msg;
		
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
	}

?>
<html>
	<head>
		<meta name="viewport" content="width=device-width">

	</head>
	
	<body>

	<h1>File Upload</h1>

	<form name="addContent" action="index.php" method="post" enctype="multipart/form-data" >
		<div class="controls">
			<input type="file" name="file" id="file">
		</div>
		<div class="controls">
			<input type="submit" class="btn btn-default" value="Save" id="addContentBtn">
		</div>
	</form>

	<h1>Uploaded Files</h1>

<?php

	// display a list of files available
	if ($files = scandir($dir)) {

		foreach ($files as $file) {
    		if ($file != '.' && $file != '..') {
				echo '<a href="'.$webdir.'files/'.$file.'">'.$file.'</a>&nbsp;
						(<a href="index.php?file='.$file.'">x</a>)<br>';
				
    		}
		}
	}

?>
<h3><a href="index.php?zip=yes">Download zip archive</a></h3>
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
