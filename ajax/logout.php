<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql = "UPDATE users SET active = 0 WHERE username = :username";
    $query = $conn->prepare($sql);

    $username = $antiXss->xss_clean($_SESSION['username']);

    $query->bindParam(':username', $username);
	$query->execute();
	
	session_unset();
	session_destroy();
?>