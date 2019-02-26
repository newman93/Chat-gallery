<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "UPDATE users SET active = :status WHERE username = :username";
    $query = $conn->prepare($sql); 
    $query->bindParam(':status', $_POST['status']);
	$query->bindParam(':username', $_SESSION['username']);
	$query->execute();
?>
