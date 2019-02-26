<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
  
  $date = date('Y-m-d H:i:s');
  $sql = "INSERT INTO messages (from_username, to_username, message, to_read, date) VALUES (:from, :to, :message, 1, :date)";
  
  $query = $conn->prepare($sql); 
  $query->bindParam(':from', $_SESSION['username']);
	$query->bindParam(':to', $_POST['username']);
  $query->bindParam(':message', $_POST['message']);
  $query->bindParam(':date', $date);
  $query->execute();
  
  $data['name'] = $_SESSION['name'];
  $data['surname'] = $_SESSION['surname'];
  $data['time'] = date("H:i",strtotime($date));
  $data['date'] = date("Y-m-d",strtotime($date));
  
  echo json_encode($data);
?>

