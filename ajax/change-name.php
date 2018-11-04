<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "UPDATE users SET name = :name, surname = :surname WHERE username = :username";
    $query = $conn->prepare($sql); 
    $query->bindParam(':name', $_POST['name']);
    $query->bindParam(':surname', $_POST['surname']);	
	$query->bindParam(':username', $_SESSION['username']);
	$query->execute();
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["surname"] = $_POST["surname"];
?>