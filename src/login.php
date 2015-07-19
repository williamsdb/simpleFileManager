<?php

$password = $_REQUEST["pswd"];

if ($password == 'yourpassword') {

	//Set the session state
	session_start();
	$_SESSION['loggedon'] = 'true';
	$_SESSION['user'] = serialize($user);
    $_SESSION['ttl'] = 1500;
    $_SESSION['stamp'] = time ();
    
	header("Location: /upload");

}else{

	echo '<html>';
	echo '<head>';
	echo '<body onload="document.login.pswd.focus()">';
	echo '<form action="login.php" method="post" name="login">';
	echo '<input type="password" name="pswd">';
	echo '<input type="submit">';
	echo '</body>';
	echo '</html>';

}	

?>