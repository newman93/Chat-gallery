<?php
 if (session_status() === PHP_SESSION_NONE){session_start();}
 require_once("../database_connection.php");
 require_once '../vendor/autoload.php';
 use \voku\helper\AntiXSS;

 $antiXss = new AntiXSS();

 $sql = "UPDATE messages SET to_read = 0 WHERE from_username = :from AND to_username = :to";
 $query = $conn->prepare($sql);

 $from = $antiXss->xss_clean($_POST['username']);
 $to = $antiXss->xss_clean($_SESSION['username']);

 $query->bindParam(':from', $from);
 $query->bindParam(':to', $to);
 $query->execute();
?>

