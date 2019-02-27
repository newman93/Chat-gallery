<?php
if (session_status() === PHP_SESSION_NONE){session_start();}
require_once("../database_connection.php");

 $sql = "UPDATE messages SET to_read = 0 WHERE from_username = :from AND to_username = :to";
 $query = $conn->prepare($sql);
 $query->bindParam(':from', $_POST['username']);
 $query->bindParam(':to', $_SESSION['username']);
 $query->execute();
?>

