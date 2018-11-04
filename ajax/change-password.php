<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "UPDATE users SET password = :password WHERE username = :username";
    $query = $conn->prepare($sql); 
	$password = md5($_POST['password']);
    $query->bindParam(':password', $password);
	$query->bindParam(':username', $_SESSION["username"]);		
	$query->execute();
	
	session_unset();
	session_destroy();
?>