<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "UPDATE users SET active = 0 WHERE username = :username";
    $query = $conn->prepare($sql); 
    $query->bindParam(':username', $_SESSION['username']);
	$query->execute();
	
	session_unset();
	session_destroy();
?>