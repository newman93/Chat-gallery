<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
  
  $sql ="INSERT INTO invitations (username, contact) VALUES(:username, :contact)";
  $query = $conn->prepare($sql); 
  $query->bindParam(':username', $_SESSION['username']);
  $query->bindParam(':contact', $_POST['contact']);
  $query->execute();
?>