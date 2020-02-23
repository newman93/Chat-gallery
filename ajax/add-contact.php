<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

    $sql = "DELETE FROM invitations WHERE username = :contact AND contact = :username";
    $query = $conn->prepare($sql);

    $username = $antiXss->xss_clean($_SESSION['username']);
    $contact = $antiXss->xss_clean($_POST['contact']) ;


    $query->bindParam(':username', $username);
	$query->bindParam(':contact', $contact);
    $query->execute();
  
    $sql = "INSERT INTO contacts (username, contact) VALUES (:contact, :username), (:username, :contact)";
    $query = $conn->prepare($sql);

    $username = $antiXss->xss_clean($_SESSION['username']);
    $contact = $antiXss->xss_clean($_POST['contact']);

    $query->bindParam(':username', $username);
    $query->bindParam(':contact', $contact);
    $query->execute();

?>

