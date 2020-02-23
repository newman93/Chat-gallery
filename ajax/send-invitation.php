<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

  $sql ="INSERT INTO invitations (username, contact) VALUES(:username, :contact)";
  $query = $conn->prepare($sql);

  $username = $antiXss->xss_clean($_SESSION['username']);
  $contact = $antiXss->xss_clean($_POST['contact']);

  $query->bindParam(':username', $username);
  $query->bindParam(':contact', $contact);
  $query->execute();
?>