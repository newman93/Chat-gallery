<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "UPDATE users SET e_mail = :email WHERE username = :username";
    $query = $conn->prepare($sql); 
    $query->bindParam(':email', $_POST['email']);	
	$query->bindParam(':username', $_SESSION['username']);
	$query->execute();
	$_SESSION["email"] = $_POST["email"];
?>