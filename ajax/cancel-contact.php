<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "DELETE FROM invitations WHERE username = :contact AND contact = :username";
  $query = $conn->prepare($sql); 
  $query->bindParam(':username', $_SESSION['username']);
	$query->bindParam(':contact', $_POST['contact']);
  $query->execute();

?>

