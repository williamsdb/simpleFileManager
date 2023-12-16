<?php

$password = '';
if (isset($_REQUEST["pswd"])) $password = $_REQUEST["pswd"];

if ($password == '<your password here>') {

	//Set the session state
	session_start();
	$_SESSION['loggedon'] = 'true';
    $_SESSION['ttl'] = 1500;
    $_SESSION['stamp'] = time ();
    
	header("Location: /");

}else{

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
			.buttonStyle {
				display: inline-block;
				padding: 10px 20px;
				background-color: #007bff;
				color: #fff;
				text-decoration: none;
				border-radius: 5px;
				font-weight: bold;
			}
		</style>
	</head>
	<header>
        <h1>Simple File Manager</h1>
    </header>

	<body onload="document.login.pswd.focus()">


	<form action="login.php" method="post" name="login">
	<input type="password" name="pswd">
	<br>
	<input type="submit" value="Login" style="margin-top: 10px;" class="buttonStyle">
	</body>
	</html>
<?php
}	

?>